<x-layout 
    title="Arsip Lengkap Tulisan - Randi Iskandar Workspace"
    description="Indeks kronologis lengkap dari seluruh esai, studi kasus, dan catatan yang telah diterbitkan."
>
    <main class="w-full max-w-5xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        
        <header class="mb-12 border-b border-zinc-200 dark:border-zinc-800 pb-10">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-3 font-sans">
                <i class="ph ph-archive text-base"></i> Direktori Publikasi
            </div>
            <h1 class="font-sans font-bold text-3xl sm:text-4xl md:text-5xl text-zinc-900 dark:text-white mb-4">
                Arsip Tulisan
            </h1>
            <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl leading-relaxed font-sans">
                Eksplorasi kronologis pemikiran, desain antarmuka, tipografi digital, dan arsitektur web modern.
            </p>

            <!-- Filter Kategori Cepat -->
            <div class="flex flex-wrap gap-2 mt-8">
                <a href="{{ route('archive.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold font-sans bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 shadow-xs">
                    Semua ({{ $allPostsCount ?? $posts->total() }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium font-sans bg-zinc-100 dark:bg-zinc-800/80 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                        {{ $cat->name }} ({{ $cat->posts_count }})
                    </a>
                @endforeach
            </div>

        </header>

        <div class="flex flex-col gap-10 sm:gap-12" id="posts-container">
            @forelse($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <p class="text-zinc-500 font-sans text-center py-12">Belum ada tulisan di arsip.</p>
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
