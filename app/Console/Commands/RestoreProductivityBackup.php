<?php

namespace App\Console\Commands;

use App\Services\ProductivityService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('productivity:restore')]
#[Description('Undo dan kembalikan data produktivitas ke backup original')]
class RestoreProductivityBackup extends Command
{
    public function handle(ProductivityService $service): int
    {
        $this->info('Mengembalikan data produktivitas dari backup...');
        if ($service->restoreBackup()) {
            $this->info('Data produktivitas berhasil dikembalikan ke versi backup!');

            return 0;
        }

        $this->error('File backup tidak ditemukan atau kosong.');

        return 1;
    }
}
