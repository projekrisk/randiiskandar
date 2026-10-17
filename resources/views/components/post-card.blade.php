@props(['post'])

<article class="article-group group flex flex-row gap-4 sm:gap-8 justify-between border-b border-zinc-100 dark:border-zinc-800/50 pb-10" data-post-id="{{ $post->id }}">
    <div class="flex-1 flex flex-col justify-center min-w-0">
        
        <!-- Meta Kategori & Tanggal -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-2 uppercase tracking-wider font-sans">
            @if($post->category)
                <a href="{{ route('category.show', $post->category->slug) }}" class="text-zinc-900 dark:text-zinc-200 font-bold hover:underline">
                    {{ $post->category->name }}
                </a>
                <span>•</span>
            @endif
            <span>{{ $post->formatted_published_at }}</span>
        </div>

        <!-- Judul Tulisan -->
        <h3 class="font-sans font-bold text-xl md:text-2xl text-zinc-900 dark:text-white leading-snug mb-2 group-hover:text-zinc-600 dark:group-hover:text-zinc-400 transition-colors">
            <a href="{{ route('post.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h3>

        <!-- Ringkasan (Excerpt) -->
        <p class="hidden sm:block text-zinc-700 dark:text-zinc-300 text-sm md:text-base leading-relaxed mb-4 line-clamp-2 font-sans">
            {{ $post->meta_description }}
        </p>

        <!-- Informasi Tambahan & Bookmark -->
        <div class="flex items-center gap-3 sm:gap-4 text-xs sm:text-sm text-zinc-500 mt-2 sm:mt-auto font-sans">
            <span>{{ $post->reading_time }} mnt baca</span>
            
            @if(isset($post->comments_count))
                <span class="flex items-center gap-1">
                    <i class="ph ph-chat-circle"></i> {{ $post->comments_count }}
                </span>
            @endif

            <button 
                type="button"
                class="bookmark-btn hover:text-zinc-900 dark:hover:text-white transition-colors ml-auto sm:ml-0" 
                data-id="{{ $post->id }}"
                data-title="{{ $post->title }}"
                data-slug="{{ $post->slug }}"
                data-url="{{ route('post.show', $post->slug) }}"
                data-date="{{ $post->formatted_published_at }}"
                data-image="{{ $post->featured_image }}"
                aria-label="Simpan Artikel"
            >
                <i class="ph ph-bookmark text-lg"></i>
            </button>
        </div>
    </div>

    @if($post->featured_image)
        <a href="{{ route('post.show', $post->slug) }}" class="image-hover-wrapper block w-24 h-24 sm:w-48 sm:h-32 flex-shrink-0 bg-zinc-100 dark:bg-zinc-900 self-start sm:self-center rounded-sm">
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover rounded-sm" loading="lazy" decoding="async">
        </a>
    @endif
</article>
