<?php
$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

$menuItem = <<<EOF
            <a href="/sektoral" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('sektoral') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('sektoral') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Peringkat Sektoral
            </a>
EOF;

$pos = strpos($content, "Papan Peringkat\n            </a>");
if ($pos !== false) {
    $insertPos = $pos + strlen("Papan Peringkat\n            </a>");
    $content = substr($content, 0, $insertPos) . "\n\n" . $menuItem . substr($content, $insertPos);
    file_put_contents($file, $content);
    echo "Added successfully.\n";
} else {
    // try different line endings
    $pos = strpos($content, "Papan Peringkat\r\n            </a>");
    if ($pos !== false) {
        $insertPos = $pos + strlen("Papan Peringkat\r\n            </a>");
        $content = substr($content, 0, $insertPos) . "\r\n\r\n" . $menuItem . substr($content, $insertPos);
        file_put_contents($file, $content);
        echo "Added successfully (CRLF).\n";
    } else {
        echo "Could not find insertion point.";
    }
}
