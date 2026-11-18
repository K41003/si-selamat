<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SI SELAMAT Desa Tanjung Selamat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-deli-serdang-sm.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-langit text-ink-900 antialiased">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center p-4 sm:p-6 lg:p-8">
        {{-- Cangkang putih besar. Isi: tab logo, hero + formulir, pita nilai. --}}
        <main class="shell w-full overflow-hidden animate-fade-in">

            {{-- Tab logo (sudut kanan-bawah melengkung) --}}
            <div class="tab-logo inline-flex min-h-16 items-center gap-3 pl-5 sm:pl-8">
                {{-- Logo ringan 128px; versi resolusi penuh hanya untuk halaman cetak --}}
                <img src="{{ asset('images/logo-deli-serdang-sm.png') }}" width="40" height="40"
                     alt="Logo Kabupaten Deli Serdang" class="size-10 shrink-0 object-contain">
                <span class="leading-tight">
                    <span class="block text-sm font-semibold text-brand-600">Pemerintah Desa</span>
                    <span class="block text-lg font-bold tracking-tight text-brand-900">TANJUNG SELAMAT</span>
                </span>
            </div>

            <div class="grid items-center gap-8 px-5 py-8 sm:px-8 lg:grid-cols-[1.1fr_1fr] lg:gap-14 lg:py-12">
                {{-- Hero (kiri) --}}
                <div>
                    <p class="text-lg text-brand-600">Sistem Informasi Surat Elektronik</p>
                    <h1 class="mt-1 text-4xl font-extrabold uppercase leading-none tracking-tight text-brand-700 sm:text-5xl lg:text-6xl">SI SELAMAT</h1>
                    <p class="mt-4 hidden max-w-md text-sm leading-relaxed text-ink-700 sm:block">
                        Layanan administrasi surat kependudukan Kantor Kepala Desa Tanjung Selamat, Kecamatan Sunggal,
                        Kabupaten Deli Serdang. Dari pengajuan hingga tanda tangan digital, semuanya tercatat.
                    </p>
                </div>

                {{-- Formulir masuk (kanan), di atas bingkai biru pucat bergeser --}}
                <div class="relative">
                    <div aria-hidden="true" class="absolute -right-3 -top-3 hidden h-full w-full rounded-tl-leaf rounded-br-card bg-brand-100 sm:block"></div>

                    <div class="leaf relative border border-line bg-surface p-6 shadow-soft sm:p-8">
                        <h2 class="text-title-sm font-bold text-ink-900">Masuk ke Portal</h2>
                        <p class="mb-6 mt-1 text-sm text-ink-600">Masukkan username dan kata sandi akun dinas Anda.</p>

                        @if($errors->any())
                        <div role="alert" class="mb-5 rounded-control border border-danger-700/30 bg-danger-100/60 p-3.5 text-sm text-danger-800 animate-slide-down">
                            {{ $errors->first() }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="username" class="mb-1 block text-sm font-semibold text-ink-700">Username pengguna</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                                       autocomplete="username" class="field" placeholder="Contoh: staff atau kades">
                            </div>

                            <div x-data="{ showPassword: false }">
                                <label for="password" class="mb-1 block text-sm font-semibold text-ink-700">Kata sandi</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" type="password" id="password" name="password" required
                                           autocomplete="current-password" class="field pr-11" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                                    <button type="button" @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-ink-600 transition-colors hover:text-ink-900"
                                            :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                            :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                        <span x-show="!showPassword" class="flex items-center justify-center"><x-icon name="eye" class="size-5" /></span>
                                        <span x-show="showPassword" x-cloak class="flex items-center justify-center"><x-icon name="eye-slash" class="size-5" /></span>
                                    </button>
                                </div>
                            </div>

                            <label class="flex cursor-pointer items-center gap-2 pt-1 text-sm text-ink-700">
                                <input type="checkbox" name="remember" class="size-4 rounded border-ink-500 text-brand-700 focus:ring-brand-600">
                                <span>Ingat saya di perangkat ini</span>
                            </label>

                            <button type="submit" class="btn btn-primary w-full py-3">
                                <span>Masuk ke sistem</span>
                                <x-icon name="arrow-right" class="size-4" />
                            </button>
                        </form>

                        {{-- Akun demo hanya tampil di lingkungan lokal; JANGAN tampil di produksi. --}}
                        @if(app()->environment('local'))
                        <div class="mt-6 border-t border-line pt-6">
                            <p class="mb-2 text-sm font-semibold text-ink-600">Akun demo:</p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="fillCreds('staff', 'password123')"
                                        class="group rounded-control border border-line bg-canvas p-2.5 text-left transition hover:border-brand-200 hover:bg-brand-50">
                                    <p class="text-sm font-semibold text-ink-900 group-hover:text-brand-700">Staff Pelayanan</p>
                                    <p class="text-xs text-ink-600">staff / password123</p>
                                </button>
                                <button type="button" onclick="fillCreds('kades', 'kades123')"
                                        class="group rounded-control border border-line bg-canvas p-2.5 text-left transition hover:border-brand-200 hover:bg-brand-50">
                                    <p class="text-sm font-semibold text-ink-900 group-hover:text-brand-700">Kepala Desa</p>
                                    <p class="text-xs text-ink-600">kades / kades123</p>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pita nilai: 3 manfaat utama (dari tujuan PRD). Kartu putih di atas pita biru. --}}
            <section aria-label="Keunggulan layanan" class="pita rounded-none px-5 sm:px-8">
                <ul class="grid gap-3 md:grid-cols-3">
                    <li class="flex items-center gap-4 rounded-card bg-surface p-4 shadow-card">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-control bg-brand-100 text-brand-700"><x-icon name="clock" class="size-7" /></span>
                        <span>
                            <span class="block font-semibold text-ink-900">Penerbitan lebih cepat</span>
                            <span class="block text-sm text-ink-600">Dari pengajuan hingga tanda tangan digital.</span>
                        </span>
                    </li>
                    <li class="flex items-center gap-4 rounded-card bg-surface p-4 shadow-card">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-control bg-brand-100 text-brand-700"><x-icon name="inbox" class="size-7" /></span>
                        <span>
                            <span class="block font-semibold text-ink-900">Arsip tertata</span>
                            <span class="block text-sm text-ink-600">Surat tersimpan rapi dan mudah dicari.</span>
                        </span>
                    </li>
                    <li class="flex items-center gap-4 rounded-card bg-surface p-4 shadow-card">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-control bg-brand-100 text-brand-700"><x-icon name="check-circle" class="size-7" /></span>
                        <span>
                            <span class="block font-semibold text-ink-900">QR anti-pemalsuan</span>
                            <span class="block text-sm text-ink-600">Setiap surat terbit memiliki token unik.</span>
                        </span>
                    </li>
                </ul>
            </section>
        </main>
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
