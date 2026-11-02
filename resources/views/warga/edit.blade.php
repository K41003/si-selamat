@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <x-page-header title="Edit Data Warga" :subtitle="'Perbarui data identitas kependudukan ' . $warga->nama . '.'" :back="route('warga.index')" />

    <x-card>
        <form method="POST" action="{{ route('warga.update', $warga) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('warga._form')

            <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
                <x-button variant="secondary" href="{{ route('warga.index') }}">Batal</x-button>
                <x-button type="submit">Perbarui data warga</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
