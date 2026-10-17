@props(['title' => 'Workspace - Dasbor Randi Iskandar'])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
</head>
<body class="bg-base-light text-zinc-900 dark:bg-base-dark dark:text-zinc-100 theme-transition font-sans h-screen flex overflow-hidden selection:bg-zinc-900 selection:text-white dark:selection:bg-white dark:selection:text-zinc-900">

    <div id="admin-sidebar-overlay" class="fixed inset-0 bg-zinc-900/60 dark:bg-black/80 backdrop-blur-sm z-40 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"></div>

    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 z-50 transform -translate-x-full lg:translate-x-0 lg:static transition-all duration-300 flex flex-col">
        
        <div class="h-16 flex items-center justify-between px-5 border-b border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="font-sans italic font-bold text-xl tracking-wide text-zinc-900 dark:text-white flex items-center gap-2 overflow-hidden">
                <span class="sidebar-expanded flex items-center gap-2">
                    Randi. <span class="not-italic text-[10px] font-bold px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-800 rounded-xs text-zinc-500 uppercase tracking-widest mt-0.5">Workspace</span>
                </span>
                <span class="sidebar-collapsed-only hidden font-serif italic text-2xl font-bold">R.</span>
            </a>

            <button id="close-admin-sidebar" class="lg:hidden text-zinc-500 hover:text-zinc-900 dark:hover:text-white p-1" aria-label="Tutup Sidebar">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <div class="flex-grow overflow-y-auto py-6 px-3 flex flex-col gap-1">
            <p class="sidebar-expanded px-3 text-xs font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2 font-sans">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Dasbor">
                <i class="ph ph-squares-four text-lg shrink-0"></i>
                <span class="sidebar-expanded">Dasbor</span>
                <span class="sidebar-tooltip">Dasbor</span>
            </a>

            <a href="{{ route('admin.posts.index') }}" class="sidebar-link flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('admin.posts.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Tulisan">
                <div class="flex items-center gap-3 min-w-0">
                    <i class="ph ph-pencil-line text-lg shrink-0"></i>
                    <span class="sidebar-expanded truncate">Tulisan</span>
                </div>
                <span class="sidebar-expanded text-xs bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 px-1.5 py-0.2 rounded-xs font-bold shrink-0">
                    {{ \App\Models\Post::count() }}
                </span>
                <span class="sidebar-tooltip">Tulisan ({{ \App\Models\Post::count() }})</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.categories.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Kategori">
                <i class="ph ph-folder-open text-lg shrink-0"></i>
                <span class="sidebar-expanded truncate">Kategori</span>
                <span class="sidebar-tooltip">Kategori</span>
            </a>

            <a href="{{ route('admin.pages.index') }}" class="sidebar-link flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('admin.pages.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Halaman Statis">
                <div class="flex items-center gap-3 min-w-0">
                    <i class="ph ph-file-text text-lg shrink-0"></i>
                    <span class="sidebar-expanded truncate">Halaman</span>
                </div>
                <span class="sidebar-expanded text-xs bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 px-1.5 py-0.2 rounded-xs font-bold shrink-0">
                    {{ \App\Models\Page::count() }}
                </span>
                <span class="sidebar-tooltip">Halaman ({{ \App\Models\Page::count() }})</span>
            </a>

            <a href="{{ route('admin.menus.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.menus.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Menu Navigasi">
                <i class="ph ph-list-dashes text-lg shrink-0"></i>
                <span class="sidebar-expanded truncate">Menu Navigasi</span>
                <span class="sidebar-tooltip">Menu Navigasi</span>
            </a>

            @php $pendingCount = \App\Models\Comment::pending()->count(); @endphp
            <a href="{{ route('admin.comments.index') }}" class="sidebar-link flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('admin.comments.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Komentar">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative shrink-0 flex items-center justify-center">
                        <i class="ph ph-chats-circle text-lg"></i>
                        @if($pendingCount > 0)
                            <span class="sidebar-collapsed-only hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white dark:border-zinc-900"></span>
                        @endif
                    </div>
                    <span class="sidebar-expanded truncate">Komentar</span>
                </div>
                @if($pendingCount > 0)
                    <span class="sidebar-expanded text-[10px] bg-red-600 text-white px-1.5 py-0.5 rounded-full font-bold shrink-0">
                        {{ $pendingCount }}
                    </span>
                @endif
                <span class="sidebar-tooltip">Komentar @if($pendingCount > 0)({{ $pendingCount }})@endif</span>
            </a>

            <a href="{{ route('admin.analytics.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.analytics.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Analitik & Laporan">
                <i class="ph ph-chart-line-up text-lg shrink-0"></i>
                <span class="sidebar-expanded truncate">Analitik & Laporan</span>
                <span class="sidebar-tooltip">Analitik & Laporan</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.settings.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 hover:text-zinc-900 dark:hover:text-white font-medium' }} rounded-xs text-sm transition-colors relative group" title="Pengaturan">
                <i class="ph ph-gear text-lg shrink-0"></i>
                <span class="sidebar-expanded truncate">Pengaturan</span>
                <span class="sidebar-tooltip">Pengaturan</span>
            </a>

            <div class="sidebar-expanded my-4 border-t border-zinc-200 dark:border-zinc-800"></div>
            <p class="sidebar-expanded px-3 text-xs font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2 font-sans">Aksi Cepat</p>
        </div>

        <div class="p-3 border-t border-zinc-200 dark:border-zinc-800 flex flex-col gap-1.5 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link flex items-center gap-3 px-3 py-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors font-medium text-sm rounded-xs relative group" title="Lihat Situs Publik">
                <i class="ph ph-arrow-square-out text-lg shrink-0"></i>
                <span class="sidebar-expanded truncate">Lihat Situs Publik</span>
                <span class="sidebar-tooltip">Lihat Situs Publik</span>
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full flex items-center gap-3 px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xs transition-colors font-medium text-sm cursor-pointer relative group" title="Keluar">
                    <i class="ph ph-sign-out text-lg shrink-0"></i>
                    <span class="sidebar-expanded truncate">Keluar</span>
                    <span class="sidebar-tooltip">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 bg-base-light dark:bg-base-dark">
        
        <header class="h-16 flex items-center justify-between px-6 border-b border-zinc-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md z-30 sticky top-0">
            <div class="flex items-center gap-3">
                <button id="admin-mobile-trigger" class="lg:hidden text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors p-1" aria-label="Buka Menu">
                    <i class="ph ph-list text-2xl"></i>
                </button>

                <button id="topbar-sidebar-toggle" class="hidden lg:flex items-center justify-center w-8 h-8 rounded text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer" title="Ciutkan / Rentangkan Sidebar" aria-label="Ciutkan Sidebar">
                    <i class="ph ph-sidebar-simple text-xl"></i>
                </button>

                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                    {{ auth()->user()->name }}'s Workspace
                </div>
            </div>

            <div class="flex items-center gap-5">
                <button id="theme-toggle" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors" aria-label="Toggle Tema">
                    <i class="ph ph-moon text-xl dark:hidden block"></i>
                    <i class="ph ph-sun text-xl hidden dark:block"></i>
                </button>
                <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-700"></div>
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100' }}" alt="Admin" class="w-8 h-8 rounded-full object-cover">
                    <span class="hidden sm:block text-sm font-bold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <div class="px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="p-4 mb-4 rounded-xs bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 rounded-xs bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeBtn = document.getElementById('theme-toggle');
            themeBtn?.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });

            const toggleSidebar = () => {
                const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
                localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
            };
            document.getElementById('sidebar-collapse-toggle')?.addEventListener('click', toggleSidebar);
            document.getElementById('topbar-sidebar-toggle')?.addEventListener('click', toggleSidebar);

            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            const openBtn = document.getElementById('admin-mobile-trigger');
            const closeBtn = document.getElementById('close-admin-sidebar');

            const openAdminSidebar = () => {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('opacity-0', 'pointer-events-none');
            };

            const closeAdminSidebar = () => {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('opacity-0', 'pointer-events-none');
            };

            openBtn?.addEventListener('click', openAdminSidebar);
            closeBtn?.addEventListener('click', closeAdminSidebar);
            overlay?.addEventListener('click', closeAdminSidebar);
        });
    </script>

    <x-confirm-delete-modal />
</body>
</html>
