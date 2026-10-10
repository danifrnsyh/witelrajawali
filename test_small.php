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
$url = 'https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/Sheet1!A1:B2';

$start = microtime(true);
$res = Http::withToken($token)->get($url);
echo 'Time: '.(microtime(true) - $start)."s\n";
echo 'Status: '.$res->status()."\n";
echo 'Body: '.substr($res->body(), 0, 200)."\n";
