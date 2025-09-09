<?php

namespace App\Jobs;

use App\Models\SuratRekomendasi;
use App\Models\User;
use App\Notifications\RekomendasiBaruNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KetuaSatgas;
use App\Models\PasalPelanggaran;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfReader\PdfReaderException;


class GenerateMergedRekomendasiPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $suratRekomendasiId;

    public function __construct(SuratRekomendasi $suratRekomendasi)
    {
        $this->suratRekomendasiId = $suratRekomendasi->id;
    }

    public function handle(): void
    {
        $suratRekomendasi = SuratRekomendasi::find($this->suratRekomendasiId);

        if (!$suratRekomendasi) {
            Log::error("Job Gagal: SuratRekomendasi dengan ID {$this->suratRekomendasiId} tidak ditemukan.");
            return;
        }

        $suratRekomendasi->load([
            'pengaduan.suratPanggilans',
            'pengaduan.borangPenanganans',
            'pengaduan.beritaAcaraPemeriksaans',
            'pengaduan.borangPemeriksaans',
            'pengaduan.laporanHasilPemeriksaans',
            'laporanHasilPemeriksaan.beritaAcaraPemeriksaan'
        ]);

        Log::info("Memulai Job GenerateMergedRekomendasiPdf untuk SR ID: {$suratRekomendasi->id}");

        $tempPath = storage_path('tmp');
        if (!File::isDirectory($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }

        $pdfMerger = PDFMerger::init();
        $pengaduan = $suratRekomendasi->pengaduan;

        if (!$pengaduan) {
            Log::error("Job Gagal: Relasi Pengaduan tidak ditemukan untuk SR ID: {$suratRekomendasi->id}");
            return;
        }

        try {
            Log::info("Mempersiapkan data untuk PDF utama...");
            $lhp = $suratRekomendasi->laporanHasilPemeriksaan;

            if ($lhp) {
                if (!empty($lhp->pelanggaran_data)) {
                    $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();
                    $lhp->pasalPelanggarans = PasalPelanggaran::whereIn('id', $pasalIds)->get();
                } else {
                    $lhp->pasalPelanggarans = collect();
                }
            }

            $viewData = [
                'suratRekomendasi' => $suratRekomendasi,
                'pengaduan' => $pengaduan,
                'lhp' => $lhp,
                'ketuaSatgas' => KetuaSatgas::first(),
            ];

            Log::info("Membuat PDF utama (Surat Rekomendasi) dari string...");
            $view = view('pdf.surat_rekomendasi', $viewData);
            $pdfMerger->addString(Pdf::loadHTML($view->render())->output());
            Log::info("PDF utama berhasil ditambahkan.");

            Log::info("Memeriksa lampiran tipe: Laporan Pengaduan Awal...");
            if (isset($pengaduan->pdf_path) && Storage::disk('public')->exists($pengaduan->pdf_path)) {
                $pdfMerger->addPDF(Storage::disk('public')->path($pengaduan->pdf_path), 'all');
                Log::info("-> Berhasil menambahkan lampiran PDF: {$pengaduan->pdf_path}");
            } else {
                Log::warning("-> Lampiran PDF Laporan Pengaduan Awal tidak ditemukan. Path: " . ($pengaduan->pdf_path ?? 'NULL'));
            }

            $relatedDocs = [
                'Surat Panggilan' => $pengaduan->suratPanggilans,
                'Borang Penanganan' => $pengaduan->borangPenanganans,
                'BAP' => $pengaduan->beritaAcaraPemeriksaans,
                'Borang Pemeriksaan' => $pengaduan->borangPemeriksaans,
                'LHP' => $pengaduan->laporanHasilPemeriksaans,
            ];

            foreach ($relatedDocs as $type => $collection) {
                Log::info("Memeriksa lampiran tipe: {$type}...");
                foreach ($collection as $doc) {
                    if (isset($doc->pdf_path) && Storage::disk('public')->exists($doc->pdf_path)) {
                        $pdfMerger->addPDF(Storage::disk('public')->path($doc->pdf_path), 'all');
                        Log::info("-> Berhasil menambahkan lampiran PDF: {$doc->pdf_path}");
                    } else {
                        Log::warning("-> Lampiran PDF tidak ditemukan untuk {$type} ID: {$doc->id}. Path: " . ($doc->pdf_path ?? 'NULL'));
                    }
                }
            }

            Log::info("Menggabungkan semua PDF...");
            $fileName = 'rekomendasi_gabungan/' . Str::slug($pengaduan->nomor_pengaduan) . '.pdf';
            $fullPath = Storage::disk('public')->path($fileName);
            $directory = dirname($fullPath);
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            $pdfMerger->merge();
            $pdfMerger->save($fullPath);
            Log::info("PDF Gabungan berhasil disimpan di: {$fileName}");

            $suratRekomendasi->update(['file_gabungan_path' => $fileName]);
            Log::info("Path di database berhasil diperbarui.");

            $rektors = User::role('rektor')->get();
            if ($rektors->isNotEmpty()) {
                Notification::send($rektors, new RekomendasiBaruNotification($suratRekomendasi));
                Log::info("Notifikasi berhasil dikirim ke " . $rektors->count() . " rektor.");
            }

            Log::info("Job GenerateMergedRekomendasiPdf untuk SR ID: {$suratRekomendasi->id} SELESAI.");
        } catch (CrossReferenceException | PdfReaderException $e) {
            Log::error("FPDI/PDF Parser Error: Terjadi masalah saat memproses salah satu file PDF. Cek kembali file-file lampiran. - " . $e->getMessage());
            $this->fail($e);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF Gabungan untuk SR ID: ' . $suratRekomendasi->id . ' - Error: ' . $e->getMessage() . ' di file ' . $e->getFile() . ' baris ' . $e->getLine());
            $this->fail($e);
        }
    }
}
