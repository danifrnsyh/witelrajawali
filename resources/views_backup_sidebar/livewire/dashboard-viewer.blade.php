<div class="space-y-6" id="export-dashboard-area">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 bg-white  p-6 rounded-3xl border border-white/60 shadow-sm">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $title }}</h1>
            <p class="mt-2 text-sm text-gray-600">Tampilan rekap laporan harian teknisi.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
            
            <div class="flex space-x-2">
                <button onclick="exportToJPG('export-dashboard-area', 'Dashboard_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white hover:bg-white text-gray-700 border border-white shadow-sm ">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    JPG
                </button>
                <button onclick="exportToPDF('export-dashboard-area', 'Dashboard_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white hover:bg-white text-gray-700 border border-white shadow-sm ">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    PDF
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full sm:w-64 export-btn-hide" style="position: relative;">
                <svg style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); pointer-events: none;" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input style="padding-left: 2.75rem;" wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2 pr-4 text-sm text-gray-900 border border-gray-300 rounded-full bg-white focus:ring-telkom-red focus:border-telkom-red shadow-sm  outline-none" placeholder="Cari teknisi...">
            </div>

            <!-- Refresh Button -->
            <button wire:click="$set('search', '')" class="export-btn-hide inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white  bg-telkom-red border border-transparent rounded-full shadow-sm hover:bg-telkom-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red w-full sm:w-auto whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-300/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm whitespace-nowrap text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200 divide-x divide-gray-200/50">
                        @foreach($headers as $header)
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-xs">{{ $header }}</th>
                        @endforeach
                        @auth
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200 border-l border-gray-200/50">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rows as $row)
                        @php
                            // Hitung kolom yang tidak kosong
                            $nonEmptyCount = collect($row['data'])->filter(fn($v) => trim($v ?? '') !== '')->count();
                            $isSectionTitle = $nonEmptyCount === 1 && !empty($row['data'][0]);
                        @endphp

                        @if($isSectionTitle)
                            <tr class="bg-gray-50/80">
                                <td colspan="100%" class="px-6 py-6 text-sm font-bold text-telkom-dark uppercase tracking-wider text-center border-t-2 border-t-telkom-red/20 shadow-sm">
                                    {{ $row['data'][0] }}
                                </td>
                            </tr>
                        @else
                            @php
                                $isHeaderRow = (isset($row['data'][0]) && $row['data'][0] == 'NO') && (isset($row['data'][1]) && $row['data'][1] == 'NIK');
                                $isTotalRow = (isset($row['data'][1]) && str_contains($row['data'][1], 'TOTAL')) || (isset($row['data'][0]) && str_contains($row['data'][0], 'TOTAL'));
                                
                                $rowClass = 'hover:bg-gray-50/80 group divide-x divide-gray-100 ';
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
                                    <td class="px-6 py-3.5 border-b border-gray-100/70 {{ $textClass }}">{{ $row['data'][$i] ?? '-' }}</td>
                                @endfor
                                @auth
                                    <td class="px-6 py-3.5 border-b border-gray-100/70 text-right whitespace-nowrap">
                                        <button wire:click="editRow({{ $row['row_index'] ?? 0 }})" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded-full border border-gray-300 transition-colors">Edit</button>
                                    </td>
                                @endauth
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

    <!-- MODAL EDIT ADMIN -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl  relative flex flex-col" style="max-height: 90vh;">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0">
                <h3 class="text-lg font-bold text-gray-900">Edit Data Dashboard (Baris {{ $editingRowIndex }})</h3>
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
                        @if(trim($header) !== '')
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ $header }}</label>
                            <input type="text" wire:model="editForm.{{ $index }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-telkom-red focus:border-telkom-red">
                        </div>
                        @endif
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
