<x-layout :post="$post" type="article">
    
    
    <x-table-of-contents :toc="$post->table_of_contents" />

    <main class="w-full flex-grow pt-32 pb-20">
        
        
        <header class="mb-10 text-center w-full max-w-3xl mx-auto px-5 sm:px-8">
            
            @php
                $detailCats = $post->categories->isNotEmpty() ? $post->categories : ($post->category ? collect([$post->category]) : collect());
            @endphp
            @if($detailCats->isNotEmpty())
                <div class="mb-4 flex items-center justify-center flex-wrap gap-2">
                    @foreach($detailCats as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="text-xs md:text-sm font-bold uppercase tracking-widest text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors font-sans">
                            {{ $cat->name }}
                        </a>
                        @if(!$loop->last)
                            <span class="text-zinc-300 dark:text-zinc-700 text-xs">•</span>
                        @endif
                    @endforeach
                </div>
            @endif

            <h1 class="font-sans font-extrabold text-3xl sm:text-4xl md:text-5xl text-zinc-900 dark:text-white leading-[1.14] mb-6 tracking-tight text-center">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="font-sans text-base md:text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed mb-8 max-w-2xl mx-auto font-normal">
                    {{ $post->excerpt }}
                </p>
            @endif

            
            <div class="flex items-center justify-between gap-3 sm:gap-4 py-4 border-y border-zinc-200/90 dark:border-zinc-800/90 my-8 font-sans">
                
                <div class="flex items-center gap-2.5 sm:gap-3 text-left min-w-0 flex-1">
                    <img 
                        src="{{ $post->author?->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150' }}" 
                        alt="{{ $post->author?->name }}" 
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover grayscale shrink-0 border border-zinc-200 dark:border-zinc-800"
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="leading-tight min-w-0">
                        <span class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-white block truncate">
                            {{ $post->author?->name ?: 'Randi Iskandar' }}
                        </span>
                        <span class="text-[11px] sm:text-xs text-zinc-400 block truncate">
                            {{ $post->formatted_published_at }} • {{ $post->reading_time }} mnt baca
                        </span>
                    </div>
                </div>

                
                <div class="flex items-center gap-2.5 sm:gap-4 md:gap-5 shrink-0 text-zinc-600 dark:text-zinc-400">
                    
                    <button type="button" id="listen-article-btn" class="inline-flex items-center justify-center gap-1.5 w-8 h-8 sm:w-auto sm:h-auto sm:px-3 sm:py-1.5 rounded-full bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800/90 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 text-xs font-semibold transition-all cursor-pointer shadow-2xs group shrink-0" title="Dengarkan pembacaan artikel full" aria-label="Dengarkan pembacaan artikel">
                        <i class="ph ph-speaker-high text-base text-zinc-900 dark:text-white group-hover:scale-110 transition-transform" id="listen-btn-icon"></i>
                        <span id="listen-btn-text" class="hidden sm:inline">Dengarkan</span>
                    </button>

                    
                    <button type="button" class="clap-trigger flex items-center gap-1 sm:gap-1.5 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer group" title="Beri Apresiasi">
                        <i class="ph ph-hands-clapping text-lg sm:text-xl group-hover:scale-125 transition-transform text-zinc-700 dark:text-zinc-300"></i>
                        <span class="clap-count font-semibold text-xs sm:text-sm text-zinc-900 dark:text-white">{{ (int) $post->claps_count }}</span>
                    </button>

                    
                    <a href="#komentar" class="flex items-center gap-1 sm:gap-1.5 hover:text-zinc-900 dark:hover:text-white transition-colors group cursor-pointer" title="Lihat Komentar">
                        <i class="ph ph-chat-circle-dots text-lg sm:text-xl group-hover:scale-110 transition-transform text-zinc-700 dark:text-zinc-300"></i>
                        <span class="font-semibold text-xs sm:text-sm text-zinc-900 dark:text-white">{{ $comments->count() }}</span>
                    </a>
                </div>
            </div>

        </header>

        
        <div id="audio-player-bar" class="hidden floating-audio-bar bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border border-zinc-200/90 dark:border-zinc-800/90 rounded-2xl p-2.5 sm:p-3.5 font-sans transition-all duration-300">
            <div class="flex items-center justify-between gap-2.5 sm:gap-3">
                
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button type="button" id="tts-play-pause-btn" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 flex items-center justify-center shrink-0 hover:scale-105 active:scale-95 transition-all shadow-md cursor-pointer" title="Putar / Jeda Narasi">
                        <i class="ph-fill ph-pause text-sm sm:text-base" id="tts-play-icon"></i>
                    </button>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white truncate" id="tts-status">
                                Membacakan artikel...
                            </span>
                            
                            <div class="audio-equalizer text-zinc-900 dark:text-white shrink-0" id="tts-equalizer" title="Indikator suara aktif">
                                <span class="eq-bar eq-1"></span>
                                <span class="eq-bar eq-2"></span>
                                <span class="eq-bar eq-3"></span>
                            </div>
                        </div>
                        <span class="block text-[10px] sm:text-[11px] text-zinc-500 dark:text-zinc-400 truncate mt-0.5" id="tts-progress-text">
                            Narasi Suara Artikel Lengkap
                        </span>
                    </div>
                </div>

                
                <div class="flex items-center gap-1 sm:gap-1.5 shrink-0">
                    
                    <button type="button" id="tts-prev-btn" class="p-1 sm:p-1.5 text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Bagian Sebelumnya">
                        <i class="ph ph-skip-back text-xs sm:text-sm"></i>
                    </button>
                    <button type="button" id="tts-next-btn" class="p-1 sm:p-1.5 text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Bagian Berikutnya">
                        <i class="ph ph-skip-forward text-xs sm:text-sm"></i>
                    </button>

                    
                    <button type="button" id="tts-speed-btn" class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors cursor-pointer border border-zinc-200 dark:border-zinc-700" title="Ubah Kecepatan Suara">
                        <span id="tts-speed-text">1.0x</span>
                    </button>

                    
                    <button type="button" id="tts-stop-btn" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors cursor-pointer" title="Tutup Pemutar Audio">
                        <i class="ph ph-x text-sm sm:text-base"></i>
                    </button>
                </div>
            </div>

            
            <div class="w-full bg-zinc-200/80 dark:bg-zinc-800/80 h-1 rounded-full mt-2 sm:mt-2.5 overflow-hidden">
                <div id="tts-progress-bar" class="bg-zinc-900 dark:bg-white h-full w-0 transition-all duration-300"></div>
            </div>
        </div>

        
        @if($post->featured_image)
            <figure class="mb-14 w-full {{ $post->is_featured_image_wide ? 'max-w-5xl' : 'max-w-3xl' }} mx-auto px-0 sm:px-8 transition-all duration-300">
                <div class="rounded-none sm:rounded-xs overflow-hidden bg-zinc-100 dark:bg-zinc-900 group relative cursor-zoom-in">
                    <img 
                        src="{{ $post->featured_image }}" 
                        alt="{{ $post->title }}" 
                        class="w-full h-auto object-cover {{ $post->is_featured_image_wide ? 'max-h-[640px]' : 'max-h-[580px]' }} transition-transform duration-300 group-hover:scale-[1.01]"
                        data-image-preview
                        data-caption="{{ $post->featured_image_caption ?: $post->title }}"
                        fetchpriority="high"
                        loading="eager"
                        decoding="async"
                    >
                    <div class="absolute bottom-3 right-3 bg-zinc-900/80 dark:bg-zinc-100/90 text-white dark:text-zinc-900 text-xs px-2.5 py-1 rounded-full backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5 pointer-events-none shadow-sm font-sans font-medium">
                        <i class="ph ph-magnifying-glass-plus text-sm"></i>
                        <span>Perbesar</span>
                    </div>
                </div>
                @if(!empty($post->featured_image_caption))
                    <figcaption class="text-[13px] md:text-sm text-zinc-400 text-center mt-3 px-5 sm:px-0 italic font-serif">
                        {{ $post->featured_image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        
        <div class="w-full max-w-3xl mx-auto px-5 sm:px-8">
            
            <article class="prose prose-zinc dark:prose-invert w-full font-serif text-zinc-800 dark:text-zinc-200 selection:bg-zinc-900 selection:text-white dark:selection:bg-white dark:selection:text-zinc-900 article-editorial">
                {!! $post->body_with_injected_ad !!}
            </article>

        
        <x-ad-bottom />

        @if(!empty($post->tags_list))
            
            <div class="w-full flex flex-wrap items-center gap-2 my-6 pt-2 font-sans">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mr-1 flex items-center gap-1.5">
                    <i class="ph ph-tag text-sm"></i> Tag:
                </span>
                @foreach($post->tags_list as $tag)
                    <a href="{{ route('search', ['q' => $tag]) }}" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors">
                        #{{ $tag }}
                    </a>
                @endforeach
            </div>
        @endif

        
        <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 py-6 my-10 border-y border-zinc-200 dark:border-zinc-800 font-sans text-xs md:text-sm">
            <div class="flex items-center gap-5 w-full sm:w-auto justify-between sm:justify-start">
                <button type="button" class="clap-trigger flex items-center gap-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors group cursor-pointer" title="Beri Apresiasi">
                    <i class="ph ph-hands-clapping text-xl group-hover:scale-125 transition-transform text-zinc-700 dark:text-zinc-300"></i>
                    <span class="clap-count font-bold text-sm md:text-base text-zinc-900 dark:text-white">{{ (int) $post->claps_count }}</span>
                    <span class="text-xs md:text-sm text-zinc-500">Apresiasi</span>
                </button>
                <button 
                    type="button" 
                    class="bookmark-btn text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
                    data-id="{{ $post->id }}"
                    data-title="{{ $post->title }}"
                    data-slug="{{ $post->slug }}"
                    data-url="{{ route('post.show', $post->slug) }}"
                    data-date="{{ $post->formatted_published_at }}"
                    data-image="{{ $post->featured_image }}"
                    title="Simpan Artikel"
                >
                    <i class="ph ph-bookmark text-xl"></i>
                </button>
            </div>
            
            
            <div class="flex items-center gap-3 text-zinc-400 w-full sm:w-auto justify-center sm:justify-end">
                <span class="text-xs text-zinc-400 font-medium hidden sm:inline mr-1">Bagikan:</span>
                
                
                <a 
                    href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . route('post.show', $post->slug)) }}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="p-2 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-full transition-colors" 
                    title="Bagikan ke WhatsApp"
                >
                    <i class="ph ph-whatsapp-logo text-xl"></i>
                </a>

                
                <a 
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('post.show', $post->slug)) }}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="p-2 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-full transition-colors" 
                    title="Bagikan ke Facebook"
                >
                    <i class="ph ph-facebook-logo text-xl"></i>
                </a>

                
                <button 
                    type="button" 
                    id="share-instagram-btn" 
                    class="p-2 hover:text-pink-600 hover:bg-pink-50 dark:hover:bg-pink-950/40 rounded-full transition-colors cursor-pointer" 
                    title="Bagikan ke Instagram"
                >
                    <i class="ph ph-instagram-logo text-xl"></i>
                </button>

                
                <a 
                    href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('post.show', $post->slug)) }}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="p-2 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-full transition-colors" 
                    title="Bagikan ke X"
                >
                    <i class="ph ph-x-logo text-xl"></i>
                </a>

                
                <button 
                    type="button" 
                    id="copy-link-btn" 
                    class="p-2 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-full transition-colors cursor-pointer" 
                    title="Salin Tautan Artikel"
                >
                    <i class="ph ph-link text-xl"></i>
                </button>
            </div>
        </div>

        <div class="w-full p-6 sm:p-8 rounded-sm border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/40 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5 sm:gap-6 my-10 font-sans">
            <img 
                src="{{ $post->author?->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200' }}" 
                alt="{{ $post->author?->name }}" 
                class="w-16 h-16 sm:w-16 sm:h-16 rounded-full object-cover grayscale shrink-0 border border-zinc-200 dark:border-zinc-800"
                loading="lazy"
                decoding="async"
            >
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white mb-1.5">
                    {{ $post->author?->name ?: 'Randi Iskandar' }}
                </h4>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed mb-3">
                    {{ $post->author?->bio ?: 'Pengembang web dan penulis lepas. Berbagi opini seputar teknologi, tutorial, dan catatan digital.' }}
                </p>
                <a href="{{ route('about') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-zinc-900 dark:text-white hover:underline group">
                    <span>Tentang Penulis</span>
                    <i class="ph ph-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>

        
        @if($relatedPosts->isNotEmpty())
            <section class="w-full my-14 font-sans">
                <h3 class="font-bold text-lg md:text-xl text-zinc-900 dark:text-white mb-4">
                    Artikel Terkait
                </h3>
                
                <div class="flex flex-col divide-y divide-zinc-200 dark:divide-zinc-800 border-y border-zinc-200 dark:border-zinc-800">
                    @foreach($relatedPosts as $rel)
                        <article class="py-5 flex items-center justify-between gap-6 group">
                            <div class="flex-1 min-w-0">
                                <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-zinc-400 mb-1 block">
                                    {{ $rel->categories->isNotEmpty() ? $rel->categories->pluck('name')->join(', ') : ($rel->category?->name ?: 'Artikel') }}
                                </span>
                                <h4 class="font-bold text-base md:text-lg text-zinc-900 dark:text-white group-hover:underline leading-snug mb-1.5">
                                    <a href="{{ route('post.show', $rel->slug) }}">
                                        {{ $rel->title }}
                                    </a>
                                </h4>
                                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                    {{ $rel->meta_description }}
                                </p>
                            </div>
                            @if($rel->featured_image)
                                <a href="{{ route('post.show', $rel->slug) }}" class="w-24 h-16 sm:w-28 sm:h-20 shrink-0 rounded-xs overflow-hidden bg-zinc-100 dark:bg-zinc-800 block">
                                    <img 
                                        src="{{ $rel->featured_image }}" 
                                        alt="{{ $rel->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        
        <section id="komentar" class="w-full my-14 font-sans scroll-mt-24">
            
            <div class="flex items-center justify-between mb-8 pb-3 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="font-bold text-lg sm:text-xl text-zinc-900 dark:text-white flex items-center gap-2.5">
                    <span>Komentar</span>
                    <span class="text-xs sm:text-sm font-semibold px-2.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                        {{ $comments->count() }}
                    </span>
                </h3>
            </div>

            
            @if(session('success'))
                <div class="p-4 mb-6 rounded-xs bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs sm:text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-check-circle text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900">&times;</button>
                </div>
            @endif

            @if($errors->has('comment'))
                <div class="p-4 mb-6 rounded-xs bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 text-xs sm:text-sm flex items-center gap-2">
                    <i class="ph ph-warning-circle text-base"></i>
                    <span>{{ $errors->first('comment') }}</span>
                </div>
            @endif

            
            <div id="main-comment-form-slot" class="mb-10">
                
                
                <div id="comment-form-wrapper" class="bg-zinc-50/80 dark:bg-zinc-900/50 border border-zinc-200/90 dark:border-zinc-800/90 rounded-sm p-5 sm:p-6 transition-all duration-300">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h4 id="form-heading" class="font-bold text-sm sm:text-base text-zinc-900 dark:text-white">
                            Tinggalkan Tanggapan
                        </h4>
                        <span id="form-badge" class="text-[11px] sm:text-xs text-zinc-400">
                            Diskusi Editorial
                        </span>
                    </div>

                    
                    <div id="reply-indicator" class="hidden mb-4 p-3 bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xs flex items-center justify-between text-xs sm:text-sm">
                        <div class="flex items-center gap-2 text-zinc-700 dark:text-zinc-300">
                            <i class="ph ph-arrow-bend-down-right text-base text-zinc-400"></i>
                            <span>Membalas komentar dari <strong id="reply-target-name" class="text-zinc-900 dark:text-white font-semibold"></strong></span>
                        </div>
                        <button type="button" id="cancel-reply-btn" class="text-xs font-semibold text-red-500 hover:text-red-600 dark:hover:text-red-400 flex items-center gap-1 cursor-pointer">
                            <i class="ph ph-x"></i>
                            <span>Batal Balas</span>
                        </button>
                    </div>

                    
                    <form action="{{ route('comment.store', $post->slug) }}" method="POST" id="comment-form">
                        @csrf
                        
                        
                        <div class="hidden" aria-hidden="true">
                            <label for="hp_company_name">Leave this empty</label>
                            <input type="text" name="hp_company_name" id="hp_company_name" tabindex="-1" autocomplete="off">
                        </div>

                        
                        <input type="hidden" name="parent_id" id="reply_parent_id" value="">

                        
                        @guest
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-2.5">
                                <div>
                                    <label for="name" class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="name"
                                        required 
                                        placeholder="Nama Anda" 
                                        value="{{ old('name') }}" 
                                        class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xs px-3.5 py-2.5 text-xs sm:text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white transition-all"
                                    >
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                                        Alamat Email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email"
                                        required 
                                        placeholder="email@domain.com" 
                                        value="{{ old('email') }}" 
                                        class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xs px-3.5 py-2.5 text-xs sm:text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white transition-all"
                                    >
                                </div>
                            </div>
                            <div class="flex items-center justify-between mb-3 text-xs text-zinc-500 dark:text-zinc-400">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" id="save_identity" class="rounded border-zinc-300 dark:border-zinc-700 text-zinc-900 focus:ring-zinc-900 dark:focus:ring-white" checked>
                                    <span>Ingat nama dan email di peramban ini</span>
                                </label>
                                <span class="hidden sm:inline text-[11px] text-zinc-400">Avatar otomatis via Gravatar</span>
                            </div>
                        @else
                            <div class="mb-3.5 flex items-center gap-2 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                <span>Berkomentar sebagai <strong class="text-zinc-900 dark:text-white font-semibold">{{ auth()->user()->name }}</strong></span>
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            </div>
                        @endguest

                        
                        <div class="mb-4">
                            <label for="comment" class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                                Tanggapan Anda <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="3" 
                                required 
                                placeholder="Tuliskan pemikiran atau tanggapan Anda secara santun dan konstruktif..." 
                                class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xs p-3.5 text-xs sm:text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white transition-all leading-relaxed"
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="mt-2 p-2.5 rounded-xs bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/60 text-xs text-red-600 dark:text-red-400 flex items-center gap-1.5 font-sans font-medium">
                                    <i class="ph ph-warning-circle text-base shrink-0"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-1">
                            <span class="text-[11px] sm:text-xs text-zinc-400 text-center sm:text-left">
                                Alamat email Anda tidak akan dipublikasikan ke publik.
                            </span>

                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-6 py-2.5 bg-zinc-900 hover:bg-black text-white dark:bg-white dark:hover:bg-zinc-100 dark:text-zinc-900 font-bold text-xs sm:text-sm uppercase tracking-wider rounded-xs transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                            >
                                <span>Kirim Komentar</span>
                                <i class="ph ph-paper-plane-tilt text-sm sm:text-base"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            
            <div class="space-y-6">
                @forelse($comments as $comment)
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800/60" id="comment-{{ $comment->id }}">
                        
                        <div class="flex items-start gap-3.5 mb-2">
                            
                            <div class="shrink-0">
                                @if($comment->is_author_reply)
                                    <img 
                                        src="{{ $comment->author?->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150' }}" 
                                        alt="{{ $comment->name }}" 
                                        class="w-8 h-8 rounded-full object-cover grayscale border border-zinc-200 dark:border-zinc-700 shadow-2xs"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                @else
                                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200/90 dark:border-zinc-700/80 flex items-center justify-center text-xs font-bold text-zinc-700 dark:text-zinc-200 select-none">
                                        {{ strtoupper(substr($comment->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-xs md:text-sm text-zinc-900 dark:text-white">{{ $comment->name }}</span>
                                    @if($comment->is_author_reply)
                                        <span class="inline-flex items-center gap-1 text-[10px] md:text-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                            <i class="ph ph-pen-nib text-xs"></i>
                                            <span>Penulis</span>
                                        </span>
                                    @endif
                                    <span class="text-xs md:text-sm text-zinc-400">• {{ $comment->formatted_date }}</span>
                                </div>
                                <p class="text-sm md:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed mt-1">
                                    {{ $comment->comment }}
                                </p>
                                <button 
                                    type="button" 
                                    class="reply-trigger text-xs md:text-sm font-bold text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors mt-1.5 inline-flex items-center gap-1 cursor-pointer"
                                    data-id="{{ $comment->id }}"
                                    data-name="{{ $comment->name }}"
                                >
                                    <i class="ph ph-arrow-bend-down-right"></i>
                                    <span>Balas</span>
                                </button>
                            </div>
                        </div>

                        
                        @if($comment->replies->isNotEmpty())
                            <div class="ml-10 sm:ml-12 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/40 space-y-4">
                                @foreach($comment->replies as $reply)
                                    <div class="flex items-start gap-3" id="reply-{{ $reply->id }}">
                                        
                                        <div class="shrink-0">
                                            @if($reply->is_author_reply)
                                                <img 
                                                    src="{{ $reply->author?->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150' }}" 
                                                    alt="{{ $reply->name }}" 
                                                    class="w-7 h-7 rounded-full object-cover grayscale border border-zinc-200 dark:border-zinc-700 shadow-2xs"
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                            @else
                                                <div class="w-7 h-7 rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200/90 dark:border-zinc-700/80 flex items-center justify-center text-[11px] font-bold text-zinc-700 dark:text-zinc-200 select-none">
                                                    {{ strtoupper(substr($reply->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-xs md:text-sm text-zinc-900 dark:text-white">{{ $reply->name }}</span>
                                                @if($reply->is_author_reply)
                                                    <span class="inline-flex items-center gap-1 text-[10px] md:text-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">
                                                        <i class="ph ph-pen-nib text-xs"></i>
                                                        <span>Penulis</span>
                                                    </span>
                                                @endif
                                                <span class="text-xs md:text-sm text-zinc-400">• {{ $reply->formatted_date }}</span>
                                            </div>
                                            <p class="text-sm md:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed mt-1">
                                                {{ $reply->comment }}
                                            </p>
                                            <button 
                                                type="button" 
                                                class="reply-trigger text-xs md:text-sm font-bold text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors mt-1.5 inline-flex items-center gap-1 cursor-pointer"
                                                data-id="{{ $comment->id }}"
                                                data-name="{{ $reply->name }}"
                                            >
                                                <i class="ph ph-arrow-bend-down-right"></i>
                                                <span>Balas</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        
                        <div id="reply-slot-{{ $comment->id }}" class="mt-4 empty:hidden transition-all duration-300"></div>

                    </div>
                @empty
                    <p class="text-xs sm:text-sm text-zinc-400 italic py-4">Belum ada tanggapan. Jadilah yang pertama memberikan tanggapan pada tulisan ini.</p>
                @endforelse
            </div>

        </section>

        </div> 

    </main>

    
    <div 
        id="image-preview-modal" 
        class="fixed inset-0 z-50 bg-black/90 dark:bg-zinc-950/95 backdrop-blur-md flex flex-col items-center justify-between p-4 sm:p-6 md:p-8 opacity-0 pointer-events-none transition-all duration-300 ease-out"
        role="dialog"
        aria-modal="true"
        aria-label="Pratinjau Gambar"
    >
        
        <div class="w-full max-w-6xl flex items-center justify-between text-white shrink-0 py-2 z-10 font-sans">
            <div class="flex items-center gap-3 min-w-0">
                <span class="text-xs uppercase font-bold tracking-widest text-zinc-400 flex items-center gap-1.5">
                    <i class="ph ph-image text-sm"></i> Pratinjau Gambar
                </span>
                <span id="preview-image-dim" class="text-xs text-zinc-500 hidden sm:inline font-mono"></span>
            </div>

            <div class="flex items-center gap-2">
                
                <a 
                    id="preview-open-external" 
                    href="#" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="p-2 text-zinc-300 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer"
                    title="Buka gambar di tab baru"
                    aria-label="Buka gambar di tab baru"
                >
                    <i class="ph ph-arrow-square-out text-xl"></i>
                </a>
                
                <button 
                    type="button" 
                    id="close-image-preview" 
                    class="p-2 text-zinc-300 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer"
                    title="Tutup Pratinjau (Esc)"
                    aria-label="Tutup"
                >
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
        </div>

        
        <div id="preview-stage" class="flex-grow flex items-center justify-center w-full max-w-6xl min-h-0 py-2 sm:py-4 overflow-hidden relative cursor-zoom-out">
            <img 
                id="preview-target-img" 
                src="" 
                alt="" 
                class="max-w-full max-h-full object-contain rounded-xs shadow-2xl transition-transform duration-300 transform scale-95 select-none cursor-default"
            >
        </div>

        
        <div class="w-full max-w-3xl shrink-0 pt-2 pb-1 text-center z-10 font-sans">
            <p id="preview-image-caption" class="text-xs sm:text-sm text-zinc-300 font-serif italic max-w-2xl mx-auto leading-relaxed empty:hidden"></p>
            <span class="text-[11px] text-zinc-500 block mt-1">
                Klik area luar gambar atau tekan <kbd class="px-1.5 py-0.5 rounded bg-zinc-800 text-zinc-400 border border-zinc-700 text-[10px] font-mono">Esc</kbd> untuk menutup
            </span>
        </div>
    </div>

    
    <div id="editorial-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs sm:text-sm font-medium shadow-xl opacity-0 pointer-events-none transition-all duration-300 flex items-center gap-2">
        <i class="ph ph-check-circle text-emerald-400 dark:text-emerald-600 text-base"></i>
        <span id="editorial-toast-msg">Notifikasi</span>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('editorial-toast');
            const toastMsg = document.getElementById('editorial-toast-msg');
            let toastTimer = null;

            function showToast(message) {
                if (!toast || !toastMsg) return;
                toastMsg.textContent = message;
                toast.classList.remove('opacity-0', 'pointer-events-none');
                toast.classList.add('opacity-100');
                if (toastTimer) clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toast.classList.add('opacity-0', 'pointer-events-none');
                    toast.classList.remove('opacity-100');
                }, 2800);
            }

            const postSlug = '{{ $post->slug }}';
            const clapTriggers = document.querySelectorAll('.clap-trigger');
            const clapCounts = document.querySelectorAll('.clap-count');

            let hasClapped = localStorage.getItem('editorial_clapped_' + postSlug) === 'true';

            function updateClapButtonActiveState() {
                if (hasClapped) {
                    clapTriggers.forEach(b => {
                        b.querySelector('i')?.classList.add('text-amber-500');
                        b.setAttribute('title', 'Anda sudah memberikan apresiasi');
                    });
                }
            }
            updateClapButtonActiveState();

            let isSendingClap = false;
            async function syncRealtimeClaps() {
                if (isSendingClap) return;
                try {
                    const response = await fetch('{{ route('post.claps', $post->slug) }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        if (data && typeof data.claps_count === 'number') {
                            clapCounts.forEach(el => {
                                el.textContent = data.claps_count;
                            });
                        }
                    }
                } catch (e) {
                }
            }

            setInterval(syncRealtimeClaps, 8000);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) syncRealtimeClaps();
            });

            clapTriggers.forEach(btn => {
                btn.addEventListener('click', async () => {
                    if (hasClapped) {
                        showToast('Anda sudah memberikan apresiasi untuk tulisan ini.');
                        return;
                    }

                    hasClapped = true;
                    localStorage.setItem('editorial_clapped_' + postSlug, 'true');
                    updateClapButtonActiveState();

                    clapCounts.forEach(el => {
                        let current = parseInt(el.textContent) || 0;
                        el.textContent = current + 1;
                    });
                    clapTriggers.forEach(b => {
                        b.classList.add('scale-110');
                        setTimeout(() => b.classList.remove('scale-110'), 150);
                    });
                    showToast('Terima kasih atas apresiasi Anda!');

                    isSendingClap = true;
                    try {
                        const response = await fetch('{{ route('post.clap', $post->slug) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data && typeof data.claps_count === 'number') {
                            clapCounts.forEach(el => {
                                el.textContent = data.claps_count;
                            });
                        }
                    } catch (error) {
                        console.error('Gagal menyimpan apresiasi realtime:', error);
                    } finally {
                        isSendingClap = false;
                    }
                });
            });

            const listenBtn = document.getElementById('listen-article-btn');
            const listenBtnIcon = document.getElementById('listen-btn-icon');
            const listenBtnText = document.getElementById('listen-btn-text');
            const audioBar = document.getElementById('audio-player-bar');
            const ttsPlayPauseBtn = document.getElementById('tts-play-pause-btn');
            const ttsPlayIcon = document.getElementById('tts-play-icon');
            const ttsStatus = document.getElementById('tts-status');
            const ttsProgressText = document.getElementById('tts-progress-text');
            const ttsSpeedBtn = document.getElementById('tts-speed-btn');
            const ttsSpeedText = document.getElementById('tts-speed-text');
            const ttsStopBtn = document.getElementById('tts-stop-btn');
            const ttsProgressBar = document.getElementById('tts-progress-bar');
            const ttsEqualizer = document.getElementById('tts-equalizer');
            const ttsPrevBtn = document.getElementById('tts-prev-btn');
            const ttsNextBtn = document.getElementById('tts-next-btn');

            if ('speechSynthesis' in window) {
                let speechUtterance = null;
                let textQueue = [];
                let currentQueueIndex = 0;
                let isPlaying = false;
                let isPaused = false;
                let speechRate = 1.0;
                const speeds = [1.0, 1.25, 1.5, 2.0];
                let speedIndex = 0;
                let indonesianVoice = null;

                function loadVoices() {
                    const voices = window.speechSynthesis.getVoices();
                    indonesianVoice = voices.find(v => v.lang.startsWith('id') || v.lang.includes('ID')) || null;
                }
                loadVoices();
                if (window.speechSynthesis.onvoiceschanged !== undefined) {
                    window.speechSynthesis.onvoiceschanged = loadVoices;
                }

                function getArticleCleanText() {
                    const articleElem = document.querySelector('article.article-editorial');
                    if (!articleElem) return [];
                    const clone = articleElem.cloneNode(true);
                    
                    clone.querySelectorAll('figure, figcaption, pre, code, table, .not-prose, ins, .adsbygoogle, button, script').forEach(el => el.remove());

                    const chunks = [];
                    clone.querySelectorAll('h1, h2, h3, h4, h5, h6, p, blockquote, li').forEach(el => {
                        const tag = el.tagName.toLowerCase();

                        if (tag === 'p' && el.closest('li')) {
                            return;
                        }

                        let text = '';
                        if (tag === 'li') {
                            const liClone = el.cloneNode(true);
                            liClone.querySelectorAll('ul, ol').forEach(nested => nested.remove());
                            text = (liClone.innerText ? liClone.innerText.trim() : liClone.textContent.trim());

                            const parentOl = el.closest('ol');
                            if (parentOl && text.length > 0 && !/^\d+[\.\)]\s*/.test(text)) {
                                const directLis = Array.from(parentOl.children).filter(child => child.tagName && child.tagName.toLowerCase() === 'li');
                                const itemIndex = directLis.indexOf(el);
                                if (itemIndex !== -1) {
                                    text = `Poin ${itemIndex + 1}: ${text}`;
                                }
                            }
                        } else {
                            text = (el.innerText ? el.innerText.trim() : el.textContent.trim());
                        }

                        text = text.replace(/\s+/g, ' ').trim();

                        if (text.length > 1) {
                            if (!/[.!?:;,]$/.test(text)) {
                                text += '.';
                            }
                            chunks.push(text);
                        }
                    });

                    return chunks;
                }

                function speakChunk(index) {
                    if (index >= textQueue.length) {
                        isPlaying = false;
                        isPaused = false;
                        if (ttsPlayIcon) ttsPlayIcon.className = 'ph-fill ph-play text-sm sm:text-base pl-0.5';
                        if (ttsStatus) ttsStatus.textContent = 'Selesai membaca artikel';
                        if (ttsProgressBar) ttsProgressBar.style.width = '100%';
                        if (ttsProgressText) ttsProgressText.textContent = 'Selesai didengarkan';
                        ttsEqualizer?.classList.remove('playing');
                        if (listenBtnIcon) listenBtnIcon.className = 'ph ph-speaker-high text-base text-zinc-900 dark:text-white';
                        if (listenBtnText) listenBtnText.textContent = 'Dengarkan';
                        listenBtn?.setAttribute('title', 'Dengarkan pembacaan artikel full');
                        showToast('Selesai membacakan seluruh isi artikel.');
                        return;
                    }

                    currentQueueIndex = index;
                    const chunk = textQueue[index];
                    const progressPercent = Math.round(((index + 1) / textQueue.length) * 100);
                    if (ttsProgressBar) ttsProgressBar.style.width = progressPercent + '%';
                    if (ttsProgressText) ttsProgressText.textContent = `Bagian ${index + 1} dari ${textQueue.length} (${progressPercent}%)`;

                    window.speechSynthesis.cancel();
                    speechUtterance = new SpeechSynthesisUtterance(chunk);
                    speechUtterance.lang = 'id-ID';
                    if (indonesianVoice) speechUtterance.voice = indonesianVoice;
                    speechUtterance.rate = speechRate;

                    speechUtterance.onend = () => {
                        if (isPlaying && !isPaused) {
                            speakChunk(index + 1);
                        }
                    };

                    speechUtterance.onerror = (e) => {
                        if (e.error !== 'interrupted' && e.error !== 'canceled') {
                            console.warn('TTS error:', e);
                            speakChunk(index + 1);
                        }
                    };

                    window.speechSynthesis.speak(speechUtterance);
                }

                function startAudio() {
                    textQueue = getArticleCleanText();
                    if (textQueue.length === 0) {
                        showToast('Tidak ada teks artikel yang dapat dibacakan.');
                        return;
                    }

                    audioBar?.classList.remove('hidden');
                    document.body.classList.add('has-floating-audio');
                    isPlaying = true;
                    isPaused = false;
                    if (ttsPlayIcon) ttsPlayIcon.className = 'ph-fill ph-pause text-sm sm:text-base';
                    if (ttsStatus) ttsStatus.textContent = 'Membacakan artikel...';
                    ttsEqualizer?.classList.add('playing');
                    if (listenBtnIcon) listenBtnIcon.className = 'ph ph-speaker-simple-high text-base text-amber-500 animate-pulse';
                    if (listenBtnText) listenBtnText.textContent = 'Mendengarkan';
                    listenBtn?.setAttribute('title', 'Sedang mendengarkan artikel (klik untuk sembunyikan/tampilkan pemutar)');
                    speakChunk(0);
                }

                function pauseAudio() {
                    if (isPlaying && !isPaused) {
                        window.speechSynthesis.pause();
                        isPaused = true;
                        if (ttsPlayIcon) ttsPlayIcon.className = 'ph-fill ph-play text-sm sm:text-base pl-0.5';
                        if (ttsStatus) ttsStatus.textContent = 'Narasi dijeda';
                        ttsEqualizer?.classList.remove('playing');
                        if (listenBtnIcon) listenBtnIcon.className = 'ph ph-speaker-high text-base text-zinc-500';
                        if (listenBtnText) listenBtnText.textContent = 'Dijeda';
                        listenBtn?.setAttribute('title', 'Narasi dijeda (klik pemutar untuk melanjutkan)');
                    }
                }

                function resumeAudio() {
                    if (isPlaying && isPaused) {
                        window.speechSynthesis.resume();
                        isPaused = false;
                        if (ttsPlayIcon) ttsPlayIcon.className = 'ph-fill ph-pause text-sm sm:text-base';
                        if (ttsStatus) ttsStatus.textContent = 'Membacakan artikel...';
                        ttsEqualizer?.classList.add('playing');
                        if (listenBtnIcon) listenBtnIcon.className = 'ph ph-speaker-simple-high text-base text-amber-500 animate-pulse';
                        if (listenBtnText) listenBtnText.textContent = 'Mendengarkan';
                        listenBtn?.setAttribute('title', 'Sedang mendengarkan artikel (klik untuk sembunyikan/tampilkan pemutar)');
                    }
                }

                function stopAudio() {
                    window.speechSynthesis.cancel();
                    isPlaying = false;
                    isPaused = false;
                    audioBar?.classList.add('hidden');
                    document.body.classList.remove('has-floating-audio');
                    ttsEqualizer?.classList.remove('playing');
                    if (ttsPlayIcon) ttsPlayIcon.className = 'ph-fill ph-pause text-sm sm:text-base';
                    if (listenBtnIcon) listenBtnIcon.className = 'ph ph-speaker-high text-base text-zinc-900 dark:text-white group-hover:scale-110 transition-transform';
                    if (listenBtnText) listenBtnText.textContent = 'Dengarkan';
                    listenBtn?.setAttribute('title', 'Dengarkan pembacaan artikel full');
                    if (ttsProgressBar) ttsProgressBar.style.width = '0%';
                }

                listenBtn?.addEventListener('click', () => {
                    if (!isPlaying) {
                        startAudio();
                    } else {
                        const isHidden = audioBar?.classList.toggle('hidden');
                        if (isHidden) {
                            document.body.classList.remove('has-floating-audio');
                        } else {
                            document.body.classList.add('has-floating-audio');
                        }
                    }
                });

                ttsPlayPauseBtn?.addEventListener('click', () => {
                    if (!isPlaying) {
                        startAudio();
                    } else if (isPaused) {
                        resumeAudio();
                    } else {
                        pauseAudio();
                    }
                });

                ttsPrevBtn?.addEventListener('click', () => {
                    if (isPlaying && currentQueueIndex > 0) {
                        speakChunk(currentQueueIndex - 1);
                    }
                });

                ttsNextBtn?.addEventListener('click', () => {
                    if (isPlaying && currentQueueIndex + 1 < textQueue.length) {
                        speakChunk(currentQueueIndex + 1);
                    }
                });

                ttsSpeedBtn?.addEventListener('click', () => {
                    speedIndex = (speedIndex + 1) % speeds.length;
                    speechRate = speeds[speedIndex];
                    if (ttsSpeedText) ttsSpeedText.textContent = speechRate.toFixed(1) + 'x';
                    if (isPlaying && !isPaused) {
                        speakChunk(currentQueueIndex);
                    }
                });

                ttsStopBtn?.addEventListener('click', stopAudio);
            } else {
                listenBtn?.classList.add('hidden');
            }

            document.querySelectorAll('.article-editorial table').forEach(table => {
                if (!table.parentElement.classList.contains('article-table-wrapper') && !table.parentElement.classList.contains('overflow-x-auto')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'article-table-wrapper not-prose';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });

            document.querySelectorAll('.article-editorial pre').forEach(pre => {
                if (pre.parentElement.classList.contains('code-block-wrapper')) return;

                const codeEl = pre.querySelector('code') || pre;
                let lang = 'CODE';
                const langMatch = (codeEl.className || '').match(/language-([a-z0-9_-]+)/i);
                if (langMatch) {
                    lang = langMatch[1].toUpperCase();
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'code-block-wrapper not-prose my-8';

                const header = document.createElement('div');
                header.className = 'code-block-header';
                header.innerHTML = `
                    <div class="code-mac-dots">
                        <span class="code-mac-dot red"></span>
                        <span class="code-mac-dot yellow"></span>
                        <span class="code-mac-dot green"></span>
                        <span class="ml-2 font-mono text-[10px] uppercase font-bold tracking-wider text-zinc-400">${lang}</span>
                    </div>
                    <button type="button" class="code-copy-btn" title="Salin kode ke clipboard">
                        <i class="ph ph-copy"></i>
                        <span>Salin</span>
                    </button>
                `;

                const copyBtn = header.querySelector('.code-copy-btn');
                copyBtn?.addEventListener('click', () => {
                    const codeText = (codeEl ? codeEl.innerText : pre.innerText).replace(/\n$/, '');
                    navigator.clipboard.writeText(codeText).then(() => {
                        copyBtn.innerHTML = '<i class="ph ph-check text-emerald-400"></i><span>Tersalin!</span>';
                        setTimeout(() => {
                            copyBtn.innerHTML = '<i class="ph ph-copy"></i><span>Salin</span>';
                        }, 2000);
                    });
                });

                pre.parentNode.insertBefore(wrapper, pre);
                wrapper.appendChild(header);
                wrapper.appendChild(pre);
            });

            if (window.Prism) {
                window.Prism.highlightAll();
            }

            const copyBtn = document.getElementById('copy-link-btn');
            copyBtn?.addEventListener('click', () => {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Tautan artikel berhasil disalin!');
                    const icon = copyBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'ph ph-check text-emerald-500 text-xl';
                        setTimeout(() => {
                            icon.className = 'ph ph-link text-xl';
                        }, 2000);
                    }
                });
            });

            const instagramBtn = document.getElementById('share-instagram-btn');
            instagramBtn?.addEventListener('click', async () => {
                const shareData = {
                    title: document.title,
                    text: 'Baca tulisan menarik di Randi Iskandar Workspace: ' + document.title,
                    url: window.location.href,
                };

                if (navigator.share && /mobile|android|iphone|ipad/i.test(navigator.userAgent)) {
                    try {
                        await navigator.share(shareData);
                        return;
                    } catch (err) {
                    }
                }

                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Tautan disalin! Buka Instagram untuk membagikan.');
                    const icon = instagramBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'ph ph-check text-pink-500 text-xl';
                        setTimeout(() => {
                            icon.className = 'ph ph-instagram-logo text-xl';
                        }, 2000);
                    }
                });
            });

            const replyTriggers = document.querySelectorAll('.reply-trigger');
            const commentFormWrapper = document.getElementById('comment-form-wrapper');
            const mainSlot = document.getElementById('main-comment-form-slot');
            const parentIdInput = document.getElementById('reply_parent_id');
            const replyIndicator = document.getElementById('reply-indicator');
            const replyTargetName = document.getElementById('reply-target-name');
            const cancelReplyBtn = document.getElementById('cancel-reply-btn');
            const formHeading = document.getElementById('form-heading');
            const commentInput = document.getElementById('comment');

            replyTriggers.forEach(btn => {
                btn.addEventListener('click', () => {
                    const commentId = btn.dataset.id;
                    const authorName = btn.dataset.name;
                    const targetSlot = document.getElementById('reply-slot-' + commentId);

                    if (targetSlot && commentFormWrapper) {
                        targetSlot.appendChild(commentFormWrapper);

                        if (parentIdInput) parentIdInput.value = commentId;
                        if (replyTargetName) replyTargetName.textContent = authorName;
                        if (formHeading) formHeading.textContent = 'Balas Komentar';
                        if (replyIndicator) replyIndicator.classList.remove('hidden');

                        commentFormWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        setTimeout(() => commentInput?.focus(), 250);
                    }
                });
            });

            cancelReplyBtn?.addEventListener('click', () => {
                if (mainSlot && commentFormWrapper) {
                    mainSlot.appendChild(commentFormWrapper);

                    if (parentIdInput) parentIdInput.value = '';
                    if (replyIndicator) replyIndicator.classList.add('hidden');
                    if (formHeading) formHeading.textContent = 'Tinggalkan Tanggapan';

                    commentFormWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });

            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const saveIdentityCheckbox = document.getElementById('save_identity');
            const commentForm = document.getElementById('comment-form');

            if (nameInput && emailInput) {
                const savedName = localStorage.getItem('editorial_commenter_name');
                const savedEmail = localStorage.getItem('editorial_commenter_email');
                if (savedName && !nameInput.value) nameInput.value = savedName;
                if (savedEmail && !emailInput.value) emailInput.value = savedEmail;

                commentForm?.addEventListener('submit', () => {
                    if (saveIdentityCheckbox?.checked) {
                        localStorage.setItem('editorial_commenter_name', nameInput.value.trim());
                        localStorage.setItem('editorial_commenter_email', emailInput.value.trim());
                    } else {
                        localStorage.removeItem('editorial_commenter_name');
                        localStorage.removeItem('editorial_commenter_email');
                    }
                });
            }

            const previewModal = document.getElementById('image-preview-modal');
            const previewImg = document.getElementById('preview-target-img');
            const previewCaption = document.getElementById('preview-image-caption');
            const previewDim = document.getElementById('preview-image-dim');
            const previewExternal = document.getElementById('preview-open-external');
            const closePreviewBtn = document.getElementById('close-image-preview');
            const previewStage = document.getElementById('preview-stage');

            function openImagePreview(src, alt = '', caption = '') {
                if (!previewModal || !previewImg || !src) return;

                previewImg.src = src;
                previewImg.alt = alt || 'Pratinjau Gambar';
                if (previewExternal) previewExternal.href = src;
                if (previewCaption) {
                    previewCaption.textContent = caption;
                }
                if (previewDim) {
                    previewDim.textContent = '';
                }

                previewImg.onload = () => {
                    if (previewDim && previewImg.naturalWidth && previewImg.naturalHeight) {
                        previewDim.textContent = `• ${previewImg.naturalWidth} × ${previewImg.naturalHeight} px`;
                    }
                };

                previewModal.classList.remove('opacity-0', 'pointer-events-none');
                previewModal.classList.add('opacity-100', 'pointer-events-auto');
                previewImg.classList.remove('scale-95');
                previewImg.classList.add('scale-100');
                document.body.classList.add('overflow-hidden');
            }

            function closeImagePreview() {
                if (!previewModal || !previewImg) return;

                previewModal.classList.add('opacity-0', 'pointer-events-none');
                previewModal.classList.remove('opacity-100', 'pointer-events-auto');
                previewImg.classList.add('scale-95');
                previewImg.classList.remove('scale-100');
                document.body.classList.remove('overflow-hidden');
            }

            const featuredImg = document.querySelector('[data-image-preview]');
            if (featuredImg) {
                featuredImg.closest('.cursor-zoom-in')?.addEventListener('click', () => {
                    openImagePreview(
                        featuredImg.src, 
                        featuredImg.alt, 
                        featuredImg.dataset.caption || featuredImg.alt
                    );
                });
            }

            const articleImages = document.querySelectorAll('article.article-editorial img');
            articleImages.forEach(img => {
                img.classList.add('cursor-zoom-in');
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    const figure = img.closest('figure');
                    const figcaption = figure ? figure.querySelector('figcaption')?.textContent?.trim() : '';
                    const caption = img.dataset.caption || figcaption || img.alt || '';
                    openImagePreview(img.src, img.alt, caption);
                });
            });

            closePreviewBtn?.addEventListener('click', closeImagePreview);

            previewStage?.addEventListener('click', (e) => {
                if (e.target === previewStage) {
                    closeImagePreview();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && previewModal && !previewModal.classList.contains('opacity-0')) {
                    closeImagePreview();
                }
            });

            document.querySelectorAll('article.article-editorial a').forEach(link => {
                const href = link.getAttribute('href');
                if (!href) return;

                if (!href.startsWith('#') && !href.startsWith('javascript:')) {
                    if (!link.hasAttribute('target') && (href.startsWith('http://') || href.startsWith('https://') || href.startsWith('//'))) {
                        link.setAttribute('target', '_blank');
                        link.setAttribute('rel', 'noopener noreferrer');
                    }
                }
            });
        });
    </script>
</x-layout>
