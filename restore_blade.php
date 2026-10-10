<?php

$content = file_get_contents('storage/framework/views/1eda0ea07d0b73fdd7a7758110eda88f.php');

$content = preg_replace('/<\?php \/\*\*PATH .*? \*\/\ ?>\n?/', '', $content);
$content = preg_replace('/<\?php echo e\((.*?)\); \?>/s', '{{ $1 }}', $content);
$content = preg_replace('/<\?php echo (.*?); \?>/s', '{!! $1 !!}', $content);
$content = preg_replace('/<\?php if\((.*?)\): \?>/s', '@if($1)', $content);
$content = preg_replace('/<\?php elseif\((.*?)\): \?>/s', '@elseif($1)', $content);
$content = preg_replace('/<\?php else: \?>/s', '@else', $content);
$content = preg_replace('/<\?php endif; \?>/s', '@endif', $content);
$content = preg_replace('/<\?php foreach\((.*?)\): \?>/s', '@foreach($1)', $content);
$content = preg_replace('/<\?php endforeach; \$__env->popLoop\(\); \$loop = \$__env->getLastLoop\(\); \?>/s', '@endforeach', $content);
$content = preg_replace('/<\?php endforeach; \?>/s', '@endforeach', $content);
$content = preg_replace('/<\?php \$__empty_\d+ = true; foreach\((.*?)\): \$__empty_\d+ = false; \?>/s', '@forelse($1)', $content);
$content = preg_replace('/<\?php endforeach; \$__env->popLoop\(\); \$loop = \$__env->getLastLoop\(\); if \(\$__empty_\d+\): \?>/s', '@empty', $content);
$content = preg_replace('/<\?php for\((.*?)\): \?>/s', '@for($1)', $content);
$content = preg_replace('/<\?php endfor; \?>/s', '@endfor', $content);
$content = preg_replace('/<\?php\s+(.*?)\s+\?>/s', "@php\n$1\n@endphp", $content);
$content = preg_replace("/@php\n\s*@endphp\n?/s", '', $content);

file_put_contents('resources/views/livewire/kinerja-teknisi.blade.php', $content);
echo 'Restored!';
