{{-- KOMPONEN 1: <x-alert type="success|error">Pesan</x-alert>
     Algoritma:
       1. Ambil nilai type (default: success).
       2. Pilih warna sesuai type; jika type tidak dikenal, pakai warna success.
       3. Tampilkan isi pesan ($slot) di dalam kotak berwarna. --}}
@props(['type' => 'success'])

@php
  $styles = [
    'success' => 'border-emerald-700 bg-emerald-50 text-emerald-900',
    'error'   => 'border-red-700 bg-red-50 text-red-900',
  ];
@endphp

<div role="alert" {{ $attributes->class(['mb-6 border-l-4 px-4 py-3 text-sm font-medium', $styles[$type] ?? $styles['success']]) }}>
  {{ $slot }}
</div>
