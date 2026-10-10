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

echo 'Testing Sheet1... ';
$start = microtime(true);
$url1 = 'https://sheets.googleapis.com/v4/spreadsheets/18qDxr2gSCRJrkIlNbBCn3lBuHEhIKX-jwLSDsBocfZ8/values/Sheet1!A1:B2';
$res1 = Http::timeout(10)->withToken($token)->get($url1);
echo 'Time: '.(microtime(true) - $start).'s, Status: '.$res1->status()."\n";

echo 'Testing DASHBOARD HARIAN... ';
$start = microtime(true);
$url2 = "https://sheets.googleapis.com/v4/spreadsheets/18qDxr2gSCRJrkIlNbBCn3lBuHEhIKX-jwLSDsBocfZ8/values/'DASHBOARD HARIAN'!A1:B2";
$res2 = Http::timeout(10)->withToken($token)->get($url2);
echo 'Time: '.(microtime(true) - $start).'s, Status: '.$res2->status()."\n";
