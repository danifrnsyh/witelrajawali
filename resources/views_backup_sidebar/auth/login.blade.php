<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Dasbor Rajawali</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'telkom-red': '#ED1C24' }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg p-8 border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Admin Login</h1>
            <p class="text-gray-500 mt-2 text-sm">Masuk untuk mengedit data dasbor</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-sm border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-telkom-red focus:border-telkom-red outline-none transition-all" placeholder="">
            </div>
            
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-telkom-red focus:border-telkom-red outline-none transition-all" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-telkom-red hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-sm">
                Masuk
            </button>
            
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium">← Kembali ke Beranda</a>
            </div>
        </form>
    </div>
</body>
</html>
