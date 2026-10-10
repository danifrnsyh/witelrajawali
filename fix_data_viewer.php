<?php

$f = 'resources/views/livewire/data-viewer.blade.php';
$c = file_get_contents($f);

// Add action header
$c = str_replace('@endforeach
                    </tr>', '@endforeach
                        @auth
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">Aksi</th>
                        @endauth
                    </tr>', $c);

// Update data row loop
$c = str_replace('<td class="px-6 py-3.5 text-gray-700 group-hover:text-gray-900">{{ $row[$i] ?? \'-\' }}</td>', '<td class="px-6 py-3.5 text-gray-700 group-hover:text-gray-900">{{ $row[\'data\'][$i] ?? \'-\' }}</td>', $c);

// Add action cell
$c = str_replace('@endfor
                    </tr>', '@endfor
                        @auth
                            <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                <button wire:click="editRow({{ $row[\'row_index\'] }})" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded-full border border-gray-300 transition-colors">Edit</button>
                            </td>
                        @endauth
                    </tr>', $c);

// Add modal
$modal = '
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
                @if(session()->has(\'success\'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">{{ session(\'success\') }}</div>
                @endif
                @if(session()->has(\'error\'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200">{{ session(\'error\') }}</div>
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
';

$c = str_replace('</div>
</div>', "</div>\n".$modal.'</div>', $c);
file_put_contents($f, $c);
