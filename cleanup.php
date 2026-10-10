<?php

$content = file_get_contents('resources/views/livewire/kinerja-teknisi.blade.php');

$content = preg_replace('/@if\(\\\\Livewire\\\\Mechanisms\\\\ExtendBlade\\\\ExtendBlade::isRenderingLivewireComponent\(\)\)(<!--\[if BLOCK\]><!\[endif\]-->)?@php/s', '@php', $content);
$content = preg_replace('/@if\(\\\\Livewire\\\\Mechanisms\\\\ExtendBlade\\\\ExtendBlade::isRenderingLivewireComponent\(\)\)<!--\[if ENDBLOCK\]><!\[endif\]-->@endif/s', '', $content);
$content = preg_replace('/@if\(\\\\Livewire\\\\Mechanisms\\\\ExtendBlade\\\\ExtendBlade::isRenderingLivewireComponent\(\)\)/s', '', $content);
$content = preg_replace('/@php\s+\\\\Livewire\\\\Features\\\\SupportCompiledWireKeys\\\\SupportCompiledWireKeys::.*?;?\s+@endphp/s', '', $content);

$content = preg_replace('/@php\s+\$__empty_1 = true; \$__currentLoopData = \$technicians; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$tech\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\); \$__empty_1 = false;\s+@endphp/s', '@forelse($technicians as $tech)', $content);
$content = preg_replace('/@php\s+\$__currentLoopData = \$stats\[\'history\'\]; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$item\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\);\s+@endphp/s', '@foreach($stats[\'history\'] as $item)', $content);
$content = preg_replace('/@php\s+\$__currentLoopData = \[\s*\'start_dt\' => \'WAKTU MULAI\',\s*\'segment\' => \'SEGMEN\',\s*\'jenis_order\' => \'JENIS ORDER\',\s*\'peran\' => \'PERAN\',\s*\'workzone\' => \'WORKZONE\',\s*\'durasi_menit\' => \'DURASI\',\s*\'tiket_no\' => \'TIKET NO\',\s*\'partner\' => \'PARTNER\'\s*\]; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$key => \$label\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\);\s+@endphp/s', '@foreach([\'start_dt\' => \'WAKTU MULAI\', \'segment\' => \'SEGMEN\', \'jenis_order\' => \'JENIS ORDER\', \'peran\' => \'PERAN\', \'workzone\' => \'WORKZONE\', \'durasi_menit\' => \'DURASI\', \'tiket_no\' => \'TIKET NO\', \'partner\' => \'PARTNER\'] as $key => $label)', $content);
$content = preg_replace('/@php\s+\$__currentLoopData = \$kendalaStats\[\'top_kendala\'\]; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$jenis => \$count\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\);\s+@endphp/s', '@foreach($kendalaStats[\'top_kendala\'] as $jenis => $count)', $content);
$content = preg_replace('/@php\s+\$__currentLoopData = \$kendalaStats\[\'history\'\]; \$__env->addLoop\(\$__currentLoopData\); foreach\(\$__currentLoopData as \$item\): \$__env->incrementLoopIndices\(\); \$loop = \$__env->getLastLoop\(\);\s+@endphp/s', '@foreach($kendalaStats[\'history\'] as $item)', $content);

$content = preg_replace('/@endif\s*@endif\s*<tr>\s*<td colspan="3"/s', '<tr><td colspan="3"', $content);
$content = preg_replace('/@endif@empty/s', '@empty', $content);
$content = preg_replace('/@endif\s*@empty/s', '@empty', $content);
$content = preg_replace('/@endif@endforeach/s', '@endforeach', $content);
$content = preg_replace('/@endif\s*@endif\s*<\/tbody>/s', '</tbody>', $content);
$content = preg_replace('/<!--\[if BLOCK\]><!\[endif\]-->/s', '', $content);
$content = preg_replace('/<!--\[if ENDBLOCK\]><!\[endif\]-->/s', '', $content);
$content = preg_replace('/@php\s+\/\*\*PATH.*?\*\*\/\s+@endphp/s', '', $content);
$content = preg_replace('/@endif\s*@endif\s*@endforeach/s', '@endforeach', $content);
$content = preg_replace('/@endif\s*@endforeach/s', '@endforeach', $content);

file_put_contents('resources/views/livewire/kinerja-teknisi.blade.php', $content);
echo 'Cleaned up!';
