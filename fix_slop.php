<?php

// Restore Kinerja Teknisi from a simpler state and apply clean rules
$file = 'resources/views/livewire/kinerja-teknisi.blade.php';
$content = file_get_contents($file);

// Strip all gradient slop
$content = preg_replace('/bg-gradient-to-[a-z]+ from-[a-z]+-\d+ to-[a-z]+-\d+/', 'bg-white', $content);
$content = preg_replace('/text-white/', 'text-gray-900', $content);
$content = preg_replace('/text-white\/[0-9]+/', 'text-gray-500', $content);
$content = preg_replace('/border-white\/[0-9]+/', 'border-gray-200', $content);
$content = str_replace('blur-2xl', 'hidden', $content);
$content = str_replace('backdrop-blur-sm', '', $content);
$content = preg_replace('/shadow-\[.*?\]/', 'shadow-sm', $content);
$content = str_replace('rounded-3xl', 'rounded-lg', $content);
$content = str_replace('rounded-2xl', 'rounded-lg', $content);
$content = str_replace('rounded-xl', 'rounded-md', $content);
$content = str_replace('hover:-translate-y-1', 'hover:border-gray-300', $content);

// Ensure card text colors are correct after stripping gradients
$content = str_replace('text-gray-900/90', 'text-gray-600', $content);
$content = str_replace('text-gray-900/80', 'text-gray-500', $content);
$content = str_replace('bg-white/10', 'bg-gray-100', $content);
$content = str_replace('bg-white/20', 'bg-gray-100', $content);

// Put a solid dark card for the first one as accent
$accent_target = '<div class="bg-white text-gray-900 border border-red-500/50 rounded-lg p-6 shadow-sm hover:shadow-sm relative overflow-hidden group transition-all duration-300 transform hover:border-gray-300">';
$accent_replacement = '<div class="bg-gray-900 text-white border border-gray-800 rounded-lg p-6 shadow-[0_2px_10px_rgba(0,0,0,0.08)] relative overflow-hidden group transition-all duration-300 hover:border-gray-600">';
$content = str_replace($accent_target, $accent_replacement, $content);

file_put_contents($file, $content);

echo "Stripped slop from Kinerja Teknisi.\n";
