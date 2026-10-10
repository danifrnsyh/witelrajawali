<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');
$content = preg_replace('/<th class="px-4 py-3 align-middle /s', '<th class="px-4 py-3 align-middle h-12 ', $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Added h-12 to th!';
