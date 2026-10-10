<?php

$content = file_get_contents('resources/views/livewire/data-viewer.blade.php');

// 1. Remove the absolute pill
$content = preg_replace('/<!-- Fixed Pill Background for Header -->\s*<div class="absolute top-0 left-0 right-0 h-\[52px\] bg-gray-100 rounded-full pointer-events-none z-0"><\/div>\s*/s', '', $content);

// 2. Change the logic in the foreach loop for TH
// Replace: <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main whitespace-nowrap bg-transparent {{ !auth()->check() && $loop->last ? 'bg-gray-100 rounded-r-full sticky right-0 z-20' : '' }}">
$content = preg_replace('/<th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main whitespace-nowrap bg-transparent.*?">/s',
    '<th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main whitespace-nowrap bg-gray-100 {{ $loop->first ? \'rounded-l-[26px]\' : \'\' }} {{ !auth()->check() && $loop->last ? \'rounded-r-[26px]\' : \'\' }}">', $content);

file_put_contents('resources/views/livewire/data-viewer.blade.php', $content);
echo 'DataViewer modified!';
