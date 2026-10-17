<x-layout 
    :title="($page?->title ?: 'Penggunaan Layanan') . ' - Randi Iskandar'"
    :description="$page?->meta_description ?: 'Syarat dan ketentuan penggunaan blog Randi Iskandar.'"
    :image="$page?->featured_image"
>
    <main class="w-full max-w-3xl mx-auto px-5 sm:px-8 flex-grow pt-32 pb-20">
        <header class="mb-12 border-b border-zinc-200 dark:border-zinc-800 pb-10">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-4 font-sans">
                <i class="ph ph-file-text text-base"></i> Kebijakan Blog
            </div>
            <h1 class="font-sans font-bold text-4xl md:text-5xl text-zinc-900 dark:text-white mb-4">
                {{ $page?->title ?: 'Penggunaan Layanan' }}
            </h1>
            <p class="text-xs text-zinc-500 font-sans">Terakhir diperbarui: {{ $page?->updated_at?->translatedFormat('d F Y') ?: '16 Oktober 2026' }}</p>
        </header>

        <article class="prose prose-lg dark:prose-invert prose-zinc max-w-none font-sans text-zinc-700 dark:text-zinc-300">
            @if($page)
                {!! $page->body !!}
            @else
                <p>
                    Dengan mengakses dan menggunakan situs ini, Anda dianggap telah memahami dan menyetujui ketentuan penggunaan yang berlaku. Jika tidak menyetujui ketentuan ini, Anda dapat berhenti menggunakan situs.
                </p>

                <h3>Penggunaan Konten</h3>
                <p>
                    Artikel, opini, tutorial, dan konten lainnya yang dipublikasikan di blog ini merupakan karya yang dilindungi oleh ketentuan hak cipta, kecuali dinyatakan lain.
                </p>
                <p>
                    Anda boleh membaca, membagikan tautan, atau mengutip sebagian konten untuk keperluan pribadi dan edukasi, dengan tetap mencantumkan nama penulis serta tautan menuju sumber aslinya.
                </p>
                <p>
                    Penyalinan atau publikasi ulang seluruh konten, maupun penggunaan untuk tujuan komersial, harus mendapatkan izin dari pemilik hak cipta.
                </p>

                <h3>Perilaku Pengunjung</h3>
                <p>
                    Pengunjung diharapkan menggunakan fasilitas komentar dan formulir kontak dengan baik, antara lain dengan:
                </p>
                <ul>
                    <li>Tidak mengirimkan spam atau tautan promosi yang tidak relevan.</li>
                    <li>Tidak menyebarkan konten yang melanggar hukum, mengandung ujaran kebencian, atau merugikan pihak lain.</li>
                    <li>Menghargai pendapat orang lain dan menjaga komunikasi yang baik.</li>
                </ul>

                <h3>Ketersediaan Situs</h3>
                <p>
                    Saya berupaya menjaga agar blog ini dapat diakses dengan baik. Namun, gangguan teknis, pemeliharaan, atau kendala lain dapat menyebabkan situs tidak tersedia untuk sementara waktu.
                </p>

                <h3>Perubahan Ketentuan</h3>
                <p>
                    Ketentuan penggunaan ini dapat diperbarui sewaktu-waktu sesuai kebutuhan. Perubahan akan ditampilkan pada halaman ini.
                </p>
                <p>
                    Jika Anda memiliki pertanyaan mengenai ketentuan penggunaan blog ini, silakan hubungi saya melalui <a href="{{ route('contact') }}">Halaman Kontak</a> yang tersedia di situs..
                </p>
            @endif
        </article>
    </main>
</x-layout>
