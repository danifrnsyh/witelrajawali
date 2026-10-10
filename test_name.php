<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$row = App\Models\SheetData::where('data', 'like', '%SHANDIKA DWI PUTRA%')->first();
if ($row) {
    echo "Found in SheetData:\n";
    print_r($row->data);
} else {
    echo "Not found in SheetData.\n";
}
