<?php

$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

$styles = <<<EOT
    <!-- Pro UI Overrides -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f4f5; /* Zinc 50 */
            color: #09090b; /* Zinc 950 */
        }
        
        .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
        }

        /* Solid Crisp Cards */
        .rounded-xl, .rounded-2xl, .rounded-3xl {
            border-radius: 6px !important;
        }
        
        /* Stop buttons and general layout from being pills */
        .rounded-full:not(img):not(.w-32):not(.w-12):not(.w-8):not(.w-4):not(.w-2) {
            border-radius: 6px !important;
        }

        .shadow-sm, .shadow-md, .shadow-xl, [class*="shadow-"] {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
        }

        /* Crisp borders everywhere */
        .border-gray-200, .border-gray-300\/60, .border-white\/60, .border-gray-100\/70, .border-gray-300 {
            border-color: #e4e4e7 !important; /* Zinc 200 */
        }
        
        /* Professional Black/White contrast */
        .bg-telkom-red {
            background-color: #09090b !important; /* Jet Black */
            color: #ffffff !important;
        }
        
        .text-telkom-red {
            color: #09090b !important;
        }

        /* Nav and Layout */
        nav {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e4e4e7 !important;
        }

        /* Table Styling */
        table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
        }
        
        th {
            background-color: #ffffff !important;
            border-bottom: 1px solid #d4d4d8 !important; /* Zinc 300 */
            color: #52525b !important; /* Zinc 600 */
            font-weight: 600 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        
        td {
            border-bottom: 1px solid #f4f4f5 !important;
            color: #27272a !important; /* Zinc 800 */
        }
        
        tr:hover td {
            background-color: #fafafa !important;
        }

        /* Accent red for critical states only */
        .text-red-500 {
            color: #ef4444 !important;
        }
        .bg-red-400 {
            background-color: #ef4444 !important;
        }
    </style>
EOT;

$content = str_replace('</head>', $styles."\n</head>", $content);
file_put_contents($file, $content);
echo "Injected Pro CSS.\n";
