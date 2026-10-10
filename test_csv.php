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

echo "Fetching CSV...\n";
$url = 'https://docs.google.com/spreadsheets/d/18qDxr2gSCRJrkIlNbBCn3lBuHEhIKX-jwLSDsBocfZ8/export?format=csv&gid=0';
$start = microtime(true);
$res = Http::timeout(120)->withToken($token)->get($url);
echo 'Time: '.(microtime(true) - $start).'s, Status: '.$res->status()."\n";
if ($res->successful()) {
    echo 'Success! Size: '.strlen($res->body())." bytes\n";
    echo "Preview:\n".substr($res->body(), 0, 200)."\n";
} else {
    echo 'Error: '.$res->body()."\n";
}
