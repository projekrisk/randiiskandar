<x-admin-layout title="Ikhtisar Dasbor - Randi Iskandar Workspace">
    
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 font-sans">
        <div>
            <h1 class="font-bold text-2xl md:text-3xl text-zinc-900 dark:text-white mb-1">Ikhtisar Dasbor</h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Selamat datang kembali! Berikut ringkasan performa blog Anda hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xs border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-xs transition-colors shadow-2xs">
                <i class="ph ph-chart-line-up text-base"></i> Laporan Analitik
            </a>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity whitespace-nowrap shadow-xs">
                <i class="ph ph-plus-circle text-base"></i> Tulis Artikel
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8 font-sans">
        
        <!-- Total Artikel -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs flex items-start gap-4">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-xs">
                <i class="ph ph-file-text text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1">Total Artikel</p>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white">{{ $totalPosts }}</h3>
                <p class="text-xs font-medium text-zinc-500 mt-1">Publikasi tersimpan</p>
            </div>
        </div>
        
        <!-- Total Tampilan -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs flex items-start gap-4">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-xs">
                <i class="ph ph-eye text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1">Total Tampilan</p>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white">{{ number_format($totalViews) }}</h3>
                <p class="text-xs font-medium text-zinc-500 mt-1">
                    <a href="{{ route('admin.analytics.index') }}" class="hover:underline text-zinc-600 dark:text-zinc-400 inline-flex items-center gap-0.5">
                        <span>Lihat analitik</span>
                        <i class="ph ph-arrow-right text-[10px]"></i>
                    </a>
                </p>
            </div>
        </div>

        <!-- Komentar Menunggu Moderasi -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs flex items-start gap-4">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-xs">
                <i class="ph ph-chats-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1">Moderasi Komentar</p>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white">{{ $pendingCommentsCount }}</h3>
                <p class="text-xs font-medium text-zinc-500 mt-1">
                    @if($pendingCommentsCount > 0)
                        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="text-red-500 font-bold hover:underline">Perlu ditinjau</a>
                    @else
                        Semua bersih
                    @endif
                </p>
            </div>
        </div>

        <!-- Total Seluruh Komentar -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs flex items-start gap-4">
            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-xs">
                <i class="ph ph-chat-circle-dots text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1">Total Komentar</p>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white">{{ $totalComments }}</h3>
                <p class="text-xs font-medium text-zinc-500 mt-1">Interaksi pembaca</p>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Tulisan Terbaru -->
    <div class="w-full font-sans">
        <div>
            <div class="flex items-center justify-between mb-4 border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">Tulisan Terbaru</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">Lihat Semua</a>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 text-xs">
                        <tr>
                            <th class="px-4 py-3 font-medium">Judul Tulisan</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-zinc-800 dark:text-zinc-200 divide-y divide-zinc-100 dark:divide-zinc-800/50">
                        @forelse($recentPosts as $post)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/20 transition-colors">
                                <td class="px-4 py-3.5 font-bold text-zinc-900 dark:text-white truncate max-w-xs sm:max-w-md">
                                    <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="hover:underline">
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($post->status === 'published')
                                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-xs">Publik</span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 rounded-xs">Draf</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-xs text-zinc-500">
                                    {{ $post->formatted_published_at }}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus tulisan ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-zinc-400 hover:text-red-600 transition-colors" title="Hapus">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-zinc-400">Belum ada tulisan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
