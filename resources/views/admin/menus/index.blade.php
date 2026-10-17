<x-admin-layout title="Kelola Menu Navigasi - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Kelola Menu Navigasi</h1>
                <p class="text-xs text-zinc-500 mt-1">Atur tautan menu pada Header (Navbar atas & sidebar mobile) dan Footer situs publik.</p>
            </div>
            <div>
                <button 
                    type="button" 
                    id="btn-open-create-menu"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-bold rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer"
                >
                    <i class="ph ph-plus-circle text-base"></i>
                    <span>Tambah Item Menu</span>
                </button>
            </div>
        </div>

        
        <div class="w-full space-y-6">
            
            
            <div class="flex items-center gap-2 border-b border-zinc-200 dark:border-zinc-800 pb-3">
                <a 
                    href="{{ route('admin.menus.index', ['tab' => 'header']) }}"
                    class="px-4 py-2 text-xs rounded-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'header' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    <i class="ph ph-layout text-base"></i>
                    <span>Menu Header ({{ $headerMenus->count() }})</span>
                </a>
                <a 
                    href="{{ route('admin.menus.index', ['tab' => 'footer']) }}"
                    class="px-4 py-2 text-xs rounded-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'footer' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
                >
                    <i class="ph ph-browsers text-base"></i>
                    <span>Menu Footer ({{ $footerMenus->count() }})</span>
                </a>
            </div>

            @php
                $displayedMenus = $activeTab === 'footer' ? $footerMenus : $headerMenus;
            @endphp

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white">
                            {{ $activeTab === 'footer' ? 'Daftar Menu Footer' : 'Daftar Menu Header (Navbar & Mobile)' }}
                        </h3>
                        <p class="text-xs text-zinc-400 mt-0.5">
                            {{ $activeTab === 'footer' ? 'Tautan yang muncul di bagian kaki halaman publik.' : 'Tautan navigasi utama di bagian atas situs publik.' }}
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60 border-b border-zinc-200 dark:border-zinc-800 text-[11px] font-bold uppercase tracking-wider text-zinc-500">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">Urutan</th>
                                <th class="px-5 py-3">Label Menu</th>
                                <th class="px-5 py-3">URL Tautan</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-xs">
                            @forelse($displayedMenus as $index => $item)
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition-colors">
                                    
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <form action="{{ route('admin.menus.reorder') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="hidden" name="direction" value="up">
                                                <button 
                                                    type="submit" 
                                                    class="p-1 rounded-xs text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer {{ $loop->first ? 'opacity-30 pointer-events-none' : '' }}" 
                                                    title="Geser Naik"
                                                >
                                                    <i class="ph ph-caret-up text-sm"></i>
                                                </button>
                                            </form>

                                            <span class="font-mono font-bold text-xs px-1 text-zinc-600 dark:text-zinc-300">
                                                {{ $item->order }}
                                            </span>

                                            <form action="{{ route('admin.menus.reorder') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="hidden" name="direction" value="down">
                                                <button 
                                                    type="submit" 
                                                    class="p-1 rounded-xs text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer {{ $loop->last ? 'opacity-30 pointer-events-none' : '' }}" 
                                                    title="Geser Turun"
                                                >
                                                    <i class="ph ph-caret-down text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-1.5">
                                            <span>{{ $item->label }}</span>
                                            @if($item->target === '_blank')
                                                <span title="Buka di tab baru">
                                                    <i class="ph ph-arrow-square-out text-zinc-400 text-xs"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    
                                    <td class="px-5 py-3.5 font-mono text-zinc-500 dark:text-zinc-400 max-w-xs truncate">
                                        <a href="{{ $item->url }}" target="_blank" class="hover:underline">
                                            {{ $item->url }}
                                        </a>
                                    </td>

                                    
                                    <td class="px-4 py-3.5">
                                        @if($item->location === 'both')
                                            <span class="px-2 py-0.5 rounded-xs text-[10px] font-bold bg-purple-100 dark:bg-purple-950/60 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                Header & Footer
                                            </span>
                                        @elseif($item->location === 'header')
                                            <span class="px-2 py-0.5 rounded-xs text-[10px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                Header
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-xs text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                Footer
                                            </span>
                                        @endif
                                    </td>

                                    
                                    <td class="px-4 py-3.5">
                                        @if($item->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <button 
                                                type="button"
                                                class="btn-edit-menu p-1.5 text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
                                                data-id="{{ $item->id }}"
                                                data-label="{{ $item->label }}"
                                                data-url="{{ $item->url }}"
                                                data-location="{{ $item->location }}"
                                                data-order="{{ $item->order }}"
                                                data-target="{{ $item->target }}"
                                                data-is-active="{{ $item->is_active ? '1' : '0' }}"
                                                data-action="{{ route('admin.menus.update', $item) }}"
                                                title="Edit Item Menu"
                                            >
                                                <i class="ph ph-note-pencil text-base"></i>
                                            </button>

                                            
                                            <button 
                                                type="button" 
                                                class="btn-delete-trigger p-1.5 text-zinc-400 hover:text-red-600 transition-colors cursor-pointer"
                                                data-action="{{ route('admin.menus.destroy', $item) }}"
                                                data-title="Hapus Menu Navigasi"
                                                data-item="{{ $item->label }}"
                                                data-message="Apakah Anda yakin ingin menghapus tautan menu '{{ $item->label }}'? Tautan tidak akan lagi muncul di {{ $item->location === 'header' ? 'Header' : ($item->location === 'footer' ? 'Footer' : 'Header & Footer') }} situs."
                                                title="Hapus Menu"
                                            >
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-zinc-400">
                                        <i class="ph ph-list-dashes text-3xl block mx-auto mb-2 text-zinc-300 dark:text-zinc-600"></i>
                                        <p class="font-bold text-xs text-zinc-700 dark:text-zinc-300">Belum ada item menu untuk {{ $activeTab === 'footer' ? 'Footer' : 'Header' }}</p>
                                        <p class="text-[11px] text-zinc-400 mt-0.5">Gunakan tombol "Tambah Item Menu" di atas untuk menambahkan tautan baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    
    <div 
        id="create-menu-modal" 
        class="fixed inset-0 bg-zinc-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="create-menu-title"
    >
        <div 
            id="create-menu-card" 
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-lg w-full p-6 space-y-5 font-sans shadow-2xl transform scale-95 transition-transform duration-200 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2">
                    <i class="ph ph-plus-circle text-xl text-zinc-900 dark:text-white"></i>
                    <h3 id="create-menu-title" class="font-bold text-base text-zinc-900 dark:text-white">Tambah Item Menu</h3>
                </div>
                <button type="button" id="btn-close-create-menu" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-lg p-1">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <form action="{{ route('admin.menus.store') }}" method="POST" id="create-menu-form" class="space-y-4">
                @csrf
                <input type="hidden" name="_source" value="modal_create_menu">
                
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Pilih Tautan Cepat <span class="text-zinc-400 font-normal">(Opsional)</span>
                    </label>
                    <select 
                        id="create-preset-selector" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                    >
                        <option value="">-- Pilih Halaman atau Rute --</option>
                        <optgroup label="Halaman Statis">
                            @foreach($pages as $page)
                                @php
                                    $pageUrl = in_array($page->slug, ['terms', 'disclaimer', 'privacy', 'about']) 
                                        ? ($page->slug === 'about' ? '/tentang' : ($page->slug === 'privacy' ? '/privasi' : ($page->slug === 'terms' ? '/terms' : '/disclaimer')))
                                        : '/' . $page->slug;
                                @endphp
                                <option value="{{ $pageUrl }}" data-label="{{ $page->title }}">
                                    {{ $page->title }} ({{ $pageUrl }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Rute Sistem">
                            @foreach($systemRoutes as $sys)
                                <option value="{{ $sys['url'] }}" data-label="{{ $sys['label'] }}">
                                    {{ $sys['label'] }} ({{ $sys['url'] }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label for="create_label" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Label Teks Menu <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="label" 
                        id="create_label" 
                        required 
                        value="{{ old('label') }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors font-medium"
                        placeholder="Contoh: Kebijakan Privasi"
                    >
                    @error('label') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="create_url" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        URL Target <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="url" 
                        id="create_url" 
                        required 
                        value="{{ old('url') }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="/privasi atau https://..."
                    >
                    @error('url') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="create_location" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Lokasi Tampil <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="location" 
                            id="create_location" 
                            required 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="header" {{ old('location', $activeTab) === 'header' ? 'selected' : '' }}>Header Saja</option>
                            <option value="footer" {{ old('location', $activeTab) === 'footer' ? 'selected' : '' }}>Footer Saja</option>
                            <option value="both" {{ old('location') === 'both' ? 'selected' : '' }}>Keduanya (Header & Footer)</option>
                        </select>
                        @error('location') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="create_order" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Urutan Posisi
                        </label>
                        <input 
                            type="number" 
                            name="order" 
                            id="create_order" 
                            min="0"
                            value="{{ old('order') }}"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                            placeholder="Otomatis"
                        >
                        @error('order') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label for="create_target" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Target Tautan
                        </label>
                        <select 
                            name="target" 
                            id="create_target" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="_self" {{ old('target') === '_self' ? 'selected' : '' }}>Tab Sama (_self)</option>
                            <option value="_blank" {{ old('target') === '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                        </select>
                        @error('target') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="create_is_active" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Status
                        </label>
                        <select 
                            name="is_active" 
                            id="create_is_active" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button 
                        type="button" 
                        id="btn-cancel-create-menu" 
                        class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                    >
                        Tambah Item Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div 
        id="edit-menu-modal" 
        class="fixed inset-0 bg-zinc-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="edit-menu-title"
    >
        <div 
            id="edit-menu-card" 
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-lg w-full p-6 space-y-5 font-sans shadow-2xl transform scale-95 transition-transform duration-200 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2">
                    <i class="ph ph-note-pencil text-xl text-zinc-900 dark:text-white"></i>
                    <h3 id="edit-menu-title" class="font-bold text-base text-zinc-900 dark:text-white">Edit Item Menu</h3>
                </div>
                <button type="button" id="btn-close-edit-menu" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-lg p-1">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <form id="edit-menu-form" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Pilih Tautan Cepat <span class="text-zinc-400 font-normal">(Ubah Cepat)</span>
                    </label>
                    <select 
                        id="edit-preset-selector" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                    >
                        <option value="">-- Pertahankan atau Pilih Tautan Baru --</option>
                        <optgroup label="Halaman Statis">
                            @foreach($pages as $page)
                                @php
                                    $pageUrl = in_array($page->slug, ['terms', 'disclaimer', 'privacy', 'about']) 
                                        ? ($page->slug === 'about' ? '/tentang' : ($page->slug === 'privacy' ? '/privasi' : ($page->slug === 'terms' ? '/terms' : '/disclaimer')))
                                        : '/' . $page->slug;
                                @endphp
                                <option value="{{ $pageUrl }}" data-label="{{ $page->title }}">
                                    {{ $page->title }} ({{ $pageUrl }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Rute Sistem">
                            @foreach($systemRoutes as $sys)
                                <option value="{{ $sys['url'] }}" data-label="{{ $sys['label'] }}">
                                    {{ $sys['label'] }} ({{ $sys['url'] }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label for="edit_label" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Label Teks Menu <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="label" 
                        id="edit_label" 
                        required 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors font-medium"
                    >
                </div>

                <div>
                    <label for="edit_url" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        URL Target <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="url" 
                        id="edit_url" 
                        required 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_location" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Lokasi Tampil <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="location" 
                            id="edit_location" 
                            required 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="header">Header Saja</option>
                            <option value="footer">Footer Saja</option>
                            <option value="both">Keduanya (Header & Footer)</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_order" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Urutan Posisi
                        </label>
                        <input 
                            type="number" 
                            name="order" 
                            id="edit_order" 
                            min="0"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label for="edit_target" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Target Tautan
                        </label>
                        <select 
                            name="target" 
                            id="edit_target" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="_self">Tab Sama (_self)</option>
                            <option value="_blank">Tab Baru (_blank)</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_is_active" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                            Status
                        </label>
                        <select 
                            name="is_active" 
                            id="edit_is_active" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button 
                        type="button" 
                        id="btn-cancel-edit-menu" 
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
</x-admin-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const createModal = document.getElementById('create-menu-modal');
    const createCard = document.getElementById('create-menu-card');
    const btnOpenCreate = document.getElementById('btn-open-create-menu');
    const btnCloseCreate = document.getElementById('btn-close-create-menu');
    const btnCancelCreate = document.getElementById('btn-cancel-create-menu');
    const createPreset = document.getElementById('create-preset-selector');
    const createLabel = document.getElementById('create_label');
    const createUrl = document.getElementById('create_url');

    function openCreateModal() {
        createModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            createModal.classList.remove('opacity-0');
            createCard.classList.remove('scale-95');
            createCard.classList.add('scale-100');
        });
        createLabel?.focus();
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

    createPreset?.addEventListener('change', () => {
        const selected = createPreset.options[createPreset.selectedIndex];
        if (selected && selected.value) {
            createUrl.value = selected.value;
            const label = selected.getAttribute('data-label') || '';
            if (label && (!createLabel.value.trim() || confirm('Ganti nama label menu menjadi "' + label + '"?'))) {
                createLabel.value = label;
            }
        }
    });

    const editModal = document.getElementById('edit-menu-modal');
    const editCard = document.getElementById('edit-menu-card');
    const editForm = document.getElementById('edit-menu-form');
    const btnCloseEdit = document.getElementById('btn-close-edit-menu');
    const btnCancelEdit = document.getElementById('btn-cancel-edit-menu');
    const editPreset = document.getElementById('edit-preset-selector');
    const editLabel = document.getElementById('edit_label');
    const editUrl = document.getElementById('edit_url');
    const editLocation = document.getElementById('edit_location');
    const editOrder = document.getElementById('edit_order');
    const editTarget = document.getElementById('edit_target');
    const editIsActive = document.getElementById('edit_is_active');

    function openEditModal(actionUrl, label, url, location, order, target, isActive) {
        editForm.action = actionUrl;
        editLabel.value = label || '';
        editUrl.value = url || '';
        editLocation.value = location || 'header';
        editOrder.value = order ?? '';
        editTarget.value = target || '_self';
        editIsActive.value = isActive ? '1' : '0';
        editPreset.selectedIndex = 0;

        editModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            editModal.classList.remove('opacity-0');
            editCard.classList.remove('scale-95');
            editCard.classList.add('scale-100');
        });
        editLabel.focus();
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

    document.querySelectorAll('.btn-edit-menu').forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.dataset.action;
            const label = this.dataset.label;
            const url = this.dataset.url;
            const location = this.dataset.location;
            const order = this.dataset.order;
            const target = this.dataset.target;
            const isActive = this.dataset.isActive === '1';
            openEditModal(action, label, url, location, order, target, isActive);
        });
    });

    btnCloseEdit?.addEventListener('click', closeEditModal);
    btnCancelEdit?.addEventListener('click', closeEditModal);

    editPreset?.addEventListener('change', () => {
        const selected = editPreset.options[editPreset.selectedIndex];
        if (selected && selected.value) {
            editUrl.value = selected.value;
            const label = selected.getAttribute('data-label') || '';
            if (label && (!editLabel.value.trim() || confirm('Ganti nama label menu menjadi "' + label + '"?'))) {
                editLabel.value = label;
            }
        }
    });

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

    @if($errors->any() && old('_source') === 'modal_create_menu')
        openCreateModal();
    @endif
});
</script>
