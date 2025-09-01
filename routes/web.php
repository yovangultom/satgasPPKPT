<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanPublikController;
use App\Http\Controllers\PengaduanPdfController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', function (Request $request, Closure $next) {
    if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'petugas', 'rektor', 'htl'])) {
        return redirect()->route('filament.admin.pages.dashboard');
    }
    return $next($request);
}])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lapor', [PengaduanPublikController::class, 'create'])->name('lapor.create');
    Route::post('/lapor', [PengaduanPublikController::class, 'store'])->name('lapor.store');

    Route::get('/pengaduan', [PengaduanPublikController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{pengaduan}', [PengaduanPublikController::class, 'show'])->name('pengaduan.show');

    Route::post('/pengaduan/{pengaduan}/pesan', [PengaduanPublikController::class, 'storeMessage'])->name('pengaduan.storeMessage');
});


Route::middleware('auth')->group(function () {
    Route::get('/pengaduans/{pengaduan}/pdf', [PengaduanPdfController::class, 'generatePdf'])
        ->name('pengaduan.pdf');
    Route::get('/bap/export/{bap}', [PengaduanPdfController::class, 'exportBapPdf'])->name('bap.export.pdf');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notification.read');
    Route::get('/borang-penanganan/{borangPenanganan}/export-pdf', [PengaduanPdfController::class, 'exportPenangananPdf'])
        ->name('borang.export.penanganan');
    Route::get('/borang-pemeriksaan/{borangPemeriksaan}/export-pdf', [PengaduanPdfController::class, 'exportPemeriksaanPdf'])
        ->name('borang.export.pemeriksaan');
    Route::get('/laporan-hasil-pemeriksaan/{lhp}/export-pdf', [PengaduanPdfController::class, 'exportLhpPdf'])
        ->name('lhp.export.pdf');
    Route::get('/surat_rekomendasi/{suratRekomendasi}/pdf', [PengaduanPdfController::class, 'exportSuratRekomendasiPdf'])
        ->name('surat_rekomendasi.export-pdf');
});

require __DIR__ . '/auth.php';
