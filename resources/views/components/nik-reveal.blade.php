{{-- NIK tersamar secara default. NIK lengkap diminta ke server saat tombol ditekan (dicatat di log aktivitas),
     tampil sementara, lalu disembunyikan lagi otomatis setelah 30 detik.
     Pakai: <x-nik-reveal :warga="$warga" /> --}}
@props(['warga'])

@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('nikReveal', (url) => ({
        nik: null, loading: false, error: '', timer: null,
        async tampil() {
            this.loading = true; this.error = '';
            try {
                const meta = document.querySelector('meta[name="csrf-token"]');
                const res = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': meta ? meta.content : '',
                    },
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                this.nik = data.nik;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this.sembunyikan(), 30000);
            } catch (e) {
                this.error = 'Gagal menampilkan NIK. Muat ulang halaman lalu coba lagi.';
            }
            this.loading = false;
        },
        sembunyikan() { this.nik = null; clearTimeout(this.timer); },
    }));
});
</script>
@endonce

<div x-data="nikReveal(@js(route('warga.nik', $warga)))" class="flex flex-wrap items-center gap-x-3 gap-y-1">
    <span class="font-mono text-ink-900">
        <span x-show="!nik">{{ $warga->masked_nik }}</span>
        <span x-show="nik" x-cloak x-text="nik"></span>
    </span>
    <button type="button" class="btn btn-secondary btn-sm" x-show="!nik" @click="tampil()" :disabled="loading">
        <x-icon name="eye" class="size-4"/> <span x-text="loading ? 'Memuat…' : 'Tampilkan NIK'"></span>
    </button>
    <button type="button" class="btn btn-ghost btn-sm" x-show="nik" x-cloak @click="sembunyikan()">Sembunyikan</button>
    <span x-show="nik" x-cloak class="text-sm text-ink-600">Otomatis disembunyikan dalam 30 detik. Pembukaan NIK dicatat.</span>
    <span x-show="error" x-cloak x-text="error" class="text-sm font-medium text-danger-700"></span>
    <span class="sr-only" role="status" aria-live="polite" x-text="nik ? 'NIK ditampilkan' : ''"></span>
</div>
