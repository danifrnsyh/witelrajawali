<?php
$file = 'app/Livewire/SektoralViewer.php';
$content = file_get_contents($file);

// 1. Fix header logic
$content = str_replace(
    "if (strpos(strtoupper(\$row[0] ?? ''), 'RANK') !== false) {",
    "if (strtoupper(trim(\$row[0] ?? '')) === 'RANK') {",
    $content
);

// 2. Fix header parsing (keep literal newlines)
$content = str_replace(
    "\$headers[] = trim(preg_replace('/\\s+/', ' ', \$h));",
    "\$headers[] = trim(\$h);",
    $content
);

file_put_contents($file, $content);
echo "Updated logic in SektoralViewer.php\n";
