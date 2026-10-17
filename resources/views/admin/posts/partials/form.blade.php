@props([
    'post' => null,
    'categories' => [],
    'suggestedTags' => [],
    'action' => '',
    'method' => 'POST',
])

@php
    $activeSuggestedTags = !empty($suggestedTags) ? $suggestedTags : \App\Models\Post::getAllUsedTags();
@endphp

<form 
    action="{{ $action }}" 
    method="POST" 
    enctype="multipart/form-data" 
    id="post-form"
    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 space-y-8 font-sans shadow-xs"
>
    @csrf
    @if(strtoupper($method) === 'PUT')
        @method('PUT')
    @endif

    
    <div class="space-y-6">
        <div>
            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                Judul Artikel <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="title" 
                id="title" 
                required 
                value="{{ old('title', $post?->title) }}"
                class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-base text-zinc-900 dark:text-white font-medium focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                placeholder="Contoh: Mengoptimalkan Performa Query pada Aplikasi Laravel"
            >
            @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
                    Slug URL (Alamat Permalink)
                </label>
                <span class="text-[11px] text-zinc-400 font-normal">Otomatis diselaraskan dari judul bila dikosongkan</span>
            </div>
            <div class="flex items-center rounded-xs border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/80 px-3 py-2 focus-within:border-zinc-900 dark:focus-within:border-white transition-colors">
                <span class="text-xs text-zinc-400 select-none mr-1 font-mono">{{ url('/') }}/</span>
                <input 
                    type="text" 
                    name="slug" 
                    id="slug" 
                    value="{{ old('slug', $post?->slug) }}"
                    class="w-full bg-transparent text-sm text-zinc-900 dark:text-white font-mono focus:outline-none"
                    placeholder="mengoptimalkan-performa-query"
                >
            </div>
            @error('slug') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        @php
            $selectedCategoryIds = old('category_ids');
            if ($selectedCategoryIds === null) {
                if ($post && $post->categories->isNotEmpty()) {
                    $selectedCategoryIds = $post->categories->pluck('id')->all();
                } elseif ($post && $post->category_id) {
                    $selectedCategoryIds = [$post->category_id];
                } elseif (old('category_id')) {
                    $selectedCategoryIds = [(int) old('category_id')];
                } else {
                    $selectedCategoryIds = [];
                }
            }
            $selectedCategoryIds = array_map('intval', (array) $selectedCategoryIds);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[11px] text-zinc-400 font-normal">Bisa pilih lebih dari satu</span>
                </div>
                <div class="flex flex-wrap gap-2 p-2.5 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs min-h-[44px] items-center">
                    @forelse($categories as $category)
                        @php
                            $isSelected = in_array($category->id, $selectedCategoryIds);
                        @endphp
                        <label class="category-chip cursor-pointer select-none inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150 {{ $isSelected ? 'bg-zinc-900 text-white border-zinc-900 dark:bg-white dark:text-zinc-900 dark:border-white shadow-xs font-semibold' : 'bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-500' }}">
                            <input 
                                type="checkbox" 
                                name="category_ids[]" 
                                value="{{ $category->id }}" 
                                {{ $isSelected ? 'checked' : '' }} 
                                class="sr-only category-checkbox"
                            >
                            <i class="ph {{ $isSelected ? 'ph-check-bold' : 'ph-plus' }} text-[11px] chip-icon"></i>
                            <span>{{ $category->name }}</span>
                        </label>
                    @empty
                        <span class="text-xs text-zinc-400">Belum ada kategori tersedia.</span>
                    @endforelse
                </div>
                @error('category_ids') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                @error('category_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="published_at" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                    Tanggal & Jam Terbit
                </label>
                <input 
                    type="datetime-local" 
                    name="published_at" 
                    id="published_at" 
                    value="{{ old('published_at', $post?->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                    class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs sm:text-sm text-zinc-900 dark:text-white font-sans focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                >
                @error('published_at') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-5 border border-zinc-200 dark:border-zinc-800 rounded-xs bg-zinc-50/50 dark:bg-zinc-950/30 space-y-3.5">
        <div class="flex items-center justify-between">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                    Gambar Utama (Featured Image / Thumbnail)
                </label>
                <p class="text-[11px] text-zinc-400 mt-0.5">Unggah berkas foto atau sematkan URL gambar eksternal.</p>
            </div>
            
            <div class="flex items-center bg-zinc-200 dark:bg-zinc-800 p-0.5 rounded-xs text-xs font-medium">
                <button type="button" id="tab-feat-upload" class="px-2.5 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs">
                    <i class="ph ph-upload-simple mr-1"></i> Unggah File
                </button>
                <button type="button" id="tab-feat-url" class="px-2.5 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <i class="ph ph-link mr-1"></i> Tautan URL
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            <div class="lg:col-span-5 space-y-2">
                <div id="feat-upload-box">
                    <div 
                        id="feat-dropzone" 
                        class="border border-dashed border-zinc-300 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-500 rounded-xs p-3.5 text-center cursor-pointer transition-colors bg-white dark:bg-zinc-900 flex flex-col items-center justify-center min-h-[88px]"
                    >
                        <input 
                            type="file" 
                            name="featured_image_file" 
                            id="featured_image_file" 
                            accept="image/*" 
                            class="hidden"
                        >
                        <i class="ph ph-image text-xl text-zinc-400 mb-1"></i>
                        <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">Pilih gambar atau seret berkas</p>
                        <p class="text-[10px] text-zinc-400 mt-0.5">JPG, PNG, WEBP, GIF (Maks. 10MB)</p>
                    </div>
                </div>

                <div id="feat-url-box" class="hidden">
                    <input 
                        type="text" 
                        name="featured_image" 
                        id="featured_image" 
                        value="{{ old('featured_image', $post?->featured_image) }}"
                        class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="https://images.unsplash.com/photo-..."
                    >
                </div>

                <input type="hidden" name="remove_featured_image" id="remove_featured_image" value="0">

                <div id="feat-preview-wrapper" class="{{ !empty(old('featured_image', $post?->featured_image)) ? '' : 'hidden' }}">
                    <div class="flex items-center gap-3 p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs">
                        <img 
                            id="feat-preview-img" 
                            src="{{ old('featured_image', $post?->featured_image) ?: '' }}" 
                            alt="Pratinjau Gambar Utama" 
                            class="w-14 h-10 object-cover rounded-xs border border-zinc-200 dark:border-zinc-700 bg-zinc-100 shrink-0"
                        >
                        <div class="flex-grow min-w-0 text-left">
                            <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200 truncate" id="feat-preview-title">
                                Gambar Terpilih
                            </p>
                            <p class="text-[10px] text-zinc-400 truncate" id="feat-preview-size">
                                Siap digunakan
                            </p>
                        </div>
                        <button 
                            type="button" 
                            id="btn-remove-feat-img" 
                            class="text-[11px] text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold p-1 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xs transition-colors shrink-0"
                            title="Hapus Gambar"
                        >
                            <i class="ph ph-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-3">
                <div>
                    <label for="featured_image_caption" class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">
                        Keterangan Gambar (Caption) <span class="font-normal text-zinc-400 text-[11px]">(Opsional)</span>
                    </label>
                    <input 
                        type="text" 
                        name="featured_image_caption" 
                        id="featured_image_caption" 
                        value="{{ old('featured_image_caption', $post?->featured_image_caption) }}"
                        class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-1.5 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="Contoh: Ilustrasi ruang kerja minimalis..."
                    >
                    @error('featured_image_caption') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <span class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">
                        Lebar Tampilan Gambar Utama di Halaman Baca
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2 rounded-xs border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 cursor-pointer hover:border-zinc-400 dark:hover:border-zinc-500 has-[:checked]:border-zinc-900 dark:has-[:checked]:border-white has-[:checked]:ring-1 has-[:checked]:ring-zinc-900 dark:has-[:checked]:ring-white transition-all">
                            <input 
                                type="radio" 
                                name="is_featured_image_wide" 
                                value="0" 
                                {{ !old('is_featured_image_wide', $post?->is_featured_image_wide) ? 'checked' : '' }}
                                class="text-zinc-900 focus:ring-0 cursor-pointer"
                            >
                            <div class="min-w-0">
                                <span class="font-bold text-xs text-zinc-900 dark:text-zinc-100 block truncate">Standar (Lebar Artikel)</span>
                                <span class="text-[10px] text-zinc-400 block truncate">Sejajar teks artikel (768px)</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 p-2 rounded-xs border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 cursor-pointer hover:border-zinc-400 dark:hover:border-zinc-500 has-[:checked]:border-zinc-900 dark:has-[:checked]:border-white has-[:checked]:ring-1 has-[:checked]:ring-zinc-900 dark:has-[:checked]:ring-white transition-all">
                            <input 
                                type="radio" 
                                name="is_featured_image_wide" 
                                value="1" 
                                {{ old('is_featured_image_wide', $post?->is_featured_image_wide) ? 'checked' : '' }}
                                class="text-zinc-900 focus:ring-0 cursor-pointer"
                            >
                            <div class="min-w-0">
                                <span class="font-bold text-xs text-zinc-900 dark:text-zinc-100 block truncate">Lebar Penuh (Header)</span>
                                <span class="text-[10px] text-zinc-400 block truncate">Sejajar navbar (1024px)</span>
                            </div>
                        </label>
                    </div>
                    @error('is_featured_image_wide') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @error('featured_image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        @error('featured_image_file') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div class="space-y-2">
        <label for="tag-input" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
            Tag & Topik Artikel <span class="text-zinc-400 font-normal">(Tekan Enter atau tanda koma untuk menambah)</span>
        </label>
        
        
        <div 
            id="tag-container" 
            class="min-h-[46px] p-2 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs flex flex-wrap items-center gap-1.5 focus-within:border-zinc-900 dark:focus-within:border-white transition-colors"
        >
            
            <input 
                type="text" 
                id="tag-input" 
                class="bg-transparent text-sm text-zinc-900 dark:text-white focus:outline-none flex-grow min-w-[140px] px-2 py-1"
                placeholder="Ketik tag lalu tekan Enter..."
            >
        </div>

        
        <input 
            type="hidden" 
            name="tags" 
            id="tags-hidden" 
            value="{{ old('tags', is_array($post?->tags_list) ? implode(', ', $post->tags_list) : ($post?->tags ?? '')) }}"
        >

        
        <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
            <span class="text-zinc-400 text-[11px] font-medium mr-1 flex items-center gap-1">
                <i class="ph ph-tag text-xs"></i> Saran Tag Tersimpan:
            </span>
            @foreach($activeSuggestedTags as $suggestedTag)
                <button 
                    type="button" 
                    class="quick-tag-btn px-2.5 py-0.5 rounded-full bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer"
                    data-tag="{{ $suggestedTag }}"
                >
                    + {{ $suggestedTag }}
                </button>
            @endforeach
        </div>
        @error('tags') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div>
        <label for="excerpt" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
            Ringkasan / Excerpt (Opsional)
        </label>
        <textarea 
            name="excerpt" 
            id="excerpt" 
            rows="2" 
            class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
            placeholder="Ringkasan singkat artikel untuk ditampilkan di beranda dan meta SEO. Jika dikosongkan, sistem otomatis mengambil intisari konten."
        >{{ old('excerpt', $post?->excerpt) }}</textarea>
        @error('excerpt') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div class="space-y-2">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
                Isi Konten Artikel <span class="text-red-500">*</span>
            </label>

            
            <div class="flex items-center border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 p-0.5 rounded-xs text-xs font-medium self-end sm:self-auto">
                <button 
                    type="button" 
                    id="btn-mode-compose" 
                    class="flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer"
                    title="Beralih ke mode visual editor (WYSIWYG)"
                >
                    <i class="ph ph-pen-nib"></i> Mode Compose
                </button>
                <button 
                    type="button" 
                    id="btn-mode-html" 
                    class="flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer"
                    title="Beralih ke mode kode HTML mentah"
                >
                    <i class="ph ph-code"></i> Mode HTML
                </button>
            </div>
        </div>

        
        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xs overflow-hidden shadow-xs focus-within:border-zinc-900 dark:focus-within:border-zinc-400 transition-colors">
            
            
            <div id="editor-toolbar" class="bg-zinc-100/90 dark:bg-zinc-800/90 border-b border-zinc-200 dark:border-zinc-700 p-2 flex flex-wrap items-center gap-1 text-zinc-700 dark:text-zinc-300">
                
                
                <button type="button" data-cmd="formatBlock" data-val="<h2>" class="toolbar-btn px-2.5 py-1 text-xs font-bold rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Judul Bagian H2 (Otomatis masuk TOC)">
                    H2
                </button>
                <button type="button" data-cmd="formatBlock" data-val="<h3>" class="toolbar-btn px-2.5 py-1 text-xs font-bold rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Sub-Judul H3">
                    H3
                </button>
                <button type="button" data-cmd="formatBlock" data-val="<p>" class="toolbar-btn px-2 py-1 text-xs font-semibold rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Paragraf Normal">
                    P
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                
                <button type="button" data-cmd="bold" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Tebal (Ctrl+B)">
                    <i class="ph ph-text-b font-bold text-base"></i>
                </button>
                <button type="button" data-cmd="italic" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Miring (Ctrl+I)">
                    <i class="ph ph-text-italic text-base"></i>
                </button>
                <button type="button" data-cmd="underline" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Garis Bawah (Ctrl+U)">
                    <i class="ph ph-text-underline text-base"></i>
                </button>
                <button type="button" data-cmd="strikeThrough" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Coret">
                    <i class="ph ph-text-strikethrough text-base"></i>
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                
                <button type="button" data-cmd="insertUnorderedList" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Daftar Poin (Bullet List)">
                    <i class="ph ph-list-bullets text-base"></i>
                </button>
                <button type="button" data-cmd="insertOrderedList" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Daftar Angka (Numbered List)">
                    <i class="ph ph-list-numbers text-base"></i>
                </button>
                <button type="button" id="btn-insert-quote" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Kutipan / Blockquote">
                    <i class="ph ph-quotes text-base"></i>
                </button>
                <button type="button" id="btn-insert-code" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Blok Kode Pemrograman">
                    <i class="ph ph-code-block text-base"></i>
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                
                <button type="button" id="btn-insert-link" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Sisipkan Tautan Link">
                    <i class="ph ph-link text-base"></i>
                </button>
                <button type="button" id="btn-insert-table" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Sisipkan Tabel Editorial">
                    <i class="ph ph-table text-base"></i>
                </button>
                <button type="button" data-cmd="insertHorizontalRule" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Garis Pemisah (Horizontal Rule)">
                    <i class="ph ph-minus text-base"></i>
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                
                <button 
                    type="button" 
                    id="btn-open-image-modal" 
                    class="flex items-center gap-1.5 px-3 py-1 bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 rounded-xs text-xs font-bold hover:opacity-90 transition-opacity cursor-pointer shadow-xs ml-auto sm:ml-0"
                    title="Unggah gambar dari komputer dan sisipkan ke dalam postingan"
                >
                    <i class="ph ph-image text-sm"></i> Sisipkan Gambar
                </button>
                <button 
                    type="button" 
                    id="btn-edit-img-caption" 
                    class="flex items-center gap-1.5 px-2.5 py-1 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 text-zinc-800 dark:text-zinc-200 rounded-xs text-xs font-semibold transition-colors cursor-pointer"
                    title="Beri atau ubah keterangan gambar (caption)"
                >
                    <i class="ph ph-chat-text text-sm"></i> Caption Gambar
                </button>

                
                <button type="button" data-cmd="removeFormat" class="toolbar-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-500 transition-colors ml-auto" title="Bersihkan Format Teks">
                    <i class="ph ph-paint-brush text-base"></i>
                </button>
            </div>

            @php
                $editorBody = \App\Models\Post::cleanTableHtml(\App\Models\Post::stripBacaJuga(old('body', $post?->body ?? '<p>Tulis paragraf pembuka artikel di sini...</p><h2>Judul Bagian</h2><p>Lanjutkan pembahasan materi secara mendalam...</p>')));
            @endphp

            
            <div 
                id="editor-compose" 
                contenteditable="true" 
                class="article-editorial font-serif leading-relaxed text-zinc-900 dark:text-zinc-100 min-h-[460px] max-h-[750px] overflow-y-auto p-6 sm:p-8 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-0"
                spellcheck="false"
            >{!! $editorBody !!}</div>

            
            <textarea 
                name="body" 
                id="editor-html" 
                rows="18" 
                required 
                class="hidden w-full font-mono text-xs sm:text-sm leading-relaxed p-6 bg-[#0c0e14] text-emerald-400 border-none focus:outline-none min-h-[460px] max-h-[750px] resize-y selection:bg-zinc-700 selection:text-white"
                spellcheck="false"
            >{{ $editorBody }}</textarea>

            
            <div class="bg-zinc-50 dark:bg-zinc-950 px-4 py-2 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 font-sans">
                <div class="flex items-center gap-3">
                    <span id="stat-words" class="font-medium">0 kata</span>
                    <span>&bull;</span>
                    <span id="stat-time" class="font-medium">1 menit baca</span>
                </div>
                <div class="text-[11px] text-zinc-400 hidden sm:block">
                    Mendukung Drag & Drop gambar langsung ke kanvas Compose
                </div>
            </div>

        </div>
        @error('body') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pt-6 border-t border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-wrap items-center gap-6">
            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-1.5">
                    Status Publikasi
                </label>
                <select 
                    name="status" 
                    id="status" 
                    class="bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs font-semibold text-zinc-900 dark:text-white cursor-pointer focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                >
                    <option value="draft" {{ old('status', $post?->status) === 'draft' ? 'selected' : '' }}>Simpan sebagai Draf</option>
                    <option value="published" {{ old('status', $post?->status ?? 'published') === 'published' ? 'selected' : '' }}>Publikasikan Langsung</option>
                </select>
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer mt-4 sm:mt-5 text-xs font-bold text-zinc-700 dark:text-zinc-300">
                <input 
                    type="checkbox" 
                    name="is_featured" 
                    value="1" 
                    {{ old('is_featured', $post?->is_featured) ? 'checked' : '' }} 
                    class="rounded-xs text-zinc-900 focus:ring-0 w-4 h-4 cursor-pointer"
                >
                <span>Jadikan Artikel Sorotan Utama (Featured)</span>
            </label>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a 
                href="{{ route('admin.posts.index') }}" 
                class="px-5 py-2.5 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors"
            >
                Batal
            </a>
            <button 
                type="submit" 
                id="btn-submit-post"
                class="px-7 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer"
            >
                {{ $post ? 'Perbarui Artikel' : 'Simpan & Publikasikan' }}
            </button>
        </div>
    </div>
</form>

<div 
    id="article-image-modal" 
    class="fixed inset-0 bg-zinc-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-lg w-full p-6 space-y-5 font-sans shadow-xl transform transition-all">
        
        <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
            <div class="flex items-center gap-2">
                <i class="ph ph-image text-xl text-zinc-900 dark:text-white"></i>
                <h3 class="font-bold text-base text-zinc-900 dark:text-white">Sisipkan Gambar ke Artikel</h3>
            </div>
            <button type="button" id="btn-close-img-modal" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white text-lg">
                <i class="ph ph-x"></i>
            </button>
        </div>

        
        <div>
            <div 
                id="modal-img-dropzone" 
                class="border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-600 rounded-xs p-6 text-center cursor-pointer transition-colors bg-zinc-50 dark:bg-zinc-950/40"
            >
                <input type="file" id="modal-img-file" accept="image/*" class="hidden">
                <i class="ph ph-cloud-arrow-up text-3xl text-zinc-400 mb-1.5 block"></i>
                <p class="text-xs sm:text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Pilih gambar dari komputer atau seret ke sini
                </p>
                <p class="text-[11px] text-zinc-400 mt-1">
                    Mendukung JPG, PNG, WEBP, GIF (Maks. 10MB)
                </p>
            </div>
            
            
            <div id="modal-img-preview-box" class="hidden mt-3 p-2 bg-zinc-50 dark:bg-zinc-800 rounded-xs flex items-center gap-3">
                <img id="modal-img-preview" src="" alt="Pratinjau" class="w-16 h-14 object-cover rounded-xs border border-zinc-200 dark:border-zinc-700">
                <div class="flex-grow min-w-0">
                    <p id="modal-img-name" class="text-xs font-bold text-zinc-800 dark:text-zinc-200 truncate"></p>
                    <p id="modal-img-size" class="text-[11px] text-zinc-400"></p>
                </div>
                <button type="button" id="modal-img-reset" class="text-zinc-400 hover:text-red-500 text-sm p-1">
                    <i class="ph ph-trash"></i>
                </button>
            </div>
        </div>

        
        <div class="space-y-3">
            <div>
                <label for="modal-img-caption" class="block text-xs font-bold text-zinc-600 dark:text-zinc-400 mb-1">
                    Keterangan Gambar (Figcaption)
                </label>
                <input 
                    type="text" 
                    id="modal-img-caption" 
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                    placeholder="Contoh: Ilustrasi tipografi sans-serif modern pada layar resolusi tinggi..."
                >
            </div>

            <div>
                <label for="modal-img-alt" class="block text-xs font-bold text-zinc-600 dark:text-zinc-400 mb-1">
                    Teks Alternatif (Alt Text SEO)
                </label>
                <input 
                    type="text" 
                    id="modal-img-alt" 
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                    placeholder="Contoh: Desain tipografi editorial web"
                >
            </div>
        </div>

        
        <div id="modal-img-error" class="hidden p-2.5 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/60 rounded-xs text-xs text-red-600 dark:text-red-400">
        </div>

        
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
            <button 
                type="button" 
                id="btn-modal-cancel" 
                class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors"
            >
                Batal
            </button>
            <button 
                type="button" 
                id="btn-modal-upload" 
                class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity flex items-center gap-2 cursor-pointer"
            >
                <span id="modal-upload-spinner" class="hidden"><i class="ph ph-spinner animate-spin"></i></span>
                <span id="modal-upload-text">Unggah & Sisipkan</span>
            </button>
        </div>

    </div>
</div>

<div 
    id="article-link-modal" 
    class="fixed inset-0 bg-zinc-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-md w-full p-6 space-y-4 font-sans shadow-xl transform transition-all">
        <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
            <div class="flex items-center gap-2">
                <i class="ph ph-link text-xl text-zinc-900 dark:text-white"></i>
                <h3 class="font-bold text-base text-zinc-900 dark:text-white" id="link-modal-title">Sisipkan Tautan Link</h3>
            </div>
            <button type="button" id="btn-close-link-modal" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white text-lg cursor-pointer">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <div class="space-y-3">
            <div>
                <label for="link-text-input" class="block text-xs font-bold text-zinc-600 dark:text-zinc-400 mb-1">
                    Teks Tautan (Anchor Text)
                </label>
                <input 
                    type="text" 
                    id="link-text-input" 
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                    placeholder="Contoh: Baca artikel selengkapnya"
                >
            </div>

            <div>
                <label for="link-url-input" class="block text-xs font-bold text-zinc-600 dark:text-zinc-400 mb-1">
                    Alamat URL Tautan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="link-url-input" 
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white"
                    placeholder="https://example.com atau /slug-artikel"
                >
                <p id="link-url-error" class="hidden text-[11px] text-red-500 mt-1">Harap masukkan alamat URL tautan.</p>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input 
                    type="checkbox" 
                    id="link-target-blank" 
                    class="rounded-xs border-zinc-300 dark:border-zinc-700 text-zinc-900 focus:ring-0 cursor-pointer"
                    checked
                >
                <label for="link-target-blank" class="text-xs text-zinc-700 dark:text-zinc-300 cursor-pointer select-none">
                    Buka tautan di tab baru (target="_blank")
                </label>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800 pt-4">
            <button 
                type="button" 
                id="btn-remove-link" 
                class="hidden text-xs text-red-500 hover:text-red-600 hover:underline flex items-center gap-1 cursor-pointer font-medium"
            >
                <i class="ph ph-link-break text-sm"></i> Hapus Tautan
            </button>
            <div class="flex items-center gap-2 ml-auto">
                <button 
                    type="button" 
                    id="btn-cancel-link" 
                    class="px-4 py-2 border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-700 dark:text-zinc-300 rounded-xs hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer transition-colors"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    id="btn-save-link" 
                    class="px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer"
                >
                    Terapkan Tautan
                </button>
            </div>
        </div>
    </div>
</div>

<div 
    id="editor-link-bubble" 
    class="hidden fixed z-50 bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 px-3 py-1.5 rounded-md shadow-2xl flex items-center gap-2.5 text-xs font-sans border border-zinc-700 dark:border-zinc-300 transition-opacity"
>
    <a id="bubble-link-url" href="#" target="_blank" rel="noopener noreferrer" class="max-w-[220px] truncate underline hover:opacity-80 flex items-center gap-1.5 font-medium text-sky-400 dark:text-blue-600" title="Buka tautan di tab baru">
        <i class="ph ph-arrow-square-out text-sm shrink-0"></i>
        <span id="bubble-link-text">https://...</span>
    </a>
    <span class="text-zinc-500 dark:text-zinc-400 select-none">|</span>
    <button type="button" id="bubble-link-edit" class="hover:underline flex items-center gap-1 font-semibold text-zinc-200 dark:text-zinc-800 cursor-pointer" title="Edit Tautan">
        <i class="ph ph-pencil-simple text-sm"></i> Edit
    </button>
    <button type="button" id="bubble-link-unlink" class="hover:text-red-400 dark:hover:text-red-600 flex items-center gap-1 font-semibold text-zinc-300 dark:text-zinc-700 cursor-pointer ml-1" title="Lepas / Hapus Tautan">
        <i class="ph ph-link-break text-sm"></i> Hapus
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const uploadImageUrl = '{{ route("admin.posts.upload-image") }}';

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    let isSlugManual = Boolean(slugInput.value.trim());

    slugInput.addEventListener('input', () => {
        isSlugManual = Boolean(slugInput.value.trim());
    });

    titleInput.addEventListener('input', () => {
        if (!isSlugManual) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    const tabUpload = document.getElementById('tab-feat-upload');
    const tabUrl = document.getElementById('tab-feat-url');
    const uploadBox = document.getElementById('feat-upload-box');
    const urlBox = document.getElementById('feat-url-box');
    const dropzone = document.getElementById('feat-dropzone');
    const featFileInput = document.getElementById('featured_image_file');
    const featUrlInput = document.getElementById('featured_image');
    const featPreviewWrapper = document.getElementById('feat-preview-wrapper');
    const featPreviewImg = document.getElementById('feat-preview-img');
    const featPreviewTitle = document.getElementById('feat-preview-title');
    const featPreviewSize = document.getElementById('feat-preview-size');
    const btnRemoveFeat = document.getElementById('btn-remove-feat-img');
    const removeFeatHidden = document.getElementById('remove_featured_image');

    tabUpload.addEventListener('click', () => {
        tabUpload.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs";
        tabUrl.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white";
        uploadBox.classList.remove('hidden');
        urlBox.classList.add('hidden');
    });

    tabUrl.addEventListener('click', () => {
        tabUrl.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs";
        tabUpload.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white";
        urlBox.classList.remove('hidden');
        uploadBox.classList.add('hidden');
    });

    dropzone.addEventListener('click', () => featFileInput.click());

    featFileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                featPreviewImg.src = e.target.result;
                featPreviewTitle.textContent = file.name;
                featPreviewSize.textContent = `${(file.size / 1024).toFixed(1)} KB (File Siap Diunggah)`;
                featPreviewWrapper.classList.remove('hidden');
                removeFeatHidden.value = "0";
            };
            reader.readAsDataURL(file);
        }
    });

    featUrlInput.addEventListener('input', function () {
        const val = this.value.trim();
        if (val) {
            featPreviewImg.src = val;
            featPreviewTitle.textContent = 'Gambar Tautan URL';
            featPreviewSize.textContent = val;
            featPreviewWrapper.classList.remove('hidden');
            removeFeatHidden.value = "0";
        } else if (!featFileInput.files.length) {
            featPreviewWrapper.classList.add('hidden');
        }
    });

    btnRemoveFeat.addEventListener('click', () => {
        featFileInput.value = '';
        featUrlInput.value = '';
        featPreviewImg.src = '';
        featPreviewWrapper.classList.add('hidden');
        removeFeatHidden.value = "1";
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('border-zinc-900', 'dark:border-white');
        });
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('border-zinc-900', 'dark:border-white');
        });
    });
    dropzone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt.files && dt.files.length) {
            featFileInput.files = dt.files;
            featFileInput.dispatchEvent(new Event('change'));
        }
    });

    const tagContainer = document.getElementById('tag-container');
    const tagInput = document.getElementById('tag-input');
    const tagsHidden = document.getElementById('tags-hidden');
    let tagsList = [];

    if (tagsHidden.value.trim()) {
        tagsList = tagsHidden.value.split(',').map(t => t.trim()).filter(Boolean);
    }

    function renderTags() {
        tagContainer.querySelectorAll('.tag-chip').forEach(el => el.remove());

        tagsList.forEach((tag, idx) => {
            const chip = document.createElement('span');
            chip.className = 'tag-chip inline-flex items-center gap-1.5 px-3 py-1 bg-zinc-200/80 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 text-xs font-semibold rounded-full border border-zinc-300 dark:border-zinc-700 transition-all';
            chip.innerHTML = `
                <span>#${tag}</span>
                <button type="button" class="remove-tag text-zinc-400 hover:text-red-500 font-bold ml-0.5 leading-none cursor-pointer" data-index="${idx}">&times;</button>
            `;
            tagContainer.insertBefore(chip, tagInput);
        });

        tagsHidden.value = tagsList.join(', ');
    }

    function addTag(tagText) {
        const clean = tagText.replace(/^[#,\s]+|[,\s]+$/g, '').trim();
        if (clean && !tagsList.includes(clean)) {
            tagsList.push(clean);
            renderTags();
        }
        tagInput.value = '';
    }

    tagInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(tagInput.value);
        } else if (e.key === 'Backspace' && !tagInput.value && tagsList.length) {
            tagsList.pop();
            renderTags();
        }
    });

    tagInput.addEventListener('blur', () => {
        if (tagInput.value.trim()) {
            addTag(tagInput.value);
        }
    });

    tagContainer.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.remove-tag');
        if (removeBtn) {
            const index = parseInt(removeBtn.dataset.index, 10);
            tagsList.splice(index, 1);
            renderTags();
        } else {
            tagInput.focus();
        }
    });

    document.querySelectorAll('.quick-tag-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            addTag(btn.dataset.tag);
        });
    });

    renderTags();

    const btnModeCompose = document.getElementById('btn-mode-compose');
    const btnModeHtml = document.getElementById('btn-mode-html');
    const editorToolbar = document.getElementById('editor-toolbar');
    const editorCompose = document.getElementById('editor-compose');
    const editorHtml = document.getElementById('editor-html');
    const statWords = document.getElementById('stat-words');
    const statTime = document.getElementById('stat-time');
    let currentMode = 'compose'; // 'compose' | 'html'

    function formatHTMLString(html) {
        let formatted = '';
        let indent = '';
        const tab = '    ';
        const isCloseTag = /^\/\w/;
        const isOpenTag = new RegExp('^<{0,1}\\w[^>]*[^/]$');
        html.split(/>\s*</).forEach(node => {
            if (isCloseTag.test(node)) indent = indent.substring(tab.length);
            formatted += indent + '<' + node + '>\r\n';
            if (isOpenTag.test(node) && !node.startsWith('input') && !node.startsWith('img') && !node.startsWith('hr') && !node.startsWith('br')) {
                indent += tab;
            }
        });
        return formatted.substring(1, formatted.length - 3);
    }

    function sanitizeTableHtml(html) {
        if (!html || !html.includes('<table')) return html;
        const temp = document.createElement('div');
        temp.innerHTML = html;

        temp.querySelectorAll('.overflow-x-auto, .table-scroll-container, .article-table-wrapper').forEach(wrapper => {
            const table = wrapper.querySelector('table');
            if (table && wrapper.children.length === 1) {
                wrapper.replaceWith(table);
            }
        });

        const tableTags = ['table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'colgroup', 'col', 'caption'];
        tableTags.forEach(tag => {
            temp.querySelectorAll(tag).forEach(el => {
                const colspan = el.getAttribute('colspan');
                const rowspan = el.getAttribute('rowspan');
                const scope = el.getAttribute('scope');
                while (el.attributes.length > 0) {
                    el.removeAttribute(el.attributes[0].name);
                }
                if (colspan) el.setAttribute('colspan', colspan);
                if (rowspan) el.setAttribute('rowspan', rowspan);
                if (scope) el.setAttribute('scope', scope);
            });
        });

        return temp.innerHTML;
    }

    function switchMode(newMode) {
        if (newMode === currentMode) return;

        if (newMode === 'html') {
            editorHtml.value = sanitizeTableHtml(editorCompose.innerHTML.trim());
            editorCompose.classList.add('hidden');
            editorHtml.classList.remove('hidden');
            editorToolbar.classList.add('opacity-40', 'pointer-events-none');

            btnModeHtml.className = "flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer";
            btnModeCompose.className = "flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer";
            currentMode = 'html';
        } else {
            editorCompose.innerHTML = editorHtml.value.trim();
            editorHtml.classList.add('hidden');
            editorCompose.classList.remove('hidden');
            editorToolbar.classList.remove('opacity-40', 'pointer-events-none');

            btnModeCompose.className = "flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer";
            btnModeHtml.className = "flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer";
            currentMode = 'compose';
        }
        updateStats();
    }

    btnModeCompose.addEventListener('click', () => switchMode('compose'));
    btnModeHtml.addEventListener('click', () => switchMode('html'));

    document.querySelectorAll('.toolbar-btn[data-cmd]').forEach(btn => {
        btn.addEventListener('click', function () {
            const cmd = this.dataset.cmd;
            const val = this.dataset.val || null;
            document.execCommand(cmd, false, val);
            editorCompose.focus();
            updateStats();
        });
    });

    document.getElementById('btn-insert-quote').addEventListener('click', () => {
        const quoteHtml = `<blockquote class="border-l-2 border-zinc-900 dark:border-white pl-4 italic my-4"><p>Tulis kutipan menarik di sini...</p><cite class="not-italic text-xs text-zinc-500 font-sans block mt-1">— Nama Tokoh</cite></blockquote><p></p>`;
        document.execCommand('insertHTML', false, quoteHtml);
        editorCompose.focus();
        updateStats();
    });

    document.getElementById('btn-insert-code').addEventListener('click', () => {
        const codeHtml = `<pre class="bg-zinc-900 text-zinc-100 p-4 rounded-xs my-4 overflow-x-auto font-mono text-sm leading-relaxed"><code class="language-javascript">// Tulis cuplikan kode di sini\nconsole.log("Hello Randi");</code></pre><p></p>`;
        document.execCommand('insertHTML', false, codeHtml);
        editorCompose.focus();
        updateStats();
    });

    document.getElementById('btn-insert-table').addEventListener('click', () => {
        const tableHtml = `
<table>
    <thead>
        <tr>
            <th>Kolom 1</th>
            <th>Kolom 2</th>
            <th>Kolom 3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data Baris 1</td>
            <td>Nilai A</td>
            <td>Keterangan X</td>
        </tr>
        <tr>
            <td>Data Baris 2</td>
            <td>Nilai B</td>
            <td>Keterangan Y</td>
        </tr>
    </tbody>
</table>
<p></p>`;
        document.execCommand('insertHTML', false, tableHtml);
        editorCompose.focus();
        updateStats();
    });

    const btnInsertLink = document.getElementById('btn-insert-link');
    const linkModal = document.getElementById('article-link-modal');
    const btnCloseLinkModal = document.getElementById('btn-close-link-modal');
    const btnCancelLink = document.getElementById('btn-cancel-link');
    const btnSaveLink = document.getElementById('btn-save-link');
    const btnRemoveLink = document.getElementById('btn-remove-link');
    const linkTextInput = document.getElementById('link-text-input');
    const linkUrlInput = document.getElementById('link-url-input');
    const linkTargetBlank = document.getElementById('link-target-blank');
    const linkModalTitle = document.getElementById('link-modal-title');
    const linkUrlError = document.getElementById('link-url-error');

    const editorLinkBubble = document.getElementById('editor-link-bubble');
    const bubbleLinkUrl = document.getElementById('bubble-link-url');
    const bubbleLinkText = document.getElementById('bubble-link-text');
    const bubbleLinkEdit = document.getElementById('bubble-link-edit');
    const bubbleLinkUnlink = document.getElementById('bubble-link-unlink');

    let activeEditingLink = null;
    let savedLinkRange = null;
    let currentBubbleLink = null;

    function openLinkModal(linkEl = null) {
        if (linkUrlError) linkUrlError.classList.add('hidden');

        if (currentMode === 'compose') {
            const sel = window.getSelection();
            if (sel && sel.rangeCount > 0) {
                savedLinkRange = sel.getRangeAt(0).cloneRange();
            } else {
                savedLinkRange = null;
            }

            if (!linkEl && sel && sel.anchorNode) {
                let node = sel.anchorNode;
                while (node && node !== editorCompose) {
                    if (node.nodeType === Node.ELEMENT_NODE && node.tagName.toLowerCase() === 'a') {
                        linkEl = node;
                        break;
                    }
                    node = node.parentNode;
                }
            }
        } else {
            const start = editorHtml.selectionStart;
            const end = editorHtml.selectionEnd;
            const selectedText = editorHtml.value.substring(start, end);
            linkTextInput.value = selectedText;
        }

        activeEditingLink = linkEl;

        if (linkEl) {
            linkModalTitle.textContent = 'Edit Tautan';
            linkTextInput.value = linkEl.textContent;
            linkUrlInput.value = linkEl.getAttribute('href') || '';
            linkTargetBlank.checked = linkEl.getAttribute('target') === '_blank';
            btnRemoveLink.classList.remove('hidden');
        } else {
            linkModalTitle.textContent = 'Sisipkan Tautan Link';
            if (currentMode === 'compose') {
                const selectedText = savedLinkRange ? savedLinkRange.toString() : '';
                linkTextInput.value = selectedText;
            }
            linkUrlInput.value = '';
            linkTargetBlank.checked = true;
            btnRemoveLink.classList.add('hidden');
        }

        hideLinkBubble();
        linkModal.classList.remove('hidden');
        setTimeout(() => {
            if (!linkTextInput.value.trim()) {
                linkTextInput.focus();
            } else {
                linkUrlInput.focus();
            }
        }, 60);
    }

    function closeLinkModal() {
        linkModal.classList.add('hidden');
        activeEditingLink = null;
        if (linkUrlError) linkUrlError.classList.add('hidden');
    }

    function saveLink() {
        let url = linkUrlInput.value.trim();
        if (!url) {
            if (linkUrlError) linkUrlError.classList.remove('hidden');
            linkUrlInput.focus();
            return;
        }

        if (!/^([a-z]+:|\/|#|\?)/i.test(url)) {
            url = 'https://' + url;
        }

        const text = linkTextInput.value.trim() || url;
        const isBlank = linkTargetBlank.checked;

        if (currentMode === 'compose') {
            if (activeEditingLink) {
                activeEditingLink.setAttribute('href', url);
                activeEditingLink.textContent = text;
                if (isBlank) {
                    activeEditingLink.setAttribute('target', '_blank');
                    activeEditingLink.setAttribute('rel', 'noopener noreferrer');
                } else {
                    activeEditingLink.removeAttribute('target');
                    activeEditingLink.removeAttribute('rel');
                }
            } else {
                const a = document.createElement('a');
                a.setAttribute('href', url);
                a.textContent = text;
                if (isBlank) {
                    a.setAttribute('target', '_blank');
                    a.setAttribute('rel', 'noopener noreferrer');
                }

                editorCompose.focus();
                if (savedLinkRange) {
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(savedLinkRange);

                    savedLinkRange.deleteContents();
                    savedLinkRange.insertNode(a);

                    const newRange = document.createRange();
                    newRange.setStartAfter(a);
                    newRange.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(newRange);
                } else {
                    editorCompose.appendChild(a);
                }
            }

            editorCompose.focus();
        } else {
            const targetAttr = isBlank ? ' target="_blank" rel="noopener noreferrer"' : '';
            const tagString = `<a href="${url}"${targetAttr}>${text}</a>`;
            const start = editorHtml.selectionStart;
            const end = editorHtml.selectionEnd;
            editorHtml.setRangeText(tagString, start, end, 'end');
            editorHtml.focus();
        }

        closeLinkModal();
        updateStats();
    }

    function removeLink() {
        if (activeEditingLink) {
            const textNode = document.createTextNode(activeEditingLink.textContent);
            activeEditingLink.replaceWith(textNode);
            closeLinkModal();
            hideLinkBubble();
            editorCompose.focus();
            updateStats();
        }
    }

    function showLinkBubble(linkEl) {
        currentBubbleLink = linkEl;
        const href = linkEl.getAttribute('href') || '#';
        bubbleLinkUrl.href = href;
        bubbleLinkText.textContent = href;

        const rect = linkEl.getBoundingClientRect();
        editorLinkBubble.classList.remove('hidden');

        const bubbleHeight = 36;
        let top = rect.bottom + 6;
        if (top + bubbleHeight > window.innerHeight) {
            top = rect.top - bubbleHeight - 6;
        }
        let left = rect.left;
        if (left + 300 > window.innerWidth) {
            left = window.innerWidth - 310;
        }
        if (left < 10) left = 10;

        editorLinkBubble.style.top = `${top}px`;
        editorLinkBubble.style.left = `${left}px`;
    }

    function hideLinkBubble() {
        editorLinkBubble.classList.add('hidden');
        currentBubbleLink = null;
    }

    editorCompose.addEventListener('click', (e) => {
        const linkEl = e.target.closest('a');
        if (linkEl && editorCompose.contains(linkEl)) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                const href = linkEl.getAttribute('href');
                if (href) {
                    window.open(href, '_blank', 'noopener,noreferrer');
                }
                return;
            }

            showLinkBubble(linkEl);
            return;
        }

        hideLinkBubble();
    });

    editorCompose.addEventListener('scroll', hideLinkBubble);
    window.addEventListener('scroll', hideLinkBubble);

    bubbleLinkEdit?.addEventListener('click', () => {
        if (currentBubbleLink) {
            openLinkModal(currentBubbleLink);
        }
    });

    bubbleLinkUnlink?.addEventListener('click', () => {
        if (currentBubbleLink) {
            const textNode = document.createTextNode(currentBubbleLink.textContent);
            currentBubbleLink.replaceWith(textNode);
            hideLinkBubble();
            editorCompose.focus();
            updateStats();
        }
    });

    btnInsertLink?.addEventListener('click', () => openLinkModal());
    btnCloseLinkModal?.addEventListener('click', closeLinkModal);
    btnCancelLink?.addEventListener('click', closeLinkModal);
    btnSaveLink?.addEventListener('click', saveLink);
    btnRemoveLink?.addEventListener('click', removeLink);

    linkUrlInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveLink();
        }
    });

    linkTextInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveLink();
        }
    });

    linkModal?.addEventListener('click', (e) => {
        if (e.target === linkModal) {
            closeLinkModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
            const active = document.activeElement;
            if (active === editorCompose || active === editorHtml || editorCompose.contains(active)) {
                e.preventDefault();
                openLinkModal();
            }
        }
        if (e.key === 'Escape') {
            if (linkModal && !linkModal.classList.contains('hidden')) {
                closeLinkModal();
            }
            hideLinkBubble();
        }
    });

    function updateStats() {
        const text = currentMode === 'compose' ? editorCompose.innerText : editorHtml.value.replace(/<[^>]*>/g, ' ');
        const words = text.trim().split(/\s+/).filter(Boolean).length;
        const readTime = Math.max(1, Math.ceil(words / 200));

        statWords.textContent = `${words} kata`;
        statTime.textContent = `~${readTime} menit baca`;
    }

    editorCompose.addEventListener('input', updateStats);
    editorHtml.addEventListener('input', updateStats);
    updateStats();

    const imgModal = document.getElementById('article-image-modal');
    const btnOpenImgModal = document.getElementById('btn-open-image-modal');
    const btnCloseImgModal = document.getElementById('btn-close-img-modal');
    const btnCancelModal = document.getElementById('btn-modal-cancel');
    const modalDropzone = document.getElementById('modal-img-dropzone');
    const modalFileInput = document.getElementById('modal-img-file');
    const modalPreviewBox = document.getElementById('modal-img-preview-box');
    const modalPreviewImg = document.getElementById('modal-img-preview');
    const modalImgName = document.getElementById('modal-img-name');
    const modalImgSize = document.getElementById('modal-img-size');
    const modalResetBtn = document.getElementById('modal-img-reset');
    const modalCaptionInput = document.getElementById('modal-img-caption');
    const modalAltInput = document.getElementById('modal-img-alt');
    const modalErrorAlert = document.getElementById('modal-img-error');
    const btnModalUpload = document.getElementById('btn-modal-upload');
    const modalSpinner = document.getElementById('modal-upload-spinner');
    const modalUploadText = document.getElementById('modal-upload-text');

    let savedSelectionRange = null;

    function saveSelection() {
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
            savedSelectionRange = sel.getRangeAt(0);
        }
    }

    function restoreSelection() {
        if (savedSelectionRange) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedSelectionRange);
        }
    }

    function openImageModal() {
        saveSelection();
        imgModal.classList.remove('hidden');
        resetModalState();
    }

    function closeImageModal() {
        imgModal.classList.add('hidden');
        resetModalState();
        editorCompose.focus();
    }

    function resetModalState() {
        modalFileInput.value = '';
        modalPreviewBox.classList.add('hidden');
        modalDropzone.classList.remove('hidden');
        modalCaptionInput.value = '';
        modalAltInput.value = '';
        modalErrorAlert.classList.add('hidden');
        modalErrorAlert.textContent = '';
        btnModalUpload.disabled = false;
        modalSpinner.classList.add('hidden');
        modalUploadText.textContent = 'Unggah & Sisipkan';
    }

    btnOpenImgModal.addEventListener('click', openImageModal);
    btnCloseImgModal.addEventListener('click', closeImageModal);
    btnCancelModal.addEventListener('click', closeImageModal);

    modalDropzone.addEventListener('click', () => modalFileInput.click());

    modalFileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                modalPreviewImg.src = e.target.result;
                modalImgName.textContent = file.name;
                modalImgSize.textContent = `${(file.size / 1024).toFixed(1)} KB`;
                modalDropzone.classList.add('hidden');
                modalPreviewBox.classList.remove('hidden');
                
                if (!modalAltInput.value) {
                    modalAltInput.value = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, " ");
                }
            };
            reader.readAsDataURL(file);
        }
    });

    modalResetBtn.addEventListener('click', () => {
        modalFileInput.value = '';
        modalPreviewBox.classList.add('hidden');
        modalDropzone.classList.remove('hidden');
    });

    btnModalUpload.addEventListener('click', async () => {
        if (!modalFileInput.files || !modalFileInput.files[0]) {
            modalErrorAlert.textContent = 'Silakan pilih berkas gambar terlebih dahulu.';
            modalErrorAlert.classList.remove('hidden');
            return;
        }

        const file = modalFileInput.files[0];
        const caption = modalCaptionInput.value.trim();
        const altText = modalAltInput.value.trim() || 'Gambar Artikel';

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', csrfToken);

        btnModalUpload.disabled = true;
        modalSpinner.classList.remove('hidden');
        modalUploadText.textContent = 'Mengunggah...';
        modalErrorAlert.classList.add('hidden');

        try {
            const res = await fetch(uploadImageUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await res.json();

            if (res.ok && data.success) {
                const figureHtml = `
<figure class="my-8">
    <img src="${data.url}" alt="${altText}" class="w-full rounded-xl object-cover max-h-[500px] shadow-sm">
    <figcaption contenteditable="true" class="text-center text-xs text-zinc-500 dark:text-zinc-400 mt-2 italic font-sans" data-placeholder="Tulis keterangan gambar di sini...">${caption || ''}</figcaption>
</figure>
<p></p>`;

                closeImageModal();

                if (currentMode === 'compose') {
                    editorCompose.focus();
                    restoreSelection();
                    document.execCommand('insertHTML', false, figureHtml);
                } else {
                    editorHtml.value += "\n" + figureHtml + "\n";
                }
                updateStats();
            } else {
                modalErrorAlert.textContent = data.message || 'Gagal mengunggah gambar. Silakan periksa ukuran dan format gambar.';
                modalErrorAlert.classList.remove('hidden');
                btnModalUpload.disabled = false;
                modalSpinner.classList.add('hidden');
                modalUploadText.textContent = 'Unggah & Sisipkan';
            }
        } catch (err) {
            modalErrorAlert.textContent = 'Terjadi kesalahan jaringan saat mengunggah gambar.';
            modalErrorAlert.classList.remove('hidden');
            btnModalUpload.disabled = false;
            modalSpinner.classList.add('hidden');
            modalUploadText.textContent = 'Unggah & Sisipkan';
        }
    });

    async function directUploadAndInsert(file) {
        if (!file || !file.type.startsWith('image/')) return;

        const altText = file.name ? file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, " ") : 'Gambar Artikel';
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', csrfToken);

        const tempLoading = document.createElement('div');
        tempLoading.className = "my-4 p-4 text-xs font-semibold text-zinc-500 bg-zinc-100 dark:bg-zinc-800 rounded flex items-center gap-2 font-sans";
        tempLoading.innerHTML = `<i class="ph ph-spinner animate-spin text-base"></i> Mengunggah gambar ke server...`;
        editorCompose.appendChild(tempLoading);

        try {
            const res = await fetch(uploadImageUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });
            const data = await res.json();
            tempLoading.remove();

            if (res.ok && data.success) {
                const figureHtml = `
<figure class="my-8">
    <img src="${data.url}" alt="${altText}" class="w-full rounded-xl object-cover max-h-[500px] shadow-sm">
    <figcaption contenteditable="true" class="text-center text-xs text-zinc-500 dark:text-zinc-400 mt-2 italic font-sans" data-placeholder="Tulis keterangan gambar di sini..."></figcaption>
</figure>
<p></p>`;
                editorCompose.focus();
                document.execCommand('insertHTML', false, figureHtml);
                updateStats();
            } else {
                alert(data.message || 'Gagal mengunggah gambar.');
            }
        } catch (err) {
            tempLoading.remove();
            alert('Terjadi kesalahan koneksi saat mengunggah gambar.');
        }
    }

    editorCompose.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    editorCompose.addEventListener('drop', (e) => {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
            const file = e.dataTransfer.files[0];
            if (file.type.startsWith('image/')) {
                e.preventDefault();
                directUploadAndInsert(file);
            }
        }
    });

    editorCompose.addEventListener('paste', (e) => {
        const items = (e.clipboardData || e.originalEvent?.clipboardData)?.items;
        if (items) {
            for (let item of items) {
                if (item.type && item.type.indexOf('image') === 0) {
                    e.preventDefault();
                    const file = item.getAsFile();
                    directUploadAndInsert(file);
                    break;
                }
            }
        }
    });

    let selectedEditorImg = null;

    editorCompose.addEventListener('click', (e) => {
        const img = e.target.closest('img');
        if (img) {
            if (selectedEditorImg && selectedEditorImg !== img) {
                selectedEditorImg.classList.remove('ring-2', 'ring-zinc-900', 'dark:ring-white');
            }
            selectedEditorImg = img;
            img.classList.add('ring-2', 'ring-zinc-900', 'dark:ring-white');

            let figure = img.closest('figure');
            if (!figure) {
                figure = document.createElement('figure');
                figure.className = 'my-8';
                img.parentNode.insertBefore(figure, img);
                figure.appendChild(img);
            }

            let figcaption = figure.querySelector('figcaption');
            if (!figcaption) {
                figcaption = document.createElement('figcaption');
                figcaption.contentEditable = 'true';
                figcaption.className = 'text-center text-xs text-zinc-500 dark:text-zinc-400 mt-2 italic font-sans';
                figcaption.setAttribute('data-placeholder', 'Tulis keterangan gambar di sini...');
                figure.appendChild(figcaption);
            }
        } else {
            if (selectedEditorImg && !e.target.closest('figcaption') && !e.target.closest('#btn-edit-img-caption')) {
                selectedEditorImg.classList.remove('ring-2', 'ring-zinc-900', 'dark:ring-white');
                selectedEditorImg = null;
            }
        }
    });

    const btnEditCaption = document.getElementById('btn-edit-img-caption');
    if (btnEditCaption) {
        btnEditCaption.addEventListener('click', () => {
            let targetImg = selectedEditorImg;
            if (!targetImg) {
                const sel = window.getSelection();
                if (sel && sel.anchorNode) {
                    const node = sel.anchorNode.nodeType === 3 ? sel.anchorNode.parentNode : sel.anchorNode;
                    targetImg = node.querySelector?.('img') || node.closest?.('figure')?.querySelector('img');
                }
            }

            if (!targetImg) {
                const allImgs = editorCompose.querySelectorAll('img');
                if (allImgs.length === 1) {
                    targetImg = allImgs[0];
                } else {
                    alert('Silakan klik gambar di dalam artikel terlebih dahulu, lalu klik tombol ini untuk memberi keterangan.');
                    return;
                }
            }

            let figure = targetImg.closest('figure');
            if (!figure) {
                figure = document.createElement('figure');
                figure.className = 'my-8';
                targetImg.parentNode.insertBefore(figure, targetImg);
                figure.appendChild(targetImg);
            }

            let figcaption = figure.querySelector('figcaption');
            if (!figcaption) {
                figcaption = document.createElement('figcaption');
                figcaption.contentEditable = 'true';
                figcaption.className = 'text-center text-xs text-zinc-500 dark:text-zinc-400 mt-2 italic font-sans';
                figcaption.setAttribute('data-placeholder', 'Tulis keterangan gambar di sini...');
                figure.appendChild(figcaption);
            }

            const currentCaption = figcaption.textContent.trim();
            const newCaption = prompt('Masukkan keterangan gambar (caption):', currentCaption);
            if (newCaption !== null) {
                figcaption.textContent = newCaption.trim();
                figcaption.focus();
                updateStats();
            }
        });
    }

    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const chip = cb.closest('.category-chip');
            const icon = chip?.querySelector('.chip-icon');
            if (cb.checked) {
                chip?.classList.remove('bg-white', 'dark:bg-zinc-900', 'text-zinc-700', 'dark:text-zinc-300', 'border-zinc-200', 'dark:border-zinc-700', 'hover:border-zinc-400', 'dark:hover:border-zinc-500');
                chip?.classList.add('bg-zinc-900', 'text-white', 'border-zinc-900', 'dark:bg-white', 'dark:text-zinc-900', 'dark:border-white', 'shadow-xs', 'font-semibold');
                if (icon) {
                    icon.classList.remove('ph-plus');
                    icon.classList.add('ph-check-bold');
                }
            } else {
                chip?.classList.remove('bg-zinc-900', 'text-white', 'border-zinc-900', 'dark:bg-white', 'dark:text-zinc-900', 'dark:border-white', 'shadow-xs', 'font-semibold');
                chip?.classList.add('bg-white', 'dark:bg-zinc-900', 'text-zinc-700', 'dark:text-zinc-300', 'border-zinc-200', 'dark:border-zinc-700', 'hover:border-zinc-400', 'dark:hover:border-zinc-500');
                if (icon) {
                    icon.classList.remove('ph-check-bold');
                    icon.classList.add('ph-plus');
                }
            }
        });
    });

    const postForm = document.getElementById('post-form');
    postForm.addEventListener('submit', function (e) {
        const checkedCats = document.querySelectorAll('.category-checkbox:checked');
        if (checkedCats.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu kategori untuk artikel ini.');
            return false;
        }

        if (currentMode === 'compose') {
            const clone = editorCompose.cloneNode(true);
            clone.querySelectorAll('img').forEach(img => {
                img.classList.remove('ring-2', 'ring-zinc-900', 'dark:ring-white');
            });
            clone.querySelectorAll('figcaption').forEach(fc => {
                fc.removeAttribute('contenteditable');
                fc.removeAttribute('data-placeholder');
                const txt = fc.textContent.trim();
                if (!txt || txt === 'Tulis keterangan gambar di sini...') {
                    fc.remove();
                }
            });
            editorHtml.value = sanitizeTableHtml(clone.innerHTML);
        } else {
            editorHtml.value = sanitizeTableHtml(editorHtml.value);
        }

        if (!editorHtml.value.trim() || editorHtml.value.trim() === '<p><br></p>') {
            e.preventDefault();
            alert('Konten artikel tidak boleh kosong.');
            editorCompose.focus();
            return false;
        }
    });

});
</script>
