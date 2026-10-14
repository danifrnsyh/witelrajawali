<div id="export-sektoral-area" class="space-y-6 pb-8">
    <!-- Feedback Alerts -->
    @if(session()->has('success'))
        <div class="p-4 bg-green-50 text-green-700 text-sm font-semibold rounded-2xl border border-green-200 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="p-4 bg-red-50 text-red-700 text-sm font-semibold rounded-2xl border border-red-200 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- BOX 1: Header Utama -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main">Peringkat Sektoral</h1>
            <p class="mt-2 text-sm text-text-muted">Periode: <span class="font-bold text-telkom-red">{{ $activePeriodText }}</span> | Dibuat: {{ $lastUpdated }}</p>
        </div>
        <div class="flex flex-col gap-3 w-full md:w-auto items-end relative z-10">
            <!-- Top Controls Row -->
            <div class="flex flex-col sm:flex-row gap-3 w-full items-center justify-end">
                <!-- Export Buttons -->
                <div class="flex space-x-2 bg-surface-subtle p-1 rounded-full border border-border-card/60 export-btn-hide justify-center items-center shrink-0">
                    <button onclick="exportToJPG('export-sektoral-area', 'Peringkat_Sektoral')" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        JPG
                    </button>
                    <button onclick="exportToPDF('export-sektoral-area', 'Peringkat_Sektoral')" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </button>
                </div>

                <div class="flex flex-row items-center gap-2 w-full sm:w-auto">
                    <!-- Search Bar -->
                    <div class="relative flex-1 sm:w-56 w-full export-btn-hide">
                        <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari nama atau NIK...">
                    </div>

                    <!-- Refresh Button -->
                    <button wire:click="loadData" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh Data">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>

            @auth
            <!-- Date Range Creator (Below) -->
            <div x-data="{ open: false }" class="flex flex-col items-end export-btn-hide w-full sm:w-auto mt-2 justify-end">
                <!-- Tombol Saat Tertutup -->
                <div x-show="!open" class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    @if(!empty($startDate) && !empty($endDate))
                        <button wire:click="resetDateFilter" class="h-[42px] px-4 text-sm font-semibold text-telkom-red bg-red-50 hover:bg-red-100 rounded-full border border-red-200 transition-colors inline-flex items-center gap-1.5 shrink-0 shadow-2xs" title="Reset ke Default">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Reset Periode</span>
                        </button>
                    @endif
                    <button @click="open = true" class="h-[42px] px-5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-all inline-flex items-center justify-center w-full sm:w-auto shrink-0">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Ubah Periode Data</span>
                    </button>
                </div>

                <!-- Form Ubah Periode Saat Terbuka -->
                <div x-show="open" style="display: none;" class="min-h-[42px] flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 bg-red-50/90 p-2 sm:py-1 sm:pl-4 sm:pr-5 rounded-2xl sm:rounded-full border border-red-200/80 shadow-sm w-full sm:w-auto box-border">
                    <!-- Label & Close Icon (Mobile) -->
                    <div class="flex items-center justify-between sm:justify-start gap-1.5 shrink-0">
                        <span class="text-xs font-bold text-telkom-red tracking-wider uppercase flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Ubah Periode:
                        </span>
                        <button @click="open = false" class="sm:hidden text-text-muted hover:text-text-main p-0.5" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Input Tanggal Dari & Sampai -->
                    <div class="flex items-center gap-1.5 w-full sm:w-auto justify-between shrink-0">
                        <div class="h-[34px] flex-1 sm:flex-initial flex items-center bg-white border border-border-card rounded-full px-3 focus-within:ring-1 focus-within:ring-telkom-red focus-within:border-telkom-red transition-all shadow-2xs">
                            <span class="text-xs font-semibold text-text-muted mr-1.5 shrink-0 whitespace-nowrap">Dari:</span>
                            <input type="date" wire:model="startDate" class="w-full sm:w-[115px] text-xs text-text-main font-semibold bg-transparent border-0 p-0 focus:ring-0 outline-none cursor-pointer">
                        </div>
                        <span class="text-text-muted text-xs font-bold shrink-0 px-0.5">s/d</span>
                        <div class="h-[34px] flex-1 sm:flex-initial flex items-center bg-white border border-border-card rounded-full px-3 focus-within:ring-1 focus-within:ring-telkom-red focus-within:border-telkom-red transition-all shadow-2xs">
                            <span class="text-xs font-semibold text-text-muted mr-1.5 shrink-0 whitespace-nowrap">Sampai:</span>
                            <input type="date" wire:model="endDate" class="w-full sm:w-[115px] text-xs text-text-main font-semibold bg-transparent border-0 p-0 focus:ring-0 outline-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-1.5 w-full sm:w-auto justify-end shrink-0">
                        <button wire:click="applyDateFilter" @click="open = false" class="h-[34px] flex-1 sm:flex-none px-4 text-xs font-bold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full transition-colors inline-flex items-center justify-center shrink-0 shadow-sm active:scale-95 whitespace-nowrap">
                            Terapkan
                        </button>
                        @if(!empty($startDate) && !empty($endDate))
                            <button wire:click="resetDateFilter" class="h-[34px] px-3 text-xs font-bold text-telkom-red hover:text-telkom-red-hover bg-white hover:bg-red-50 border border-red-200 rounded-full transition-colors inline-flex items-center justify-center shrink-0 active:scale-95 whitespace-nowrap" title="Reset Periode">
                                Reset
                            </button>
                        @endif
                        <button @click="open = false" class="h-[34px] flex-1 sm:flex-none px-3.5 text-xs font-semibold text-text-muted hover:text-telkom-red bg-white hover:bg-gray-50 border border-border-card rounded-full transition-colors inline-flex items-center justify-center shrink-0 active:scale-95 shadow-2xs whitespace-nowrap">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>

    <!-- BOX 2: Parameter Penilaian -->
    <div class="bg-surface-card border border-border-card rounded-[32px] shadow-sm p-4 sm:p-6">
        <h3 class="text-sm font-bold text-text-main mb-4 pl-1">Parameter Penilaian</h3>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 align-middle rounded-l-full min-w-[200px]">Parameter</th>
                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 align-middle {{ !auth()->check() ? 'rounded-r-full' : '' }}">Bobot / Nilai</th>
                        @auth
                            <th class="no-export px-5 py-4 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 rounded-r-full text-center align-middle min-w-[100px]">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-[13px] text-text-main">
                    @foreach($parameters as $param)
                        <tr class="hover:bg-surface-subtle transition-colors group">
                            <td class="px-5 py-3.5 text-sm text-text-muted font-medium w-2/3">{{ $param['name'] }}</td>
                            <td class="px-5 py-3.5 text-sm text-text-main font-bold">{{ $param['value'] }}</td>
                            @auth
                            <td class="no-export px-5 py-3.5 text-center whitespace-nowrap sticky right-0 bg-surface-card group-hover:bg-surface-subtle transition-colors">
                                <button wire:click="openEditParamModal({{ $param['row_index'] }})" class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all inline-flex items-center justify-center mx-auto" title="Edit Bobot">
                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                </button>
                            </td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Segmented Control (Pill Toggle) Animasi Halus dengan AlpineJS -->
    <div x-data="{ localTab: '{{ $activeTab }}' }" class="export-btn-hide relative bg-white rounded-full p-1 shadow-sm flex w-full max-w-2xl mx-auto border border-gray-200 mt-8 mb-4 overflow-hidden">
        
        <!-- Sliding Red Indicator -->
        <div class="absolute top-1 bottom-1 w-[calc(33.333%-0.166rem)] bg-telkom-red rounded-full transition-transform duration-300 ease-out shadow-sm"
             :style="`transform: translateX(${localTab === '1_PSB_B2C' ? '0' : (localTab === '2_ASSR_B2C' ? '100%' : '200%')}); left: 0.25rem;`">
        </div>

        <!-- Buttons -->
        <button @click="localTab = '1_PSB_B2C'; $wire.setTab('1_PSB_B2C')" class="flex-1 relative z-10 py-2.5 text-sm sm:text-[15px] font-bold rounded-full transition-colors duration-300" :class="localTab === '1_PSB_B2C' ? 'text-white' : 'text-gray-600 hover:text-gray-900'">
            PSB B2C
        </button>
        <button @click="localTab = '2_ASSR_B2C'; $wire.setTab('2_ASSR_B2C')" class="flex-1 relative z-10 py-2.5 text-sm sm:text-[15px] font-bold rounded-full transition-colors duration-300" :class="localTab === '2_ASSR_B2C' ? 'text-white' : 'text-gray-600 hover:text-gray-900'">
            ASSURANCE B2C
        </button>
        <button @click="localTab = '3_ASSR_B2B'; $wire.setTab('3_ASSR_B2B')" class="flex-1 relative z-10 py-2.5 text-sm sm:text-[15px] font-bold rounded-full transition-colors duration-300" :class="localTab === '3_ASSR_B2B' ? 'text-white' : 'text-gray-600 hover:text-gray-900'">
            ASSURANCE B2B
        </button>
    </div>
    
    <!-- Teks Info Print -->
    <div class="hidden export-show-text text-center text-lg font-bold text-text-main my-6 bg-surface-subtle p-3 rounded-full border border-border-card">
        Tabel Data: @if($activeTab === '1_PSB_B2C') PSB B2C @elseif($activeTab === '2_ASSR_B2C') ASSURANCE B2C @else ASSURANCE B2B @endif
    </div>

    <!-- BOX 3: Data Tabel Utama -->
    <div class="bg-surface-card border border-border-card rounded-[32px] shadow-sm relative z-0 p-4 sm:p-6 min-h-[400px]">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr>
                        @foreach($tableHeaders as $header)
                            <th class="px-4 py-4 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 align-middle min-w-[90px] {{ $loop->first ? 'rounded-l-full' : '' }} {{ !auth()->check() && $loop->last ? 'rounded-r-full' : '' }}">
                                <button wire:click="sortBy('{{ $header }}')" class="flex flex-col items-center justify-center w-full focus:outline-none hover:text-telkom-red transition-colors text-center leading-snug">
                                    <span class="whitespace-pre-line">{{ $header }}</span>
                                    @if($sortColumn === $header)
                                        <span class="mt-1 text-telkom-red shrink-0 export-btn-hide">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </button>
                            </th>
                        @endforeach
                        @auth
                            <th class="no-export px-4 py-4 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 rounded-r-full text-center align-middle min-w-[100px]">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-[13px] text-text-main">
                    @forelse($displayData as $row)
                        <tr class="hover:bg-surface-subtle transition-colors group">
                            @foreach($tableHeaders as $header)
                                <td class="px-4 py-4 text-[13px] text-center whitespace-nowrap {{ (strpos(strtoupper($header), 'SKOR AKHIR') !== false || strpos(strtoupper($header), 'WORLDSKILLS') !== false) ? 'font-bold text-telkom-red' : 'text-text-main' }} {{ !auth()->check() && $loop->last ? 'rounded-r-full' : '' }}">
                                    {{ $row[$header] ?? '-' }}
                                    @if(isset($row['orders_in_period']) && $loop->first)
                                        <span class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-red-100 text-telkom-red" title="Order dalam periode">({{ $row['orders_in_period'] }} order)</span>
                                    @endif
                                </td>
                            @endforeach
                            @auth
                            <td class="no-export px-4 py-4 text-center whitespace-nowrap sticky right-0 bg-surface-card group-hover:bg-surface-subtle transition-colors">
                                <button wire:click="editRow({{ $row['original_row_index'] }})" class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all flex items-center justify-center mx-auto" title="Edit Data">
                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                </button>
                            </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($tableHeaders) + (auth()->check() ? 1 : 0) }}" class="px-5 py-8 text-center text-text-muted">
                                Belum ada data atau tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT PARAMETER BOBOT -->
    @if($isEditParamModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-lg relative flex flex-col overflow-hidden transform transition-all">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-text-main">Edit Parameter Penilaian</h3>
                    <p class="text-xs text-text-muted mt-0.5">Tab Spreadsheet: <span class="font-semibold text-telkom-red">{{ $activeTab }}</span></p>
                </div>
                <button wire:click="closeEditParamModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8 bg-surface-card space-y-4">
                <div>
                    <label class="text-xs font-semibold text-text-muted mb-2 block uppercase tracking-wide">Nama Parameter</label>
                    <input type="text" value="{{ $editingParamName }}" disabled class="w-full px-4 py-2.5 bg-gray-100 border border-border-card rounded-xl text-sm font-medium text-text-muted cursor-not-allowed outline-none">
                </div>

                <div>
                    <label class="text-xs font-semibold text-text-muted mb-2 block uppercase tracking-wide">Bobot / Nilai</label>
                    <input type="text" wire:model="editingParamValue" placeholder="Contoh: 10% atau 0.1" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-bold text-text-main focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
                    <p class="text-[11px] text-text-muted mt-1.5">Nilai ini akan otomatis ter-update langsung ke Google Spreadsheet di tab {{ $activeTab }}.</p>
                </div>
            </div>
            
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle grid grid-cols-2 sm:flex sm:flex-row sm:justify-end gap-3 shrink-0">
                <button wire:click="closeEditParamModal" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-border-card rounded-full text-center flex items-center justify-center text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">
                    Batal
                </button>
                <button wire:click="saveParameter" class="w-full sm:w-auto px-6 py-2.5 bg-telkom-red text-white rounded-full text-center flex items-center justify-center text-sm font-bold hover:bg-telkom-red-hover shadow-sm transition-colors">
                    <svg wire:loading wire:target="saveParameter" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL EDIT DATA BARIS TEKNISI -->
    @if($isEditRowModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-5xl relative flex flex-col overflow-hidden transform transition-all" style="max-height: 90vh;">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-text-main">Edit Data (Baris {{ $editingRowIndex }})</h3>
                    <p class="text-xs text-text-muted mt-0.5">Tab Spreadsheet: <span class="font-semibold text-telkom-red">{{ $activeTab }}</span></p>
                </div>
                <button wire:click="closeEditRowModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8 overflow-y-auto grow bg-surface-card">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tableHeaders as $header)
                        @if(trim($header) !== '')
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-text-muted mb-2 uppercase tracking-wide">{{ $header }}</label>
                            <input type="text" wire:model="editForm.{{ $header }}" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle grid grid-cols-2 sm:flex sm:flex-row sm:justify-end gap-3 shrink-0">
                <button wire:click="closeEditRowModal" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-border-card rounded-full text-center flex items-center justify-center text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">Batal</button>
                <button wire:click="saveRow" class="w-full sm:w-auto px-6 py-2.5 bg-telkom-red text-white rounded-full text-center flex items-center justify-center text-sm font-bold hover:bg-telkom-red-hover shadow-sm transition-colors">
                    <svg wire:loading wire:target="saveRow" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
