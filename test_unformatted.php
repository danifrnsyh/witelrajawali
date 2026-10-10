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

echo "Fetching A1:AZ2000 UNFORMATTED...\n";
$url = 'https://sheets.googleapis.com/v4/spreadsheets/18qDxr2gSCRJrkIlNbBCn3lBuHEhIKX-jwLSDsBocfZ8/values/Sheet1!A1:AZ2000?valueRenderOption=UNFORMATTED_VALUE';
$start = microtime(true);
$res = Http::timeout(120)->withToken($token)->get($url);
echo 'Time: '.(microtime(true) - $start).'s, Status: '.$res->status()."\n";
if ($res->successful()) {
    echo 'Success! Size: '.strlen($res->body())." bytes\n";
} else {
    echo 'Error: '.$res->body()."\n";
}
