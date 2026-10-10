<?php

use App\Models\DashboardData;
use App\Models\SheetData;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$sheets = SheetData::orderBy('id', 'asc')->get();
foreach ($sheets as $k => $s) {
    $s->row_index = $k + 1;
    $s->save();
}

$dash = DashboardData::orderBy('id', 'asc')->get();
foreach ($dash as $k => $d) {
    $d->row_index = $k + 1;
    $d->save();
}
echo 'Done fixing row indexes!';
