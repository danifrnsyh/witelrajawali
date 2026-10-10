<?php

$content = file_get_contents('resources/views/livewire/kinerja-teknisi.blade.php');

$content = preg_replace('/@php\s+\$__empty_\d+ = true; \$__currentLoopData = \$stats\[\'top_areas\'\]; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$area => \$count\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\); \$__empty_\d+ = false;\s+@endphp/s', '@forelse($stats[\'top_areas\'] as $area => $count)', $content);

$content = preg_replace('/@php\s+endforeach; \$__env->popLoop\(\); \$loop = \$__env->getLastLoop\(\); if \(\$__empty_-\d+\):\s+@endphp@endif/s', '@empty', $content);

$content = preg_replace('/@php\s+\$__currentLoopData = \$headers; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$key => \$label\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\);\s+@endphp@endif/s', '@foreach($headers as $key => $label)', $content);

file_put_contents('resources/views/livewire/kinerja-teknisi.blade.php', $content);
echo 'Cleaned!';
