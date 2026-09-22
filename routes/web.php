<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DataViewer;
use App\Livewire\DashboardViewer;

Route::get('/', DataViewer::class);
Route::get('/dashboard', DashboardViewer::class);
