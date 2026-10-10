<?php

use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$rows = DB::table('dashboard_data')->limit(5)->get();
foreach ($rows as $row) {
    print_r(json_decode($row->data, true));
}
