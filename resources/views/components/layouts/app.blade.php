<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Witel Rajawali</title>
    <!-- Fonts: Inter (Apple-like) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        telkom: {
                            red: '#ED1C24',
                            dark: '#C8102E'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Apple-style minimalist scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
    @livewireStyles
</head>
<body class="bg-[#F8F9FA] text-gray-900 font-sans antialiased selection:bg-telkom-red selection:text-white relative overflow-x-hidden min-h-screen">
    <!-- Ambient Background Blobs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] rounded-full bg-red-100/50 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[45%] h-[45%] rounded-full bg-slate-200/60 blur-[120px]"></div>
    </div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/50 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 2xl:px-12 mx-auto">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <!-- Fake Logo Dot -->
                    <div class="w-8 h-8 rounded-full bg-telkom-red flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-xs">TR</span>
                    </div>
                    <span class="font-semibold text-lg tracking-tight">Telkom Akses Rajawali</span>
                </div>
                <div class="flex space-x-6">
                    <a href="/" class="text-sm font-medium transition-colors {{ request()->is('/') ? 'text-telkom-red' : 'text-gray-500 hover:text-gray-900' }}">Data Sheet Utama</a>
                    <a href="/dashboard" class="text-sm font-medium transition-colors {{ request()->is('dashboard') ? 'text-telkom-red' : 'text-gray-500 hover:text-gray-900' }}">Dashboard Harian</a>
                    <a href="/kinerja" class="text-sm font-medium transition-colors {{ request()->is('kinerja') ? 'text-telkom-red' : 'text-gray-500 hover:text-gray-900' }}">Rapor Teknisi</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full px-4 sm:px-6 lg:px-8 2xl:px-12 mx-auto py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
