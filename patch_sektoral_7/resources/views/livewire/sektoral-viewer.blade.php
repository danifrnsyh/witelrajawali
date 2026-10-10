<div class="space-y-6">
    
    <!-- New "Cardiology" Style Header Container -->
    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card overflow-hidden">
        
        <!-- Dark Top Bar (Tab & Title Area) -->
        <div class="bg-[#595959] p-2 flex flex-col md:flex-row md:items-center gap-4 relative">
            <!-- The Title 'Cutout' -->
            <div class="bg-surface-card rounded-[24px] px-6 py-3 self-start md:self-stretch flex items-center min-w-[200px] shadow-sm">
                <h1 class="text-xl md:text-2xl font-semibold text-text-main tracking-tight">
                    @if($activeTab === '1_PSB_B2C') PSB B2C
                    @elseif($activeTab === '2_ASSR_B2C') ASSURANCE B2C
                    @elseif($activeTab === '3_ASSR_B2B') ASSURANCE B2B
                    @else {{ $activeTab }}
                    @endif
                </h1>
            </div>
            
            <!-- Tab Buttons (Pills) -->
            <div class="flex gap-2 overflow-x-auto pb-1 md:pb-0 px-2 md:px-0 md:ml-auto custom-scrollbar">
                <button wire:click="setTab('1_PSB_B2C')" class="whitespace-nowrap px-4 py-2.5 rounded-full text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === '1_PSB_B2C' ? 'bg-white text-gray-900 shadow-sm' : 'bg-white/20 text-white hover:bg-white/30' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    PSB B2C
                </button>
                <button wire:click="setTab('2_ASSR_B2C')" class="whitespace-nowrap px-4 py-2.5 rounded-full text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === '2_ASSR_B2C' ? 'bg-white text-gray-900 shadow-sm' : 'bg-white/20 text-white hover:bg-white/30' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    ASSURANCE B2C
                </button>
                <button wire:click="setTab('3_ASSR_B2B')" class="whitespace-nowrap px-4 py-2.5 rounded-full text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTab === '3_ASSR_B2B' ? 'bg-white text-gray-900 shadow-sm' : 'bg-white/20 text-white hover:bg-white/30' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    ASSURANCE B2B
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            
            <!-- Controls (Search & Meta) -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-8">
                <div>
                    <p class="text-sm text-text-muted">Periode: 16 Agustus - 30 September 2026 (46 hari)</p>
                    <p class="text-xs text-text-muted mt-1">Dibuat: {{ $lastUpdated }}</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative w-full sm:w-64">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIK..." class="w-full pl-10 pr-4 py-2.5 bg-surface-subtle border border-border-card rounded-full text-sm focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all text-text-main placeholder-text-muted shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-3 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button wire:click="loadData" class="inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors" title="Refresh Data">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Parameter Penilaian Tabel -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-text-main mb-3">
                    Parameter Penilaian
                </h3>
                <div class="overflow-x-auto rounded-xl border border-border-card">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface-subtle text-text-muted">
                            <tr>
                                <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-xs border-b border-border-card">Parameter</th>
                                <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-xs border-b border-border-card">Bobot / Nilai</th>
                                <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-xs border-b border-border-card text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-card/50">
                            @foreach($parameters as $param)
                                <tr class="hover:bg-surface-subtle/50 transition-colors group">
                                    <td class="px-4 py-2 text-text-muted font-medium w-2/3">{{ $param['name'] }}</td>
                                    <td class="px-4 py-2 text-text-main font-bold">{{ $param['value'] }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <button class="p-1.5 text-telkom-red hover:bg-telkom-red/10 rounded-full transition-colors opacity-0 group-hover:opacity-100" title="Edit Bobot">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Main Table -->
            <div class="overflow-x-auto custom-scrollbar pb-4">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr>
                            @foreach($tableHeaders as $header)
                                <th class="px-3 py-3 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 align-middle min-w-[90px] {{ $loop->first ? 'rounded-l-full' : '' }}">
                                    <button wire:click="sortBy('{{ $header }}')" class="flex items-center justify-center w-full focus:outline-none hover:text-telkom-red transition-colors whitespace-pre-line text-center leading-snug">
                                        {{ $header }}
                                        @if($sortColumn === $header)
                                            <span class="ml-1 text-telkom-red shrink-0">{{ $sortDirection === 'asc' ? '?' : '?' }}</span>
                                        @endif
                                    </button>
                                </th>
                            @endforeach
                            <th class="px-3 py-3 font-bold text-text-main uppercase tracking-wider text-[11px] bg-gray-100 rounded-r-full text-center align-middle min-w-[70px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-card/50">
                        @forelse($displayData as $row)
                            <tr class="hover:bg-surface-subtle transition-colors group">
                                @foreach($tableHeaders as $header)
                                    <td class="px-3 py-3 text-sm text-center whitespace-nowrap {{ (strpos(strtoupper($header), 'SKOR AKHIR') !== false || strpos(strtoupper($header), 'WORLDSKILLS') !== false) ? 'font-bold text-telkom-red' : 'text-text-main' }}">
                                        {{ $row[$header] ?? '-' }}
                                    </td>
                                @endforeach
                                <td class="px-3 py-3 text-center whitespace-nowrap">
                                    <button class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
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
</div>
