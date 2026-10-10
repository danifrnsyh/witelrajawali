<?php

use App\Services\GoogleSheetService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$service = new GoogleSheetService;
$token = $service->getAccessToken();

// Test 1: Full rawurlencode
$range1 = rawurlencode("'DASHBOARD HARIAN'!A1:Z10");
$res1 = Http::withToken($token)->get('https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/'.$range1);
echo 'Test 1 status: '.$res1->status()."\n";

// Test 2: No encoding
$range2 = "'DASHBOARD HARIAN'!A1:Z10";
$res2 = Http::withToken($token)->get('https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/'.$range2);
echo 'Test 2 status: '.$res2->status()."\n";
