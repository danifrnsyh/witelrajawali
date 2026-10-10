<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Http;

$sheetId = env('GOOGLE_SHEET_ID_SOBI');
$service = new GoogleSheetService;
try {
    $token = $service->getAccessToken();
    $range = urlencode("'SOBI/DIGIPOS'!A1:K10");
    $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}";
    $response = Http::withToken($token)->get($url);
    if ($response->successful()) {
        echo "SUCCESS!\n";
        print_r($response->json());
    } else {
        echo "Failed: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
