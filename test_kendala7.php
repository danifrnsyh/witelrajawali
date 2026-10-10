<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$laborCode = '18980509'; // MOH WILDAN FIRDAUS
$kendalaStats = ['total_kendala' => 0, 'total_libur' => 0, 'total_sakit' => 0, 'total_ct' => 0, 'top_kendala' => [], 'history' => []];

$headerRecord = DB::table('dashboard_data')->where('data', 'LIKE', '["NO",%')->first();
$dashRecord = DB::table('dashboard_data')->where('data', 'LIKE', '%"'.$laborCode.'"%')->first();

if ($headerRecord && $dashRecord) {
    $headers = json_decode($headerRecord->data, true);
    $dashData = json_decode($dashRecord->data, true);

    for ($i = 4; $i <= 34; $i++) {
        if (! isset($headers[$i]) || ! isset($dashData[$i])) {
            continue;
        }

        $val = strtoupper(trim((string) $dashData[$i]));
        if (in_array($val, ['L', 'S', 'CT', 'CUTI', 'SAKIT', 'LIBUR'])) {
            $kendalaStats['history'][] = [
                'tanggal' => $headers[$i],
                'jenis' => $val,
                'nama' => 'MOH WILDAN',
                'nilai' => 'Absen',
            ];

            if ($val === 'L' || $val === 'LIBUR') {
                $kendalaStats['total_libur']++;
            } elseif ($val === 'S' || $val === 'SAKIT') {
                $kendalaStats['total_sakit']++;
            } else {
                $kendalaStats['total_ct']++;
            }

            $kendalaStats['total_kendala']++;
        }
    }
}
print_r($kendalaStats);
