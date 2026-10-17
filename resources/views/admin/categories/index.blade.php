<x-admin-layout title="Kelola Kategori - Randi Iskandar">
    <div class="w-full font-sans pb-16">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Kelola Kategori</h1>
                <p class="text-xs text-zinc-500 mt-1">Kelompokkan tulisan ke dalam taksonomi editorial dan lengkapi deskripsinya untuk arsip publik.</p>
            </div>
            <div>
                <button 
                    type="button" 
                    id="btn-open-create-category"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-bold rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer"
                >
                    <i class="ph ph-folder-plus text-base"></i>
                    <span>Tambah Kategori Baru</span>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 text-xs bg-zinc-50/50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-5 py-3.5 font-medium">Kategori & Keterangan</th>
                            <th class="px-5 py-3.5 font-medium">Slug</th>
                            <th class="px-5 py-3.5 font-medium text-center">Jumlah Tulisan</th>
                            <th class="px-5 py-3.5 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/20 transition-colors">
                                <td class="px-5 py-4 max-w-sm whitespace-normal">
                                    <a href="{{ route('category.show', $cat->slug) }}" target="_blank" class="font-bold text-sm text-zinc-900 dark:text-white hover:underline flex items-center gap-1.5">
                                        <span>{{ $cat->name }}</span>
                                        <i class="ph ph-arrow-square-out text-xs text-zinc-400"></i>
                                    </a>
                                    @if($cat->description)
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-2 leading-relaxed">
                                            {{ $cat->description }}
                                        </p>
                                    @else
                                        <span class="text-[11px] text-zinc-400 italic">Belum ada keterangan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-zinc-500 font-mono">
                                    {{ $cat->slug }}
                                </td>
                                <td class="px-5 py-4 text-xs text-center font-bold">
                                    <span class="px-2.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                                        {{ $cat->posts_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button" 
                                            class="btn-edit-category text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer p-1.5" 
                                            data-id="{{ $cat->id }}" 
                                            data-name="{{ $cat->name }}" 
                                            data-slug="{{ $cat->slug }}" 
                                            data-description="{{ $cat->description }}"
                                            data-action="{{ route('admin.categories.update', $cat) }}"
                                            title="Edit Kategori"
                                        >
                                            <i class="ph ph-pencil-simple text-base"></i>
                                        </button>

                                        @if($cat->posts_count === 0)
                                            <button 
                                                type="button" 
                                                class="btn-delete-trigger text-zinc-400 hover:text-red-600 transition-colors cursor-pointer p-1.5"
                                                data-action="{{ route('admin.categories.destroy', $cat) }}"
                                                data-title="Hapus Kategori"
                                                data-item="{{ $cat->name }}"
                                                data-message="Apakah Anda yakin ingin menghapus kategori '{{ $cat->name }}'? Kategori ini tidak dapat dipulihkan."
                                                title="Hapus Kategori"
                                            >
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        @else
                                            <span class="text-xs text-zinc-300 dark:text-zinc-600 p-1.5" title="Kategori masih memiliki {{ $cat->posts_count }} tulisan terhubung, tidak dapat dihapus.">
                                                <i class="ph ph-lock text-base"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-zinc-400">
                                    <i class="ph ph-folder-dashed text-4xl block mx-auto mb-2 text-zinc-300 dark:text-zinc-600"></i>
                                    <p class="font-bold text-sm text-zinc-800 dark:text-zinc-200">Belum ada kategori yang dibuat</p>
                                    <p class="text-xs text-zinc-400 mt-1">Buat kategori baru untuk mengelompokkan tulisan blog Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div 
        id="create-category-modal" 
        class="fixed inset-0 bg-zinc-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="create-category-title"
    >
        <div 
            id="create-category-card" 
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-md w-full p-6 space-y-5 font-sans shadow-2xl transform scale-95 transition-transform duration-200"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2">
                    <i class="ph ph-folder-plus text-xl text-zinc-900 dark:text-white"></i>
                    <h3 id="create-category-title" class="font-bold text-base text-zinc-900 dark:text-white">Tambah Kategori Baru</h3>
                </div>
                <button type="button" id="btn-close-create-modal" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-lg p-1">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" id="create-category-form" class="space-y-4">
                @csrf
                <input type="hidden" name="_source" value="modal">

                <div>
                    <label for="create_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="create_name" 
                        required 
                        value="{{ old('name') }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors font-medium"
                        placeholder="Misal: Arsitektur Web"
                    >
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="create_slug" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Slug URL <span class="text-zinc-400 font-normal">(Otomatis bila kosong)</span>
                    </label>
                    <input 
                        type="text" 
                        name="slug" 
                        id="create_slug" 
                        value="{{ old('slug') }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="arsitektur-web"
                    >
                    @error('slug') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="create_description" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Keterangan / Deskripsi Kategori
                    </label>
                    <textarea 
                        name="description" 
                        id="create_description" 
                        rows="3" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                        placeholder="Tuliskan keterangan kategori yang akan muncul pada direktori arsip..."
                    >{{ old('description') }}</textarea>
                    <p class="text-[11px] text-zinc-400 mt-1 leading-normal">
                        Keterangan ini akan ditampilkan pada kartu arsip dan header halaman kategori publik.
                    </p>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button 
                        type="button" 
                        id="btn-cancel-create-modal" 
                        class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                    >
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div 
        id="edit-category-modal" 
        class="fixed inset-0 bg-zinc-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="edit-category-title"
    >
        <div 
            id="edit-category-card" 
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-md w-full p-6 space-y-5 font-sans shadow-2xl transform scale-95 transition-transform duration-200"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2">
                    <i class="ph ph-pencil-simple text-xl text-zinc-900 dark:text-white"></i>
                    <h3 id="edit-category-title" class="font-bold text-base text-zinc-900 dark:text-white">Edit Kategori</h3>
                </div>
                <button type="button" id="btn-close-edit-modal" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-lg p-1">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <form id="edit-category-form" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_name" 
                        required 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors font-medium"
                    >
                </div>

                <div>
                    <label for="edit_slug" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Slug URL
                    </label>
                    <input 
                        type="text" 
                        name="slug" 
                        id="edit_slug" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                    >
                </div>

                <div>
                    <label for="edit_description" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Keterangan / Deskripsi Kategori
                    </label>
                    <textarea 
                        name="description" 
                        id="edit_description" 
                        rows="3" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                        placeholder="Keterangan ini akan muncul pada deskripsi kategori di arsip..."
                    ></textarea>
                    <p class="text-[11px] text-zinc-400 mt-1 leading-normal">
                        Keterangan ini akan muncul pada kartu indeks arsip dan header kategori.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button 
                        type="button" 
                        id="btn-cancel-edit-modal" 
                        class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const createModal = document.getElementById('create-category-modal');
        const createCard = document.getElementById('create-category-card');
        const btnOpenCreate = document.getElementById('btn-open-create-category');
        const btnCloseCreate = document.getElementById('btn-close-create-modal');
        const btnCancelCreate = document.getElementById('btn-cancel-create-modal');
        const createName = document.getElementById('create_name');
        const createSlug = document.getElementById('create_slug');
        let createSlugManual = Boolean(createSlug?.value.trim());

        function openCreateModal() {
            createModal.classList.remove('hidden');
            requestAnimationFrame(() => {
                createModal.classList.remove('opacity-0');
                createCard.classList.remove('scale-95');
                createCard.classList.add('scale-100');
            });
            createName?.focus();
        }

        function closeCreateModal() {
            createModal.classList.add('opacity-0');
            createCard.classList.remove('scale-100');
            createCard.classList.add('scale-95');
            setTimeout(() => {
                createModal.classList.add('hidden');
            }, 200);
        }

        btnOpenCreate?.addEventListener('click', openCreateModal);
        btnCloseCreate?.addEventListener('click', closeCreateModal);
        btnCancelCreate?.addEventListener('click', closeCreateModal);

        createSlug?.addEventListener('input', () => {
            createSlugManual = Boolean(createSlug.value.trim());
        });

        createName?.addEventListener('input', () => {
            if (!createSlugManual && createSlug) {
                createSlug.value = createName.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        const editModal = document.getElementById('edit-category-modal');
        const editCard = document.getElementById('edit-category-card');
        const editForm = document.getElementById('edit-category-form');
        const editName = document.getElementById('edit_name');
        const editSlug = document.getElementById('edit_slug');
        const editDesc = document.getElementById('edit_description');
        const btnCloseEdit = document.getElementById('btn-close-edit-modal');
        const btnCancelEdit = document.getElementById('btn-cancel-edit-modal');

        function openEditModal(actionUrl, name, slug, desc) {
            editForm.action = actionUrl;
            editName.value = name || '';
            editSlug.value = slug || '';
            editDesc.value = desc || '';

            editModal.classList.remove('hidden');
            requestAnimationFrame(() => {
                editModal.classList.remove('opacity-0');
                editCard.classList.remove('scale-95');
                editCard.classList.add('scale-100');
            });
            editName.focus();
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editCard.classList.remove('scale-100');
            editCard.classList.add('scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
                editForm.action = '';
            }, 200);
        }

        document.querySelectorAll('.btn-edit-category').forEach(btn => {
            btn.addEventListener('click', function () {
                const action = this.dataset.action;
                const name = this.dataset.name;
                const slug = this.dataset.slug;
                const desc = this.dataset.description;
                openEditModal(action, name, slug, desc);
            });
        });

        btnCloseEdit.addEventListener('click', closeEditModal);
        btnCancelEdit.addEventListener('click', closeEditModal);

        createModal?.addEventListener('click', function (e) {
            if (e.target === createModal) closeCreateModal();
        });

        editModal?.addEventListener('click', function (e) {
            if (e.target === editModal) closeEditModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (!createModal.classList.contains('hidden')) closeCreateModal();
                if (!editModal.classList.contains('hidden')) closeEditModal();
            }
        });

        @if($errors->any() && old('_source') === 'modal')
            openCreateModal();
        @endif
    });
    </script>
</x-admin-layout>
