<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = App\Models\DashboardData::orderBy('id', 'asc')->get();
$current = null;
$toDelete = [];
foreach ($rows as $r) {
    $d = $r->data;
    if (empty($d) || !is_array($d)) continue;
    
    $nonEmpty = collect($d)->filter(fn ($v) => trim($v ?? '') !== '')->count();
    if ($nonEmpty === 1 && !empty($d[0])) {
        $current = trim($d[0]);
    }
    
    $nik = trim($d[1] ?? '');
    if (in_array($nik, ['19810003', '18960355'])) {
        echo "Found NIK: $nik in Segment: $current (ID: $r->id)\n";
        if (stripos($current, 'B2B') !== false) {
            $toDelete[] = $r->id;
        }
    }
}

if (!empty($toDelete)) {
    echo "Deleting IDs: " . implode(', ', $toDelete) . "\n";
    App\Models\DashboardData::whereIn('id', $toDelete)->delete();
    
    // Now we must trigger a recalculation so the Google Sheet is updated!
    echo "Triggering recalculation...\n";
    $service = new App\Services\ProductivityService();
    $service->recalculateAndSave(null, true);
    echo "DONE\n";
} else {
    echo "No IDs to delete found in B2B.\n";
}
