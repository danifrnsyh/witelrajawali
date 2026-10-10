<?php

$content = file_get_contents('resources/views/livewire/peringkat-viewer.blade.php');
$content = str_replace('">`n<div class="table-cell', '">'."\n".'<div class="table-cell', $content);
$content = str_replace('</div>`n</div>', '</div>'."\n".'</div>', $content);
file_put_contents('resources/views/livewire/peringkat-viewer.blade.php', $content);
echo 'Fixed backticks!';
