<x-admin-layout title="Kelola Tulisan - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 font-sans">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Kelola Tulisan</h1>
                <p class="text-xs text-zinc-500">Daftar semua artikel publikasi dan draf editorial.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity">
                <i class="ph ph-plus-circle text-base"></i> Tulis Baru
            </a>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 p-4 border border-zinc-200 dark:border-zinc-800 rounded-xs mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 font-sans">
            <div class="flex items-center gap-2 text-xs font-medium">
                <a href="{{ route('admin.posts.index') }}" class="px-3 py-1.5 rounded-xs {{ !request('status') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                    Semua
                </a>
                <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="px-3 py-1.5 rounded-xs {{ request('status') === 'published' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                    Publik
                </a>
                <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="px-3 py-1.5 rounded-xs {{ request('status') === 'draft' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                    Draf
                </a>
            </div>

            <form action="{{ route('admin.posts.index') }}" method="GET" class="w-full sm:w-auto">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari judul..." 
                    class="w-full sm:w-64 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 text-xs text-zinc-900 dark:text-white rounded-xs focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                >
            </form>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-x-auto font-sans">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">Judul Tulisan</th>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 font-medium text-center">Status</th>
                        <th class="px-4 py-3 font-medium text-center">Sorotan</th>
                        <th class="px-4 py-3 font-medium">Tampilan</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                    @forelse($posts as $post)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="px-4 py-3.5 font-bold text-zinc-900 dark:text-white truncate max-w-sm">
                                <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="hover:underline">
                                    {{ $post->title }}
                                </a>
                                @if(!empty($post->tags_list))
                                    <div class="flex flex-wrap gap-1 mt-0.5">
                                        @foreach(array_slice($post->tags_list, 0, 3) as $tag)
                                            <span class="text-[10px] text-zinc-400 font-normal">#{{ $tag }}</span>
                                        @endforeach
                                        @if(count($post->tags_list) > 3)
                                            <span class="text-[10px] text-zinc-400 font-normal">+{{ count($post->tags_list) - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-xs text-zinc-500">
                                @if($post->categories->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($post->categories as $cat)
                                            <span class="inline-block px-1.5 py-0.5 rounded-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[11px] font-medium">{{ $cat->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    {{ $post->category?->name ?: '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($post->status === 'published')
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-xs">Publik</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 rounded-xs">Draf</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($post->is_featured)
                                    <i class="ph-fill ph-star text-amber-500 text-base" title="Featured Post"></i>
                                @else
                                    <span class="text-zinc-300 dark:text-zinc-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-xs text-zinc-500">
                                {{ number_format($post->views_count) }}
                            </td>
                            <td class="px-4 py-3.5 text-xs text-zinc-500">
                                {{ $post->formatted_published_at }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors" title="Edit">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        class="btn-delete-trigger text-zinc-400 hover:text-red-600 transition-colors cursor-pointer" 
                                        data-action="{{ route('admin.posts.destroy', $post) }}"
                                        data-title="Hapus Artikel"
                                        data-item="{{ $post->title }}"
                                        data-message="Apakah Anda yakin ingin menghapus artikel ini? Konten tulisan dan seluruh komentar terkait akan dihapus secara permanen."
                                        title="Hapus Artikel"
                                    >
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-zinc-400">Tidak ada tulisan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
