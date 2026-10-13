{{-- KOMPONEN 2: <x-card :title="..." :href="..." :image="...">isi</x-card>
     Algoritma:
       1. Jika ada gambar, tampilkan di bagian atas kartu.
       2. Tampilkan judul. Jika ada href, judul menjadi link.
       3. Tampilkan isi tambahan dari pemanggil ($slot). --}}
@props(['title', 'href' => null, 'image' => null])

<article {{ $attributes->class(['group flex flex-col overflow-hidden border border-line bg-white transition hover:-translate-y-0.5 hover:shadow-[4px_4px_0_0_#1c1917]']) }}>
  @if ($image)
    <img src="{{ $image }}" alt="{{ $title }}" class="h-40 w-full object-cover">
  @endif

  <div class="flex flex-1 flex-col p-5">
    <h2 class="mb-2 font-display text-xl font-semibold leading-snug">
      @if ($href)
        <a href="{{ $href }}" class="hover:text-rust">{{ $title }}</a>
      @else
        {{ $title }}
      @endif
    </h2>

    {{ $slot }}
  </div>
</article>
