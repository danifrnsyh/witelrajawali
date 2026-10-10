<?php

$content = file_get_contents('resources/views/components/layouts/app.blade.php');

// Shrink header height on mobile
$content = preg_replace('/<header class="h-20 px-6 sm:px-10/s', '<header class="h-16 sm:h-20 px-4 sm:px-10', $content);

// Shrink STO RAJAWALI text on mobile
$content = preg_replace('/<h2 class="text-xl font-bold/s', '<h2 class="text-base sm:text-xl font-bold truncate max-w-[140px] sm:max-w-none', $content);

// Show sync button on mobile but hide text
$content = preg_replace('/<form method="POST" action="{{ route\(\'force-sync\'\) }}" class="hidden sm:block">/s', '<form method="POST" action="{{ route(\'force-sync\') }}">', $content);
$content = preg_replace('/<button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm/s', '<button type="submit" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-sm', $content);
$content = preg_replace('/Sync Data/s', '<span class="hidden sm:inline">Sync Data</span>', $content);

// Hide Guest/Admin text on mobile
$content = preg_replace('/<span class="text-sm font-bold text-text-main mx-3">/s', '<span class="hidden sm:inline text-sm font-bold text-text-main mx-3">', $content);

file_put_contents('resources/views/components/layouts/app.blade.php', $content);
echo 'App layout modified!';
