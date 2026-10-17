@props([
    'page' => null,
    'action' => '',
    'method' => 'POST',
])

<form 
    action="{{ $action }}" 
    method="POST" 
    enctype="multipart/form-data"
    id="page-form"
    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 space-y-8 font-sans shadow-xs"
>
    @csrf
    @if(strtoupper($method) === 'PUT')
        @method('PUT')
    @endif

    
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2">
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                    Judul Halaman <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    required 
                    value="{{ old('title', $page?->title) }}"
                    class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-base text-zinc-900 dark:text-white font-medium focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                    placeholder="Contoh: Kebijakan Privasi & Perlindungan Data"
                >
                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            
            <div>
                <label for="is_published" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                    Status Publikasi
                </label>
                <select 
                    name="is_published" 
                    id="is_published" 
                    class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                >
                    <option value="1" {{ old('is_published', $page ? ($page->is_published ? '1' : '0') : '1') === '1' ? 'selected' : '' }}>
                        Diterbitkan (Publik)
                    </option>
                    <option value="0" {{ old('is_published', $page ? ($page->is_published ? '1' : '0') : '1') === '0' ? 'selected' : '' }}>
                        Draf (Tersimpan Pribadi)
                    </option>
                </select>
                @error('is_published') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
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
                    value="{{ old('slug', $page?->slug) }}"
                    class="w-full bg-transparent text-sm text-zinc-900 dark:text-white font-mono focus:outline-none"
                    placeholder="kebijakan-privasi"
                >
            </div>
            <p class="text-[11px] text-zinc-400 mt-1">
                Catatan: Halaman dapat diakses langsung tanpa awalan rute (contoh: <code class="font-mono text-zinc-600 dark:text-zinc-300">/term</code> atau <code class="font-mono text-zinc-600 dark:text-zinc-300">/kebijakan-layanan</code>).
            </p>
            @error('slug') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        
        <div>
            <label for="meta_description" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                Deskripsi Meta SEO (Opsional)
            </label>
            <textarea 
                name="meta_description" 
                id="meta_description" 
                rows="2" 
                class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                placeholder="Deskripsi singkat isi halaman untuk mesin pencari Google dan cuplikan media sosial..."
            >{{ old('meta_description', $page?->meta_description) }}</textarea>
            @error('meta_description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    
    <div class="space-y-4 pt-6 border-t border-zinc-200/80 dark:border-zinc-800">
        <div class="flex items-center justify-between">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
                    Gambar Utama Halaman (Featured Image)
                </label>
                <p class="text-[11px] text-zinc-400 mt-0.5">
                    Gambar sampul di bagian atas halaman informasi (opsional).
                </p>
            </div>

            
            <div class="flex items-center bg-zinc-200 dark:bg-zinc-800 p-0.5 rounded-xs text-xs font-medium">
                <button type="button" id="page-tab-feat-upload" class="px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer">
                    <i class="ph ph-upload-simple mr-1"></i> Unggah File
                </button>
                <button type="button" id="page-tab-feat-url" class="px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer">
                    <i class="ph ph-link mr-1"></i> Tautan URL
                </button>
            </div>
        </div>

        
        <div id="page-feat-upload-box" class="space-y-3">
            <div 
                id="page-feat-dropzone" 
                class="border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-600 rounded-xs p-6 text-center cursor-pointer transition-colors bg-zinc-50/50 dark:bg-zinc-800/40"
            >
                <input 
                    type="file" 
                    name="featured_image_file" 
                    id="page_featured_image_file" 
                    accept="image/*" 
                    class="hidden"
                >
                <i class="ph ph-image text-3xl text-zinc-400 mb-2 block"></i>
                <p class="text-xs sm:text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Klik untuk memilih gambar atau seret berkas ke sini
                </p>
                <p class="text-[11px] text-zinc-400 mt-1">
                    Mendukung JPG, PNG, WEBP, GIF (Maks. 10MB).
                </p>
            </div>
        </div>

        
        <div id="page-feat-url-box" class="hidden">
            <input 
                type="text" 
                name="featured_image" 
                id="page_featured_image" 
                value="{{ old('featured_image', $page?->featured_image) }}"
                class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-sm text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                placeholder="https://images.unsplash.com/photo-..."
            >
        </div>

        
        <input type="hidden" name="remove_featured_image" id="page_remove_featured_image" value="0">

        
        <div id="page-feat-preview-wrapper" class="{{ !empty(old('featured_image', $page?->featured_image)) ? '' : 'hidden' }} mt-3">
            <div class="flex items-center gap-4 p-3 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs">
                <img 
                    id="page-feat-preview-img" 
                    src="{{ old('featured_image', $page?->featured_image) ?: '' }}" 
                    alt="Pratinjau Gambar Utama" 
                    class="w-24 h-16 object-cover rounded-xs border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800"
                >
                <div class="flex-grow min-w-0">
                    <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200 truncate" id="page-feat-preview-title">
                        Gambar Utama Halaman
                    </p>
                    <p class="text-[11px] text-zinc-400 truncate" id="page-feat-preview-size">
                        {{ old('featured_image', $page?->featured_image) ? 'Tersimpan pada server' : 'Siap digunakan' }}
                    </p>
                </div>
                <button 
                    type="button" 
                    id="page-btn-remove-feat-img" 
                    class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold flex items-center gap-1 px-3 py-1.5 border border-red-200 dark:border-red-900/60 rounded-xs hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors cursor-pointer"
                >
                    <i class="ph ph-trash"></i> Hapus Gambar
                </button>
            </div>
        </div>

        
        <div class="pt-2">
            <label for="featured_image_caption" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                Keterangan Gambar (Caption) <span class="font-normal text-zinc-400 lowercase">(Opsional, tampil di bawah gambar)</span>
            </label>
            <input 
                type="text" 
                name="featured_image_caption" 
                id="featured_image_caption" 
                value="{{ old('featured_image_caption', $page?->featured_image_caption) }}"
                class="w-full bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                placeholder="Contoh: Ilustrasi ruang kerja dan privasi pengguna..."
            >
            @error('featured_image_caption') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        @error('featured_image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        @error('featured_image_file') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div class="space-y-2 pt-6 border-t border-zinc-200/80 dark:border-zinc-800">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400">
                Isi Konten Halaman <span class="text-red-500">*</span>
            </label>

            
            <div class="flex items-center border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 p-0.5 rounded-xs text-xs font-medium self-end sm:self-auto">
                <button 
                    type="button" 
                    id="page-btn-compose" 
                    class="flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer"
                    title="Beralih ke mode visual editor (WYSIWYG)"
                >
                    <i class="ph ph-pen-nib"></i> Mode Compose
                </button>
                <button 
                    type="button" 
                    id="page-btn-html" 
                    class="flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer"
                    title="Beralih ke mode kode HTML mentah"
                >
                    <i class="ph ph-code"></i> Mode HTML
                </button>
            </div>
        </div>

        
        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xs overflow-hidden shadow-xs focus-within:border-zinc-900 dark:focus-within:border-zinc-400 transition-colors">
            
            
            <div id="page-editor-toolbar" class="bg-zinc-100/90 dark:bg-zinc-800/90 border-b border-zinc-200 dark:border-zinc-700 p-2 flex flex-wrap items-center gap-1 text-zinc-700 dark:text-zinc-300">
                <button type="button" data-cmd="formatBlock" data-val="<h2>" class="page-tb-btn px-2.5 py-1 text-xs font-bold rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Judul Utama Bagian H2">
                    H2
                </button>
                <button type="button" data-cmd="formatBlock" data-val="<h3>" class="page-tb-btn px-2.5 py-1 text-xs font-bold rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Sub-Judul H3">
                    H3
                </button>
                <button type="button" data-cmd="formatBlock" data-val="<p>" class="page-tb-btn px-2.5 py-1 text-xs font-medium rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Paragraf Normal">
                    P
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                <button type="button" data-cmd="bold" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Tebal (Ctrl+B)">
                    <i class="ph ph-text-b text-base"></i>
                </button>
                <button type="button" data-cmd="italic" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Miring (Ctrl+I)">
                    <i class="ph ph-text-italic text-base"></i>
                </button>
                <button type="button" data-cmd="underline" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Garis Bawah (Ctrl+U)">
                    <i class="ph ph-text-underline text-base"></i>
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                <button type="button" data-cmd="insertUnorderedList" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Daftar Bullet">
                    <i class="ph ph-list-bullets text-base"></i>
                </button>
                <button type="button" data-cmd="insertOrderedList" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Daftar Nomor">
                    <i class="ph ph-list-numbers text-base"></i>
                </button>
                <button type="button" data-cmd="formatBlock" data-val="<blockquote>" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Kutipan Editorial">
                    <i class="ph ph-quotes text-base"></i>
                </button>
                <button type="button" data-cmd="insertHorizontalRule" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Garis Pemisah (Divider)">
                    <i class="ph ph-minus text-base"></i>
                </button>

                <div class="w-px h-5 bg-zinc-300 dark:bg-zinc-700 mx-1"></div>

                <button type="button" id="page-btn-link" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors" title="Sisipkan Tautan Link">
                    <i class="ph ph-link text-base"></i>
                </button>
                <button type="button" data-cmd="removeFormat" class="page-tb-btn p-1.5 rounded-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors ml-auto" title="Bersihkan Format Teks">
                    <i class="ph ph-text-align-justify text-base"></i>
                </button>
            </div>

            
            <div 
                id="page-editor-compose" 
                contenteditable="true" 
                class="min-h-[360px] p-6 sm:p-8 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 font-sans text-base leading-relaxed focus:outline-none prose prose-zinc dark:prose-invert max-w-none transition-colors"
                placeholder="Tuliskan isi halaman di sini..."
            >{!! old('body', $page?->body) !!}</div>

            
            <textarea 
                name="body" 
                id="page-editor-html" 
                rows="16" 
                required 
                class="hidden w-full p-6 sm:p-8 bg-zinc-900 text-zinc-100 font-mono text-xs leading-relaxed focus:outline-none border-none resize-y"
                placeholder="<p>Tuliskan kode HTML halaman di sini...</p>"
            >{{ old('body', $page?->body) }}</textarea>

        </div>

        
        <div class="flex items-center justify-between text-xs text-zinc-400 px-1 pt-1 font-mono">
            <div class="flex items-center gap-4">
                <span>Kata: <strong id="page-word-count" class="text-zinc-700 dark:text-zinc-300">0</strong></span>
                <span>Karakter: <strong id="page-char-count" class="text-zinc-700 dark:text-zinc-300">0</strong></span>
            </div>
            <div>
                <span>Estimasi Baca: <strong id="page-read-time" class="text-zinc-700 dark:text-zinc-300">1 mnt</strong></span>
            </div>
        </div>
        @error('body') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    
    <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-800">
        <a 
            href="{{ route('admin.pages.index') }}" 
            class="px-4 py-2 border border-zinc-300 dark:border-zinc-700 rounded-xs text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
        >
            Batal
        </a>

        <div class="flex items-center gap-3">
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
            >
                Simpan & Publikasikan
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const composeArea = document.getElementById('page-editor-compose');
    const htmlArea = document.getElementById('page-editor-html');
    const btnCompose = document.getElementById('page-btn-compose');
    const btnHtml = document.getElementById('page-btn-html');
    const toolbar = document.getElementById('page-editor-toolbar');
    const wordCountEl = document.getElementById('page-word-count');
    const charCountEl = document.getElementById('page-char-count');
    const readTimeEl = document.getElementById('page-read-time');
    const pageForm = document.getElementById('page-form');

    let currentMode = 'compose';
    let isSlugManual = {{ $page ? 'true' : 'false' }};

    titleInput?.addEventListener('input', () => {
        if (!isSlugManual && slugInput) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
        }
    });

    slugInput?.addEventListener('input', () => {
        isSlugManual = slugInput.value.trim().length > 0;
    });

    document.querySelectorAll('.page-tb-btn[data-cmd]').forEach(btn => {
        btn.addEventListener('click', () => {
            const cmd = btn.getAttribute('data-cmd');
            const val = btn.getAttribute('data-val') || null;
            document.execCommand(cmd, false, val);
            composeArea?.focus();
            syncContent();
        });
    });

    document.getElementById('page-btn-link')?.addEventListener('click', () => {
        const url = prompt('Masukkan URL tautan (contoh: https://domain.com atau /kontak):');
        if (url) {
            document.execCommand('createLink', false, url);
            composeArea?.focus();
            syncContent();
        }
    });

    function updateCounters(text) {
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const chars = text.length;
        if (wordCountEl) wordCountEl.textContent = words.toLocaleString('id-ID');
        if (charCountEl) charCountEl.textContent = chars.toLocaleString('id-ID');
        if (readTimeEl) readTimeEl.textContent = Math.max(1, Math.ceil(words / 200)) + ' mnt';
    }

    function syncContent() {
        if (currentMode === 'compose') {
            htmlArea.value = composeArea.innerHTML;
            updateCounters(composeArea.innerText || '');
        } else {
            composeArea.innerHTML = htmlArea.value;
            updateCounters(htmlArea.value.replace(/<[^>]+>/g, ''));
        }
    }

    composeArea?.addEventListener('input', syncContent);
    htmlArea?.addEventListener('input', syncContent);

    syncContent();

    btnCompose?.addEventListener('click', () => {
        if (currentMode === 'compose') return;
        currentMode = 'compose';
        composeArea.innerHTML = htmlArea.value;
        composeArea.classList.remove('hidden');
        htmlArea.classList.add('hidden');
        toolbar.classList.remove('opacity-40', 'pointer-events-none');
        
        btnCompose.className = 'flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer';
        btnHtml.className = 'flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer';
        syncContent();
    });

    btnHtml?.addEventListener('click', () => {
        if (currentMode === 'html') return;
        currentMode = 'html';
        htmlArea.value = composeArea.innerHTML;
        htmlArea.classList.remove('hidden');
        composeArea.classList.add('hidden');
        toolbar.classList.add('opacity-40', 'pointer-events-none');

        btnHtml.className = 'flex items-center gap-1.5 px-3 py-1 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold transition-all shadow-xs cursor-pointer';
        btnCompose.className = 'flex items-center gap-1.5 px-3 py-1 rounded-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium transition-all cursor-pointer';
        syncContent();
    });

    const tabFeatUpload = document.getElementById('page-tab-feat-upload');
    const tabFeatUrl = document.getElementById('page-tab-feat-url');
    const featUploadBox = document.getElementById('page-feat-upload-box');
    const featUrlBox = document.getElementById('page-feat-url-box');
    const featDropzone = document.getElementById('page-feat-dropzone');
    const featFileInput = document.getElementById('page_featured_image_file');
    const featUrlInput = document.getElementById('page_featured_image');
    const featPreviewWrapper = document.getElementById('page-feat-preview-wrapper');
    const featPreviewImg = document.getElementById('page-feat-preview-img');
    const featPreviewTitle = document.getElementById('page-feat-preview-title');
    const featPreviewSize = document.getElementById('page-feat-preview-size');
    const btnRemoveFeat = document.getElementById('page-btn-remove-feat-img');
    const removeFeatHidden = document.getElementById('page_remove_featured_image');

    tabFeatUpload?.addEventListener('click', () => {
        tabFeatUpload.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer";
        tabFeatUrl.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer";
        featUploadBox?.classList.remove('hidden');
        featUrlBox?.classList.add('hidden');
    });

    tabFeatUrl?.addEventListener('click', () => {
        tabFeatUrl.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer";
        tabFeatUpload.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer";
        featUrlBox?.classList.remove('hidden');
        featUploadBox?.classList.add('hidden');
    });

    featDropzone?.addEventListener('click', () => featFileInput?.click());

    featFileInput?.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                if (featPreviewImg) featPreviewImg.src = e.target.result;
                if (featPreviewTitle) featPreviewTitle.textContent = file.name;
                if (featPreviewSize) featPreviewSize.textContent = `${(file.size / 1024).toFixed(1)} KB (File Siap Diunggah)`;
                featPreviewWrapper?.classList.remove('hidden');
                if (removeFeatHidden) removeFeatHidden.value = "0";
            };
            reader.readAsDataURL(file);
        }
    });

    featUrlInput?.addEventListener('input', function () {
        const val = this.value.trim();
        if (val) {
            if (featPreviewImg) featPreviewImg.src = val;
            if (featPreviewTitle) featPreviewTitle.textContent = 'Gambar Tautan URL';
            if (featPreviewSize) featPreviewSize.textContent = val;
            featPreviewWrapper?.classList.remove('hidden');
            if (removeFeatHidden) removeFeatHidden.value = "0";
        } else if (!featFileInput?.files?.length) {
            featPreviewWrapper?.classList.add('hidden');
        }
    });

    btnRemoveFeat?.addEventListener('click', () => {
        if (featFileInput) featFileInput.value = '';
        if (featUrlInput) featUrlInput.value = '';
        if (featPreviewImg) featPreviewImg.src = '';
        featPreviewWrapper?.classList.add('hidden');
        if (removeFeatHidden) removeFeatHidden.value = "1";
    });

    pageForm?.addEventListener('submit', () => {
        syncContent();
    });
});
</script>
