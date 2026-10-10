<?php
require '/var/www/webwitelrajawali/vendor/autoload.php';
$app = require_once '/var/www/webwitelrajawali/bootstrap/app.php';
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
        if (stripos($current, 'B2B') !== false) {
            $toDelete[] = $r->id;
        }
    }
}

if (!empty($toDelete)) {
    App\Models\DashboardData::whereIn('id', $toDelete)->delete();
    $service = new App\Services\ProductivityService();
    $service->recalculateAndSave(null, true);
    echo "BERHASIL\n";
} else {
    echo "SUDAH BERSIH\n";
}
