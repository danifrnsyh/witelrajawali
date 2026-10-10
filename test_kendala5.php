<?php

use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$row = DB::table('dashboard_data')->first();
$data = json_decode($row->data, true);
print_r($data);
