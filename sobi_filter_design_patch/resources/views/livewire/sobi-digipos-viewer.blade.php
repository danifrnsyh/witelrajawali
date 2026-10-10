<div>
    <div class="space-y-6 relative z-10 animate-fade-in pb-10">
        <!-- Header Area -->
        <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card p-6 md:p-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4 relative overflow-hidden mb-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-bold tracking-tight text-text-main">Rekap SOBI/DIGIPOS</h1>
                <p class="mt-2 text-sm text-text-muted">Menampilkan data rekap untuk: <span class="font-bold text-telkom-red">{{ $activeMonthsString }}</span></p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-64 md:w-80">
                    <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-surface-subtle focus:bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari NIK, Nama...">
                </div>

                <!-- Buttons -->
                <div class="flex flex-row gap-2 w-full sm:w-auto">
                    @auth
                    <button wire:click="openEditModal" class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors shrink-0">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Atur Bulan
                    </button>
                    @endauth
                    <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="relative bg-surface-card border border-border-card rounded-[32px] shadow-sm overflow-hidden flex flex-col p-4">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr>
                            @php
                                $columns = [
                                    'nik' => 'NIK',
                                    'nama' => 'NAMA'
                                ];
                                foreach($activeMonths as $m) {
                                    $columns[$m] = strtoupper($m);
                                }
                                $columns['capaian'] = 'TOTAL CAPAIAN';
                                $columns['target'] = 'TARGET';
                            @endphp
                            
                            @foreach($columns as $colKey => $colLabel)
                                <th wire:click="sortBy('{{ $colKey }}')" class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 cursor-pointer group hover:bg-gray-200 transition-colors {{ $loop->first ? 'rounded-l-[26px]' : '' }} {{ $loop->last ? 'rounded-r-[26px] text-right' : '' }} {{ in_array($colKey, ['capaian', 'target']) || in_array($colKey, $activeMonths) ? 'text-center' : '' }}">
                                    <div class="flex items-center gap-1 min-w-max {{ $loop->last ? 'justify-end' : '' }} {{ in_array($colKey, ['capaian', 'target']) || in_array($colKey, $activeMonths) ? 'justify-center' : '' }}">
                                        {{ $colLabel }}
                                        @if($sortColumn === $colKey)
                                            <svg class="w-3.5 h-3.5 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-text-main text-[13px]">
                        @forelse($rows as $row)
                            <tr wire:key="row-{{ $row['nama'] }}" class="hover:bg-surface-subtle transition-colors group">
                                <td class="px-5 py-4 whitespace-nowrap font-variant-numeric tabular-nums font-mono text-text-muted">{{ $row['nik'] }}</td>
                                <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main">{{ $row['nama'] }}</td>
                                
                                @foreach($activeMonths as $m)
                                    @php 
                                        $score = $row['monthly_scores'][$m] ?? 0; 
                                        $targetPerBulan = 4;
                                    @endphp
                                    <td class="px-5 py-4 whitespace-nowrap font-bold text-center {{ $score >= $targetPerBulan ? 'text-green-600' : 'text-telkom-red' }}">
                                        @if($score > 0)
                                            <button type="button" 
                                                    wire:click="showDetails('{{ addslashes($row['nama']) }}', '{{ $m }}')"
                                                    class="cursor-pointer hover:underline focus:outline-none bg-transparent border-0 p-0 m-0 w-full h-full" 
                                                    title="Klik untuk lihat detail">
                                                {{ $score }}
                                            </button>
                                        @else
                                            <span class="text-text-muted">0</span>
                                        @endif
                                    </td>
                                @endforeach
                                
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-center {{ $row['capaian'] >= $row['target'] ? 'text-green-600' : 'text-telkom-red' }}">
                                    @if($row['capaian'] > 0)
                                        <button type="button" 
                                                wire:click="showDetails('{{ addslashes($row['nama']) }}', 'TOTAL')"
                                                class="cursor-pointer hover:underline focus:outline-none bg-transparent border-0 p-0 m-0 w-full h-full" 
                                                title="Klik untuk lihat detail keseluruhan">
                                            {{ $row['capaian'] }}
                                        </button>
                                    @else
                                        <span class="text-text-muted">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-text-muted text-right">{{ $row['target'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) }}" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-border-card">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-base font-bold text-text-main mb-1">Tidak Ada Data</h3>
                                        <p class="text-sm text-text-muted">Pastikan filter bulan sudah diatur dan Sync Data telah dilakukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rows->hasPages())
            <div class="px-6 py-4 bg-surface-card flex justify-center sm:justify-end mt-2">
                {{ $rows->links('vendor.livewire.pill-pagination') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Atur Bulan (Livewire) -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 transition-opacity" aria-hidden="true" wire:click="closeEditModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-surface-card rounded-[32px] text-left overflow-hidden shadow-card transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-border-card">
                <div class="px-6 pt-8 pb-6 sm:px-8 sm:py-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-telkom-red/10 sm:mx-0 sm:h-12 sm:w-12">
                            <svg class="h-6 w-6 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-text-main mb-2">Atur Filter Periode</h3>
                            <p class="text-sm text-text-muted mb-6">Pilih tahun dan bulan yang ingin ditampilkan. Target akan otomatis dikalikan 4 poin per bulan yang dicentang.</p>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-text-main mb-2">Tahun</label>
                                <!-- Mengubah input Tahun menjadi membulat sempurna (rounded-full) -->
                                <input type="number" wire:model="selectedYearInput" class="w-full px-5 py-3 bg-surface-subtle border border-border-card rounded-full text-sm focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none" placeholder="Contoh: 2026">
                            </div>
                            
                            <label class="block text-sm font-bold text-text-main mb-2">Bulan</label>
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                @forelse($availableMonths as $m)
                                    <!-- Menggunakan teknik peer murni dari Tailwind agar perubahannya instan di sisi klien -->
                                    <label class="group relative flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="selectedMonthsInput" value="{{ $m }}" class="peer sr-only">
                                        
                                        <!-- Kotak pembungkusnya kini membulat (rounded-full) -->
                                        <div class="flex items-center w-full px-4 py-3 border rounded-full transition-colors 
                                                    bg-surface-subtle border-border-card group-hover:bg-white
                                                    peer-checked:bg-red-50/50 peer-checked:border-telkom-red/50">
                                            
                                            <!-- Desain Lingkaran Baru -->
                                            <div class="w-5 h-5 rounded-full border-2 border-border-card flex-shrink-0 flex items-center justify-center transition-all peer-checked:border-telkom-red peer-checked:bg-telkom-red">
                                                <!-- Titik tengah putih saat dipilih -->
                                                <div class="w-2 h-2 rounded-full bg-white opacity-0 transition-opacity peer-checked:opacity-100"></div>
                                            </div>
                                            
                                            <span class="ml-3 text-sm font-medium text-text-main relative z-10">{{ $m }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-2 text-center text-sm text-text-muted py-2 bg-surface-subtle rounded-full border border-border-card">Belum ada data bulan yang tersedia. Tekan Sync Data dulu.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 sm:px-8 sm:flex sm:flex-row-reverse border-t border-border-card/50">
                    <button wire:click="saveMonths" type="button" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-6 py-3 bg-telkom-red text-base font-bold text-white hover:bg-telkom-red-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan & Terapkan
                    </button>
                    <button wire:click="closeEditModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-full border border-border-card shadow-sm px-6 py-3 bg-white text-base font-bold text-text-muted hover:text-text-main hover:bg-surface-subtle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail Poin (Livewire) -->
    @if($isDetailModalOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- No backdrop blur, to ensure perfect 60fps scrolling -->
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" aria-hidden="true" wire:click="closeDetailModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Removed 'flex flex-col' from the outer container to preserve inline-block rendering! -->
            <div class="inline-block align-bottom bg-surface-card rounded-[32px] text-left overflow-hidden shadow-card transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-border-card">
                
                <!-- Added flex col to an inner container -->
                <div class="flex flex-col max-h-[85vh]">
                    <div class="px-6 py-6 border-b border-border-card flex justify-between items-center bg-surface-card shrink-0">
                        <h3 class="text-xl font-bold text-text-main">{{ $detailTitle }}</h3>
                        <button wire:click="closeDetailModal" class="text-text-muted hover:text-telkom-red focus:outline-none transition-colors p-2 rounded-full hover:bg-surface-subtle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-4 sm:p-6 overflow-y-auto custom-scrollbar flex-1 bg-surface-subtle">
                        <div class="relative bg-surface-card border border-border-card rounded-[32px] shadow-sm overflow-hidden flex flex-col p-4">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-separate border-spacing-0">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 rounded-l-[26px] text-center w-16">No</th>
                                            <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100">Bulan</th>
                                            <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100">AOID</th>
                                            <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 rounded-r-[26px]">STO</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-text-main text-[13px]">
                                        @forelse($detailRecords as $index => $detail)
                                            <tr class="hover:bg-surface-subtle transition-colors group">
                                                <td class="px-5 py-4 whitespace-nowrap text-text-muted text-center font-variant-numeric tabular-nums">{{ $index + 1 }}</td>
                                                <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main">{{ $detail['bulan'] }}</td>
                                                <td class="px-5 py-4 whitespace-nowrap font-variant-numeric tabular-nums font-mono text-text-muted">{{ $detail['aoid'] }}</td>
                                                <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main">{{ $detail['sto'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-border-card">
                                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        </div>
                                                        <h3 class="text-base font-bold text-text-main mb-1">Tidak Ada Data</h3>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
