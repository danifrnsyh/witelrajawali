<?php

use App\Http\Controllers\AuthController;
use App\Livewire\DashboardViewer;
use App\Livewire\DataViewer;
use App\Livewire\HeatmapViewer;
use App\Livewire\KinerjaTeknisi;
use App\Livewire\PeringkatViewer;
use App\Livewire\SektoralViewer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', DataViewer::class);
Route::get('/dashboard', DashboardViewer::class);
Route::get('/heatmap', HeatmapViewer::class);
Route::get('/peringkat', PeringkatViewer::class);
Route::get('/sektoral', SektoralViewer::class)->name('sektoral');
Route::get('/sektoral', SektoralViewer::class)->name('sektoral');
Route::get('/kinerja', KinerjaTeknisi::class);
Route::get('/sobi-digipos', App\Livewire\SobiDigiposViewer::class)->name('sobi.digipos');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/force-sync', function () {
    $exitCode1 = Artisan::call('sheet:sync');
    $exitCode2 = Artisan::call('sheet:sync-sobi');
    
    if ($exitCode1 === 0 || $exitCode2 === 0) {
        return redirect()->back()->with('success', 'Sinkronisasi berhasil! Data terbaru telah ditarik dari Google Sheets.');
    } else {
        return redirect()->back()->with('error', 'Gagal menarik data dari Google Sheets. Pastikan koneksi internet stabil (timeout).');
    }
})->name('force-sync');

