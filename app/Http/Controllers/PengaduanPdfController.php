<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Models\BorangPenanganan;
use App\Models\BorangPemeriksaan;
use App\Models\BeritaAcaraPemeriksaan;
use App\Models\LaporanHasilPemeriksaan;
use App\Models\SuratPanggilan;
use App\Models\KetuaSatgas;
use App\Models\PasalPelanggaran;
use App\Models\SuratRekomendasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PengaduanPdfController extends Controller
{
    public function generatePdf(Pengaduan $pengaduan)
    {
        if ($pengaduan->pdf_path && Storage::disk('public')->exists($pengaduan->pdf_path)) {
            return Storage::disk('public')->response($pengaduan->pdf_path);
        }

        \Carbon\Carbon::setLocale('id');
        $pengaduan->load(['user', 'pelapors', 'terlapors']);
        $pdf = Pdf::loadView('pdf.laporan_pengaduan', ['pengaduan' => $pengaduan]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'laporan_pengaduan/laporan-' . $pengaduan->nomor_pengaduan . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $pengaduan->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-pengaduan-' . $pengaduan->nomor_pengaduan . '.pdf"',
        ]);
    }

    public function generateSuratPanggilan(Pengaduan $pengaduan, Model $pihak, string $status, string $peranText, array $formData)
    {
        \Carbon\Carbon::setLocale('id');
        $ketuaSatgas = KetuaSatgas::first();
        $pdfData = [
            'nama' => $pihak->nama,
            'status' => $status,
            'peran' => $peranText,
            'nomor_pelaporan' => $pengaduan->nomor_pengaduan,
            'jenis_kejadian' => $pengaduan->jenis_kejadian,
            'tanggal' => $formData['tanggal'],
            'waktu' => Carbon::parse($formData['waktu'])->format('H:i') . ' WIB',
            'tempat' => $formData['tempat'],
            'nim' => $formData['nim'] ?? ($pihak->nim ?? null),
            'semester' => $formData['semester'] ?? ($pihak->semester ?? null),
            'program_studi' => $formData['program_studi'] ?? ($pihak->program_studi ?? null),
            'fakultas' => $formData['fakultas'] ?? ($pihak->fakultas ?? null),
            'nip' => $formData['nip'] ?? ($pihak->nip ?? null),
            'info_tambahan' => $formData['info_tambahan'] ?? [],
            'asal_instansi' => $formData['asal_instansi'] ?? $formData['asal_instansi_dosen_tendik'] ?? null,
            'ketuaSatgas' => $ketuaSatgas,
        ];

        $pdf = Pdf::loadView('pdf.surat_pemanggilan', $pdfData);
        $fileName = 'surat-panggilan-' . Str::slug($pihak->nama) . '-' . time() . '.pdf';
        $filePath = 'surat_panggilan/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        SuratPanggilan::create([
            'pengaduan_id' => $pengaduan->id,
            'nama_pihak' => $pihak->nama,
            'status_pihak' => $status,
            'peran_pihak' => $peranText,
            'nim' => $formData['nim'] ?? null,
            'semester' => $formData['semester'] ?? null,
            'program_studi' => $formData['program_studi'] ?? null,
            'nip' => $formData['nip'] ?? null,
            'fakultas' => $formData['fakultas'] ?? null,
            'info_tambahan' => $formData['info_tambahan'] ?? null,
            'asal_instansi' => $formData['asal_instansi'] ?? $formData['asal_instansi_dosen_tendik'] ?? null,
            'tanggal_panggilan' => $formData['tanggal'],
            'waktu_panggilan' => $formData['waktu'],
            'tempat_panggilan' => $formData['tempat'],
            'pdf_path' => $filePath,
            'file_name' => $fileName,
        ]);
    }

    public function exportPenangananPdf(BorangPenanganan $borangPenanganan)
    {
        if ($borangPenanganan->pdf_path && Storage::disk('public')->exists($borangPenanganan->pdf_path)) {
            return Storage::disk('public')->response($borangPenanganan->pdf_path);
        }

        \Carbon\Carbon::setLocale('id');
        $pengaduan = $borangPenanganan->pengaduan;
        $pdf = Pdf::loadView('pdf.borang_penanganan', [
            'borangPenanganan' => $borangPenanganan,
            'pengaduan' => $pengaduan
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'borang_penanganan/bp-' . $pengaduan->nomor_pengaduan . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $borangPenanganan->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Borang Penanganan ' . $pengaduan->nomor_pengaduan . '.pdf"',
        ]);
    }

    public function exportBapPdf(BeritaAcaraPemeriksaan $bap, bool $stream = true)
    {
        if ($bap->pdf_path && Storage::disk('public')->exists($bap->pdf_path)) {
            if ($stream) {
                return Storage::disk('public')->response($bap->pdf_path);
            }
            return;
        }

        \Carbon\Carbon::setLocale('id');
        $pdf = Pdf::loadView('pdf.berita_acara_pemeriksaan', ['bap' => $bap, 'ketuaSatgas' => KetuaSatgas::first()]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'berita_acara_pemeriksaan/bap-' . Str::slug($bap->pihak_diperiksa_nama) . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $bap->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="BAP - ' . $bap->pihak_diperiksa_nama . '.pdf"',
        ]);
    }

    public function exportPemeriksaanPdf(BorangPemeriksaan $borangPemeriksaan)
    {
        if ($borangPemeriksaan->pdf_path && Storage::disk('public')->exists($borangPemeriksaan->pdf_path)) {
            return Storage::disk('public')->response($borangPemeriksaan->pdf_path);
        }

        \Carbon\Carbon::setLocale('id');
        $pengaduan = $borangPemeriksaan->pengaduan;
        $pdf = Pdf::loadView('pdf.borang_pemeriksaan', [
            'borangPemeriksaan' => $borangPemeriksaan,
            'pengaduan' => $pengaduan
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'borang_pemeriksaan/bpem-' . $pengaduan->nomor_pengaduan . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $borangPemeriksaan->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Borang Pemeriksaan ' . $pengaduan->nomor_pengaduan . '.pdf"',
        ]);
    }

    public function exportLhpPDF(LaporanHasilPemeriksaan $lhp, bool $stream = true)
    {
        if ($lhp->pdf_path && Storage::disk('public')->exists($lhp->pdf_path)) {
            if ($stream) {
                return Storage::disk('public')->response($lhp->pdf_path);
            }
            return;
        }

        \Carbon\Carbon::setLocale('id');
        $lhp->load(['beritaAcaraPemeriksaan', 'user']);
        $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();
        $pasalPelanggarans = PasalPelanggaran::whereIn('id', $pasalIds)->get();

        $pdf = Pdf::loadView('pdf.laporan_hasil_pemeriksaan', [
            'lhp' => $lhp,
            'pasalPelanggarans' => $pasalPelanggarans,
            'ketuaSatgas' => KetuaSatgas::first(),
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'laporan_hasil_pemeriksaan/lhp-' . Str::slug($lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama) . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $lhp->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="LHP - ' . $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama . '.pdf"',
        ]);
    }

    public function exportSuratRekomendasiPdf(SuratRekomendasi $suratRekomendasi)
    {
        if ($suratRekomendasi->pdf_path && Storage::disk('public')->exists($suratRekomendasi->pdf_path)) {
            return Storage::disk('public')->response($suratRekomendasi->pdf_path);
        }

        \Carbon\Carbon::setLocale('id');
        $suratRekomendasi->load('pengaduan', 'laporanHasilPemeriksaan', 'sanksis');
        $lhp = $suratRekomendasi->laporanHasilPemeriksaan;

        $pasalPelanggarans = collect();
        if ($lhp && !empty($lhp->pelanggaran_data)) {
            $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();
            $pasalPelanggarans = PasalPelanggaran::whereIn('id', $pasalIds)->get();
        }
        if ($lhp) {
            $lhp->pasalPelanggarans = $pasalPelanggarans;
        }

        $pdf = Pdf::loadView('pdf.surat_rekomendasi', [
            'suratRekomendasi' => $suratRekomendasi,
            'pengaduan' => $suratRekomendasi->pengaduan,
            'lhp' => $lhp,
            'ketuaSatgas' => KetuaSatgas::first(),
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $filePath = 'surat_rekomendasi/sr-' . $suratRekomendasi->id . '-' . time() . '.pdf';
        Storage::disk('public')->put($filePath, $pdfContent);
        $suratRekomendasi->update(['pdf_path' => $filePath]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Surat Rekomendasi - ' . $suratRekomendasi->nomor_surat . '.pdf"',
        ]);
    }
}
