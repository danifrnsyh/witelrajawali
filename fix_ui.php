<?php

function replaceAll($file, $rules)
{
    if (! file_exists($file)) {
        return;
    }
    $content = file_get_contents($file);
    foreach ($rules as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
}

// 1. App Layout
$app = 'resources/views/components/layouts/app.blade.php';
$rules_app = [
    'rounded-full' => 'rounded-md',
    'rounded-2xl' => 'rounded-xl',
    'bg-[#F8F9FA]' => 'bg-[#F4F4F5]', // Zinc-50
    'shadow-sm' => 'shadow-[0_2px_8px_rgba(0,0,0,0.04)]',
    'border-gray-200/50' => 'border-gray-200',
    'text-red-600' => 'text-gray-900', // Logout button
    'hover:text-red-800' => 'hover:text-black',
    'bg-telkom-red' => 'bg-gray-900',
    'hover:bg-red-700' => 'hover:bg-black',
    'border-telkom-red' => 'border-gray-900',
];
replaceAll($app, $rules_app);

// 2. Data Viewer
$data = 'resources/views/livewire/data-viewer.blade.php';
$rules_data = [
    'rounded-full' => 'rounded-md',
    'rounded-2xl' => 'rounded-xl',
    'bg-gray-50/50' => 'bg-gray-50',
    'border-gray-300/60' => 'border-gray-200',
    'bg-telkom-red' => 'bg-gray-900',
    'hover:bg-telkom-dark' => 'hover:bg-black',
    'focus:ring-telkom-red' => 'focus:ring-gray-900',
    'border-telkom-red' => 'border-gray-900',
];
replaceAll($data, $rules_data);

// 3. Dashboard Viewer
$dashboard = 'resources/views/livewire/dashboard-viewer.blade.php';
$rules_dashboard = [
    'rounded-full' => 'rounded-md',
    'rounded-2xl' => 'rounded-xl',
    'rounded-3xl' => 'rounded-xl',
    'bg-gray-50/50' => 'bg-gray-50',
    'border-gray-300/60' => 'border-gray-200',
    'border border-white/60' => 'border border-gray-200',
    'shadow-xl' => 'shadow-[0_20px_40px_rgba(0,0,0,0.08)]',
    'bg-telkom-red' => 'bg-gray-900',
    'hover:bg-telkom-dark' => 'hover:bg-black',
    'focus:ring-telkom-red' => 'focus:ring-gray-900',
    'border-t-telkom-red/20' => 'border-t-gray-300',
    'text-telkom-dark' => 'text-gray-900',
];
replaceAll($dashboard, $rules_dashboard);

echo "Basic layout fixes applied.\n";
