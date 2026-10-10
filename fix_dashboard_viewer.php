<?php

$f = 'resources/views/livewire/dashboard-viewer.blade.php';
$c = file_get_contents($f);

// Header Aksi
$c = str_replace('@endforeach
                    </tr>', '@endforeach
                        @auth
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">Aksi</th>
                        @endauth
                    </tr>', $c);

// Non-empty count check
$c = str_replace('$nonEmptyCount = collect($row)->filter(fn($v) => trim($v ?? \'\') !== \'\')->count();',
    '$nonEmptyCount = collect($row[\'data\'])->filter(fn($v) => trim($v ?? \'\') !== \'\')->count();', $c);

$c = str_replace('$isSectionTitle = $nonEmptyCount === 1 && !empty($row[0]);',
    '$isSectionTitle = $nonEmptyCount === 1 && !empty($row[\'data\'][0]);', $c);

$c = str_replace('{{ $row[0] }}', '{{ $row[\'data\'][0] }}', $c);

// Header check
$c = str_replace('$isHeaderRow = (isset($row[0]) && $row[0] == \'NO\') && (isset($row[1]) && $row[1] == \'NIK\');',
    '$isHeaderRow = (isset($row[\'data\'][0]) && $row[\'data\'][0] == \'NO\') && (isset($row[\'data\'][1]) && $row[\'data\'][1] == \'NIK\');', $c);

$c = str_replace('$isTotalRow = (isset($row[1]) && str_contains($row[1], \'TOTAL\')) || (isset($row[0]) && str_contains($row[0], \'TOTAL\'));',
    '$isTotalRow = (isset($row[\'data\'][1]) && str_contains($row[\'data\'][1], \'TOTAL\')) || (isset($row[\'data\'][0]) && str_contains($row[\'data\'][0], \'TOTAL\'));', $c);

// Loop TD
$c = str_replace('<td class="px-6 py-3.5 {{ $textClass }}">{{ $row[$i] ?? \'-\' }}</td>', '<td class="px-6 py-3.5 {{ $textClass }}">{{ $row[\'data\'][$i] ?? \'-\' }}</td>', $c);

// Loop End (add Aksi cell)
$c = str_replace('@endfor
                            </tr>', '@endfor
                                @auth
                                    <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                        <button wire:click="editRow({{ $row[\'row_index\'] }})" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded-full border border-gray-300 transition-colors">Edit</button>
                                    </td>
                                @endauth
                            </tr>', $c);

// Add Modal
$modal = '
    <!-- MODAL EDIT ADMIN -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden relative flex flex-col" style="max-height: 90vh;">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0">
                <h3 class="text-lg font-bold text-gray-900">Edit Data Dashboard (Baris {{ $editingRowIndex }})</h3>
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
                        @if(trim($header) !== \'\')
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
';

$c = str_replace('</div>
</div>', "</div>\n".$modal.'</div>', $c);

// Also we need to fix the section title colspan to span over Aksi column if auth
$c = str_replace('<td colspan="{{ count($headers) }}" class="px-6 py-6 text-sm font-bold text-telkom-dark uppercase tracking-wider text-center border-t-2 border-t-telkom-red/20 shadow-sm">', '<td colspan="100%" class="px-6 py-6 text-sm font-bold text-telkom-dark uppercase tracking-wider text-center border-t-2 border-t-telkom-red/20 shadow-sm">', $c);

file_put_contents($f, $c);
