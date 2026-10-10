<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

$pattern = '/<div class="w-8 h-8 rounded-full table mx-auto shadow-sm\s*\{\{.*?\}\}\s*">\s*<div class="table-cell align-middle text-center font-extrabold text-sm"><span style="position: relative; top: -2px; display: block;">\{\{ \$i \+ 1 \}\}<\/span><\/div>\s*<\/div>/s';

$replacement = '<div class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm mx-auto shadow-sm'."\n".
               '                                    {{ $i === 0 ? \'bg-yellow-400 text-yellow-900\' : ($i === 1 ? \'bg-gray-300 text-gray-800\' : ($i === 2 ? \'bg-orange-300 text-orange-900\' : \'bg-gray-800 text-white\')) }}'."\n".
               '                                " style="padding-bottom: 3px; line-height: 1;">'."\n".
               '                                    {{ $i + 1 }}'."\n".
               '                                </div>';

$content = preg_replace($pattern, $replacement, $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Applied flex padding-bottom hack!';
