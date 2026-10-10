<div class="space-y-6" id="export-dashboard-area">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <h1 class="text-3xl font-bold tracking-tight text-text-main">{{ $title }}</h1>
                @auth
                    <button wire:click="editTitle" class="export-btn-hide p-1.5 text-text-muted hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors" title="Edit Judul">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                @endauth
            </div>
            <p class="mt-2 text-sm text-text-muted">{{ array_sum(array_map('count', $segments)) }} Teknisi &bull; Bulan Ini</p>
        </div>
        
        <div class="flex flex-col gap-3 w-full md:w-auto items-end relative z-10">
            
            <!-- Top Controls Row -->
            <div class="flex flex-col sm:flex-row gap-3 w-full items-center justify-end">
                <!-- Export Buttons -->
                <div class="flex space-x-2 bg-surface-subtle p-1 rounded-full border border-border-card/60">
                    <button onclick="exportToJPG('export-dashboard-area', 'Dashboard_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        JPG
                    </button>
                    <button onclick="exportToPDF('export-dashboard-area', 'Dashboard_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="relative w-full sm:w-56 export-btn-hide">
                    <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari teknisi...">
                </div>

                <!-- Refresh Button -->
                <button wire:click="recalculate" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                    <svg wire:loading.remove wire:target="recalculate" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <svg wire:loading wire:target="recalculate" class="w-4 h-4 shrink-0 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                </button>
            </div>

            @auth
            <!-- Date Range Creator (Below) -->
            <div x-data="{ open: false }" class="flex flex-col items-end space-y-2 export-btn-hide w-full sm:w-auto mt-2 justify-end">
                <button x-show="!open" @click="open = true" class="px-5 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors flex items-center justify-center w-full sm:w-auto shrink-0">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Ubah Periode Data
                </button>
                <div x-show="open" style="display: none;" class="flex flex-col sm:flex-row flex-wrap sm:flex-nowrap items-center gap-2 bg-red-50 p-3 rounded-xl border border-red-100 shadow-sm relative mt-2 w-full box-border">
                    <span class="text-xs font-bold text-telkom-red uppercase tracking-wider w-full sm:w-auto text-center sm:text-left mx-2 shrink-0">Ubah Periode:</span>
                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="Dari Tanggal" wire:model="startDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
                        <span class="text-text-subtle text-xs font-bold shrink-0">s/d</span>
                        <input type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="Sampai Tanggal" wire:model="endDate" class="w-full sm:w-[130px] px-3 py-2 text-sm text-text-main border border-border-card rounded-lg bg-white focus:ring-1 focus:ring-telkom-red outline-none min-h-[40px]">
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
            @endauth
        </div>
    </div>

    <!-- Master Segment Loop -->
    @forelse($segments as $segmentTitle => $rows)
    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card flex flex-col overflow-hidden relative p-4 mb-6">
        <h2 class="text-sm sm:text-base md:text-xl font-bold text-telkom-red px-4 pt-4 pb-2 uppercase tracking-tight break-words whitespace-normal leading-tight">{{ $segmentTitle }}</h2>
        <div class="relative">
            <div class="overflow-x-auto relative z-10">
                @php
                    $totalColIndex = count($headers);
                    foreach($headers as $idx => $h) {
                        if (str_contains(strtoupper($h ?? ''), 'TOTAL')) {
                            $totalColIndex = $idx;
                            break;
                        }
                    }
                @endphp
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main whitespace-nowrap bg-gray-100 {{ $loop->first ? 'rounded-l-full' : '' }} {{ !auth()->check() && $loop->last ? 'rounded-r-full' : '' }}">{{ $header }}</th>
                            @endforeach
                            @auth
                                <th class="no-export px-3 sm:px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main text-right bg-gray-100 rounded-r-full sticky right-0 z-20 drop-shadow-[-2px_0_2px_rgba(0,0,0,0.02)]">Aksi</th>
                            @endauth
                        </tr>
                    </thead>
                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-[13px] text-text-main">
                    @foreach($rows as $row)
                        @php
                            $isHeaderRow = (isset($row['data'][0]) && $row['data'][0] == 'NO') && (isset($row['data'][1]) && $row['data'][1] == 'NIK');
                            $isTotalRow = $row['is_total'];
                            
                            $rowClass = 'hover:bg-surface-subtle transition-colors group ';
                            $textClass = '';
                            
                            if ($isHeaderRow) {
                                $rowClass = '';
                                $textClass = 'bg-surface-subtle font-semibold text-text-muted text-xs uppercase tracking-wider';
                            } elseif ($isTotalRow) {
                                $rowClass = '';
                                $textClass = 'bg-gray-50 font-bold text-text-main';
                            }
                        @endphp
                        <tr wire:key="dash-row-{{ $row['row_index'] ?? $loop->index }}-{{ md5(json_encode($row['data'] ?? [])) }}" class="{{ $rowClass }}">
                            @for($i = 0; $i < count($headers); $i++)
                                <td class="px-5 py-4 whitespace-nowrap align-middle {{ $i === 0 && ($isHeaderRow || $isTotalRow) ? 'rounded-l-full' : '' }} {{ $i === count($headers) - 1 && ($isHeaderRow || $isTotalRow) && !auth()->check() ? 'rounded-r-full' : '' }} {{ $textClass }} {{ $i > 3 && $i < $totalColIndex ? 'text-center' : '' }}">
                                    @if($i > 3 && $i < $totalColIndex && !$isHeaderRow && !$isTotalRow)
                                        @php
                                            $val = $row['data'][$i] ?? '';
                                            $badgeClass = 'bg-surface-subtle text-text-muted'; // default empty
                                            if (is_numeric($val) && trim($val) !== '') {
                                                $num = (float) $val;
                                                if ($num >= 1.5) {
                                                    $badgeClass = 'bg-green-100 text-green-700';
                                                } elseif ($num >= 1.0) {
                                                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                } else {
                                                    $badgeClass = 'bg-red-50 text-red-600';
                                                }
                                            } elseif (in_array(trim($val), ['L', 'S', 'CT'])) {
                                                $badgeClass = 'bg-gray-100 text-gray-400 font-normal';
                                            }
                                        @endphp
                                        @if($val !== '')
                                            <div class="inline-block align-middle min-w-[28px] h-[28px] pt-[7px] px-1.5 text-center rounded-lg text-[11px] font-bold leading-none {{ $badgeClass }}">
                                                {{ $val }}
                                            </div>
                                        @else
                                            <span class="inline-block align-middle text-text-subtle/50 text-xs">-</span>
                                        @endif
                                    @else
                                        @php
                                            $cellVal = $row['data'][$i] ?? '-';
                                            $cellClass = '';
                                            if (!$isHeaderRow && !$isTotalRow && strtoupper($headers[$i] ?? '') === 'PROD' && is_numeric($cellVal)) {
                                                $cellClass = $cellVal >= 1.5 ? 'text-green-600 font-extrabold' : ($cellVal >= 1.0 ? 'text-yellow-500 font-extrabold' : 'text-telkom-red font-extrabold');
                                            }
                                        @endphp
                                        <span class="{{ $cellClass }}">{{ $cellVal }}</span>
                                    @endif
                                </td>
                            @endfor
                            @auth
                                <td class="no-export px-3 sm:px-5 py-4 whitespace-nowrap text-right sticky right-0 border-l border-border-card/50 transition-colors {{ $isHeaderRow || $isTotalRow ? $textClass . ' rounded-r-full' : 'bg-surface-card group-hover:bg-surface-subtle' }}">
                                    @if(!$isHeaderRow)
                                        <button wire:click="editRow({{ $row['row_index'] ?? 0 }})" class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all flex items-center justify-center mx-auto sm:mx-0 sm:ml-auto" title="Edit">
                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                    </button>
                                    @endif
                                </td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card flex flex-col overflow-hidden relative p-4 mb-6">
        <div class="px-6 py-16 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-surface-subtle rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-text-main font-medium">Data Dashboard tidak ditemukan.</p>
            </div>
        </div>
    </div>
    @endforelse

    <!-- MODAL EDIT ADMIN -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-5xl relative flex flex-col overflow-hidden transform transition-all" style="max-height: 90vh;">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <h3 class="text-lg font-bold text-text-main">Edit Data Dashboard (Baris {{ $editingRowIndex }})</h3>
                <button wire:click="closeEditModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8 overflow-y-auto grow  bg-surface-card">
                @if(session()->has('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-medium rounded-xl border border-green-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-700 text-sm font-medium rounded-xl border border-red-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($headers as $index => $header)
                        @if(trim($header) !== '')
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-text-muted mb-2 uppercase tracking-wide">{{ $header }}</label>
                            <input type="text" wire:model="editForm.{{ $index }}" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle flex justify-end space-x-3 shrink-0">
                <button wire:click="closeEditModal" class="px-6 py-2.5 bg-white border border-border-card rounded-full text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">Batal</button>
                <button wire:click="saveRow" class="px-6 py-2.5 bg-telkom-red text-white rounded-full text-sm font-bold hover:bg-telkom-red-hover shadow-sm flex items-center transition-colors">
                    <svg wire:loading wire:target="saveRow" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL EDIT JUDUL -->
    @if($isTitleEditModalOpen)
    <div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-xl relative flex flex-col overflow-hidden transform transition-all">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <h3 class="text-lg font-bold text-text-main">Edit Judul Dashboard</h3>
                <button wire:click="closeTitleEditModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8 bg-surface-card">
                <label class="text-xs font-semibold text-text-muted mb-2 uppercase tracking-wide block">Judul Halaman</label>
                <input type="text" wire:model="titleForm" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
            </div>
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle flex justify-end space-x-3 shrink-0">
                <button wire:click="closeTitleEditModal" class="px-6 py-2.5 bg-white border border-border-card rounded-full text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">Batal</button>
                <button wire:click="saveTitle" class="px-6 py-2.5 bg-telkom-red text-white rounded-full text-sm font-bold hover:bg-telkom-red-hover shadow-sm flex items-center transition-colors">
                    <svg wire:loading wire:target="saveTitle" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif
</div>






