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

$id = '1z8ma3xXtLGqESGHVdqrwJch22-_QW17gxjxCD-kitF0';
$url = "https://sheets.googleapis.com/v4/spreadsheets/{$id}";
$res = Http::withToken($token)->get($url);

if ($res->successful()) {
    echo 'Success! Title: '.$res->json()['properties']['title']."\nSheets:\n";
    foreach ($res->json()['sheets'] as $sheet) {
        echo '- '.$sheet['properties']['title']."\n";
    }
} else {
    echo 'Error: '.$res->body()."\n";
}
