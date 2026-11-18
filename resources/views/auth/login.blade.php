<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SI SELAMAT Desa Tanjung Selamat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-deli-serdang.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-brand-900 via-slate-900 to-brand-900 text-ink-900">
    <div class="max-w-md w-full">
        <!-- Logo & Title -->
        @php
            $logoFile = public_path('images/logo-deli-serdang.png');
            $logoSrc = file_exists($logoFile)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile))
                : asset('images/logo-deli-serdang.png');
        @endphp
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-card bg-white/10 p-2.5 shadow-xl shadow-black/20 mb-4 border border-white/20 backdrop-blur-sm">
                <img src="{{ $logoSrc }}" alt="Logo Kabupaten Deli Serdang" class="w-full h-full object-contain drop-shadow">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">SI SELAMAT</h1>
            <p class="text-sm text-blue-200 mt-1">Sistem Informasi Surat Elektronik & Administrasi</p>
            <p class="text-xs text-blue-300/80 font-medium">Kantor Kepala Desa Tanjung Selamat, Sunggal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-surface rounded-card shadow-lg p-6 sm:p-8 border border-line animate-fade-in">
            <h2 class="text-lg font-bold text-ink-900 mb-1">Masuk ke Portal</h2>
            <p class="text-xs text-ink-600 mb-6">Silakan masukkan username dan kata sandi akun dinas Anda.</p>

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-control bg-danger-100 border border-danger-700/30 text-danger-800 text-xs animate-slide-down">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-semibold text-ink-700 mb-1">Username Pengguna</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="field text-sm"
                        placeholder="Contoh: staff atau kades">
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-semibold text-ink-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" type="password" id="password" name="password" required
                            class="field text-sm pr-11"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                        <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-ink-600 hover:text-ink-900 focus:text-brand-600 focus:outline-none transition-colors"
                            :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                            :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <span x-show="!showPassword" class="flex items-center justify-center">
                                <x-icon name="eye" class="w-5 h-5" />
                            </span>
                            <span x-show="showPassword" x-cloak class="flex items-center justify-center">
                                <x-icon name="eye-slash" class="w-5 h-5" />
                            </span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-ink-700">
                        <input type="checkbox" name="remember" class="rounded border-ink-500 text-brand-700 focus:ring-brand-600">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit"
                    class="btn btn-primary w-full py-3 text-sm font-semibold shadow-sm flex items-center justify-center gap-2">
                    <span>Masuk ke Sistem</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
            </form>

            {{-- Akun demo hanya tampil di lingkungan lokal; JANGAN tampil di produksi. --}}
            @if(app()->environment('local'))
            <!-- Quick Demo Credentials Fill -->
            <div class="mt-6 pt-6 border-t border-line">
                <p class="text-xs font-semibold text-ink-600 mb-2">Akun Demo Resmi:</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="fillCreds('staff', 'password123')"
                        class="p-2.5 rounded-control border border-line bg-canvas hover:bg-brand-50 hover:border-brand-200 text-left transition group">
                        <p class="text-xs font-semibold text-ink-900 group-hover:text-brand-700">Staff Pelayanan</p>
                        <p class="text-[10px] text-ink-600">staff / password123</p>
                    </button>
                    <button type="button" onclick="fillCreds('kades', 'kades123')"
                        class="p-2.5 rounded-control border border-line bg-canvas hover:bg-brand-50 hover:border-brand-200 text-left transition group">
                        <p class="text-xs font-semibold text-ink-900 group-hover:text-brand-700">Kepala Desa</p>
                        <p class="text-[10px] text-ink-600">kades / kades123</p>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(app()->environment('local'))
    <script>
        function fillCreds(u, p) {
            document.getElementById('username').value = u;
            document.getElementById('password').value = p;
        }
    </script>
    @endif
</body>
</html>
