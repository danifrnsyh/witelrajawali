<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $title }}</h1>
            <p class="mt-2 text-sm text-gray-500">Tampilan rekap laporan harian teknisi.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-64" style="position: relative;">
                <svg style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); pointer-events: none;" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input style="padding-left: 2.75rem;" wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pr-4 text-sm text-gray-900 border border-gray-300 rounded-full bg-white focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none" placeholder="Cari teknisi...">
            </div>

            <!-- Refresh Button -->
            <button wire:click="$set('search', '')" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition-all bg-telkom-red border border-transparent rounded-full shadow-sm hover:bg-telkom-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red w-full sm:w-auto whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset Cari
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm whitespace-nowrap text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        @foreach($headers as $header)
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rows as $row)
                        @php
                            // Hitung kolom yang tidak kosong
                            $nonEmptyCount = collect($row)->filter(fn($v) => trim($v ?? '') !== '')->count();
                            $isSectionTitle = $nonEmptyCount === 1 && !empty($row[0]);
                        @endphp

                        @if($isSectionTitle)
                            <tr class="bg-gray-50/80">
                                <td colspan="{{ count($headers) }}" class="px-6 py-6 text-sm font-bold text-telkom-dark uppercase tracking-wider text-center border-t-2 border-t-telkom-red/20 shadow-sm">
                                    {{ $row[0] }}
                                </td>
                            </tr>
                        @else
                            @php
                                $isHeaderRow = (isset($row[0]) && $row[0] == 'NO') && (isset($row[1]) && $row[1] == 'NIK');
                                $isTotalRow = (isset($row[1]) && str_contains($row[1], 'TOTAL')) || (isset($row[0]) && str_contains($row[0], 'TOTAL'));
                                
                                $rowClass = 'hover:bg-gray-50/80 transition-colors group ';
                                $textClass = 'text-gray-700 group-hover:text-gray-900';
                                
                                if ($isHeaderRow) {
                                    $rowClass = 'bg-gray-100/50';
                                    $textClass = 'font-semibold text-gray-600 text-xs uppercase tracking-wider';
                                } elseif ($isTotalRow) {
                                    $rowClass = 'bg-red-50/50';
                                    $textClass = 'font-bold text-telkom-dark';
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                @for($i = 0; $i < count($headers); $i++)
                                    <td class="px-6 py-3.5 {{ $textClass }}">{{ $row[$i] ?? '-' }}</td>
                                @endfor
                            </tr>
                        @endif
                    @empty
                    <tr>
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Data Dashboard masih kosong. Silakan jalankan sinkronisasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
