<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

$pattern = '/<div class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm mx-auto shadow-sm\s*\{\{.*?\}\}\s*" style="padding-bottom: 3px; line-height: 1;">\s*\{\{ \$i \+ 1 \}\}\s*<\/div>/s';

$replacement = '<div class="w-8 h-8 rounded-full mx-auto shadow-sm relative'."\n".
               '                                    {{ $i === 0 ? \'bg-yellow-400 text-yellow-900\' : ($i === 1 ? \'bg-gray-300 text-gray-800\' : ($i === 2 ? \'bg-orange-300 text-orange-900\' : \'bg-gray-800 text-white\')) }}'."\n".
               '                                ">'."\n".
               '                                    <div class="absolute left-0 right-0 text-center font-extrabold text-sm" style="top: 7px; line-height: 1;">{{ $i + 1 }}</div>'."\n".
               '                                </div>';

$content = preg_replace($pattern, $replacement, $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Applied absolute positioning hack!';
