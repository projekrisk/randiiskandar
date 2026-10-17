@props(['post'])

@if($post)
    <div class="my-8 p-4 sm:p-5 border border-zinc-200 dark:border-zinc-800 rounded-xs bg-zinc-50/70 dark:bg-zinc-900/50 flex items-center justify-between gap-4 not-prose hover:border-zinc-400 dark:hover:border-zinc-600 transition-all duration-200 group">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                <span class="inline-block w-2 h-2 rounded-full bg-zinc-900 dark:bg-white shrink-0"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 font-sans">BACA JUGA</span>
                @php
                    $bjCats = $post->categories->isNotEmpty() ? $post->categories : ($post->category ? collect([$post->category]) : collect());
                @endphp
                @if($bjCats->isNotEmpty())
                    <span class="text-zinc-300 dark:text-zinc-700">&bull;</span>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 font-sans truncate">{{ $bjCats->pluck('name')->join(', ') }}</span>
                @endif
            </div>
            <a href="{{ route('post.show', $post->slug) }}" class="font-sans font-bold text-base sm:text-lg text-zinc-900 dark:text-white group-hover:underline leading-snug line-clamp-2 block">
                {{ $post->title }}
            </a>
        </div>

        @if($post->featured_image)
            <a href="{{ route('post.show', $post->slug) }}" class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-xs overflow-hidden bg-zinc-100 dark:bg-zinc-800 block border border-zinc-200 dark:border-zinc-700/60" aria-label="{{ $post->title }}">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
            </a>
        @else
            <a href="{{ route('post.show', $post->slug) }}" class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-full bg-zinc-200/60 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white group-hover:translate-x-1 transition-all" aria-label="Baca Artikel">
                <i class="ph ph-arrow-right text-lg"></i>
            </a>
        @endif
    </div>
@endif
