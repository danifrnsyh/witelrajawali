<?php

use App\Models\SheetData;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
try {
    $count = SheetData::orderByRaw("json_extract(data, '$[0]') asc")->count();
    echo 'Count: '.$count;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}
