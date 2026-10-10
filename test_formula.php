<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Http;

$service = new GoogleSheetService();
$sheetId = '11tQOZmkTYxEHe9ZabpJ3D7Bs_p-IHwMeR3cehW_puIQ';
$reflection = new \ReflectionClass($service);
$prop = $reflection->getProperty('sheetId');
$prop->setAccessible(true);
$prop->setValue($service, $sheetId);
$method = $reflection->getMethod('getAccessToken');
$method->setAccessible(true);
$token = $method->invoke($service);

$ts = '1_PSB_B2C';
echo "\n=== FORMULAS from $ts ===\n";
$url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/'{$ts}'!A4:O15?valueRenderOption=FORMULA";
$res = Http::withToken($token)->get($url);
if ($res->successful()) {
    $rows = $res->json()['values'] ?? [];
    foreach ($rows as $row) {
        echo implode(" | ", $row) . "\n";
    }
}
