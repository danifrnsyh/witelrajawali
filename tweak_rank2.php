<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

// Replace the inline-block line-height circle with table-cell
$content = preg_replace(
    '/<div class="w-8 h-8 rounded-full inline-block text-center leading-\[32px\] font-extrabold text-sm mx-auto shadow-sm/s',
    '<div class="w-8 h-8 rounded-full table mx-auto shadow-sm',
    $content
);

// We need to inject the inner table-cell div around {{ $i + 1 }}
// In the current file:
/*
<div class="w-8 h-8 rounded-full table mx-auto shadow-sm
    {{ $i === 0 ? 'bg-yellow-400 text-yellow-900' : ($i === 1 ? 'bg-gray-300 text-gray-800' : ($i === 2 ? 'bg-orange-300 text-orange-900' : 'bg-gray-800 text-white')) }}
">
    {{ $i + 1 }}
</div>
*/

$content = preg_replace(
    '/(<div class="w-8 h-8 rounded-full table mx-auto shadow-sm\s*\{\{.*?\}\}\s*">)\s*\{\{\s*\$i \+ 1\s*\}\}\s*(<\/div>)/s',
    '$1<div class="table-cell align-middle text-center font-extrabold text-sm">{{ $i + 1 }}</div>$2',
    $content
);

file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Rank circle changed to table-cell!';
