<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

// Fix rank circle
$content = preg_replace('/flex items-center justify-center font-extrabold text-sm mx-auto shadow-sm leading-none pb-\[1px\]/s', 'inline-block text-center leading-[32px] font-extrabold text-sm shadow-sm', $content);

// Fix table headers to align-middle and tighten padding to make it more pill-like
$content = preg_replace('/<th class="px-4 py-4 /s', '<th class="px-4 py-3 align-middle ', $content);

file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Fixed!';
