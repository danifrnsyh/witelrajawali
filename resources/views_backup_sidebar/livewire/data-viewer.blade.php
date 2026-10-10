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
                <input style="padding-left: 2.75rem;" wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pr-4 text-sm text-gray-900 border border-gray-300 rounded-full bg-white focus:ring-telkom-red focus:border-telkom-red shadow-sm  outline-none" placeholder="Cari data...">
            </div>

            <!-- Refresh Button (Resets Search & Sort) -->
            <button wire:click="resetData" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white  bg-telkom-red border border-transparent rounded-full shadow-sm hover:bg-telkom-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red w-full sm:w-auto whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-300/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm whitespace-nowrap text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200 divide-x divide-gray-200/50">
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
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                        @endif
                                    </span>
                                </button>
                            </th>
                        @endforeach
                        @auth
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200 border-l border-gray-200/50">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50/80 group divide-x divide-gray-100">
                        @for($i = 0; $i < count($headers); $i++)
                            <td class="px-6 py-3.5 border-b border-gray-100/70 text-gray-700 group-hover:text-gray-900">{{ $row['data'][$i] ?? '-' }}</td>
                        @endfor
                        @auth
                            <td class="px-6 py-3.5 border-b border-gray-100/70 text-right whitespace-nowrap">
                                <button wire:click="editRow({{ $row['row_index'] ?? 0 }})" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded-full border border-gray-300 transition-colors">Edit</button>
                            </td>
                        @endauth
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
            <div class="w-full sm:w-auto sm:ml-auto [&_p.leading-5]:mr-8 [&_p.leading-5]:mt-1 [&_p.leading-5]:mb-2 sm:[&_p.leading-5]:mb-0">
                {{ $paginatedData->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL EDIT ADMIN -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden relative flex flex-col" style="max-height: 90vh;">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0">
                <h3 class="text-lg font-bold text-gray-900">Edit Baris (Row {{ $editingRowIndex }})</h3>
                <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto grow">
                @if(session()->has('success'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">{{ session('success') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200">{{ session('error') }}</div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($headers as $index => $header)
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ $header }}</label>
                            <input type="text" wire:model="editForm.{{ $index }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-telkom-red focus:border-telkom-red">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3 shrink-0">
                <button wire:click="closeEditModal" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Tutup</button>
                <button wire:click="saveRow" class="px-4 py-2 bg-telkom-red text-white rounded-lg text-sm font-medium hover:bg-red-700 flex items-center">
                    <svg wire:loading wire:target="saveRow" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
