<x-layout 
    :title="$page->title . ' - Randi Iskandar Workspace'"
    :description="$page->meta_description ?: 'Halaman ' . $page->title . ' pada blog editorial Randi Iskandar Workspace.'"
    :image="$page->featured_image"
>
    <main class="w-full max-w-3xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        
        <header class="mb-12 border-b border-zinc-200 dark:border-zinc-800 pb-10">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-4 font-sans">
                <i class="ph ph-file-text text-base"></i> Informasi & Kebijakan
            </div>
            <h1 class="font-sans font-bold text-4xl md:text-5xl text-zinc-900 dark:text-white mb-4 leading-tight">
                {{ $page->title }}
            </h1>
            <div class="flex items-center gap-3 text-xs text-zinc-500 font-sans">
                <span>Terakhir diperbarui: {{ $page->updated_at?->translatedFormat('d F Y') ?: date('d F Y') }}</span>
                <span>•</span>
                <span>{{ $page->reading_time }} mnt baca</span>
            </div>
        </header>

        @if($page->featured_image)
            <figure class="mb-10 overflow-hidden rounded-xs border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800/50">
                <img 
                    src="{{ $page->featured_image }}" 
                    alt="{{ $page->title }}" 
                    class="w-full h-auto max-h-[500px] object-cover"
                    loading="lazy"
                    decoding="async"
                >
                @if($page->featured_image_caption)
                    <figcaption class="p-3 text-center text-xs text-zinc-500 dark:text-zinc-400 font-sans italic border-t border-zinc-200 dark:border-zinc-800">
                        {{ $page->featured_image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <article class="prose prose-lg dark:prose-invert prose-zinc max-w-none font-sans text-zinc-700 dark:text-zinc-300 leading-relaxed">
            {!! $page->parsed_body ?: $page->body !!}
        </article>

    </main>
</x-layout>
