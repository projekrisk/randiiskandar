<x-layout 
    :title="($page?->title ?: 'Sanggahan (Disclaimer)') . ' - Randi Iskandar'"
    :description="$page?->meta_description ?: 'Sanggahan legalitas, opini independen, dan batasan tanggung jawab blog Randi Iskandar.'"
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
                    Informasi yang disajikan di situs <strong>Randi Iskandar</strong> ditujukan untuk tujuan edukasi, dokumentasi teknologi, opini independen, tutorial, dan referensi  umum.
                </p>

                <h3>Opini Pribadi</h3>
                <p>
                    Seluruh opini, artikel teknologi, tutorial, dan evaluasi teknis yang dimuat pada blog ini merupakan pandangan pribadi saya dan tidak mewakili entitas atau institusi lain manapun.
                </p>

                <h3>Ketepatan Informasi</h3>
                <p>
                    Meskipun saya berupaya memastikan akurasi data dan kode tutorial pada saat penulisan, perkembangan teknologi software bergerak cepat. Pembaca disarankan melakukan penyesuaian dan pengujian mandiri sebelum menerapkannya di lingkungan produksi.
                </p>

                <h3>Tautan Eksternal</h3>
                <p>
                    Situs ini mungkin memuat tautan ke situs luar. Saya tidak bertanggung jawab atas isi, kebijakan privasi, atau perubahan informasi pada situs pihak ketiga tersebut.
                </p>
            @endif
        </article>
    </main>
</x-layout>
