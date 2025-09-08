<?php

namespace App\Http\Controllers;

use App\Models\SuratRekomendasi;
use Illuminate\Http\Request;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratRekomendasiController extends Controller
{
    /**
     * Method ini akan membuat PDF gabungan secara on-the-fly.
     *
     * @param SuratRekomendasi $suratRekomendasi
     * @return \Illuminate\Http\Response
     */
    public function exportMergedPdf(SuratRekomendasi $suratRekomendasi)
    {
        $pdfMerger = PDFMerger::init();
        $pengaduan = $suratRekomendasi->pengaduan;

        if (!$pengaduan) {
            return back()->with('error', 'Data pengaduan terkait tidak ditemukan.');
        }

        try {
            $view = view('pdf.surat_rekomendasi', ['suratRekomendasi' => $suratRekomendasi]);
            $pdfMerger->addString(Pdf::loadHTML($view->render())->output());
            $relatedDocs = [
                $pengaduan->suratPemanggilans ?? [],
                $pengaduan->borangPenanganans ?? [],
                $pengaduan->beritaAcaraPemeriksaans ?? [],
                $pengaduan->borangPemeriksaans ?? [],
                $pengaduan->laporanHasilPemeriksaans ?? [],
            ];

            foreach ($relatedDocs as $collection) {
                foreach ($collection as $doc) {
                    if (isset($doc->pdf_path) && Storage::disk('public')->exists($doc->pdf_path)) {
                        $pdfMerger->addPDF(Storage::disk('public')->path($doc->pdf_path), 'all');
                    }
                }
            }

            $pdfMerger->merge();
            $fileName = 'Surat_Rekomendasi_Gabungan_' . $pengaduan->nomor_pengaduan . '.pdf';

            return $pdfMerger->stream($fileName);
        } catch (\Exception $e) {
            \Log::error('Gagal menggabungkan PDF untuk SR ID: ' . $suratRekomendasi->id . ' - ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses file PDF gabungan. Silakan coba lagi.');
        }
    }
}
