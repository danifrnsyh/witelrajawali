<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main">Peringkat Sektoral</h1>
            <p class="mt-2 text-sm text-text-muted">{{ $lastUpdated }}</p>
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

    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button wire:click="setTab('1_PSB_B2C')" class="px-5 py-2.5 rounded-full text-sm font-bold shadow-sm border transition-all {{ $activeTab === '1_PSB_B2C' ? 'bg-telkom-red text-white border-telkom-red' : 'bg-white text-text-muted border-border-card hover:bg-gray-50' }}">
            ?? PSB B2C
        </button>
        <button wire:click="setTab('2_ASSR_B2C')" class="px-5 py-2.5 rounded-full text-sm font-bold shadow-sm border transition-all {{ $activeTab === '2_ASSR_B2C' ? 'bg-telkom-red text-white border-telkom-red' : 'bg-white text-text-muted border-border-card hover:bg-gray-50' }}">
            ?? ASSURANCE B2C
        </button>
        <button wire:click="setTab('3_ASSR_B2B')" class="px-5 py-2.5 rounded-full text-sm font-bold shadow-sm border transition-all {{ $activeTab === '3_ASSR_B2B' ? 'bg-telkom-red text-white border-telkom-red' : 'bg-white text-text-muted border-border-card hover:bg-gray-50' }}">
            ?? ASSURANCE B2B
        </button>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        
        <!-- Sidebar Parameters -->
        <div class="xl:col-span-1 space-y-4">
            <div class="bg-surface-card border border-border-card rounded-[24px] p-5 shadow-sm">
                <h3 class="text-sm font-bold text-text-main mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Parameter Penilaian
                </h3>
                
                <div class="space-y-3">
                    @foreach($parameters as $param)
                        <div class="p-3 bg-surface-subtle rounded-xl border border-border-card flex flex-col group relative hover:border-telkom-red/30 transition-colors">
                            <span class="text-xs text-text-muted mb-1 font-medium">{{ $param['name'] }}</span>
                            <span class="text-sm font-bold text-text-main">{{ $param['value'] }}</span>
                            <button class="absolute top-2 right-2 p-1.5 bg-white text-telkom-red rounded-full opacity-0 group-hover:opacity-100 transition-opacity border border-border-card shadow-sm" title="Edit Bobot">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 text-blue-800 text-xs rounded-xl border border-blue-100">
                    Klik tulisan header tabel untuk mengurutkan.
                </div>
            </div>
        </div>
        
        <!-- Main Table -->
        <div class="xl:col-span-4">
            <div class="bg-surface-card border border-border-card rounded-[24px] shadow-sm flex flex-col p-4 relative">
                <!-- Rotate container to move scrollbar to top -->
                <div class="overflow-x-auto custom-scrollbar pb-2" style="transform: rotateX(180deg);">
                    <table class="w-full text-left border-separate border-spacing-0 whitespace-nowrap" style="transform: rotateX(180deg);">
                        <thead>
                            <tr>
                                @foreach($tableHeaders as $header)
                                    <th class="sticky top-0 bg-surface-card z-10 px-4 py-3 border-b-2 border-border-card">
                                        <button wire:click="sortBy('{{ $header }}')" class="flex items-center justify-center text-[11px] font-bold text-text-muted uppercase tracking-wider group focus:outline-none w-full whitespace-nowrap">
                                            {{ $header }}
                                            @if($sortColumn === $header)
                                                <span class="ml-1 text-telkom-red">{{ $sortDirection === 'asc' ? '?' : '?' }}</span>
                                            @endif
                                        </button>
                                    </th>
                                @endforeach
                                <th class="sticky top-0 bg-surface-card z-10 px-4 py-3 text-[11px] font-bold text-text-muted uppercase tracking-wider border-b-2 border-border-card text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-card/50">
                            @forelse($displayData as $row)
                                <tr class="hover:bg-surface-subtle transition-colors group">
                                    @foreach($tableHeaders as $header)
                                        <td class="px-4 py-3 text-sm text-center {{ in_array($header, ['SKOR AKHIR', 'WorldSkills Score']) ? 'font-bold text-telkom-red' : 'text-text-main' }}">
                                            {{ $row[$header] ?? '-' }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center">
                                        <button class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($tableHeaders) + 1 }}" class="px-4 py-8 text-center text-text-muted">
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
</div>
