<x-layout 
    :title="'Kategori: ' . $category->name . ' - Randi Iskandar Workspace'"
    :description="$category->description ?: 'Arsip esai dan tulisan seputar ' . $category->name"
>
    <main class="w-full max-w-3xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        
        <!-- Header Kategori -->
        <header class="mb-14 border-b border-zinc-200 dark:border-zinc-800 pb-10">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-3 font-sans">
                <i class="ph ph-folder-open text-base"></i> Arsip Kategori
            </div>
            <h1 class="font-sans font-bold text-3xl sm:text-4xl md:text-5xl text-zinc-900 dark:text-white mb-4">
                {{ $category->name }}
            </h1>
            @if($category->description)
                <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl leading-relaxed font-sans">
                    {{ $category->description }}
                </p>
            @endif
        </header>

        <!-- Daftar Artikel Kategori -->
        <div class="flex flex-col gap-10 sm:gap-12" id="posts-container">
            @forelse($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <div class="text-center py-16">
                    <p class="text-zinc-500 font-sans text-base mb-6">Belum ada tulisan di dalam kategori ini.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-sm hover:opacity-90 transition-opacity">
                        Kembali ke Beranda
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Muat Lainnya -->
        @if($posts->hasMorePages())
            <div id="load-more-container" class="mt-12 text-center">
                <button 
                    type="button" 
                    id="btn-load-more" 
                    data-next-url="{{ $posts->nextPageUrl() }}"
                    class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full text-xs sm:text-sm font-sans font-bold bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:hover:bg-zinc-100 dark:text-zinc-900 shadow-xs hover:shadow-md transition-all duration-200 active:scale-95 cursor-pointer group"
                >
                    <span>Muat Lainnya</span>
                    <i class="ph ph-arrow-down text-base transition-transform group-hover:translate-y-0.5"></i>
                </button>
            </div>
        @endif

        <noscript>
            @if($posts->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @endif
        </noscript>

    </main>
</x-layout>
