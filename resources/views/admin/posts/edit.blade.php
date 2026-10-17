<x-admin-layout title="Edit Artikel - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white flex items-center gap-1 mb-1 font-medium transition-colors">
                    <i class="ph ph-arrow-left"></i> Kembali ke daftar artikel
                </a>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Edit Artikel</h1>
            </div>
            @if($post->status === 'published')
                <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="text-xs font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white flex items-center gap-1.5 px-3 py-1.5 rounded-xs border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 transition-colors">
                    <i class="ph ph-arrow-square-out text-sm"></i> Pratinjau Publik
                </a>
            @endif
        </div>

        @include('admin.posts.partials.form', [
            'post' => $post,
            'categories' => $categories,
            'suggestedTags' => $suggestedTags ?? [],
            'action' => route('admin.posts.update', $post),
            'method' => 'PUT',
        ])

    </div>
</x-admin-layout>
