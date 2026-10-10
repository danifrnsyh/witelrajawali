<?php
// Fix data-viewer.blade.php
$file = "resources/views/livewire/data-viewer.blade.php";
$content = file_get_contents($file);

$startMarker = '<div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">';
$endMarker = '<!-- Master Grid Container -->';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker);

if ($startPos !== false && $endPos !== false) {
    $replacement = <<<EOF
<div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
              
              <!-- Row 1: Search & Refresh -->
              <div class="flex flex-row items-center gap-2 w-full sm:w-auto order-1 sm:order-none">
                  <div class="relative flex-1 w-full sm:w-48 xl:w-64">
                      <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                      <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari data...">
                  </div>
                  
                  <button wire:click="resetData" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                  </button>
              </div>

              <!-- Date Filters -->
              <div class="flex flex-row items-center space-x-2 w-full sm:w-auto justify-center mt-2 sm:mt-0 order-2 sm:order-none">
                  <div class="relative w-1/2 sm:w-auto">
                      <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Dari Tanggal</span>
                      <input wire:model.live="startDate" type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                  </div>
                  <span class="text-text-muted font-bold hidden sm:block">-</span>
                  <div class="relative w-1/2 sm:w-auto">
                      <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Sampai Tanggal</span>
                      <input wire:model.live="endDate" type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                  </div>
              </div>

              <!-- Add Data Button -->
              @auth
              <div class="w-full sm:w-auto order-3 sm:order-none mt-2 sm:mt-0">
                  <button wire:click="openAddModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-[#006400] hover:bg-[#004d00] rounded-full shadow-card transition-colors shrink-0">
                      <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                      <span class="inline">Tambah Data</span>
                  </button>
              </div>
              @endauth
              
          </div>
      </div>
  </div>

  <!-- Master Grid Container -->
EOF;

    $newContent = substr($content, 0, $startPos) . $replacement . substr($content, $endPos);
    file_put_contents($file, $newContent);
    echo "data-viewer updated\n";
} else {
    echo "data-viewer markers not found\n";
}

// Fix peringkat-viewer.blade.php
$file = "resources/views/livewire/peringkat-viewer.blade.php";
$content = file_get_contents($file);

$startMarker = '<div class="relative flex-1 w-full sm:w-64 export-btn-hide">';
$endMarker = '<!-- Refresh Button -->';
$endMarkerFull = strpos($content, '</button>', strpos($content, $endMarker));
if ($endMarkerFull !== false) {
    $endMarkerFull += 9;
}

$startPos = strpos($content, $startMarker);
$endPos = $endMarkerFull;

if ($startPos !== false && $endMarkerFull !== false) {
    $replacement = <<<EOF
<div class="flex flex-row items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 w-full sm:w-64 export-btn-hide">
                    <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama teknisi..." class="block w-full py-2.5 pl-10 pr-4 text-sm font-semibold text-text-main border border-border-card rounded-full bg-white focus:bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none">
                </div>
    
                <!-- Refresh Button -->
                <button wire:click="\$set('search', '')" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>
EOF;

    $newContent = substr($content, 0, $startPos) . $replacement . substr($content, $endPos);
    file_put_contents($file, $newContent);
    echo "peringkat-viewer updated\n";
} else {
    echo "peringkat-viewer markers not found\n";
}
?>
