<?php

$content = file_get_contents('resources/views/livewire/kinerja-teknisi.blade.php');

// Change card padding
$content = preg_replace('/class="bg-surface-subtle border border-border-card rounded-\[24px\] p-6/s', 'class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6', $content);

// Change red accent bar
$content = preg_replace('/class="absolute top-6 left-0 w-1\.5 h-12 bg-telkom-red/s', 'class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red', $content);

// Change space-x-3 in card headers
$content = preg_replace('/class="flex items-center space-x-3 mb-4/s', 'class="flex items-center space-x-2 sm:space-x-3 mb-4', $content);
$content = preg_replace('/class="flex items-center space-x-3 mb-5/s', 'class="flex items-center space-x-2 sm:space-x-3 mb-5', $content);

// Change icon container p-3 to p-2 sm:p-3
$content = preg_replace('/class="p-3 bg-white/s', 'class="p-2 sm:p-3 bg-white', $content);

// Change SVG w-5 h-5 to w-4 h-4 sm:w-5 sm:h-5 inside the cards only
// Actually, it's easier to just target all svgs with w-5 h-5 right after the p-2 sm:p-3 bg-white
$content = preg_replace('/(class="p-2 sm:p-3 bg-white[^>]+>\s*<svg class=")(w-5 h-5)/s', '$1w-4 h-4 sm:w-5 sm:h-5', $content);

// Also shrink text for the header text-xs to text-[10px] sm:text-xs so it fits on mobile
$content = preg_replace('/<h3 class="text-xs font-bold text-text-subtle uppercase tracking-wider">/s', '<h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">', $content);

file_put_contents('resources/views/livewire/kinerja-teknisi.blade.php', $content);
echo 'Cards modified!';
