<?php

use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$rows = DB::table('sheet_data')->get();
$found = false;

foreach ($rows as $row) {
    $data = json_decode($row->data, true);
    foreach ($data as $key => $col) {
        $val = strtolower(trim((string) $col));
        if ($val === 'l' || $val === 's' || $val === 'ct' || strpos($val, 'libur') !== false || strpos($val, 'sakit') !== false || strpos($val, 'cuti') !== false) {
            echo "MATCH FOUND in column index $key: $col\n";
            print_r($data);
            $found = true;
            break 2;
        }
    }
}
if (! $found) {
    echo "No precise L/S/CT found.\n";
}
