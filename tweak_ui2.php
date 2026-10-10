<?php
$dataFile = 'resources/views/livewire/data-viewer.blade.php';
$data = file_get_contents($dataFile);

$dataNewDates = '            <!-- Date Filters -->
            <div class="flex flex-row items-center space-x-2 w-full sm:w-auto justify-center mt-2 sm:mt-0">
                <div class="relative w-1/2 sm:w-auto">
                    <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Dari Tanggal</span>
                    <input wire:model.live="startDate" type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                </div>
                <span class="text-text-muted font-bold hidden sm:block">-</span>
                <div class="relative w-1/2 sm:w-auto">
                    <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Sampai Tanggal</span>
                    <input wire:model.live="endDate" type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                </div>
            </div>

            <div class="flex flex-row items-center space-x-2 w-full sm:w-auto mt-2 sm:mt-0 justify-between">
                <!-- Add Data Button (Admin Only) -->
                @auth
                <button wire:click="openAddModal" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-[#006400] hover:bg-[#004d00] rounded-full shadow-card transition-colors shrink-0">
                    <svg class="w-4 h-4 sm:mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="hidden sm:inline">Tambah</span>
                </button>
                @endauth
    
                <!-- Refresh Button -->
                <button wire:click="resetData" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors shrink-0">
                    <svg class="w-4 h-4 sm:mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span class="hidden sm:inline">Refresh</span>
                </button>
            </div>';

$data = preg_replace('/<!-- Date Filters -->.*?<\/button>\s*<\/div>/s', $dataNewDates."\n        </div>", $data);
file_put_contents($dataFile, $data);
echo "DONE\n";
