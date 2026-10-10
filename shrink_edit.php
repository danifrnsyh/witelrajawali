<?php
$files = [
    'resources/views/livewire/data-viewer.blade.php',
    'resources/views/livewire/dashboard-viewer.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // 1. Shrink column padding for AKSI header
    $content = preg_replace(
        '/<th([^>]*?)px-5([^>]*?)>(Aksi|AKSI)<\/th>/i', 
        '<th$1px-3 sm:px-5$2>$3</th>', 
        $content
    );

    // 2. Shrink column padding for AKSI cells
    $content = preg_replace(
        '/<td([^>]*?)class="no-export px-5/i', 
        '<td$1class="no-export px-3 sm:px-5', 
        $content
    );

    // 3. Replace "Edit" button with compact responsive button
    $oldBtn1 = 'class="text-xs font-bold px-4 py-1.5 rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all">Edit</button>';
    
    $newBtn = 'class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all flex items-center justify-center mx-auto sm:mx-0 sm:ml-auto" title="Edit">
                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                    </button>';
    
    $content = str_replace($oldBtn1, $newBtn, $content);
    
    file_put_contents($file, $content);
}
echo "DONE\n";
