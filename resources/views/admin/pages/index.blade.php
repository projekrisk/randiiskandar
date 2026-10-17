<x-admin-layout title="Kelola Halaman Statis - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Kelola Halaman Statis</h1>
                <p class="text-xs text-zinc-500 mt-1">Atur dan perbarui konten halaman informasi seperti Kebijakan Privasi, Ketentuan Layanan, Sanggahan, dan lainnya.</p>
            </div>
            
            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity self-start sm:self-auto shadow-xs">
                <i class="ph ph-plus-circle text-base"></i>
                <span>Buat Halaman Baru</span>
            </a>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-4 mb-6 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            
            
            <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 text-xs font-medium">
                <a 
                    href="{{ route('admin.pages.index', array_merge(request()->query(), ['status' => null])) }}"
                    class="px-3 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ !request()->filled('status') ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    Semua Halaman
                </a>
                <a 
                    href="{{ route('admin.pages.index', array_merge(request()->query(), ['status' => 'published'])) }}"
                    class="px-3 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ request()->input('status') === 'published' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    Diterbitkan
                </a>
                <a 
                    href="{{ route('admin.pages.index', array_merge(request()->query(), ['status' => 'draft'])) }}"
                    class="px-3 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ request()->input('status') === 'draft' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    Draf
                </a>
            </div>

            
            <form action="{{ route('admin.pages.index') }}" method="GET" class="w-full md:w-72 flex items-center relative">
                @if(request()->filled('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <i class="ph ph-magnifying-glass absolute left-3 text-zinc-400 text-sm"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari judul atau slug..." 
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs pl-8 pr-3 py-1.5 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                >
            </form>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60 border-b border-zinc-200 dark:border-zinc-800 text-[11px] font-bold uppercase tracking-wider text-zinc-500">
                        <tr>
                            <th class="px-5 py-3.5">Halaman & Slug</th>
                            <th class="px-5 py-3.5">Estimasi Baca</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Terakhir Diperbarui</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-xs">
                        @forelse($pages as $page)
                            @php
                                $publicUrl = in_array($page->slug, ['terms', 'disclaimer', 'privacy', 'about']) 
                                    ? ($page->slug === 'about' ? route('about') : ($page->slug === 'privacy' ? route('privacy') : ($page->slug === 'terms' ? route('terms') : route('disclaimer'))))
                                    : url($page->slug);
                            @endphp
                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-5 py-4 max-w-sm">
                                    <div class="flex items-start gap-3">
                                        @if($page->featured_image)
                                            <img 
                                                src="{{ $page->featured_image }}" 
                                                alt="{{ $page->title }}" 
                                                class="w-12 h-12 object-cover rounded-xs border border-zinc-200 dark:border-zinc-700 shrink-0 bg-zinc-100 dark:bg-zinc-800"
                                            >
                                        @else
                                            <div class="w-12 h-12 rounded-xs border border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center shrink-0 text-zinc-400">
                                                <i class="ph ph-file-text text-lg"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-grow">
                                            <div class="font-bold text-sm text-zinc-900 dark:text-white truncate">
                                                {{ $page->title }}
                                            </div>
                                            <div class="text-[11px] text-zinc-400 font-mono mt-0.5 flex items-center gap-1.5 truncate">
                                                <i class="ph ph-link text-xs"></i>
                                                <span>/{{ $page->slug === 'about' ? 'tentang' : ($page->slug === 'privacy' ? 'privasi' : $page->slug) }}</span>
                                            </div>
                                            @if($page->meta_description)
                                                <div class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-1 italic max-w-xs whitespace-normal">
                                                    {{ $page->meta_description }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-5 py-4 text-zinc-600 dark:text-zinc-400">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="ph ph-clock text-xs"></i>
                                        {{ $page->reading_time }} mnt
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if($page->is_published)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Diterbitkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                            Draf
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-zinc-500 dark:text-zinc-400 font-mono text-[11px]">
                                    {{ $page->updated_at->translatedFormat('d M Y, H:i') }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a 
                                            href="{{ $publicUrl }}" 
                                            target="_blank" 
                                            class="p-1.5 text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors"
                                            title="Lihat Halaman Publik"
                                        >
                                            <i class="ph ph-arrow-square-out text-base"></i>
                                        </a>

                                        <a 
                                            href="{{ route('admin.pages.edit', $page) }}" 
                                            class="p-1.5 text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors"
                                            title="Edit Halaman"
                                        >
                                            <i class="ph ph-note-pencil text-base"></i>
                                        </a>

                                        <button 
                                            type="button" 
                                            class="btn-delete-trigger p-1.5 text-zinc-400 hover:text-red-600 transition-colors cursor-pointer"
                                            data-action="{{ route('admin.pages.destroy', $page) }}"
                                            data-title="Hapus Halaman"
                                            data-item="{{ $page->title }}"
                                            data-message="Apakah Anda yakin ingin menghapus halaman statis ini? Halaman tidak lagi dapat diakses oleh publik."
                                            title="Hapus Halaman"
                                        >
                                            <i class="ph ph-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-zinc-500">
                                    <i class="ph ph-file-text text-4xl block mx-auto mb-3 text-zinc-400"></i>
                                    <p class="font-bold text-sm text-zinc-800 dark:text-zinc-200">Belum ada halaman statis ditemukan</p>
                                    <p class="text-xs text-zinc-400 mt-1">Buat halaman informasi baru seperti Kebijakan Privasi atau Ketentuan Layanan.</p>
                                    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-1.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity">
                                        <i class="ph ph-plus-circle text-sm"></i>
                                        <span>Buat Halaman Pertama</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            @if($pages->hasPages())
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>
