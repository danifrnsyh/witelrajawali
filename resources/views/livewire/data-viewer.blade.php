<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Data Sheet Utama</h1>
            <p class="mt-2 text-sm text-gray-500">Tampilan data tersinkronisasi dari spreadsheet utama.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-64" style="position: relative;">
                <svg style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); pointer-events: none;" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input style="padding-left: 2.75rem;" wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pr-4 text-sm text-gray-900 border border-gray-300 rounded-full bg-white focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none" placeholder="Cari data...">
            </div>

            <!-- Refresh Button (Resets Search & Sort) -->
            <button wire:click="resetData" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition-all bg-telkom-red border border-transparent rounded-full shadow-sm hover:bg-telkom-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red w-full sm:w-auto whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm whitespace-nowrap text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        @foreach($headers as $index => $header)
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">
                                <button wire:click="sortBy({{ $index }})" class="flex items-center group focus:outline-none w-full">
                                    {{ $header }}
                                    <span class="ml-2 flex-none rounded text-gray-400 group-hover:bg-gray-200 group-hover:text-gray-900">
                                        @if($sortColumn === $index)
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                        @endif
                                    </span>
                                </button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        @for($i = 0; $i < count($headers); $i++)
                            <td class="px-6 py-3.5 text-gray-700 group-hover:text-gray-900">{{ $row[$i] ?? '-' }}</td>
                        @endfor
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Belum ada data. Silakan jalankan sinkronisasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination inside table card -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-col xl:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 shrink-0">
                <span>Tampilkan</span>
                <select wire:model.live="perPage" class="border border-gray-300 rounded-md py-1.5 px-3 bg-white focus:outline-none focus:ring-1 focus:ring-telkom-red focus:border-telkom-red cursor-pointer">
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
                <span>data</span>
            </div>
            <div class="w-full sm:w-auto sm:ml-auto overflow-x-auto [&_p.leading-5]:mr-8 [&_p.leading-5]:mt-1 [&_p.leading-5]:mb-2 sm:[&_p.leading-5]:mb-0">
                {{ $paginatedData->links() }}
            </div>
        </div>
    </div>
</div>
