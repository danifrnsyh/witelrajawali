<?php

$f = 'resources/views/livewire/kinerja-teknisi.blade.php';
$c = file_get_contents($f);

$c = preg_replace('/@auth\s*<th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">Aksi<\/th>\s*@endauth/s', '', $c);
$c = preg_replace('/@auth\s*<td class="px-6 py-4 whitespace-nowrap text-right">\s*<button wire:click="editTicket\(\{\{ \$item\[\'row_index\'\] \}\}\)".*?<\/button>\s*<\/td>\s*@endauth/s', '', $c);
$c = preg_replace('/<!-- MODAL EDIT ADMIN -->.*?@endif/s', '', $c);

file_put_contents($f, $c);
