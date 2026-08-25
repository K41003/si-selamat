<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SI SELAMAT' }} - Sistem Informasi Surat Elektronik & Layanan Administrasi Masyarakat</title>
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    @auth
        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 transform bg-slate-900 text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="flex h-16 items-center gap-2 border-b border-slate-800 px-6">
                    <span class="text-lg font-bold tracking-tight">SI SELAMAT</span>
                </div>

                <nav class="mt-4 space-y-1 px-3">
                    <a href="{{ route('dashboard') }}"
                       class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Dashboard
                    </a>

                    @auth
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('warga.index') }}"
                               class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('warga.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                Kelola Data Warga
                            </a>
                            {{-- Menu Permohonan Surat, Arsip, Cetak akan ditambahkan pada step berikutnya --}}
                        @endif

                        @if(auth()->user()->isKades())
                            {{-- Menu khusus Kades akan ditambahkan pada step modul berikutnya --}}
                        @endif
                    @endauth
                </nav>
            </aside>

            <div class="flex flex-1 flex-col lg:pl-0">
                {{-- Topbar --}}
                <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden">
                        <span class="text-slate-600">☰</span>
                    </button>

                    <div class="ml-auto flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-200">
                                Keluar
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-4 lg:p-6">
                    @if (session('success'))
                        <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

</body>
</html>
