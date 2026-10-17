<x-admin-layout title="Tulis Artikel Baru - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white flex items-center gap-1 mb-1 font-medium transition-colors">
                    <i class="ph ph-arrow-left"></i> Kembali ke daftar artikel
                </a>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Tulis Artikel Baru</h1>
            </div>
        </div>

        @include('admin.posts.partials.form', [
            'post' => null,
            'categories' => $categories,
            'suggestedTags' => $suggestedTags ?? [],
            'action' => route('admin.posts.store'),
            'method' => 'POST',
        ])

    </div>
</x-admin-layout>
