<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SpdController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Surat Tugas
    Route::get('/surat-tugas/create', \App\Livewire\CreateSuratTugas::class)->name('surat-tugas.create');
    Route::get('/surat-tugas/{id}/preview', function($id) {
        $suratTugas = \App\Models\SuratTugas::with(['peserta', 'penandatangan'])->findOrFail($id);
        return view('surat-tugas.preview', compact('suratTugas'));
    })->name('surat-tugas.preview');
    
    // Surat Dinas (SPD)
    Route::get('/spd', [SpdController::class, 'index'])->name('spd.index');
    Route::get('/surat-dinas/create/{tugas_id}', \App\Livewire\CreateSuratDinas::class)->name('surat-dinas.create');
    Route::get('/spd/{id}/preview', function($id) {
        $spd = \App\Models\SuratPerjalananDinas::with(['suratTugas', 'pegawai', 'ppk', 'pptk'])->findOrFail($id);
        return view('spd.preview', compact('spd'));
    })->name('spd.preview');
    Route::get('/spd/{id}/detail', [SpdController::class, 'show'])->name('spd.show');
    
    // PDF Downloads
    Route::get('/pdf/surat-tugas/{id}', [\App\Http\Controllers\PdfController::class, 'downloadSuratTugas'])->name('pdf.surat-tugas.download');
    Route::get('/pdf/surat-tugas/{id}/preview', [\App\Http\Controllers\PdfController::class, 'previewSuratTugas'])->name('pdf.surat-tugas.preview');
    Route::get('/pdf/spd/{id}', [\App\Http\Controllers\PdfController::class, 'downloadSpd'])->name('pdf.spd.download');
    Route::get('/pdf/spd/{id}/preview', [\App\Http\Controllers\PdfController::class, 'previewSpd'])->name('pdf.spd.preview');
});
