<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

// Change space-x-3 in card headers
$content = preg_replace('/class="flex items-center space-x-3 mb-4/s', 'class="flex items-center space-x-2 sm:space-x-3 mb-4', $content);

// Change icon container p-3 to p-2 sm:p-3
$content = preg_replace('/class="p-3 bg-white/s', 'class="p-2 sm:p-3 bg-white', $content);

// Change SVG w-5 h-5
$content = preg_replace('/(class="p-2 sm:p-3 bg-white[^>]+>\s*<svg class=")(w-5 h-5)/s', '$1w-4 h-4 sm:w-5 sm:h-5', $content);

// Change header text
$content = preg_replace('/<h3 class="text-xs font-bold text-text-subtle uppercase tracking-wider">/s', '<h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">', $content);

file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Cards modified!';
