<?php

namespace App\Console\Commands;

use App\Services\ProductivityService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('productivity:calculate')]
#[Description('Hitung ulang Tabel Produktivitas, Heatmap, dan Peringkat dari Data Sheet Utama')]
class CalculateProductivity extends Command
{
    public function handle(ProductivityService $service): int
    {
        $this->info('Menghitung ulang data produktivitas dari SheetData...');
        $count = $service->recalculateAndSave();
        $this->info("Perhitungan selesai! {$count} baris berhasil diperbarui.");

        return 0;
    }
}
