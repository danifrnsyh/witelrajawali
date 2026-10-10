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
    
    <!-- Export Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function exportToJPG(elementId, filename) {
            const element = document.getElementById(elementId);
            
            // Sembunyikan elemen yg tak perlu (tombol export, search)
            const exportBtns = document.querySelectorAll('.export-btn-hide');
            exportBtns.forEach(btn => btn.style.display = 'none');
            
            // Tampilkan teks khusus export (seperti rentang tanggal)
            const exportTexts = document.querySelectorAll('.export-show-text');
            exportTexts.forEach(txt => txt.classList.remove('hidden'));
            
            html2canvas(element, { scale: 2, useCORS: true, backgroundColor: '#F8F9FA' }).then(canvas => {
                let link = document.createElement('a');
                link.download = filename + '.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
                
                // Kembalikan seperti semula
                exportBtns.forEach(btn => btn.style.display = '');
                exportTexts.forEach(txt => txt.classList.add('hidden'));
            });
        }

        function exportToPDF(elementId, filename) {
            const element = document.getElementById(elementId);
            
            const exportBtns = document.querySelectorAll('.export-btn-hide');
            exportBtns.forEach(btn => btn.style.display = 'none');
            
            const exportTexts = document.querySelectorAll('.export-show-text');
            exportTexts.forEach(txt => txt.classList.remove('hidden'));

            const opt = {
                margin:       0.2,
                filename:     filename + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#F8F9FA' },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
            };
            
            html2pdf().set(opt).from(element).save().then(() => {
                exportBtns.forEach(btn => btn.style.display = '');
                exportTexts.forEach(txt => txt.classList.add('hidden'));
            });
        }
    </script>
</head>
<body class="bg-[#F8F9FA] text-gray-900 font-sans antialiased selection:bg-telkom-red selection:text-white relative  min-h-screen">
    <!-- Navbar -->
    <nav class="z-50 bg-white  border-b border-gray-200/50 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 2xl:px-12 mx-auto">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-8">
                    <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                        <!-- App Logo -->
                        <img src="{{ asset('images/logo.png') }}" alt="Telkom Akses Logo" class="h-7 sm:h-10 w-auto object-contain drop-shadow-sm">
                        <span class="font-semibold text-[15px] sm:text-xl tracking-tight whitespace-nowrap">Telkom Akses Rajawali</span>
                    </div>
                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-8 h-full items-center">
                        <a href="/" wire:navigate class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('/') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium' }} text-base transition-colors h-full">
                            Data Sheet Utama
                        </a>
                        <a href="/dashboard" wire:navigate class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('dashboard') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium' }} text-base transition-colors h-full">
                            Dashboard Harian
                        </a>
                        <a href="/kinerja" wire:navigate class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('kinerja') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium' }} text-base transition-colors h-full">
                            Kinerja Teknisi
                        </a>
                    </div>
                </div>
                
                <!-- Auth / Admin Section -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <form method="POST" action="{{ route('force-sync') }}" class="inline" onsubmit="this.querySelector('button').innerHTML='Syncing...'; this.querySelector('button').disabled=true;">
                            @csrf
                            <button type="submit" class="text-[10px] sm:text-sm font-bold text-white bg-telkom-red hover:bg-red-700 px-2 sm:px-4 py-1.5 rounded-full shadow transition-colors flex items-center">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Sync
                            </button>
                        </form>
                        <span class="hidden sm:inline text-sm font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full">Admin</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs sm:text-base font-bold text-red-600 hover:text-red-800 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-telkom-red transition-colors flex items-center" title="Login Admin">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Mobile Navigation Bottom Bar -->
            <div class="md:hidden flex overflow-x-auto pb-2 space-x-6 border-t border-gray-100 pt-2 hide-scrollbar">
                <a href="/" wire:navigate class="inline-flex items-center px-1 pb-1 border-b-2 {{ request()->is('/') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 font-medium' }} text-sm whitespace-nowrap">
                    Data Sheet Utama
                </a>
                <a href="/dashboard" wire:navigate class="inline-flex items-center px-1 pb-1 border-b-2 {{ request()->is('dashboard') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 font-medium' }} text-sm whitespace-nowrap">
                    Dashboard Harian
                </a>
                <a href="/kinerja" wire:navigate class="inline-flex items-center px-1 pb-1 border-b-2 {{ request()->is('kinerja') ? 'border-telkom-red text-gray-900 font-bold' : 'border-transparent text-gray-500 font-medium' }} text-sm whitespace-nowrap">
                    Kinerja Teknisi
                </a>
            </div>
        </div>


    <!-- Main Content -->
    <main class="w-full px-4 sm:px-6 lg:px-8 2xl:px-12 mx-auto py-10">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
