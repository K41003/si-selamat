<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SI SELAMAT Desa Tanjung Selamat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Poppins', system-ui, sans-serif; }</style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-slate-800">
    <div class="max-w-md w-full">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 text-white shadow-xl shadow-blue-500/20 mb-4 border border-blue-400/30">
                <svg class="w-9 h-9 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">SI SELAMAT</h1>
            <p class="text-sm text-blue-200 mt-1">Sistem Informasi Surat Elektronik & Administrasi</p>
            <p class="text-xs text-blue-300/80 font-medium">Kantor Kepala Desa Tanjung Selamat, Sunggal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Masuk ke Portal</h2>
            <p class="text-xs text-slate-500 mb-6">Silakan masukkan username dan kata sandi akun dinas Anda.</p>

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username Pengguna</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                        placeholder="Contoh: staff atau kades">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-sm font-semibold shadow-lg shadow-blue-900/20 transition duration-150 flex items-center justify-center gap-2">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Quick Demo Credentials Fill -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-500 mb-2">Akun Demo Resmi:</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="fillCreds('staff', 'password123')"
                        class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 text-left transition group">
                        <p class="text-xs font-semibold text-slate-800 group-hover:text-blue-900">Staff Pelayanan</p>
                        <p class="text-[10px] text-slate-500">staff / password123</p>
                    </button>
                    <button type="button" onclick="fillCreds('kades', 'kades123')"
                        class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 text-left transition group">
                        <p class="text-xs font-semibold text-slate-800 group-hover:text-blue-900">Kepala Desa</p>
                        <p class="text-[10px] text-slate-500">kades / kades123</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillCreds(u, p) {
            document.getElementById('username').value = u;
            document.getElementById('password').value = p;
        }
    </script>
</body>
</html>
