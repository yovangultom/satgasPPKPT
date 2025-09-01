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
        \Carbon\Carbon::setLocale('id');
        $pengaduan->load(['user', 'pelapors', 'terlapors']);
        $pdf = Pdf::loadView('pdf.laporan_pengaduan', ['pengaduan' => $pengaduan]);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-pengaduan-' . $pengaduan->nomor_pengaduan . '.pdf');
    }

    public function exportBapPdf(BeritaAcaraPemeriksaan $bap)
    {
        \Carbon\Carbon::setLocale('id');
        $ketuaSatgas = KetuaSatgas::first();

        $pdfData = [
            'bap' => $bap,
            'ketuaSatgas' => $ketuaSatgas,
        ];

        $pdf = Pdf::loadView('pdf.berita_acara_pemeriksaan', $pdfData);
        $pdf->setPaper('a4', 'portrait');
        $namaFile = "BAP - {$bap->pihak_diperiksa_nama} - {$bap->tanggal_pemeriksaan->format('Y-m-d')}.pdf";

        return $pdf->stream($namaFile);
    }
    public function exportLhpPDF(LaporanHasilPemeriksaan $lhp)
    {
        \Carbon\Carbon::setLocale('id');
        $lhp->load(['beritaAcaraPemeriksaan', 'user']);
        $pasalPelanggarans = PasalPelanggaran::whereIn('id', $lhp->pasal_pelanggaran_id ?? [])->get();
        $ketuaSatgas = KetuaSatgas::first();
        $pasalIds = collect($lhp->pelanggaran_data)
            ->pluck('pasal_pelanggaran_ids')
            ->flatten()
            ->unique()
            ->filter();

        $pasalPelanggarans = PasalPelanggaran::whereIn('id', $pasalIds)->get();
        $pdfData = [
            'lhp' => $lhp,
            'pasalPelanggarans' => $pasalPelanggarans,
            'ketuaSatgas' => $ketuaSatgas,
        ];
        $pdf = Pdf::loadView('pdf.laporan_hasil_pemeriksaan', $pdfData);
        $pdf->setPaper('a4', 'portrait');
        $namaFile = 'LHP - ' . $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama . ' - ' . $lhp->created_at->format('Y-m-d') . '.pdf';
        return $pdf->stream($namaFile);
    }
    public function exportSuratRekomendasiPdf(SuratRekomendasi $suratRekomendasi)
    {
        \Carbon\Carbon::setLocale('id');
        $suratRekomendasi->load('pengaduan', 'laporanHasilPemeriksaan', 'sanksis');
        $lhp = $suratRekomendasi->laporanHasilPemeriksaan;

        if ($lhp && !empty($lhp->pelanggaran_data)) {
            $pasalIds = collect($lhp->pelanggaran_data)
                ->pluck('pasal_pelanggaran_ids')
                ->flatten()
                ->unique()
                ->filter();

            $lhp->pasalPelanggarans = PasalPelanggaran::whereIn('id', $pasalIds)->get();
        } else {
            $lhp->pasalPelanggarans = collect();
        }


        $ketuaSatgas = KetuaSatgas::first();

        $data = [
            'suratRekomendasi' => $suratRekomendasi,
            'pengaduan' => $suratRekomendasi->pengaduan,
            'lhp' => $lhp,
            'ketuaSatgas' => $ketuaSatgas,

        ];

        $pdf = Pdf::loadView('pdf.surat_rekomendasi', $data);

        $pdf->setPaper('a4', 'portrait');

        $fileName = 'surat-rekomendasi-' . $suratRekomendasi->id . '.pdf';
        return $pdf->stream($fileName);
    }
    public function exportPenangananPdf(BorangPenanganan $borangPenanganan)
    {
        \Carbon\Carbon::setLocale('id');
        $pengaduan = $borangPenanganan->pengaduan;
        $pdfData = [
            'pengaduan' => $pengaduan,
            'borangPenanganan' => $borangPenanganan,
        ];

        $pdf = Pdf::loadView('pdf.borang_penanganan', $pdfData);
        $pdf->setPaper('a4', 'portrait');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $namaFile = "Borang Penanganan {$pengaduan->nomor_pengaduan}-{$timestamp}.pdf";



        return $pdf->stream($namaFile);
    }
    public function exportPemeriksaanPdf(BorangPemeriksaan $borangPemeriksaan)
    {
        \Carbon\Carbon::setLocale('id');
        $pengaduan = $borangPemeriksaan->pengaduan;
        $pdfData = [
            'pengaduan' => $pengaduan,
            'borangPemeriksaan' => $borangPemeriksaan,
        ];
        $pdf = Pdf::loadView('pdf.borang_pemeriksaan', $pdfData);
        $pdf->setPaper('a4', 'potrait');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $namaFile = "Borang Pemeriksaan {$pengaduan->nomor_pengaduan}-{$timestamp}.pdf";

        return $pdf->stream($namaFile);
    }

    public function exportKesimpulanPdf(Pengaduan $pengaduan)
    {

        $ketuaSatgas = KetuaSatgas::first();
        $data = [
            'pengaduan' => $pengaduan,
            'ketuaSatgas' => $ketuaSatgas,
        ];
        $pdf = Pdf::loadView('pdf.borang_kesimpulan', $data);
        $nomor = $pengaduan->data_kesimpulan['nomor_pengaduan'] ?? $pengaduan->id;
        $fileName = 'Borang-Kesimpulan-' . $nomor . '.pdf';


        return $pdf->stream($fileName);
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
            'fakultas' => $status == 'mahasiswa' ? ($formData['fakultas_mhs'] ?? ($pihak->fakultas ?? null)) : ($formData['fakultas_dosen'] ?? ($pihak->fakultas ?? null)),
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
            'fakultas' => $formData['fakultas_mhs'] ?? $formData['fakultas_dosen'] ?? null,
            'info_tambahan' => $formData['info_tambahan'] ?? null,
            'asal_instansi' => $formData['asal_instansi'] ?? $formData['asal_instansi_dosen_tendik'] ?? null,
            'tanggal_panggilan' => $formData['tanggal'],
            'waktu_panggilan' => $formData['waktu'],
            'tempat_panggilan' => $formData['tempat'],
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);
    }
}
