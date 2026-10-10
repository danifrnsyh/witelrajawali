<?php

use App\Services\GoogleSheetService;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$service = new GoogleSheetService;
$token = $service->getAccessToken();
$url = 'https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID').'/values/Sheet1!A1:AZ50';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$start = microtime(true);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL error: '.curl_error($ch)."\n";
}
echo 'Time: '.(microtime(true) - $start)."s\n";
echo 'Len: '.strlen($result)."\n";
