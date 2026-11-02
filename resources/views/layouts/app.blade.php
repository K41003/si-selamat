<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SI SELAMAT' }} - Sistem Informasi Surat Desa Tanjung Selamat</title>

    {{-- Aset dibangun oleh Vite (Tailwind v4 + font self-host). Jalankan `npm run build`. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col">
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

    <div class="min-h-full flex flex-col">
        <header class="sticky top-0 z-40 border-b border-line bg-surface no-print">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-control">
                        <span class="flex size-9 items-center justify-center rounded-control bg-brand-700 text-white">
                            <x-icon name="building" class="size-5"/>
                        </span>
                        <span class="leading-tight">
                            <span class="block text-base font-bold tracking-tight text-ink-900">SI SELAMAT</span>
                            <span class="block text-xs text-ink-600">Desa Tanjung Selamat &bull; Kec. Sunggal</span>
                        </span>
                    </a>

                    @auth
                    <div class="flex items-center gap-4">
                        <div class="hidden text-right leading-tight md:block">
                            <p class="text-sm font-semibold text-ink-900">{{ auth()->user()->active_staff_name ?: auth()->user()->name }}</p>
                            <p class="text-xs text-ink-600">{{ auth()->user()->role === 'kades' ? 'Kepala Desa Tanjung Selamat' : 'Staf Pelayanan Administrasi' }}</p>
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
                </div>

                <nav aria-label="Menu utama" class="-mb-px flex gap-1 overflow-x-auto">
                    @foreach($menu as [$label, $url, $active])
                    <a href="{{ $url }}" @if($active) aria-current="page" @endif
                       class="inline-flex h-11 shrink-0 items-center gap-2 border-b-2 px-3 text-sm transition-colors {{ $active ? 'border-brand-700 font-semibold text-brand-700' : 'border-transparent font-medium text-ink-600 hover:border-line hover:text-ink-900' }}">
                        {{ $label }}
                        @if($label === 'Validasi Kades' && $pendingCount > 0)
                        <span class="rounded-full bg-warning-100 px-1.5 text-xs font-semibold text-warning-800">{{ $pendingCount }}<span class="sr-only"> surat menunggu</span></span>
                        @endif
                    </a>
                    @endforeach
                </nav>
            </div>
        </header>

        <main id="konten-utama" tabindex="-1" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 focus:outline-none sm:px-6 lg:px-8 lg:py-8">
            @if(session('success'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-control border border-success-700/30 bg-success-100/60 p-4 text-sm text-success-800 no-print">
                <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0"/>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div role="alert" class="mb-6 flex items-start gap-3 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800 no-print">
                <x-icon name="exclamation" class="mt-0.5 size-5 shrink-0"/>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div role="alert" class="mb-6 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800 no-print">
                <p class="mb-1 font-semibold">Terdapat kesalahan pada isian form:</p>
                <ul class="list-disc space-y-0.5 pl-5">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </main>

        <footer class="mt-auto border-t border-line py-5 text-center text-xs text-ink-600 no-print">
            <div class="mx-auto max-w-7xl px-4">
                &copy; {{ date('Y') }} SI SELAMAT &bull; Kantor Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.
            </div>
        </footer>
    </div>
</body>
</html>
