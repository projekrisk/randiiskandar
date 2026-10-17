@php
    $activeTab = $currentTab;
    if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation')) {
        $activeTab = 'password';
    }
@endphp

<x-admin-layout title="Pengaturan - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Pengaturan Workspace</h1>
                <p class="text-xs text-zinc-500 mt-1">Kelola profil penulis, foto avatar, bio, kata sandi akun, dan konfigurasi penayangan iklan artikel.</p>
            </div>
        </div>

        
        <div class="flex items-center gap-2 mb-8 border-b border-zinc-200 dark:border-zinc-800 pb-3 text-xs font-medium overflow-x-auto">
            <a 
                href="{{ route('admin.settings.index', ['tab' => 'profile']) }}" 
                class="px-4 py-2 rounded-xs transition-colors whitespace-nowrap flex items-center gap-2 {{ $activeTab === 'profile' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-user-circle text-base"></i> Profil & Bio Penulis
            </a>
            
            <a 
                href="{{ route('admin.settings.index', ['tab' => 'ads']) }}" 
                class="px-4 py-2 rounded-xs transition-colors whitespace-nowrap flex items-center gap-2 {{ $activeTab === 'ads' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-megaphone text-base"></i> Iklan & Artikel
            </a>

            <a 
                href="{{ route('admin.settings.index', ['tab' => 'password']) }}" 
                class="px-4 py-2 rounded-xs transition-colors whitespace-nowrap flex items-center gap-2 {{ $activeTab === 'password' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-lock-key text-base"></i> Kata Sandi & Keamanan
            </a>
        </div>

        @if($activeTab === 'profile')
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 shadow-xs w-full">
                
                <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="profile-form">
                    @csrf

                    
                    <div class="flex items-start gap-4 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="w-12 h-12 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-2xl shrink-0">
                            <i class="ph ph-user"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-zinc-900 dark:text-white">Identitas & Foto Penulis</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                                Informasi ini akan tampil di kartu penulis beranda, header artikel, serta kotak tentang penulis di setiap tulisan.
                            </p>
                        </div>
                    </div>

                    
                    <div class="space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Foto Profil (Avatar)
                        </label>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            
                            <div class="relative group shrink-0">
                                <img 
                                    id="avatar-preview-img" 
                                    src="{{ old('avatar', $user->avatar) ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop' }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-2 border-zinc-300 dark:border-zinc-700 shadow-sm bg-zinc-100"
                                >
                            </div>

                            <div class="flex-grow space-y-3 w-full sm:w-auto">
                                
                                <div class="flex items-center gap-2 border border-zinc-200 dark:border-zinc-700 p-0.5 rounded-xs w-fit bg-zinc-50 dark:bg-zinc-800/80 text-xs font-medium">
                                    <button 
                                        type="button" 
                                        id="tab-avatar-upload" 
                                        class="px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer"
                                    >
                                        <i class="ph ph-upload mr-1"></i> Unggah Berkas
                                    </button>
                                    <button 
                                        type="button" 
                                        id="tab-avatar-url" 
                                        class="px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer"
                                    >
                                        <i class="ph ph-link mr-1"></i> Tautan URL
                                    </button>
                                </div>

                                
                                <div id="avatar-upload-box" class="space-y-2">
                                    <div 
                                        id="avatar-dropzone" 
                                        class="border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-600 rounded-xs p-5 text-center cursor-pointer transition-colors bg-zinc-50/50 dark:bg-zinc-950/40"
                                    >
                                        <input 
                                            type="file" 
                                            name="avatar_file" 
                                            id="avatar_file" 
                                            accept="image/*" 
                                            class="hidden"
                                        >
                                        <i class="ph ph-camera text-2xl text-zinc-400 mb-1 block"></i>
                                        <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                            Pilih foto baru atau seret ke sini
                                        </p>
                                        <p class="text-[11px] text-zinc-400 mt-0.5">
                                            JPG, PNG, WEBP (Maks. 5MB). Rasio persegi 1:1 disarankan.
                                        </p>
                                    </div>
                                </div>

                                
                                <div id="avatar-url-box" class="hidden">
                                    <input 
                                        type="text" 
                                        name="avatar" 
                                        id="avatar-url-input" 
                                        value="{{ old('avatar', $user->avatar) }}" 
                                        placeholder="https://images.unsplash.com/photo-..." 
                                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                    >
                                </div>

                                
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                                    <button 
                                        type="button" 
                                        id="btn-remove-avatar" 
                                        class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold flex items-center gap-1 cursor-pointer"
                                    >
                                        <i class="ph ph-trash"></i> Hapus Foto (Gunakan Default)
                                    </button>
                                </div>

                                @error('avatar') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                                @error('avatar_file') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Nama Lengkap Penulis <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            required 
                            value="{{ old('name', $user->name) }}" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                            placeholder="Randi Iskandar"
                        >
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                                Bio & Deskripsi Singkat Penulis
                            </label>
                            <span id="bio-counter" class="text-[11px] text-zinc-400">0 / 1000 karakter</span>
                        </div>
                        <textarea 
                            name="bio" 
                            id="bio" 
                            rows="4" 
                            maxlength="1000"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs p-3.5 text-xs sm:text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                            placeholder="Tuliskan latar belakang, keahlian, dan deskripsi singkat Anda sebagai penulis..."
                        >{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-[11px] text-zinc-400 mt-1">
                            * Bio ini muncul di halaman beranda, kartu penulis di setiap detail artikel, serta halaman Tentang Penulis.
                        </p>
                        @error('bio') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    
                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Tautan Media Sosial
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="social_github" class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1.5">
                                    <i class="ph ph-github-logo text-sm"></i> Profil GitHub
                                </label>
                                <input 
                                    type="url" 
                                    name="social_github" 
                                    id="social_github" 
                                    value="{{ old('social_github', $socialGithub) }}" 
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                    placeholder="https://github.com/username"
                                >
                            </div>
                            <div>
                                <label for="social_instagram" class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1.5">
                                    <i class="ph ph-instagram-logo text-sm text-pink-600"></i> Profil Instagram
                                </label>
                                <input 
                                    type="url" 
                                    name="social_instagram" 
                                    id="social_instagram" 
                                    value="{{ old('social_instagram', $socialInstagram) }}" 
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3.5 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                    placeholder="https://instagram.com/username"
                                >
                            </div>
                        </div>
                    </div>

                    
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="px-7 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <i class="ph ph-check-circle text-base"></i> Simpan Profil Penulis
                        </button>
                    </div>

                </form>

            </div>

        @elseif($activeTab === 'ads')
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 shadow-xs w-full">
                
                <form action="{{ route('admin.settings.ads.update') }}" method="POST" class="space-y-10" id="ads-form">
                    @csrf

                    
                    <div class="flex items-start gap-4 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center text-2xl shrink-0">
                            <i class="ph ph-megaphone"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-zinc-900 dark:text-white">Pengaturan Iklan & Monetisasi</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                                Atur apakah iklan ditampilkan atau disembunyikan pada artikel, serta tentukan kode iklan kustom Google AdSense Anda.
                            </p>
                        </div>
                    </div>

                    
                    <div class="p-5 sm:p-6 rounded-xs border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $adInArticleEnabled === '1' ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                <h3 class="font-bold text-sm sm:text-base text-zinc-900 dark:text-white">
                                    Iklan In-Article (Tengah Artikel)
                                </h3>
                            </div>

                            
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="ad_in_article_enabled" 
                                    value="1" 
                                    {{ old('ad_in_article_enabled', $adInArticleEnabled) === '1' ? 'checked' : '' }} 
                                    class="rounded-xs text-zinc-900 focus:ring-0 w-4 h-4 cursor-pointer"
                                >
                                <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200">
                                    Tampilkan Iklan In-Article
                                </span>
                            </label>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Iklan ini disisipkan otomatis persis setelah paragraf ke-3 pada setiap artikel. Jika opsi ini dinonaktifkan, tulisan mengalir tanpa jeda iklan.
                        </p>

                        <div>
                            <label for="ad_in_article_code" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Kode Skrip Iklan In-Article (HTML / Google AdSense)
                            </label>
                            <textarea 
                                name="ad_in_article_code" 
                                id="ad_in_article_code" 
                                rows="5" 
                                class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xs p-3.5 text-xs text-zinc-900 dark:text-emerald-400 font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                                placeholder="&lt;ins class=&quot;adsbygoogle&quot; ...&gt;&lt;/ins&gt;&#10;&lt;script&gt;(adsbygoogle = window.adsbygoogle || []).push({});&lt;/script&gt;"
                            >{{ old('ad_in_article_code', $adInArticleCode) }}</textarea>
                            <p class="text-[11px] text-zinc-400 mt-1">
                                * Biarkan kosong untuk menggunakan template AdSense bawaan sistem dengan format responsif fluid.
                            </p>
                            @error('ad_in_article_code') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    
                    <div class="p-5 sm:p-6 rounded-xs border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $adBottomEnabled === '1' ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                <h3 class="font-bold text-sm sm:text-base text-zinc-900 dark:text-white">
                                    Iklan Bawah Artikel (Akhir Tulisan)
                                </h3>
                            </div>

                            
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="ad_bottom_enabled" 
                                    value="1" 
                                    {{ old('ad_bottom_enabled', $adBottomEnabled) === '1' ? 'checked' : '' }} 
                                    class="rounded-xs text-zinc-900 focus:ring-0 w-4 h-4 cursor-pointer"
                                >
                                <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200">
                                    Tampilkan Iklan di Bawah Artikel
                                </span>
                            </label>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Unit iklan display yang diletakkan tepat di bawah konten artikel sebelum deretan tombol apresiasi dan komentar.
                        </p>

                        <div>
                            <label for="ad_bottom_code" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                                Kode Skrip Iklan Bawah Artikel (HTML / Google AdSense)
                            </label>
                            <textarea 
                                name="ad_bottom_code" 
                                id="ad_bottom_code" 
                                rows="5" 
                                class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xs p-3.5 text-xs text-zinc-900 dark:text-emerald-400 font-mono focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed"
                                placeholder="&lt;ins class=&quot;adsbygoogle&quot; style=&quot;display:block&quot; ...&gt;&lt;/ins&gt;&#10;&lt;script&gt;(adsbygoogle = window.adsbygoogle || []).push({});&lt;/script&gt;"
                            >{{ old('ad_bottom_code', $adBottomCode) }}</textarea>
                            <p class="text-[11px] text-zinc-400 mt-1">
                                * Biarkan kosong untuk menggunakan template unit display Google AdSense bawaan sistem.
                            </p>
                            @error('ad_bottom_code') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    
                    <div class="p-5 sm:p-6 rounded-xs border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/30 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $bacaJugaEnabled === '1' ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                <h3 class="font-bold text-sm sm:text-base text-zinc-900 dark:text-white flex items-center gap-2">
                                    <i class="ph ph-article text-base"></i> Fitur Rekomendasi "Baca Juga" Otomatis
                                </h3>
                            </div>

                            
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="baca_juga_enabled" 
                                    value="1" 
                                    {{ old('baca_juga_enabled', $bacaJugaEnabled) === '1' ? 'checked' : '' }} 
                                    class="rounded-xs text-zinc-900 focus:ring-0 w-4 h-4 cursor-pointer"
                                >
                                <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200">
                                    Aktifkan Baca Juga Otomatis
                                </span>
                            </label>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Secara otomatis menyisipkan tepat 1 kartu rekomendasi <strong>"BACA JUGA"</strong> setelah paragraf 3 yang diambil dari artikel lain dalam kategori yang sama. Jika tulisan sudah memuat blok manual atau tidak terdapat artikel lain dalam kategori tersebut, kartu tidak akan diduplikasi.
                        </p>
                    </div>

                    
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="px-7 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <i class="ph ph-check-circle text-base"></i> Simpan Pengaturan
                        </button>
                    </div>

                </form>

            </div>

        @elseif($activeTab === 'password')
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 shadow-xs w-full max-w-2xl">
                
                <form action="{{ route('admin.settings.password.update') }}" method="POST" class="space-y-6" id="password-form">
                    @csrf

                    
                    <div class="flex items-start gap-4 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl shrink-0">
                            <i class="ph ph-shield-check"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-zinc-900 dark:text-white">Ganti Kata Sandi Akun</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                                Pastikan akun Anda terlindungi dengan menggunakan kata sandi yang aman dan tidak digunakan pada layanan lain.
                            </p>
                        </div>
                    </div>

                    
                    <div class="space-y-2">
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Kata Sandi Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password" 
                                required 
                                autocomplete="current-password"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-zinc-200 dark:border-zinc-700' }} rounded-xs pl-3.5 pr-10 py-2.5 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                placeholder="Masukkan kata sandi saat ini"
                            >
                            <button 
                                type="button" 
                                data-toggle-password="current_password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="ph ph-eye text-base"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1 font-medium">
                                <i class="ph ph-warning-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    
                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                autocomplete="new-password"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-zinc-200 dark:border-zinc-700' }} rounded-xs pl-3.5 pr-10 py-2.5 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                placeholder="Minimal 8 karakter kombinasi"
                            >
                            <button 
                                type="button" 
                                data-toggle-password="password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="ph ph-eye text-base"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                            Gunakan minimal 8 karakter. Disarankan mengombinasikan huruf besar, huruf kecil, angka, dan simbol.
                        </p>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1 font-medium">
                                <i class="ph ph-warning-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-zinc-200 dark:border-zinc-700' }} rounded-xs pl-3.5 pr-10 py-2.5 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                                placeholder="Ulangi kata sandi baru"
                            >
                            <button 
                                type="button" 
                                data-toggle-password="password_confirmation"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="ph ph-eye text-base"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1 font-medium">
                                <i class="ph ph-warning-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    
                    <div class="p-4 rounded-xs bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 flex items-start gap-3">
                        <i class="ph ph-info text-lg text-zinc-600 dark:text-zinc-300 shrink-0 mt-0.5"></i>
                        <div class="text-xs text-zinc-600 dark:text-zinc-400 space-y-1">
                            <p class="font-bold text-zinc-800 dark:text-zinc-200">Tips Keamanan Akun:</p>
                            <p>Setelah kata sandi diperbarui, sesi Anda saat ini akan tetap aktif. Pastikan Anda mengingat kata sandi baru untuk login berikutnya.</p>
                        </div>
                    </div>

                    
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="px-7 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <i class="ph ph-lock-key text-base"></i> Perbarui Kata Sandi
                        </button>
                    </div>

                </form>

            </div>
        @endif

    </div>

    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const bioTextarea = document.getElementById('bio');
        const bioCounter = document.getElementById('bio-counter');
        if (bioTextarea && bioCounter) {
            const updateCounter = () => {
                bioCounter.textContent = `${bioTextarea.value.length} / 1000 karakter`;
            };
            bioTextarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        const tabUpload = document.getElementById('tab-avatar-upload');
        const tabUrl = document.getElementById('tab-avatar-url');
        const uploadBox = document.getElementById('avatar-upload-box');
        const urlBox = document.getElementById('avatar-url-box');
        const dropzone = document.getElementById('avatar-dropzone');
        const fileInput = document.getElementById('avatar_file');
        const urlInput = document.getElementById('avatar-url-input');
        const previewImg = document.getElementById('avatar-preview-img');
        const btnRemove = document.getElementById('btn-remove-avatar');
        const removeAvatarHidden = document.getElementById('remove_avatar');

        if (tabUpload && tabUrl) {
            tabUpload.addEventListener('click', () => {
                tabUpload.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer";
                tabUrl.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer";
                uploadBox?.classList.remove('hidden');
                urlBox?.classList.add('hidden');
            });

            tabUrl.addEventListener('click', () => {
                tabUrl.className = "px-3 py-1 rounded-xs transition-colors bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white font-bold shadow-xs cursor-pointer";
                tabUpload.className = "px-3 py-1 rounded-xs transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer";
                urlBox?.classList.remove('hidden');
                uploadBox?.classList.add('hidden');
            });
        }

        if (dropzone && fileInput) {
            dropzone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (previewImg) previewImg.src = e.target.result;
                        if (removeAvatarHidden) removeAvatarHidden.value = "0";
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            ['dragenter', 'dragover'].forEach(name => {
                dropzone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('border-zinc-900', 'dark:border-white');
                });
            });

            ['dragleave', 'drop'].forEach(name => {
                dropzone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('border-zinc-900', 'dark:border-white');
                });
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    fileInput.files = dt.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        }

        if (urlInput) {
            urlInput.addEventListener('input', function () {
                const val = this.value.trim();
                if (val && previewImg) {
                    previewImg.src = val;
                    if (removeAvatarHidden) removeAvatarHidden.value = "0";
                }
            });
        }

        if (btnRemove) {
            btnRemove.addEventListener('click', () => {
                if (fileInput) fileInput.value = '';
                if (urlInput) urlInput.value = '';
                if (previewImg) previewImg.src = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop';
                if (removeAvatarHidden) removeAvatarHidden.value = "1";
            });
        }

        document.querySelectorAll('[data-toggle-password]').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-toggle-password');
                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');
                if (input && icon) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'ph ph-eye-slash text-base text-zinc-700 dark:text-zinc-200';
                    } else {
                        input.type = 'password';
                        icon.className = 'ph ph-eye text-base text-zinc-400';
                    }
                }
            });
        });
    });
    </script>
</x-admin-layout>
