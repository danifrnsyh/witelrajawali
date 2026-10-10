<?php

use App\Services\GoogleSheetService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$service = new GoogleSheetService;
$token = $service->getAccessToken();
$res = Http::withToken($token)->get('https://sheets.googleapis.com/v4/spreadsheets/'.env('GOOGLE_SHEET_ID'));
$json = $res->json();

if (isset($json['sheets'])) {
    foreach ($json['sheets'] as $sheet) {
        echo $sheet['properties']['title']."\n";
    }
} else {
    echo "Error: \n";
    print_r($json);
}
