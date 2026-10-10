<?php

use App\Services\GoogleSheetService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$service = new GoogleSheetService;
$token = $service->getAccessToken();

// Test 3: AZ
$range3 = "'DASHBOARD HARIAN'!A1:AZ";
$res3 = Http::withToken($token)->get('https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/'.$range3);
echo 'Test 3 status: '.$res3->status()."\n";
