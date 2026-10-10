<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');

$content = preg_replace(
    '/<div class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm mx-auto shadow-sm/s',
    '<div class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm mx-auto shadow-sm leading-none pb-[1px]',
    $content
);

file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Fixed circle centering!';
