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
    if (isset($data[3])) { // jenis_order
        $jenis = strtolower($data[3]);
        if (strpos($jenis, 'libur') !== false || strpos($jenis, 'sakit') !== false || strpos($jenis, 'ct') !== false || strpos($jenis, 'cuti') !== false) {
            print_r($data);
            $found = true;
            break;
        }
    }
}
if (! $found) {
    echo "No L/S/CT found in jenis_order. Checking segment...\n";
}

foreach ($rows as $row) {
    $data = json_decode($row->data, true);
    if (isset($data[2])) { // segment
        $seg = strtolower($data[2]);
        if (strpos($seg, 'libur') !== false || strpos($seg, 'sakit') !== false || strpos($seg, 'ct') !== false || strpos($seg, 'cuti') !== false) {
            print_r($data);
            $found = true;
            break;
        }
    }
}
if (! $found) {
    echo "No L/S/CT found in segment either. Checking ALL columns...\n";
}

foreach ($rows as $row) {
    $data = json_decode($row->data, true);
    foreach ($data as $col) {
        $val = strtolower((string) $col);
        if ($val === 'l' || $val === 's' || $val === 'ct' || $val === 'sakit' || $val === 'libur' || $val === 'cuti') {
            print_r($data);
            $found = true;
            break 2;
        }
    }
}
