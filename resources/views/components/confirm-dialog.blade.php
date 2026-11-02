{{-- Dialog konfirmasi berbasis <dialog> native: focus trap, tombol Esc, dan latar inert otomatis.
     Menggantikan window.confirm() dan modal div buatan sendiri.
     Pakai (taruh di mana saja di halaman, buka dengan onclick="openDialog('setujui-12')"):
     <x-confirm-dialog id="setujui-{{ $s->id }}" :action="route('validasi.approve', $s)"
         title="Setujui dan tanda tangani surat ini?" confirm-label="Setujui & tanda tangani" variant="success">
         <dl>...ringkasan surat...</dl>
     </x-confirm-dialog>
     Untuk aksi dengan isian (mis. alasan tolak), taruh <x-field as="textarea" name="catatan" required /> di slot. --}}
@props(['id', 'title', 'action', 'method' => 'POST', 'confirmLabel' => 'Ya, lanjutkan', 'cancelLabel' => 'Batal', 'variant' => 'primary'])
<dialog id="{{ $id }}" aria-labelledby="{{ $id }}-title"
        onclick="if (event.target === this) this.close()"
        class="m-auto w-[min(28rem,92vw)] rounded-card bg-surface p-0 text-ink-900 shadow-lg backdrop:bg-ink-900/50">
    <form method="POST" action="{{ $action }}" class="p-6">
        @csrf
        @if(strtoupper($method) !== 'POST') @method($method) @endif
        <h2 id="{{ $id }}-title" class="text-title-sm font-semibold">{{ $title }}</h2>
        <div class="mt-3 space-y-3 text-sm text-ink-700">{{ $slot }}</div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" class="btn btn-secondary" onclick="this.closest('dialog').close()">{{ $cancelLabel }}</button>
            <button type="submit" class="btn btn-{{ $variant }}">{{ $confirmLabel }}</button>
        </div>
    </form>
</dialog>
