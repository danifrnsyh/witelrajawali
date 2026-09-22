<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DataViewer;
use App\Livewire\DashboardViewer;
use App\Livewire\KinerjaTeknisi;

Route::get('/', DataViewer::class);
Route::get('/dashboard', DashboardViewer::class);
Route::get('/kinerja', KinerjaTeknisi::class);
