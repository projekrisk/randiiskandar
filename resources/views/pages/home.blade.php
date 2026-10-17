<x-layout>
    <main class="w-full flex-grow pt-32 pb-20">

        <!-- Section Profil Penulis & Media Sosial -->
        <section class="w-full max-w-5xl mx-auto px-5 sm:px-8 mb-14 sm:mb-18 font-sans">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 sm:gap-8 pb-12 sm:pb-16 border-b border-zinc-200 dark:border-zinc-800">
                <div class="relative shrink-0">
                    <img 
                        src="{{ $author?->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop' }}" 
                        alt="{{ $author?->name ?: 'Randi Iskandar' }}" 
                        class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full object-cover grayscale hover:grayscale-0 transition-all duration-500 border-2 border-zinc-200 dark:border-zinc-800 shadow-md"
                        loading="eager"
                        decoding="async"
                    >
                </div>
                <div class="flex-1 max-w-2xl">
                    <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Penulis & Desainer
                        </span>
                    </div>
                    <h1 class="font-sans font-extrabold text-3xl sm:text-4xl text-zinc-900 dark:text-white tracking-tight leading-tight mb-3">
                        {{ $author?->name ?: 'Randi Iskandar' }}
                    </h1>
                    <p class="text-zinc-600 dark:text-zinc-400 font-sans text-base sm:text-lg leading-relaxed mb-6 font-normal">
                        {{ $author?->bio ?: 'Desainer antarmuka dan penulis lepas. Mencatat eksplorasi tentang tipografi, arsitektur web modern, dan pengalaman pengguna dalam format esai panjang.' }}
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <a 
                            href="{{ \App\Models\Setting::get('social_github', 'https://github.com/randiiskandar') }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold font-sans bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:hover:bg-zinc-100 dark:text-zinc-900 transition-all duration-200 shadow-2xs hover:shadow-xs group cursor-pointer"
                            title="GitHub @randiiskandar"
                        >
                            <i class="ph ph-github-logo text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
                            <span>GitHub</span>
                            <i class="ph ph-arrow-up-right text-xs opacity-70 group-hover:opacity-100 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                        </a>
                        <a 
                            href="{{ \App\Models\Setting::get('social_instagram', 'https://instagram.com/randi.iskandar') }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold font-sans bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white transition-all duration-200 shadow-2xs hover:shadow-xs group cursor-pointer"
                            title="Instagram @randi.iskandar"
                        >
                            <i class="ph ph-instagram-logo text-base sm:text-lg text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform"></i>
                            <span>Instagram</span>
                            <i class="ph ph-arrow-up-right text-xs text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                        </a>
                        <a 
                            href="{{ route('contact') }}" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold font-sans text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                        >
                            <i class="ph ph-paper-plane-tilt text-base"></i>
                            <span>Kontak</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Post (Sorotan) -->
        @if($featuredPost)
            <section class="w-full max-w-5xl mx-auto px-5 sm:px-8 mb-20">
                <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-zinc-900 dark:text-white mb-8 border-b border-zinc-900 dark:border-white pb-2 inline-flex font-sans">
                    <i class="ph ph-star text-lg"></i> Sorotan
                </div>
                
                <article class="group relative overflow-hidden rounded-sm bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row">
                    @if($featuredPost->featured_image)
                        <a href="{{ route('post.show', $featuredPost->slug) }}" class="md:w-1/2 block overflow-hidden bg-zinc-200 dark:bg-zinc-800">
                            <img 
                                src="{{ $featuredPost->featured_image }}" 
                                alt="{{ $featuredPost->title }}" 
                                class="w-full h-64 md:h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                fetchpriority="high"
                                loading="eager"
                                decoding="async"
                            >
                        </a>
                    @endif
                    <div class="{{ $featuredPost->featured_image ? 'md:w-1/2' : 'w-full' }} p-6 sm:p-10 flex flex-col justify-center">
                        <div class="flex items-center gap-3 text-xs font-bold text-zinc-500 dark:text-zinc-400 mb-4 uppercase tracking-wider font-sans">
                            @if($featuredPost->category)
                                <a href="{{ route('category.show', $featuredPost->category->slug) }}" class="hover:underline">
                                    {{ $featuredPost->category->name }}
                                </a>
                                <span>•</span>
                            @endif
                            <span>{{ $featuredPost->formatted_published_at }}</span>
                        </div>
                        <h2 class="font-sans font-bold text-2xl sm:text-3xl text-zinc-900 dark:text-white leading-tight mb-4 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
                            <a href="{{ route('post.show', $featuredPost->slug) }}">
                                {{ $featuredPost->title }}
                            </a>
                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6 font-sans text-sm sm:text-base">
                            {{ $featuredPost->meta_description }}
                        </p>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('post.show', $featuredPost->slug) }}" class="inline-flex items-center gap-2 font-bold text-sm text-zinc-900 dark:text-white hover:text-zinc-500 transition-colors font-sans">
                                Baca selengkapnya <i class="ph ph-arrow-right"></i>
                            </a>
                            <button 
                                type="button"
                                class="bookmark-btn text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors"
                                data-id="{{ $featuredPost->id }}"
                                data-title="{{ $featuredPost->title }}"
                                data-slug="{{ $featuredPost->slug }}"
                                data-url="{{ route('post.show', $featuredPost->slug) }}"
                                data-date="{{ $featuredPost->formatted_published_at }}"
                                data-image="{{ $featuredPost->featured_image }}"
                                aria-label="Simpan Artikel"
                            >
                                <i class="ph ph-bookmark text-xl"></i>
                            </button>
                        </div>
                    </div>
                </article>
            </section>
        @endif

        <!-- Daftar Terbitan Terbaru -->
        <section class="w-full max-w-3xl mx-auto px-5 sm:px-8">
            <div class="flex items-center justify-between mb-8 border-b border-zinc-200 dark:border-zinc-800 pb-4">
                <h2 class="font-sans font-bold text-2xl text-zinc-900 dark:text-white">Terbitan Terbaru</h2>
                <a href="{{ route('archive.index') }}" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors flex items-center gap-1 font-sans">
                    Lihat Semua <i class="ph ph-caret-right"></i>
                </a>
            </div>

            <div class="flex flex-col gap-10 sm:gap-12" id="posts-container">
                @forelse($latestPosts as $post)
                    <x-post-card :post="$post" />
                @empty
                    <p class="text-zinc-500 py-12 text-center font-sans">Belum ada tulisan yang diterbitkan.</p>
                @endforelse
            </div>

            <!-- Muat Lainnya -->
            @if($latestPosts->hasMorePages())
                <div id="load-more-container" class="mt-12 text-center">
                    <button 
                        type="button" 
                        id="btn-load-more" 
                        data-next-url="{{ $latestPosts->nextPageUrl() }}"
                        class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full text-xs sm:text-sm font-sans font-bold bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:hover:bg-zinc-100 dark:text-zinc-900 shadow-xs hover:shadow-md transition-all duration-200 active:scale-95 cursor-pointer group"
                    >
                        <span>Muat Lainnya</span>
                        <i class="ph ph-arrow-down text-base transition-transform group-hover:translate-y-0.5"></i>
                    </button>
                </div>
            @endif

            <noscript>
                @if($latestPosts->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $latestPosts->links() }}
                    </div>
                @endif
            </noscript>
        </section>

    </main>
</x-layout>
