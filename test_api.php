<?php

use App\Services\GoogleSheetService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$service = new GoogleSheetService;
$token = $service->getAccessToken();
echo "Got token\n";
$start = microtime(true);
$url = 'https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/Sheet1!A1:AZ';
$res = Http::withToken($token)->get($url);
echo 'Time1: '.(microtime(true) - $start).'s, Size1: '.strlen($res->body())."\n";

$start = microtime(true);
$url = 'https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID')."/values/'DASHBOARD HARIAN'!A1:AZ";
$res = Http::withToken($token)->get($url);
echo 'Time2: '.(microtime(true) - $start).'s, Size2: '.strlen($res->body())."\n";
