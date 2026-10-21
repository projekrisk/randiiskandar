<x-admin-layout :title="'Edit Menu: ' . $menu->label . ' - Randi Iskandar Workspace'">
    <div class="w-full font-sans pb-16 max-w-2xl">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.menus.index', ['tab' => $menu->location === 'footer' ? 'footer' : 'header']) }}" class="text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white flex items-center gap-1 mb-1 font-medium transition-colors">
                    <i class="ph ph-arrow-left"></i> Kembali ke daftar menu
                </a>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Edit Item Menu: {{ $menu->label }}</h1>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1">
                        Pilih dari Halaman atau Rute Sistem <span class="text-zinc-400 font-normal">(Opsional)</span>
                    </label>
                    <select 
                        id="preset-selector-edit" 
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                    >
                        <option value="">-- Ganti dengan Halaman atau Rute --</option>
                        <optgroup label="Halaman Statis">
                            @foreach($pages as $page)
                                @php
                                    $pageUrl = in_array($page->slug, ['terms', 'term', 'disclaimer', 'privacy', 'privasi', 'about', 'tentang']) 
                                        ? ($page->slug === 'about' || $page->slug === 'tentang' ? '/tentang' : (in_array($page->slug, ['privacy', 'privasi']) ? '/privasi' : ($page->slug === 'terms' || $page->slug === 'term' ? '/terms' : '/disclaimer')))
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
                    <label for="label" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                        Label Teks Menu <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="label" 
                        id="label" 
                        required 
                        value="{{ old('label', $menu->label) }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="Contoh: Kebijakan Privasi"
                    >
                    @error('label') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="url" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                        URL Target <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="url" 
                        id="url" 
                        required 
                        value="{{ old('url', $menu->url) }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        placeholder="/privasi atau https://..."
                    >
                    @error('url') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Lokasi Tampil <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="location" 
                            id="location" 
                            required 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="header" {{ old('location', $menu->location) === 'header' ? 'selected' : '' }}>Header Saja</option>
                            <option value="footer" {{ old('location', $menu->location) === 'footer' ? 'selected' : '' }}>Footer Saja</option>
                            <option value="both" {{ old('location', $menu->location) === 'both' ? 'selected' : '' }}>Keduanya (Header & Footer)</option>
                        </select>
                        @error('location') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="order" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Urutan Posisi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="order" 
                            id="order" 
                            required 
                            min="0"
                            value="{{ old('order', $menu->order) }}"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                        >
                        @error('order') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="target" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Target Tautan
                        </label>
                        <select 
                            name="target" 
                            id="target" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="_self" {{ old('target', $menu->target) === '_self' ? 'selected' : '' }}>Tab Sama (_self)</option>
                            <option value="_blank" {{ old('target', $menu->target) === '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Status
                        </label>
                        <select 
                            name="is_active" 
                            id="is_active" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="1" {{ old('is_active', $menu->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $menu->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-800">
                    <a 
                        href="{{ route('admin.menus.index', ['tab' => $menu->location === 'footer' ? 'footer' : 'header']) }}" 
                        class="px-4 py-2 border border-zinc-300 dark:border-zinc-700 rounded-xs text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                    >
                        Perbarui Item Menu
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-admin-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const presetSelector = document.getElementById('preset-selector-edit');
    const labelInput = document.getElementById('label');
    const urlInput = document.getElementById('url');

    presetSelector?.addEventListener('change', () => {
        const selected = presetSelector.options[presetSelector.selectedIndex];
        if (selected && selected.value) {
            urlInput.value = selected.value;
            const label = selected.getAttribute('data-label') || '';
            if (label && confirm('Ganti nama label menu menjadi "' + label + '"?')) {
                labelInput.value = label;
            }
        }
    });
});
</script>
