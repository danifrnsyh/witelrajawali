<?php

$f = 'resources/views/livewire/kinerja-teknisi.blade.php';
$c = file_get_contents($f);

// Add Action Header
$c = str_replace('@endforeach
                              </tr>', '@endforeach
                                  @auth
                                      <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200 text-right">Aksi</th>
                                  @endauth
                              </tr>', $c);

// Add Action Cell
$c = str_replace("<td class=\"px-6 py-4 whitespace-nowrap text-gray-700 font-medium\">{{ \$item['durasi_teks'] }}</td>
                                  </tr>", "<td class=\"px-6 py-4 whitespace-nowrap text-gray-700 font-medium\">{{ \$item['durasi_teks'] }}</td>
                                      @auth
                                      <td class=\"px-6 py-4 whitespace-nowrap text-right\">
                                          <button wire:click=\"editTicket({{ \$item['row_index'] }})\" class=\"text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1 px-3 rounded-full border border-gray-300\">Edit</button>
                                      </td>
                                      @endauth
                                  </tr>", $c);

// Add Modal at the end
$modal = '
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Edit Data Tiket (Row {{ $editingRowIndex }})</h3>
                <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                @if(session()->has(\'success\'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">{{ session(\'success\') }}</div>
                @endif
                @if(session()->has(\'error\'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200">{{ session(\'error\') }}</div>
                @endif
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">No. Tiket</label><input type="text" wire:model="editForm.tiket_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">No. Order</label><input type="text" wire:model="editForm.order_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                    
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Segmen</label><input type="text" wire:model="editForm.segment" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Jenis Order</label><input type="text" wire:model="editForm.jenis_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>

                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Waktu Mulai (Start)</label><input type="text" wire:model="editForm.start_dt" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="YYYY-MM-DD HH:MM:SS"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Waktu Selesai (Close)</label><input type="text" wire:model="editForm.close_dt" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="YYYY-MM-DD HH:MM:SS"></div>

                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Teknisi Utama</label><input type="text" wire:model="editForm.labor_1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Teknisi Pendamping</label><input type="text" wire:model="editForm.labor_2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                    
                    <div class="col-span-2"><label class="block text-xs font-bold text-gray-700 mb-1">Workzone (Area)</label><input type="text" wire:model="editForm.workzone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                <button wire:click="closeEditModal" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Tutup</button>
                <button wire:click="saveTicket" class="px-4 py-2 bg-telkom-red text-white rounded-lg text-sm font-medium hover:bg-red-700 flex items-center">
                    <svg wire:loading wire:target="saveTicket" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
