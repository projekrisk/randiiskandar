<x-layout 
    :title="'Pencarian: ' . ($query ?: 'Artikel') . ' - Randi Iskandar Workspace'"
    description="Hasil pencarian tulisan dan esai di blog Randi Iskandar Workspace."
>
    <main class="w-full max-w-5xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        
        <header class="mb-12 border-b border-zinc-200 dark:border-zinc-800 pb-8">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-4 font-sans">
                <i class="ph ph-magnifying-glass text-base"></i> Hasil Penelusuran
            </div>
            <h1 class="font-sans font-bold text-3xl sm:text-4xl text-zinc-900 dark:text-white mb-2">
                @if($query)
                    Pencarian: "{{ $query }}"
                @else
                    Pencarian Artikel
                @endif
            </h1>
            <p class="text-sm text-zinc-500 font-sans">
                Ditemukan {{ $posts->count() }} tulisan yang relevan.
            </p>
        </header>

        <div class="flex flex-col gap-10 sm:gap-12">
            @forelse($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <div class="text-center py-16 font-sans">
                    <p class="text-zinc-500 text-base mb-6">Tidak ditemukan tulisan yang cocok dengan kata kunci tersebut.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-sm hover:opacity-90 transition-opacity">
                        Kembali ke Beranda
                    </a>
                </div>
            @endforelse
        </div>

    </main>
</x-layout>
