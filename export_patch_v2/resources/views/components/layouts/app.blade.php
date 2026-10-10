<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Web Witel Rajawali</title>

    <script>
        // Global Fetch & XHR Interceptor for seamless ngrok & proxy support
        (function() {
            const originalFetch = window.fetch;
            window.fetch = function(url, options) {
                options = options || {};
                options.headers = options.headers || {};

                if (typeof url === 'string') {
                    try {
                        const parsed = new URL(url, window.location.origin);
                        if (window.location.protocol === 'https:' && parsed.protocol === 'http:') {
                            parsed.protocol = 'https:';
                            parsed.host = window.location.host;
                            url = parsed.toString();
                        } else if (parsed.host !== window.location.host && (parsed.host.includes('localhost') || parsed.host.includes('127.0.0.1') || parsed.host.includes('ngrok'))) {
                            parsed.protocol = window.location.protocol;
                            parsed.host = window.location.host;
                            url = parsed.toString();
                        }
                    } catch (e) {}
                }

                if (options.headers instanceof Headers) {
                    options.headers.set('ngrok-skip-browser-warning', '69420');
                } else if (Array.isArray(options.headers)) {
                    options.headers.push(['ngrok-skip-browser-warning', '69420']);
                } else {
                    options.headers['ngrok-skip-browser-warning'] = '69420';
                }

                return originalFetch.call(this, url, options);
            };

            const originalOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url, ...rest) {
                if (typeof url === 'string') {
                    try {
                        const parsed = new URL(url, window.location.origin);
                        if (window.location.protocol === 'https:' && parsed.protocol === 'http:') {
                            parsed.protocol = 'https:';
                            parsed.host = window.location.host;
                            url = parsed.toString();
                        } else if (parsed.host !== window.location.host && (parsed.host.includes('localhost') || parsed.host.includes('127.0.0.1') || parsed.host.includes('ngrok'))) {
                            parsed.protocol = window.location.protocol;
                            parsed.host = window.location.host;
                            url = parsed.toString();
                        }
                    } catch (e) {}
                }
                return originalOpen.call(this, method, url, ...rest);
            };

            const originalSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.send = function(...args) {
                try {
                    this.setRequestHeader('ngrok-skip-browser-warning', '69420');
                } catch(e) {}
                return originalSend.apply(this, args);
            };
        })();
    </script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        'bg-ambient': '#ebedf0',
                        'surface-card': '#ffffff',
                        'surface-subtle': '#f8fafc',
                        'telkom-red': '#dc2626',
                        'telkom-red-hover': '#b91c1c',
                        'telkom-red-light': '#fef2f2',
                        'text-main': '#0f172a',
                        'text-muted': '#64748b',
                        'text-subtle': '#94a3b8',
                        'border-card': '#e2e8f0',
                    },
                    borderRadius: {
                        'outer': '32px',
                        'inner': '20px',
                        'pill': '9999px',
                    },
                    boxShadow: {
                        'soft': '0 20px 40px -15px rgba(0,0,0,0.05), 0 0 1px 1px rgba(255,255,255,0.8)',
                        'card': '0 10px 30px -10px rgba(15, 23, 42, 0.06)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #ffffff;
            color: #0f172a;
        }
        @media (min-width: 768px) {
            body {
                background-color: #ebedf0;
            }
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @livewireStyles
</head>
<body x-data="{ mobileMenuOpen: false }" 
      @keydown.escape.window="mobileMenuOpen = false" 
      class="antialiased h-[100dvh] md:h-screen flex flex-col md:flex-row overflow-hidden bg-white md:bg-bg-ambient selection:bg-telkom-red selection:text-white">

    <!-- Mobile Header & Toggle -->
    <div class="md:hidden flex items-center justify-between bg-surface-card px-6 py-4 border-b border-border-card shrink-0 relative z-30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full overflow-hidden bg-white shadow-sm flex items-center justify-center p-0.5 border border-border-card shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain rounded-full">
            </div>
            <span class="font-bold text-text-main">Telkom Akses</span>
        </div>
        <button @click="mobileMenuOpen = !mobileMenuOpen" id="mobile-menu-btn" type="button" aria-label="Menu" class="p-2 bg-surface-subtle hover:bg-gray-100 rounded-full text-text-muted active:scale-95 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </div>

    <!-- Left Sidebar -->
    <aside id="sidebar" 
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed md:static inset-y-0 left-0 z-50 w-64 md:w-[260px] h-[100dvh] md:h-screen bg-white md:bg-transparent transform transition-transform duration-300 ease-in-out flex flex-col shrink-0 border-r border-gray-200/50 md:border-none overflow-y-auto pb-8 shadow-2xl md:shadow-none pointer-events-auto">
        
        <div class="px-6 md:px-8 py-6 md:py-8 flex items-center justify-between md:justify-start space-x-3 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full overflow-hidden bg-white shadow-sm flex items-center justify-center p-0.5 border border-border-card shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain rounded-full">
                </div>
                <span class="font-bold text-lg text-text-main tracking-tight">Telkom Akses</span>
            </div>
            <!-- Close button for mobile -->
            <button @click="mobileMenuOpen = false" id="mobile-close-btn" type="button" aria-label="Tutup Menu" class="md:hidden p-1.5 bg-surface-subtle hover:bg-gray-100 rounded-full text-text-muted transition-colors active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <nav class="flex-1 px-4 md:px-6 space-y-2.5 mt-2 md:mt-0">
            <p class="px-4 text-xs font-bold text-text-subtle uppercase tracking-wider mb-4">Menu Utama</p>
            
            <a href="/" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('/') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('/') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Data Sheet
            </a>
            
            <a href="/dashboard" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('dashboard') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('dashboard') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Produktivitas
            </a>

            <a href="/heatmap" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('heatmap') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('heatmap') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Heatmap Harian
            </a>

            <a href="/peringkat" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('peringkat') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('peringkat') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                Papan Peringkat
            </a>
            
            <a href="/kinerja" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('kinerja') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('kinerja') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Kinerja Teknisi
            </a>

            <a href="/sobi-digipos" wire:navigate @click="if (window.innerWidth < 768) mobileMenuOpen = false" class="flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->is('sobi-digipos') ? 'bg-telkom-red text-white shadow-md font-medium' : 'bg-black/[0.04] text-text-muted hover:bg-white hover:text-text-main hover:shadow-sm font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->is('sobi-digipos') ? 'text-white' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                SOBI/DIGIPOS
            </a>
        </nav>
        
        <div class="px-6 md:px-8 mt-auto pt-4 shrink-0">
            @auth
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-white text-text-main font-bold rounded-full shadow-sm hover:shadow-md transition-shadow border border-border-card">
                        Logout Admin
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-3 bg-white text-text-main hover:text-telkom-red font-bold rounded-full shadow-sm hover:shadow-md transition-all border border-border-card">
                    Login Admin
                </a>
            @endauth
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="mobile-overlay" 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileMenuOpen = false" 
        class="fixed inset-0 bg-text-main/30 backdrop-blur-sm z-40 md:hidden" 
        style="display: none;"></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-[100dvh] md:h-screen md:py-4 md:pr-4 min-w-0 bg-white md:bg-transparent">
        <!-- Card-in-container: The massive white curved background -->
        <main class="flex-1 bg-surface-card md:rounded-[32px] shadow-soft border-t md:border border-border-card overflow-hidden flex flex-col relative">
            
            <!-- Top Bar -->
            <header class="h-16 sm:h-20 px-4 sm:px-10 flex items-center justify-between border-b border-border-card/50 shrink-0 bg-surface-card z-10">
                <div>
                    <h2 class="text-base sm:text-xl font-bold truncate max-w-[140px] sm:max-w-none text-text-main tracking-wide uppercase">
                        STO RAJAWALI
                    </h2>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Last Sync Text -->
                    <div class="hidden lg:flex flex-col text-right mr-2">
                        <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider">Terakhir Sinkron</span>
                        <span class="text-xs font-extrabold text-telkom-red">
                            {{ \App\Models\SheetData::latest('updated_at')->first()?->updated_at?->diffForHumans() ?? 'Belum ada data' }}
                        </span>
                    </div>

                    <!-- Sync Button -->
                    <form method="POST" action="{{ route('force-sync') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-sm font-semibold text-text-main bg-white hover:text-telkom-red border border-border-card rounded-full shadow-sm hover:shadow transition-colors shrink-0">
                            <svg class="w-4 h-4 mr-0 sm:mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span class="hidden sm:inline">Sync Data</span>
                        </button>
                    </form>
                    
                    <!-- Avatar Pill -->
                    <div class="flex items-center pl-2 pr-1 py-1 bg-surface-subtle rounded-full border border-border-card/60 shadow-sm cursor-pointer hover:bg-gray-100 transition-colors">
                        <span class="hidden sm:inline text-sm font-bold text-text-main mx-3">{{ auth()->check() ? 'Admin' : 'Guest' }}</span>
                        <div class="w-8 h-8 rounded-full {{ auth()->check() ? 'bg-telkom-red' : 'bg-gray-400' }} text-white flex items-center justify-center font-bold text-xs shadow-inner">
                            {{ auth()->check() ? 'AD' : 'GS' }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar bg-surface-card">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            if (window.Livewire && typeof window.Livewire.hook === 'function') {
                window.Livewire.hook('request', ({ options }) => {
                    if (options && options.headers) {
                        options.headers['ngrok-skip-browser-warning'] = '69420';
                    }
                });
            }
        });

        // Global Navigation Hook to close mobile menu automatically
        document.addEventListener('livewire:navigated', () => {
            if (window.Alpine) {
                const body = document.querySelector('body');
                if (body && body._x_dataStack && body._x_dataStack[0]) {
                    body._x_dataStack[0].mobileMenuOpen = false;
                }
            }
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            if (window.innerWidth < 768) {
                sidebar?.classList.add('-translate-x-full');
                if (overlay) overlay.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/export.js') }}?v={{ time() }}"></script>
</body>
</html>

