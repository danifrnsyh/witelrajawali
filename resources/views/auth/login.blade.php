<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Dasbor Rajawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Inter"', 'sans-serif'] },
                    colors: {
                        'bg-ambient': '#ebedf0',
                        'surface-card': '#ffffff',
                        'surface-subtle': '#f8fafc',
                        'telkom-red': '#dc2626',
                        'telkom-red-hover': '#b91c1c',
                        'text-main': '#0f172a',
                        'text-muted': '#64748b',
                        'border-card': '#e2e8f0',
                    },
                    borderRadius: {
                        'outer': '32px',
                        'inner': '20px',
                    },
                    boxShadow: {
                        'soft': '0 20px 40px -15px rgba(0,0,0,0.05), 0 0 1px 1px rgba(255,255,255,0.8)',
                    }
                }
            }
        }
    </script>
    <style>body { background-color: #ebedf0; color: #0f172a; }</style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 antialiased selection:bg-telkom-red selection:text-white">
    <div class="w-full max-w-md bg-surface-card rounded-outer shadow-soft p-10 border border-white/70">
        <div class="text-center mb-10">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
            </div>
            <h1 class="text-2xl font-bold text-text-main tracking-tight">Admin Login</h1>
            <p class="text-text-muted mt-2 text-sm font-medium">Masuk untuk mengedit data dasbor Rajawali</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl text-sm font-medium border border-red-200 flex items-center">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-bold text-text-muted mb-2 uppercase tracking-wide">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3.5 bg-surface-subtle border border-border-card rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red outline-none transition-all text-text-main font-medium">
            </div>
            
            <div class="mb-10">
                <label class="block text-xs font-bold text-text-muted mb-2 uppercase tracking-wide">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3.5 bg-surface-subtle border border-border-card rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red outline-none transition-all text-text-main font-medium" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-telkom-red hover:bg-telkom-red-hover text-white font-bold py-3.5 px-4 rounded-full transition-colors shadow-sm flex justify-center items-center">
                Masuk Dashboard
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
            
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="text-text-muted hover:text-text-main text-sm font-semibold transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>
</body>
</html>
