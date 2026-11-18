<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SI SELAMAT · Desa Tanjung Selamat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-deli-serdang-sm.png') }}">

    {{-- Aset dibangun oleh Vite (Tailwind v4 + font self-host). Jalankan `npm run build`. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-surface">
    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-control focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand-700 focus:shadow-lg no-print">Lewati ke konten utama</a>

    @php
        $isKades = auth()->check() && auth()->user()->isKades();
        $pendingCount = $isKades ? \App\Models\Surat::where('status', 'diajukan')->count() : 0;

        // [label, url, aktif?]
        $menu = [
            ['Dashboard',     route('dashboard'),     request()->routeIs('dashboard')],
            ['Data Warga',    route('warga.index'),   request()->routeIs('warga.*')],
            ['Daftar Surat',  route('surat.index'),   request()->routeIs('surat.index') || request()->routeIs('surat.show')],
            ['Buat Surat',    route('surat.create'),  request()->routeIs('surat.create')],
        ];
        if ($isKades) {
            $menu[] = ['Validasi Kades', route('validasi.index'), request()->routeIs('validasi.*')];
        }
        $menu[] = ['Log Aktivitas', route('logs.index'), request()->routeIs('logs.*')];
    @endphp

    <div class="flex min-h-screen flex-col">
        <div class="app-shell">
            <header class="no-print z-40 border-b border-line bg-surface xl:sticky xl:top-0">
                {{-- Layar < xl: baris 1 = logo + pengguna, baris 2 = menu. Layar xl ke atas: satu baris. --}}
                <div class="flex flex-wrap items-center gap-x-4 pr-4 sm:pr-8 xl:flex-nowrap xl:gap-x-6">
                    <a href="{{ route('dashboard') }}" class="tab-logo group flex min-h-20 items-center gap-3 pl-5 pr-8 sm:pl-8">
                        <img src="{{ asset('images/logo-deli-serdang-sm.png') }}" width="40" height="40" alt="Logo Kabupaten Deli Serdang" class="size-10 shrink-0 object-contain transition-transform group-hover:scale-105">
                        <span class="leading-tight">
                            <span class="block text-lg font-extrabold tracking-tight text-brand-900">SI SELAMAT</span>
                            <span class="hidden text-xs text-ink-600 sm:block">Desa Tanjung Selamat</span>
                        </span>
                    </a>

                    @auth
                    <div class="ml-auto flex items-center gap-4 py-2 xl:order-last">
                        <div class="hidden text-right leading-tight md:block">
                            <p class="text-xs font-semibold text-ink-900 whitespace-nowrap" title="{{ auth()->user()->active_staff_name ?: auth()->user()->name }}">{{ auth()->user()->active_staff_name ?: auth()->user()->name }}</p>
                            <p class="text-xs text-ink-600">{{ auth()->user()->role === 'kades' ? 'Kepala Desa' : 'Staf Pelayanan' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <x-icon name="logout" class="size-4"/>
                                Keluar
                            </button>
                        </form>
                    </div>
                    @endauth

                    <nav aria-label="Menu utama" class="relative order-last flex w-full gap-1 overflow-x-auto px-5 pb-3 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:px-3 xl:order-none xl:w-auto xl:flex-1 xl:justify-center xl:px-0 xl:pb-0">
                        @foreach($menu as [$label, $url, $active])
                        <a href="{{ $url }}" @if($active) aria-current="page" @endif class="pill-tab">
                            {{ $label }}
                            @if($label === 'Validasi Kades' && $pendingCount > 0)
                            <span class="rounded-full bg-gold-400 px-2 text-xs font-bold text-brand-900">{{ $pendingCount }}<span class="sr-only"> surat menunggu</span></span>
                            @endif
                        </a>
                        @endforeach
                    </nav>
                </div>
            </header>

            <main id="konten-utama" tabindex="-1" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 focus:outline-none sm:px-6 lg:px-8 lg:py-8 animate-fade-in">
                @if(session('success'))
                <div role="status" class="mb-6 flex items-start gap-3 rounded-control border border-success-700/30 bg-success-100/60 p-4 text-sm text-success-800 no-print animate-slide-down">
                    <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0"/>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div role="alert" class="mb-6 flex items-start gap-3 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800 no-print animate-slide-down">
                    <x-icon name="exclamation" class="mt-0.5 size-5 shrink-0"/>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')
            </main>

            <footer class="py-5 text-center text-xs text-ink-600 no-print">
            &copy; {{ date('Y') }} SI SELAMAT &bull; Kantor Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.
        </footer>
        </div>

    </div>
</body>
</html>
