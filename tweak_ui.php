<?php
$dataFile = 'resources/views/livewire/data-viewer.blade.php';
$dashFile = 'resources/views/livewire/dashboard-viewer.blade.php';

// Tweak Data Viewer
$data = file_get_contents($dataFile);

// 1. Wrap Date filters and buttons properly
$dataOldDates = '            <!-- Date Filters -->
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <input wire:model.live="startDate" type="date" class="block w-full sm:w-auto py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all" title="Dari Tanggal">
                <span class="text-text-muted font-bold">-</span>
                <input wire:model.live="endDate" type="date" class="block w-full sm:w-auto py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all" title="Sampai Tanggal">
            </div>

            <!-- Add Data Button (Admin Only) -->
            @auth
            <button wire:click="openAddModal" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-[#006400] hover:bg-[#004d00] rounded-full shadow-card transition-colors w-full sm:w-[130px] whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah
            </button>
            @endauth

            <!-- Refresh Button -->
            <button wire:click="resetData" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors w-full sm:w-[130px] whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>';

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

$data = str_replace($dataOldDates, $dataNewDates, $data);
file_put_contents($dataFile, $data);


// Tweak Dashboard Viewer
$dash = file_get_contents($dashFile);

$dashOldTitle = '<h2 class="text-xl font-bold text-telkom-red px-4 pt-4 pb-2 uppercase tracking-wide">{{ $segmentTitle }}</h2>';
$dashNewTitle = '<h2 class="text-sm sm:text-base md:text-xl font-bold text-telkom-red px-4 pt-4 pb-2 uppercase tracking-tight break-words whitespace-normal leading-tight">{{ $segmentTitle }}</h2>';
$dash = str_replace($dashOldTitle, $dashNewTitle, $dash);

$dashOldDate = '<div x-show="open" style="display: none;" class="flex items-center space-x-2 bg-red-50 p-2 rounded-xl border border-red-100 shadow-sm relative">
                    <span class="text-xs font-bold text-telkom-red uppercase tracking-wider hidden sm:block mx-2">Ubah Periode:</span>
                    <input type="date" wire:model="startDate" class="px-2 py-1.5 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red outline-none">
                    <span class="text-text-subtle text-xs">s/d</span>
                    <input type="date" wire:model="endDate" class="px-2 py-1.5 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red outline-none">
                    <button wire:click="applyDateFilter" @click="open = false" class="px-4 py-1.5 font-bold text-xs bg-telkom-red text-white hover:bg-telkom-red-hover rounded-lg transition-colors flex items-center" title="Buat & Terapkan Periode Baru">
                        Terapkan
                    </button>
                </div>';
$dashNewDate = '<div x-show="open" style="display: none;" class="flex flex-row flex-wrap sm:flex-nowrap items-center gap-2 bg-red-50 p-3 rounded-xl border border-red-100 shadow-sm relative mt-2 w-full">
                    <span class="text-xs font-bold text-telkom-red uppercase tracking-wider hidden sm:block mx-2 shrink-0">Ubah Periode:</span>
                    <div class="flex-1 sm:flex-none">
                        <input type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="Dari" wire:model="startDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
                    </div>
                    <span class="text-text-subtle text-xs font-bold shrink-0">s/d</span>
                    <div class="flex-1 sm:flex-none">
                        <input type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="Sampai" wire:model="endDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
                    </div>
                    <button wire:click="applyDateFilter" @click="open = false" class="w-full sm:w-auto px-5 py-2 font-bold text-xs bg-telkom-red text-white hover:bg-telkom-red-hover rounded-lg transition-colors flex items-center justify-center shrink-0 mt-1 sm:mt-0">
                        Terapkan
                    </button>
                </div>';
$dash = str_replace($dashOldDate, $dashNewDate, $dash);
file_put_contents($dashFile, $dash);

echo "DONE\n";
