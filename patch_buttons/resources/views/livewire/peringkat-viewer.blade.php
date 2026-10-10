<div class="space-y-6" id="export-peringkat-area">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-4 sm:p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main">{{ $title }}</h1>
            <p class="mt-2 text-sm text-text-muted">Ranking performa bulan ini (Target: {{ $target }})</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
            <!-- Export Buttons -->
            <div class="flex space-x-2 bg-surface-subtle p-1 rounded-full border border-border-card/60">
                <button onclick="exportToJPG('export-peringkat-area', 'Papan_Peringkat')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    JPG
                </button>
                <button onclick="exportToPDF('export-peringkat-area', 'Papan_Peringkat')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    PDF
                </button>
            </div>

            <div class="relative w-full sm:w-64 export-btn-hide">
                <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama teknisi..." class="block w-full py-2.5 pl-10 pr-4 text-sm font-semibold text-text-main border border-border-card rounded-full bg-white focus:bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none">
            </div>

            <!-- Refresh Button -->
                <button wire:click="$set('search', '')" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>


        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:p-6 mb-8">
        <!-- PROD rata-rata tim -->
        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                <div class="p-2 sm:p-3 bg-white border border-border-card rounded-full text-text-main shadow-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">PROD rata-rata tim</h3>
            </div>
            <div class="flex items-baseline space-x-2 relative z-10">
                <span class="text-2xl sm:text-4xl font-extrabold {{ $summary['avg_team'] >= 1.5 ? 'text-green-600' : ($summary['avg_team'] >= 1 ? 'text-yellow-500' : 'text-telkom-red') }} tracking-tight font-variant-numeric">{{ $summary['avg_team'] }}</span>
            </div>
        </div>

        <!-- Total pekerjaan -->
        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                <div class="p-2 sm:p-3 bg-white border border-border-card rounded-full text-text-main shadow-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Total pekerjaan</h3>
            </div>
            <div class="flex items-baseline space-x-2 relative z-10">
                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric">{{ $summary['total_team'] }}</span>
                <span class="text-text-muted font-semibold">Tiket</span>
            </div>
        </div>

        <!-- Mencapai target -->
        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                <div class="p-2 sm:p-3 bg-white border border-border-card rounded-full text-text-main shadow-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Mencapai target</h3>
            </div>
            <div class="flex items-baseline space-x-2 relative z-10">
                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric">{{ $summary['achieved'] }}</span>
                <span class="text-text-muted font-semibold text-lg">/ {{ array_sum(array_map('count', $segments)) }}</span>
            </div>
        </div>

        <!-- Tanpa data -->
        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                <div class="p-2 sm:p-3 bg-white border border-border-card rounded-full text-text-main shadow-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Tanpa data</h3>
            </div>
            <div class="flex items-baseline space-x-2 relative z-10">
                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric">{{ $summary['no_data'] }}</span>
                <span class="text-text-muted font-semibold">Teknisi</span>
            </div>
        </div>
    </div>

    <!-- Leaderboard -->
    @forelse($segments as $segmentTitle => $rows)
    <div class="bg-surface-card border border-border-card rounded-[32px] p-4 sm:p-6 shadow-sm overflow-hidden flex flex-col mb-6">
        <h2 class="text-xl font-bold text-telkom-red px-2 pb-4 uppercase tracking-wide">{{ $segmentTitle }}</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr style="height:48px">
                        <th class="px-4 w-16 text-center bg-gray-100 rounded-l-full" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">RANK</text></svg>
                        </th>
                        <th class="px-4 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">TEKNISI</text></svg>
                        </th>
                        <th class="px-4 w-32 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">PROD</text></svg>
                        </th>
                        <th class="px-4 w-40 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">TERHADAP TARGET</text></svg>
                        </th>
                        <th class="px-4 w-48 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="8" y="24" text-anchor="start" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">HARIAN</text></svg>
                        </th>
                        <th class="px-4 bg-gray-100" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="50%" y="24" text-anchor="middle" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">KEHADIRAN</text></svg>
                        </th>
                        <th class="px-4 w-24 bg-gray-100 rounded-r-full" style="vertical-align:middle;padding-top:0;padding-bottom:0;font-size:0;line-height:0;">
                            <svg width="100%" height="48" xmlns="http://www.w3.org/2000/svg"><text x="90%" y="24" text-anchor="end" dominant-baseline="central" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#111827" letter-spacing="1">GAP</text></svg>
                        </th>
                    </tr>
                </thead>
                <tbody class="[&>tr>td]:border-b border-border-card/50">
                    @foreach($rows as $i => $row)
                        <tr class="group hover:bg-surface-subtle transition-colors">
                            <!-- Rank -->
                            <td class="px-4 py-3 rounded-l-2xl text-center" style="overflow:visible">
                                @php
                                    $bgColor  = $i === 0 ? '#FACC15' : ($i === 1 ? '#D1D5DB' : ($i === 2 ? '#FDBA74' : '#1F2937'));
                                    $txtColor = $i === 0 ? '#713F12' : ($i === 1 ? '#374151' : ($i === 2 ? '#7C2D12' : '#FFFFFF'));
                                @endphp
                                <svg width="36" height="36" viewBox="-2 -2 36 36" xmlns="http://www.w3.org/2000/svg" class="mx-auto block" style="overflow:visible">
                                    <circle cx="16" cy="16" r="16" fill="{{ $bgColor }}" />
                                    <text x="16" y="16" text-anchor="middle" dominant-baseline="central"
                                          font-family="Arial, Helvetica, sans-serif" font-size="12" font-weight="800"
                                          fill="{{ $txtColor }}">{{ $i + 1 }}</text>
                                </svg>
                            </td>
                            
                            <!-- Name -->
                            <td class="px-4 py-3">
                                <div class="font-extrabold text-sm text-text-main uppercase">{{ $row['name'] }}</div>
                                <div class="text-xs text-text-muted font-mono mt-0.5">{{ $row['nik'] }}</div>
                            </td>

                            <!-- PROD -->
                            <td class="px-4 py-3 text-center font-extrabold text-lg {{ $row['avg'] >= 1.5 ? 'text-green-600' : ($row['avg'] >= 1 ? 'text-yellow-500' : 'text-telkom-red') }}">
                                {{ $row['total'] }}
                            </td>

                            <!-- Target Slider -->
                            <td class="px-4 py-3">
                                @php
                                    $pct = $target > 0 ? min(100, ($row['avg'] / $target) * 100) : 0;
                                @endphp
                                <div class="w-full h-2.5 bg-surface-subtle rounded-full overflow-hidden border border-border-card relative">
                                    <div class="h-full rounded-full {{ $row['avg'] >= 1.5 ? 'bg-green-600' : ($row['avg'] >= 1 ? 'bg-yellow-500' : 'bg-telkom-red') }} transition-all" style="width: {{ $pct }}%"></div>
                                    <div class="absolute top-0 bottom-0 w-0.5 bg-black z-10" style="left: {{ min(100, (1.5 / max(1.5, $row['avg'] ?: 1.5)) * 100) }}%"></div>
                                </div>
                            </td>

                            <!-- Sparkline (Harian) -->
                            <td class="px-4 py-3">
                                <div class="flex items-end h-8 space-x-[2px] border-b border-border-card/50 pb-0.5">
                                    @foreach($row['daily'] as $val)
                                        @if(is_numeric($val) && trim($val) !== '')
                                            <div class="w-1.5 {{ $val >= 1.5 ? 'bg-green-500' : ($val >= 1 ? 'bg-yellow-400' : 'bg-red-500') }} rounded-t-sm" style="height: {{ max(10, min(100, ($val / 4) * 100)) }}%;"></div>
                                        @elseif(in_array(trim($val), ['L', 'S', 'CT']))
                                            <div class="w-1.5 h-full bg-gray-200 rounded-t-sm opacity-50"></div>
                                        @else
                                            <div class="w-1.5 h-0 bg-transparent"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            <!-- Kehadiran -->
                            <td class="px-4 py-3 text-center">
                                <div class="text-[10px] font-bold text-text-muted mt-1 uppercase tracking-wider">
                                    Masuk {{ $row['present'] }} &bull; L/S/CT {{ $row['absent'] }}
                                </div>
                            </td>

                            <!-- Gap -->
                            <td class="px-4 py-3 rounded-r-2xl text-right">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded-lg text-xs font-extrabold {{ $row['gap'] >= 0 ? 'text-green-600 bg-green-50' : 'text-telkom-red bg-red-50' }}">
                                    {{ $row['gap'] > 0 ? '+' : '' }}{{ $row['gap'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-surface-card border border-border-card rounded-[32px] p-4 sm:p-6 shadow-sm overflow-hidden flex flex-col mb-6">
        <div class="py-12 text-center text-text-muted font-medium">Belum ada data peringkat.</div>
    </div>
    @endforelse
</div>

















