<div class="space-y-6">
    <!-- BOX 1: Header Utama -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main">Peringkat Sektoral</h1>
            <p class="mt-2 text-sm text-text-muted">Periode: 16 Agustus - 30 September 2026 (46 hari) | Dibuat: {{ $lastUpdated }}</p>
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIK..." class="w-full pl-10 pr-4 py-2.5 bg-surface-subtle border border-border-card rounded-full text-sm focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all text-text-main placeholder-text-muted shadow-sm">
                <svg class="w-4 h-4 absolute left-3.5 top-3 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button wire:click="loadData" class="inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors" title="Refresh Data">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- BOX 2: Parameter Penilaian -->
    <div class="bg-surface-card border border-border-card rounded-[32px] shadow-sm p-6">
        <h3 class="text-sm font-bold text-text-main mb-4 pl-1">Parameter Penilaian</h3>
        <div class="overflow-x-auto rounded-[20px] border border-border-card">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-subtle text-text-muted">
                    <tr>
                        <th class="px-5 py-3.5 font-bold uppercase tracking-wider text-xs border-b border-border-card">Parameter</th>
                        <th class="px-5 py-3.5 font-bold uppercase tracking-wider text-xs border-b border-border-card">Bobot / Nilai</th>
                        <th class="px-5 py-3.5 font-bold uppercase tracking-wider text-xs border-b border-border-card text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-card/50">
                    @foreach($parameters as $param)
                        <tr class="hover:bg-surface-subtle/50 transition-colors group">
                            <td class="px-5 py-3 text-text-muted font-medium w-2/3">{{ $param['name'] }}</td>
                            <td class="px-5 py-3 text-text-main font-bold">{{ $param['value'] }}</td>
                            <td class="px-5 py-3 text-right">
                                <button class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all inline-flex items-center justify-center opacity-0 group-hover:opacity-100" title="Edit Bobot">
                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Segmented Control (Pill Toggle) Animasi Halus -->
    <div class="relative bg-white rounded-full p-2 shadow-sm flex w-full max-w-2xl mx-auto border border-border-card mt-8 mb-4 overflow-hidden">
        
        <!-- Sliding Red Indicator -->
        <div class="absolute top-2 bottom-2 w-[calc(33.333%-0.333rem)] bg-telkom-red rounded-full transition-transform duration-500 ease-out shadow-md"
             style="transform: translateX({{ $activeTab === '1_PSB_B2C' ? '0' : ($activeTab === '2_ASSR_B2C' ? '100%' : '200%') }}); left: 0.5rem;">
        </div>

        <!-- Buttons -->
        <button wire:click="setTab('1_PSB_B2C')" class="flex-1 relative z-10 py-3 text-sm sm:text-base font-bold rounded-full transition-colors duration-300 {{ $activeTab === '1_PSB_B2C' ? 'text-white' : 'text-gray-700 hover:text-gray-900' }}">
            PSB B2C
        </button>
        <button wire:click="setTab('2_ASSR_B2C')" class="flex-1 relative z-10 py-3 text-sm sm:text-base font-bold rounded-full transition-colors duration-300 {{ $activeTab === '2_ASSR_B2C' ? 'text-white' : 'text-gray-700 hover:text-gray-900' }}">
            ASSURANCE B2C
        </button>
        <button wire:click="setTab('3_ASSR_B2B')" class="flex-1 relative z-10 py-3 text-sm sm:text-base font-bold rounded-full transition-colors duration-300 {{ $activeTab === '3_ASSR_B2B' ? 'text-white' : 'text-gray-700 hover:text-gray-900' }}">
            ASSURANCE B2B
        </button>
    </div>

    <!-- BOX 3: Data Tabel Utama -->
    <div class="bg-surface-card border border-border-card rounded-[32px] shadow-sm relative z-0 p-4 sm:p-6 min-h-[400px]">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr>
                        @foreach($tableHeaders as $header)
                            <th class="px-4 py-3.5 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 align-middle min-w-[90px] {{ $loop->first ? 'rounded-l-full' : '' }}">
                                <button wire:click="sortBy('{{ $header }}')" class="flex flex-col items-center justify-center w-full focus:outline-none hover:text-telkom-red transition-colors text-center leading-snug">
                                    <span class="whitespace-pre-line">{{ $header }}</span>
                                    @if($sortColumn === $header)
                                        <span class="mt-1 text-telkom-red shrink-0">{{ $sortDirection === 'asc' ? '?' : '?' }}</span>
                                    @endif
                                </button>
                            </th>
                        @endforeach
                        <th class="px-4 py-3.5 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 rounded-r-full text-center align-middle min-w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-card/50">
                    @forelse($displayData as $row)
                        <tr class="hover:bg-surface-subtle transition-colors group">
                            @foreach($tableHeaders as $header)
                                <td class="px-4 py-4 text-sm text-center whitespace-nowrap {{ (strpos(strtoupper($header), 'SKOR AKHIR') !== false || strpos(strtoupper($header), 'WORLDSKILLS') !== false) ? 'font-bold text-telkom-red' : 'text-text-main' }}">
                                    {{ $row[$header] ?? '-' }}
                                </td>
                            @endforeach
                            <td class="px-4 py-4 text-center whitespace-nowrap sticky right-0 bg-surface-card group-hover:bg-surface-subtle transition-colors border-l border-border-card/30">
                                <button class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all flex items-center justify-center mx-auto" title="Edit">
                                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($tableHeaders) + 1 }}" class="px-5 py-8 text-center text-text-muted">
                                Belum ada data atau tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
