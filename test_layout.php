<?php
$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

$menuItem = <<<EOF
                    <!-- Peringkat Sektoral -->
                    <a href="/sektoral" wire:navigate class="nav-link flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->is('sektoral') ? 'bg-telkom-red/10 text-telkom-red font-bold shadow-sm' : 'text-text-muted hover:bg-surface-subtle hover:text-text-main hover:scale-[1.02]' }}">
                        <div class="{{ request()->is('sektoral') ? 'bg-telkom-red text-white' : 'bg-surface-card text-text-muted border border-border-card' }} p-2 rounded-xl mr-3 shadow-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        Peringkat Sektoral
                    </a>
EOF;

// Insert after Peringkat
$pos = strpos($content, "Peringkat</a>");
if ($pos !== false) {
    $endTag = "</a>";
    $endPos = strpos($content, $endTag, $pos);
    if ($endPos !== false) {
        $insertPos = $endPos + strlen($endTag);
        $content = substr($content, 0, $insertPos) . "\n" . $menuItem . substr($content, $insertPos);
        file_put_contents($file, $content);
        echo "Added to layout.";
    }
}
