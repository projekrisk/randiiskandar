<x-layout 
    :title="($page?->title ?: 'Sanggahan (Disclaimer)') . ' - Randi Iskandar Workspace'"
    :description="$page?->meta_description ?: 'Sanggahan legalitas, opini independen, dan batasan tanggung jawab blog Randi Iskandar Workspace.'"
    :image="$page?->featured_image"
>
    <main class="w-full max-w-3xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        
        <header class="mb-12 border-b border-zinc-200 dark:border-zinc-800 pb-10">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-4 font-sans">
                <i class="ph ph-shield-check text-base"></i> Transparansi
            </div>
            <h1 class="font-sans font-bold text-4xl md:text-5xl text-zinc-900 dark:text-white mb-4">
                {{ $page?->title ?: 'Sanggahan' }}
            </h1>
            <p class="text-xs text-zinc-500 font-sans">Terakhir diperbarui: {{ $page?->updated_at?->translatedFormat('d F Y') ?: '16 Oktober 2026' }}</p>
        </header>

        <article class="prose prose-lg dark:prose-invert prose-zinc max-w-none font-sans text-zinc-700 dark:text-zinc-300">
            @if($page)
                {!! $page->body !!}
            @else
                <p>
                    Informasi yang disajikan di <strong>Randi Iskandar Workspace</strong> ditujukan semata-mata untuk tujuan edukasi, eksplorasi desain, dan referensi umum.
                </p>

            <h3>Opini Pribadi</h3>
            <p>
                Seluruh opini, analisis teknis, ulasan alat, dan perspektif desain yang tertulis di blog ini merupakan pandangan pribadi saya sebagai penulis independen, dan tidak mencerminkan kebijakan atau sikap resmi dari perusahaan, klien, atau organisasi tempat saya pernah atau sedang terafiliasi.
            </p>

            <h3>Ketepatan Informasi</h3>
            <p>
                Meskipun saya berusaha sekuat tenaga untuk menyajikan data, kode sumber, dan dokumentasi yang akurat pada saat penulisan, perkembangan teknologi web bergerak sangat pesat. Saya tidak memberikan jaminan apa pun mengenai kelengkapan, keandalan, atau keakuratan informasi ini untuk setiap situasi penerapan nyata Anda.
            </p>

            <h3>Tautan Eksternal</h3>
            <p>
                Situs ini mungkin berisi tautan ke situs web pihak ketiga. Saya tidak memiliki kendali atas isi, kebijakan privasi, atau praktik situs web pihak ketiga tersebut dan tidak bertanggung jawab atas kerugian apa pun yang mungkin timbul dari penggunaannya.
            </p>
            @endif
        </article>

    </main>
</x-layout>
