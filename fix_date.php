<?php
$dashFile = 'resources/views/livewire/dashboard-viewer.blade.php';
$dash = file_get_contents($dashFile);

$newCode = '@auth
            <!-- Date Range Creator (Below) -->
            <div x-data="{ open: false }" class="flex flex-col items-end space-y-2 export-btn-hide w-full sm:w-auto mt-2 justify-end">
                <button x-show="!open" @click="open = true" class="px-4 py-2 font-bold text-xs bg-surface-subtle text-text-muted hover:text-telkom-red border border-border-card rounded-xl hover:bg-telkom-red-light transition-colors flex items-center w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Ubah Periode Data
                </button>
                <div x-show="open" style="display: none;" class="flex flex-col sm:flex-row flex-wrap sm:flex-nowrap items-center gap-2 bg-red-50 p-3 rounded-xl border border-red-100 shadow-sm relative mt-2 w-full box-border">
                    <span class="text-xs font-bold text-telkom-red uppercase tracking-wider w-full sm:w-auto text-center sm:text-left mx-2 shrink-0">Ubah Periode:</span>
                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="Dari Tanggal" wire:model="startDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
                        <span class="text-text-subtle text-xs font-bold shrink-0">s/d</span>
                        <input type="text" onfocus="(this.type=\'date\')" onblur="if(!this.value)this.type=\'text\'" placeholder="Sampai Tanggal" wire:model="endDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                        <button wire:click="applyDateFilter" @click="open = false" class="w-full sm:w-auto px-5 py-2 font-bold text-xs bg-telkom-red text-white hover:bg-telkom-red-hover rounded-lg transition-colors flex items-center justify-center shrink-0">
                            Terapkan
                        </button>
                        <button @click="open = false" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-text-muted hover:text-telkom-red rounded-lg transition-colors bg-white border border-border-card flex items-center justify-center shrink-0" title="Batal">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
            @endauth';

$pattern = '/<!-- Date Range Creator \(Below\).*?<\/button>\s*<\/div>\s*<\/div>/s';
$dash = preg_replace($pattern, $newCode, $dash);
file_put_contents($dashFile, $dash);
echo "DONE\n";
