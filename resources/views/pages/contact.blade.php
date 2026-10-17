<x-layout 
    title="Hubungi Saya - Randi Iskandar"
    description="Informasi kontak langsung, alamat kerja, dan media sosial Randi Iskandar."
>
    <main class="w-full max-w-2xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-24 font-sans">
        
        <header class="mb-14">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">
                <i class="ph ph-paper-plane-tilt text-base"></i> Kontak
            </div>
            <h1 class="font-bold text-4xl sm:text-5xl text-zinc-900 dark:text-white mb-5 tracking-tight">
                Mari Terhubung.
            </h1>
            <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed font-sans">
                Saya selalu terbuka untuk mendiskusikan peluang kolaborasi proyek, konsultasi teknologi, penulisan artikel, atau sekadar bertukar sapa.
            </p>
        </header>

        <div class="space-y-8 border-t border-zinc-200 dark:border-zinc-800 pt-10">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-6 items-baseline">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">
                    Surel Elektronik (Email)
                </span>
                <div class="sm:col-span-2">
                    @php
                        $contactEmail = $author?->email ?: 'randi@example.com';
                    @endphp
                    <a 
                        href="mailto:{{ $contactEmail }}" 
                        class="text-lg sm:text-xl font-medium text-zinc-900 dark:text-white hover:underline underline-offset-4 font-mono transition-colors"
                    >
                        {{ $contactEmail }}
                    </a>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-normal">
                        Kanal utama untuk komunikasi dan penawaran kerja sama.
                    </p>
                </div>
            </div>

            <div class="w-full h-px bg-zinc-100 dark:bg-zinc-800/60"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-6 items-baseline">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">
                    Alamat Kerja
                </span>
                <div class="sm:col-span-2 space-y-1">
                    <p class="text-base font-medium text-zinc-900 dark:text-white">
                        Jakarta Selatan, DKI Jakarta, Indonesia
                    </p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-normal">
                        Waktu Indonesia Barat (WIB, UTC+07:00). Terbuka untuk kolaborasi jarak jauh (<em>remote</em>).
                    </p>
                </div>
            </div>

            <div class="w-full h-px bg-zinc-100 dark:bg-zinc-800/60"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-6 items-baseline">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">
                    Media Sosial
                </span>
                <div class="sm:col-span-2">
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a 
                                href="https://github.com" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1.5 text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white font-medium hover:underline underline-offset-4 group"
                            >
                                <span>GitHub</span>
                                <span class="text-xs text-zinc-400 font-mono">(@randiiskandar)</span>
                                <i class="ph ph-arrow-up-right text-xs text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors"></i>
                            </a>
                        </li>
                        <li>
                            <a 
                                href="https://linkedin.com" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1.5 text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white font-medium hover:underline underline-offset-4 group"
                            >
                                <span>LinkedIn</span>
                                <span class="text-xs text-zinc-400 font-mono">(Randi Iskandar)</span>
                                <i class="ph ph-arrow-up-right text-xs text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors"></i>
                            </a>
                        </li>
                        <li>
                            <a 
                                href="https://x.com" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1.5 text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white font-medium hover:underline underline-offset-4 group"
                            >
                                <span>X (Twitter)</span>
                                <span class="text-xs text-zinc-400 font-mono">(@randiiskandar)</span>
                                <i class="ph ph-arrow-up-right text-xs text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors"></i>
                            </a>
                        </li>
                        <li>
                            <a 
                                href="https://instagram.com" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1.5 text-zinc-800 dark:text-zinc-200 hover:text-zinc-900 dark:hover:text-white font-medium hover:underline underline-offset-4 group"
                            >
                                <span>Instagram</span>
                                <span class="text-xs text-zinc-400 font-mono">(@randi.iskandar)</span>
                                <i class="ph ph-arrow-up-right text-xs text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </main>
</x-layout>
