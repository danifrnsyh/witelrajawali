<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dashboardRows = App\Models\DashboardData::orderBy('id', 'asc')->get();
$currentSegment = 'PROVISIONING';
$nikToUnit = [];
foreach ($dashboardRows as $index => $dbRow) {
    if ($index < 2) continue;
    $data = $dbRow->data;
    $nonEmptyCount = collect($data)->filter(fn ($v) => trim($v ?? '') !== '')->count();
    if ($nonEmptyCount === 1 && !empty($data[0])) {
        $rawTitle = strtoupper(trim($data[0]));
        $rawTitle = preg_replace('/^PRODUKTIVITAS\s+/', '', $rawTitle);
        $rawTitle = preg_replace('/\s+BULAN\s+.*$/', '', $rawTitle);
        if ($rawTitle === 'PSB B2C') $rawTitle = 'PROVISIONING';
        $currentSegment = trim($rawTitle);
        continue;
    }
    $isTotal = str_contains(strtoupper($data[1] ?? ''), 'TOTAL') || str_contains(strtoupper($data[0] ?? ''), 'TOTAL');
    if (!$isTotal && !empty($data[1])) {
        $nikToUnit[trim($data[1])] = $currentSegment;
    }
}
echo json_encode(array_slice($nikToUnit, 0, 10));
