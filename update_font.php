<?php
$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    'family=Plus+Jakarta+Sans:wght@400;500;600;700',
    'family=Inter:wght@400;500;600;700',
    $content
);

$content = str_replace(
    '"Plus Jakarta Sans"',
    '"Inter"',
    $content
);

file_put_contents($file, $content);
echo "Updated font to Inter in app.blade.php\n";
