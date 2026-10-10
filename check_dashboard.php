<?php

$f = 'resources/views/livewire/dashboard-viewer.blade.php';
$c = file_get_contents($f);

// Add Action Header
$c = str_replace('@endforeach
                    </tr>', '@endforeach
                        @auth
                            <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">Aksi</th>
                        @endauth
                    </tr>', $c);

// Update Loop values
$c = str_replace('<td class="px-6 py-4 text-gray-700 whitespace-nowrap {{ $loop->parent->index % 2 === 0 ? \'bg-gray-50/30\' : \'bg-white\' }} group-hover:bg-gray-100/50 transition-colors">
                                    {{ $col ?? \'-\' }}
                                </td>', '<td class="px-6 py-4 text-gray-700 whitespace-nowrap {{ $loop->parent->index % 2 === 0 ? \'bg-gray-50/30\' : \'bg-white\' }} group-hover:bg-gray-100/50 transition-colors">
                                    {{ $col ?? \'-\' }}
                                </td>', $c); // wait, in dashboard the loop is foreach($row as $col) not $row[$i].

// Let's check how the loop works in dashboard-viewer
