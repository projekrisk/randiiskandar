@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'post' => null,
])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}">
    
    <!-- Dynamic SEO & Meta Tags -->
    <x-seo-meta 
        :title="$title ?: ($post ? $post->title . ' - Randi Iskandar' : 'Randi Iskandar Workspace - Blog Editorial')"
        :description="$description ?: ($post ? $post->meta_description : 'Blog personal bertema editorial minimalist seputar tipografi, pengalaman pengguna, dan arsitektur web modern.')"
        :image="$image ?: ($post?->featured_image ?: asset('og-image.jpg'))"
        :type="$type"
        :publishedTime="$post?->published_at?->toIso8601String()"
    />

    <!-- DNS Prefetch & Google Fonts: Inter untuk UI & Judul -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

    <!-- Syntax Highlighting (Prism.js Dark Theme) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">

    <!-- JSON-LD Structured Data Schema -->
    <x-schema-jsonld :post="$post" />

    <!-- Google AdSense Library Script (Hanya dimuat jika kode iklan aktif dikonfigurasi) -->
    @php
        $adInArticleCode = \App\Models\Setting::get('ad_in_article_code', '');
        $adBottomCode = \App\Models\Setting::get('ad_bottom_code', '');
        $hasRealAdSense = (str_contains($adInArticleCode, 'adsbygoogle') || str_contains($adBottomCode, 'adsbygoogle')) 
            && !str_contains($adInArticleCode, 'ca-pub-XXXXXXXXXXXXXXXX') 
            && !str_contains($adBottomCode, 'ca-pub-XXXXXXXXXXXXXXXX');
    @endphp
    @if($hasRealAdSense)
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" crossorigin="anonymous"></script>
    @endif

    <!-- Styles: Vite jika tersedia, atau Static CSS untuk Shared Hosting tanpa NPM/Build -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    <!-- Dark Mode Init Script (Mencegah FOUC - Flash of Unstyled Content) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-base-light text-zinc-900 dark:bg-base-dark dark:text-zinc-100 theme-transition font-sans min-h-screen flex flex-col relative selection:bg-zinc-900 selection:text-white dark:selection:bg-white dark:selection:text-zinc-900">

    <!-- Navbar Sticky -->
    <nav class="fixed top-0 w-full z-40 bg-base-light/90 dark:bg-base-dark/90 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800 transition-colors">
        <div class="max-w-5xl mx-auto px-5 sm:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-4">
                <button id="mobile-menu-trigger" class="sm:hidden text-zinc-900 dark:text-white hover:opacity-70 transition-opacity" aria-label="Buka Menu Navigasi">
                    <i class="ph ph-list text-2xl"></i>
                </button>
                <a href="{{ route('home') }}" class="font-sans italic font-bold text-xl tracking-wide text-zinc-900 dark:text-white">
                    Randi Iskandar.
                </a>
            </div>
            
            <div class="flex items-center gap-6 sm:gap-8 h-full">
                <!-- Navigasi Desktop -->
                <div class="hidden sm:flex items-center gap-6 font-sans text-sm font-medium">
                    @forelse($headerMenus as $hMenu)
                        <a 
                            href="{{ url($hMenu->url) }}" 
                            target="{{ $hMenu->target ?: '_self' }}"
                            class="{{ $hMenu->isCurrent() ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100' }} transition-colors"
                        >
                            {{ $hMenu->label }}
                        </a>
                    @empty
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100' }} transition-colors">Esai</a>
                        <a href="{{ route('archive.index') }}" class="{{ request()->routeIs('archive.*') || request()->routeIs('category.*') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100' }} transition-colors">Arsip</a>
                        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100' }} transition-colors">Tentang</a>
                    @endforelse
                </div>

                <div class="hidden sm:block w-px h-4 bg-zinc-300 dark:bg-zinc-700"></div>

                <!-- Ikon Aksi Header -->
                <div class="flex items-center gap-4 h-full">
                    <!-- Bookmark Dropdown Container (Anchor Desktop Tepat di Bawah Ikon) -->
                    <div class="relative h-full flex items-center" id="bookmark-container">
                        <button id="bookmark-header-trigger" class="relative text-zinc-900 dark:text-white hover:opacity-70 transition-opacity p-1 cursor-pointer" aria-label="Baca Nanti">
                            <i class="ph ph-bookmarks text-xl"></i>
                            <span id="bookmark-badge" class="hidden absolute -top-1 -right-1 w-4 h-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full text-[10px] font-bold flex items-center justify-center">0</span>
                        </button>

                        <!-- Modal / Dropdown Floating Bookmark (Muncul tepat di menu bookmark pada desktop, dan responsif penuh di mobile) -->
                        <div 
                            id="bookmark-popover" 
                            class="hidden fixed inset-x-3 top-18 sm:inset-x-auto sm:absolute sm:top-full sm:right-0 sm:mt-2.5 z-50 w-auto sm:w-88 md:w-96 max-h-[75vh] sm:max-h-[30rem] bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl font-sans transition-all duration-200 flex flex-col overflow-hidden"
                        >
                            <!-- Caret pointer ke arah tombol bookmark di desktop -->
                            <div class="hidden sm:block absolute -top-1.5 right-2.5 w-3 h-3 bg-zinc-50 dark:bg-zinc-900 border-t border-l border-zinc-200 dark:border-zinc-800 rotate-45 z-10"></div>

                            <div class="relative z-20 px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between bg-zinc-50 dark:bg-zinc-900 shrink-0">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-900 dark:text-white">
                                        <i class="ph ph-bookmarks text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-800 dark:text-zinc-200">Daftar Baca Nanti</h3>
                                        <span id="bookmark-drawer-count" class="text-[11px] text-zinc-500 dark:text-zinc-400 block font-sans">0 artikel tersimpan</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button id="clear-bookmarks-btn" class="text-[11px] font-semibold text-zinc-400 hover:text-red-500 transition-colors hidden px-2 py-1 rounded-sm hover:bg-red-50 dark:hover:bg-red-950/30 cursor-pointer" title="Hapus Semua">Hapus Semua</button>
                                    <button id="close-bookmark-popover" class="w-7 h-7 rounded-full flex items-center justify-center text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer" aria-label="Tutup Popover Bookmark">
                                        <i class="ph ph-x text-base"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="bookmark-list" class="relative z-20 p-4 overflow-y-auto flex-grow flex flex-col gap-2.5">
                                <div id="bookmark-empty-msg" class="text-center py-10 px-4">
                                    <div class="w-12 h-12 rounded-full bg-zinc-100 dark:bg-zinc-800/70 text-zinc-400 dark:text-zinc-500 flex items-center justify-center mx-auto mb-3">
                                        <i class="ph ph-bookmark-simple text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">Belum ada tulisan yang disimpan</p>
                                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Klik ikon bookmark di artikel untuk membaca nanti.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="search-trigger" class="text-zinc-900 dark:text-white hover:opacity-70 transition-opacity" aria-label="Pencarian">
                        <i class="ph ph-magnifying-glass text-xl"></i>
                    </button>
                    <button id="theme-toggle" class="text-zinc-900 dark:text-white hover:opacity-70 transition-opacity p-1" aria-label="Toggle Tema Gelap/Terang" title="Toggle Tema Gelap/Terang">
                        <i class="ph ph-moon text-xl dark:hidden block"></i>
                        <i class="ph ph-sun text-xl hidden dark:block"></i>
                    </button>
                </div>
            </div>

        </div>
    </nav>

    <!-- Overlay Global untuk Laci / Sidebar -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-zinc-900/60 dark:bg-black/80 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>
    
    <!-- Sidebar Mobile Kiri -->
    <aside id="mobile-sidebar" class="fixed top-0 left-0 h-full w-[85%] max-w-sm bg-base-light dark:bg-base-dark z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col border-r border-zinc-200 dark:border-zinc-800 shadow-2xl font-sans">
        <!-- Header Sidebar -->
        <div class="p-5 sm:p-6 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center bg-zinc-50/50 dark:bg-zinc-900/30">
            <div>
                <a href="{{ route('home') }}" class="font-sans italic font-bold text-xl tracking-wide text-zinc-900 dark:text-white block">
                    Randi Iskandar.
                </a>
                <span class="text-[11px] text-zinc-400 dark:text-zinc-500 font-sans block mt-0.5">
                    Ruang Esai & Desain Web
                </span>
            </div>
            <button id="close-sidebar" class="w-8 h-8 rounded-full flex items-center justify-center text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer" aria-label="Tutup Menu">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <!-- Body Navigasi Scrollable -->
        <div class="p-5 sm:p-6 flex-1 overflow-y-auto space-y-6 flex flex-col justify-between">
            <div class="space-y-6">
                <!-- Trigger Pencarian Cepat -->
                <button 
                    type="button"
                    id="mobile-search-trigger" 
                    class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-zinc-100 dark:bg-zinc-800/80 hover:bg-zinc-200/70 dark:hover:bg-zinc-700/70 text-zinc-500 dark:text-zinc-400 text-xs font-medium transition-all text-left cursor-pointer border border-transparent hover:border-zinc-200 dark:hover:border-zinc-700"
                >
                    <i class="ph ph-magnifying-glass text-base text-zinc-400"></i>
                    <span class="flex-1">Cari artikel atau topik...</span>
                    <span class="text-[10px] uppercase font-bold text-zinc-400 dark:text-zinc-500 font-mono px-1.5 py-0.5 rounded-xs bg-zinc-200/60 dark:bg-zinc-700/60">Cari</span>
                </button>

                <!-- Daftar Menu Navigasi Utama -->
                <nav class="space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 px-3 block mb-2 font-sans">
                        Navigasi Utama
                    </span>
                    @forelse($headerMenus as $hMenu)
                        @php
                            $isCurrent = $hMenu->isCurrent();
                            $icon = match(strtolower(trim($hMenu->label))) {
                                'esai', 'beranda' => 'ph-book-open',
                                'arsip' => 'ph-archive',
                                'tentang', 'tentang saya' => 'ph-user',
                                'kontak' => 'ph-paper-plane-tilt',
                                default => 'ph-arrow-right',
                            };
                        @endphp
                        <a 
                            href="{{ url($hMenu->url) }}" 
                            target="{{ $hMenu->target ?: '_self' }}"
                            class="{{ $isCurrent ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all group"
                        >
                            <span class="flex items-center gap-3">
                                <i class="ph {{ $icon }} text-lg {{ $isCurrent ? 'text-white dark:text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white' }}"></i>
                                <span>{{ $hMenu->label }}</span>
                            </span>
                            <i class="ph ph-caret-right text-xs {{ $isCurrent ? 'opacity-90' : 'opacity-40 group-hover:opacity-100' }} transition-opacity"></i>
                        </a>
                    @empty
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all group">
                            <span class="flex items-center gap-3">
                                <i class="ph ph-book-open text-lg"></i>
                                <span>Esai</span>
                            </span>
                            <i class="ph ph-caret-right text-xs"></i>
                        </a>
                        <a href="{{ route('archive.index') }}" class="{{ request()->routeIs('archive.*') || request()->routeIs('category.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all group">
                            <span class="flex items-center gap-3">
                                <i class="ph ph-archive text-lg"></i>
                                <span>Arsip</span>
                            </span>
                            <i class="ph ph-caret-right text-xs"></i>
                        </a>
                        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all group">
                            <span class="flex items-center gap-3">
                                <i class="ph ph-user text-lg"></i>
                                <span>Tentang</span>
                            </span>
                            <i class="ph ph-caret-right text-xs"></i>
                        </a>
                    @endforelse

                    @if(!$headerMenus->contains(fn($m) => strtolower(trim($m->label)) === 'kontak' || str_contains($m->url, 'kontak')))
                        <a 
                            href="{{ route('contact') }}" 
                            class="{{ request()->routeIs('contact') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all group"
                        >
                            <span class="flex items-center gap-3">
                                <i class="ph ph-paper-plane-tilt text-lg {{ request()->routeIs('contact') ? 'text-white dark:text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white' }}"></i>
                                <span>Kontak</span>
                            </span>
                            <i class="ph ph-caret-right text-xs opacity-40 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                    @endif
                </nav>

                <!-- Preferensi & Utilitas Membaca -->
                <div class="space-y-1.5 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 px-3 block mb-2 font-sans">
                        Fitur Membaca
                    </span>
                    
                    <button 
                        type="button"
                        id="mobile-bookmark-trigger" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium transition-all group cursor-pointer"
                    >
                        <span class="flex items-center gap-3">
                            <i class="ph ph-bookmarks text-lg text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white"></i>
                            <span>Daftar Baca Nanti</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-zinc-200/80 dark:bg-zinc-800 text-zinc-900 dark:text-white bookmark-count-text">0</span>
                    </button>

                    <button 
                        type="button"
                        id="mobile-theme-toggle" 
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 font-medium transition-all group cursor-pointer"
                    >
                        <span class="flex items-center gap-3">
                            <i class="ph ph-moon text-lg text-zinc-400 dark:hidden block"></i>
                            <i class="ph ph-sun text-lg text-zinc-400 hidden dark:block"></i>
                            <span>Mode Tampilan</span>
                        </span>
                        <span class="text-xs uppercase font-bold text-zinc-400 dark:text-zinc-500 font-sans">
                            <span class="dark:hidden">Terang</span>
                            <span class="hidden dark:inline">Gelap</span>
                        </span>
                    </button>
                </div>

                <!-- Media Sosial & Jejaring -->
                <div class="space-y-2 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 px-3 block mb-1 font-sans">
                        Media Sosial
                    </span>
                    <div class="grid grid-cols-2 gap-2">
                        <a 
                            href="{{ \App\Models\Setting::get('social_github', 'https://github.com/randiiskandar') }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200/80 dark:bg-zinc-800/80 dark:hover:bg-zinc-700 text-xs font-semibold text-zinc-800 dark:text-zinc-200 transition-colors"
                        >
                            <i class="ph ph-github-logo text-base"></i>
                            <span>GitHub</span>
                        </a>
                        <a 
                            href="{{ \App\Models\Setting::get('social_instagram', 'https://instagram.com/randi.iskandar') }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200/80 dark:bg-zinc-800/80 dark:hover:bg-zinc-700 text-xs font-semibold text-zinc-800 dark:text-zinc-200 transition-colors"
                        >
                            <i class="ph ph-instagram-logo text-base text-pink-600 dark:text-pink-400"></i>
                            <span>Instagram</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Sidebar -->
            <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800/80 text-[11px] text-zinc-400 dark:text-zinc-500 font-sans">
                <p class="font-semibold text-zinc-600 dark:text-zinc-400">Randi Iskandar</p>
                <p class="mt-0.5">© 2026 Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </aside>

    <!-- Search Overlay Fullscreen -->
    <div id="search-overlay" class="fixed inset-0 z-50 bg-base-light/95 dark:bg-base-dark/95 backdrop-blur-md flex flex-col items-center justify-start p-6 pt-24 sm:pt-32">
        <button id="close-search" class="absolute top-6 right-6 sm:top-8 sm:right-8 p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white" aria-label="Tutup Pencarian">
            <i class="ph ph-x text-3xl"></i>
        </button>
        <div class="w-full max-w-2xl">
            <div class="relative flex items-center border-b-2 border-zinc-900 dark:border-white pb-4">
                <i class="ph ph-magnifying-glass text-3xl text-zinc-400 mr-4"></i>
                <input 
                    type="text" 
                    id="search-input" 
                    placeholder="Ketik kata kunci untuk mencari..." 
                    class="w-full bg-transparent text-2xl sm:text-3xl font-sans font-bold focus:outline-none text-zinc-900 dark:text-white placeholder-zinc-400"
                    autocomplete="off"
                >
            </div>
            <div class="flex items-center justify-between mt-3 text-xs text-zinc-400 uppercase tracking-widest font-sans">
                <span>Tekan ESC untuk menutup</span>
                <span id="search-status"></span>
            </div>

            <!-- Hasil Pencarian Cepat AJAX -->
            <div id="search-results" class="mt-8 flex flex-col gap-4 max-h-[60vh] overflow-y-auto"></div>
        </div>
    </div>

    <!-- Konten Utama Halaman -->
    {{ $slot }}

    <!-- Back to Top Button -->
    <button 
        id="back-to-top" 
        class="fixed bottom-8 right-6 sm:bottom-10 sm:right-8 z-30 w-11 h-11 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:scale-105"
        aria-label="Kembali ke atas"
    >
        <i class="ph ph-arrow-up text-lg font-bold"></i>
    </button>

    <!-- Footer Editorial -->
    <footer class="w-full mt-auto border-t border-zinc-200 dark:border-zinc-800 bg-base-light dark:bg-base-dark py-12">
        <div class="max-w-5xl mx-auto px-5 sm:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h3 class="font-sans italic font-bold text-2xl text-zinc-900 dark:text-white mb-1">Randi Iskandar.</h3>
                <p class="text-xs md:text-sm text-zinc-500 font-sans">© 2026 Randi Iskandar Workspace. Seluruh hak cipta dilindungi.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-6 text-xs md:text-sm font-medium text-zinc-600 dark:text-zinc-400 font-sans">
                @forelse($footerMenus as $fMenu)
                    <a 
                        href="{{ url($fMenu->url) }}" 
                        target="{{ $fMenu->target ?: '_self' }}"
                        class="{{ $fMenu->isCurrent() ? 'text-zinc-900 dark:text-white font-bold' : 'hover:text-zinc-900 dark:hover:text-white' }} transition-colors"
                    >
                        {{ $fMenu->label }}
                    </a>
                @empty
                    <a href="{{ route('about') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Tentang</a>
                    <a href="{{ route('terms') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Layanan</a>
                    <a href="{{ route('disclaimer') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Sanggahan</a>
                    <a href="{{ route('contact') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Kontak</a>
                @endforelse
            </div>
        </div>
    </footer>

    <!-- Syntax Highlighter Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <!-- Core UI Interactive Logic (Vanilla JS) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Dark Mode Toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            const mobileThemeToggleBtn = document.getElementById('mobile-theme-toggle');
            const htmlElement = document.documentElement;

            const toggleTheme = () => {
                htmlElement.classList.toggle('dark');
                if (htmlElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            };

            themeToggleBtn?.addEventListener('click', toggleTheme);
            mobileThemeToggleBtn?.addEventListener('click', toggleTheme);

            // 2. Overlay & Modals / Drawers Logic
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const bookmarkContainer = document.getElementById('bookmark-container');
            const bookmarkPopover = document.getElementById('bookmark-popover');
            const bookmarkHeaderTrigger = document.getElementById('bookmark-header-trigger');
            const mobileBookmarkTrigger = document.getElementById('mobile-bookmark-trigger');
            const closeBookmarkPopoverBtn = document.getElementById('close-bookmark-popover');
            const searchOverlay = document.getElementById('search-overlay');
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            const searchStatus = document.getElementById('search-status');

            const closeAllDrawers = () => {
                mobileSidebar?.classList.add('-translate-x-full');
                sidebarOverlay?.classList.add('opacity-0', 'pointer-events-none');
                document.body.style.overflow = '';
            };

            const closeSearch = () => {
                searchOverlay?.classList.remove('active');
                document.body.style.overflow = '';
            };

            const openSearch = () => {
                closeAllDrawers();
                closeBookmarkPopover();
                searchOverlay?.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => searchInput?.focus(), 200);
            };

            document.getElementById('search-trigger')?.addEventListener('click', openSearch);
            document.getElementById('mobile-search-trigger')?.addEventListener('click', openSearch);
            document.getElementById('close-search')?.addEventListener('click', closeSearch);

            // Escape Key handler
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (searchOverlay?.classList.contains('active')) closeSearch();
                    closeAllDrawers();
                    closeBookmarkPopover();
                }
            });
            sidebarOverlay?.addEventListener('click', closeAllDrawers);

            // Mobile Sidebar triggers
            document.getElementById('mobile-menu-trigger')?.addEventListener('click', () => {
                closeBookmarkPopover();
                mobileSidebar?.classList.remove('-translate-x-full');
                sidebarOverlay?.classList.remove('opacity-0', 'pointer-events-none');
                document.body.style.overflow = 'hidden';
            });
            document.getElementById('close-sidebar')?.addEventListener('click', closeAllDrawers);

            // Bookmark Popover triggers (Seperti Daftar Isi / Floating Popover)
            const openBookmarkPopover = () => {
                closeAllDrawers();
                renderBookmarks();
                bookmarkPopover?.classList.remove('hidden');
                document.body.classList.add('cursor-pointer');
            };

            const closeBookmarkPopover = () => {
                bookmarkPopover?.classList.add('hidden');
                document.body.classList.remove('cursor-pointer');
            };

            const toggleBookmarkPopover = (e) => {
                e?.stopPropagation();
                if (bookmarkPopover?.classList.contains('hidden')) {
                    openBookmarkPopover();
                } else {
                    closeBookmarkPopover();
                }
            };

            bookmarkHeaderTrigger?.addEventListener('click', toggleBookmarkPopover);
            mobileBookmarkTrigger?.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDrawers();
                openBookmarkPopover();
            });
            closeBookmarkPopoverBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                closeBookmarkPopover();
            });

            // Tutup popover bookmark jika user mengklik di luar area (seperti Daftar Isi)
            document.addEventListener('click', (e) => {
                if (bookmarkPopover && !bookmarkPopover.classList.contains('hidden')) {
                    if (!bookmarkPopover.contains(e.target) && !bookmarkHeaderTrigger?.contains(e.target) && !mobileBookmarkTrigger?.contains(e.target)) {
                        closeBookmarkPopover();
                    }
                }
            });

            // 3. Bookmark Storage Engine (localStorage)
            const getBookmarks = () => {
                try {
                    return JSON.parse(localStorage.getItem('randi_bookmarks') || '[]');
                } catch {
                    return [];
                }
            };

            const saveBookmarks = (items) => {
                localStorage.setItem('randi_bookmarks', JSON.stringify(items));
                updateBookmarkBadge();
            };

            const updateBookmarkBadge = () => {
                const items = getBookmarks();
                const badge = document.getElementById('bookmark-badge');
                const countTexts = document.querySelectorAll('.bookmark-count-text');
                const drawerCount = document.getElementById('bookmark-drawer-count');
                const clearBtn = document.getElementById('clear-bookmarks-btn');

                if (badge) {
                    if (items.length > 0) {
                        badge.textContent = items.length;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                countTexts.forEach(el => el.textContent = items.length);
                if (drawerCount) drawerCount.textContent = `${items.length} artikel tersimpan`;
                if (clearBtn) clearBtn.classList.toggle('hidden', items.length === 0);

                // Update semua ikon bookmark di halaman
                document.querySelectorAll('.bookmark-btn').forEach(btn => {
                    const id = btn.dataset.id;
                    const isSaved = items.some(item => String(item.id) === String(id));
                    const icon = btn.querySelector('i');
                    if (icon) {
                        if (isSaved) {
                            icon.className = 'ph-fill ph-bookmark text-lg text-zinc-900 dark:text-white';
                        } else {
                            icon.className = 'ph ph-bookmark text-lg';
                        }
                    }
                });
            };

            const renderBookmarks = () => {
                const items = getBookmarks();
                const container = document.getElementById('bookmark-list');
                const emptyMsg = document.getElementById('bookmark-empty-msg');
                if (!container) return;

                // Kosongkan item lama kecuali empty message
                container.querySelectorAll('.bookmark-item').forEach(el => el.remove());

                if (items.length === 0) {
                    if (emptyMsg) emptyMsg.style.display = 'block';
                    return;
                }

                if (emptyMsg) emptyMsg.style.display = 'none';

                items.forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'bookmark-item flex gap-3 items-center group justify-between p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition-colors border border-transparent hover:border-zinc-200/50 dark:hover:border-zinc-700/50';
                    el.innerHTML = `
                        <a href="${item.url}" class="flex gap-3 items-center flex-1 min-w-0">
                            ${item.image ? `<img src="${item.image}" class="w-14 h-12 object-cover bg-zinc-100 dark:bg-zinc-800 rounded-lg shrink-0 border border-zinc-200/60 dark:border-zinc-700/60" alt="Thumb" loading="lazy" decoding="async">` : ''}
                            <div class="min-w-0 flex-1">
                                <h4 class="font-sans text-xs font-bold text-zinc-900 dark:text-white leading-snug group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors line-clamp-2">${item.title}</h4>
                                <p class="text-[11px] text-zinc-400 mt-0.5 font-sans">${item.date || ''}</p>
                            </div>
                        </a>
                        <button class="remove-bookmark-btn w-7 h-7 rounded-full flex items-center justify-center text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors shrink-0 ml-1 cursor-pointer" data-id="${item.id}" title="Hapus dari daftar">
                            <i class="ph ph-trash text-sm"></i>
                        </button>
                    `;
                    container.appendChild(el);
                });

                container.querySelectorAll('.remove-bookmark-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const id = btn.dataset.id;
                        const filtered = getBookmarks().filter(i => String(i.id) !== String(id));
                        saveBookmarks(filtered);
                        renderBookmarks();
                    });
                });
            };

            document.getElementById('clear-bookmarks-btn')?.addEventListener('click', () => {
                if (confirm('Hapus semua artikel yang disimpan?')) {
                    saveBookmarks([]);
                    renderBookmarks();
                }
            });

            // Event delegation untuk tombol bookmark di feed/detail
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.bookmark-btn');
                if (!btn) return;
                e.preventDefault();

                const id = btn.dataset.id;
                const title = btn.dataset.title;
                const url = btn.dataset.url;
                const date = btn.dataset.date;
                const image = btn.dataset.image;

                let items = getBookmarks();
                const existsIndex = items.findIndex(i => String(i.id) === String(id));

                if (existsIndex > -1) {
                    items.splice(existsIndex, 1);
                } else {
                    items.unshift({ id, title, url, date, image });
                }

                saveBookmarks(items);

                // Micro animation
                btn.style.transform = 'scale(1.25)';
                setTimeout(() => btn.style.transform = 'scale(1)', 150);
            });

            updateBookmarkBadge();

            // 4. AJAX Live Search
            let searchTimeout = null;
            searchInput?.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();

                if (query.length < 2) {
                    if (searchResults) searchResults.innerHTML = '';
                    if (searchStatus) searchStatus.textContent = '';
                    return;
                }

                if (searchStatus) searchStatus.textContent = 'Mencari...';

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('search') }}?q=${encodeURIComponent(query)}`, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (searchStatus) searchStatus.textContent = `${data.count} hasil ditemukan`;
                        if (!searchResults) return;

                        if (data.count === 0) {
                            searchResults.innerHTML = `<p class="text-sm text-zinc-500 text-center py-6">Tidak ada tulisan yang cocok dengan "${query}".</p>`;
                            return;
                        }

                        searchResults.innerHTML = data.results.map(post => `
                            <a href="${post.url}" class="p-4 rounded-sm border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/50 hover:border-zinc-900 dark:hover:border-zinc-500 transition-colors block">
                                <div class="flex items-center gap-2 text-xs text-zinc-400 uppercase tracking-wider mb-1 font-bold">
                                    <span>${post.category || 'Artikel'}</span> • <span>${post.date || ''}</span>
                                </div>
                                <h4 class="font-sans font-bold text-lg text-zinc-900 dark:text-white mb-1">${post.title}</h4>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 line-clamp-2">${post.excerpt || ''}</p>
                            </a>
                        `).join('');
                    })
                    .catch(() => {
                        if (searchStatus) searchStatus.textContent = 'Gagal memuat hasil.';
                    });
                }, 300);
            });

            // 5. Back to Top Button
            const backToTopBtn = document.getElementById('back-to-top');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTopBtn?.classList.remove('opacity-0', 'pointer-events-none');
                    backToTopBtn?.classList.add('opacity-100');
                } else {
                    backToTopBtn?.classList.add('opacity-0', 'pointer-events-none');
                    backToTopBtn?.classList.remove('opacity-100');
                }
            });

            backToTopBtn?.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 6. Muat Lainnya (Load More Posts)
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('#btn-load-more');
                if (!btn) return;
                e.preventDefault();

                const nextUrl = btn.dataset.nextUrl;
                if (!nextUrl || btn.disabled) return;

                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `<i class="ph ph-spinner ph-spin text-base"></i> <span>Memuat tulisan...</span>`;

                try {
                    const response = await fetch(nextUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Gagal memuat artikel.');

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newArticles = doc.querySelectorAll('#posts-container > article');
                    const targetContainer = document.getElementById('posts-container');

                    if (targetContainer && newArticles.length > 0) {
                        newArticles.forEach(article => {
                            article.classList.add('transition-all', 'duration-500', 'opacity-0', 'translate-y-2');
                            targetContainer.appendChild(article);
                            requestAnimationFrame(() => {
                                setTimeout(() => {
                                    article.classList.remove('opacity-0', 'translate-y-2');
                                    article.classList.add('opacity-100', 'translate-y-0');
                                }, 30);
                            });
                        });
                    }

                    // Sinkronkan ikon bookmark pada artikel yang baru dimuat
                    if (typeof updateBookmarkBadge === 'function') {
                        updateBookmarkBadge();
                    }

                    // Perbarui URL untuk pagination berikutnya
                    const nextBtn = doc.querySelector('#btn-load-more');
                    const loadMoreContainer = document.getElementById('load-more-container');

                    if (nextBtn && nextBtn.dataset.nextUrl) {
                        btn.dataset.nextUrl = nextBtn.dataset.nextUrl;
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    } else if (loadMoreContainer) {
                        loadMoreContainer.innerHTML = `
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 font-sans italic py-4">
                                Semua tulisan telah berhasil dimuat.
                            </p>
                        `;
                    }
                } catch (err) {
                    console.error(err);
                    btn.innerHTML = `<span>Gagal memuat. Coba lagi</span> <i class="ph ph-arrow-clockwise text-base"></i>`;
                    btn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>
