<x-layout 
    :title="($page?->title ?: 'Penggunaan Layanan') . ' - Randi Iskandar Workspace'"
    :description="$page?->meta_description ?: 'Syarat dan ketentuan penggunaan blog editorial Randi Iskandar Workspace.'"
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
                    Selamat datang di blog <strong>Randi Iskandar</strong>. Dengan mengakses dan menggunakan situs web ini, Anda menerima dan setuju untuk terikat oleh Syarat dan Ketentuan Penggunaan Layanan ini. Jika Anda tidak setuju dengan ketentuan ini, harap tidak menggunakan situs web ini.
                </p>

            <h3>Hak Kekayaan Intelektual</h3>
            <p>
                Kecuali dinyatakan lain secara eksplisit, seluruh konten yang ada di blog ini—termasuk namun tidak terbatas pada teks, artikel, esai, desain antarmuka, tata letak, dan grafis tertentu—adalah milik eksklusif <strong>Randi Iskandar</strong> dan dilindungi oleh undang-undang hak cipta dan kekayaan intelektual.
            </p>
            <p>
                Anda diberikan lisensi terbatas untuk melihat dan membagikan konten ini untuk penggunaan pribadi dan non-komersial, asalkan Anda selalu memberikan atribusi yang tepat berupa penyebutan nama penulis dan menyertakan tautan kembali (<em>hyperlink</em>) ke artikel asli. Reproduksi keseluruhan, modifikasi, atau pendistribusian konten untuk tujuan komersial tanpa izin tertulis dari penulis sangat dilarang.
            </p>

            <h3>Perilaku Pengguna</h3>
            <p>
                Dalam menggunakan situs web ini, khususnya saat meninggalkan komentar atau berinteraksi melalui formulir kontak, Anda setuju untuk tidak:
            </p>
            <ul>
                <li>Memposting komentar yang mengandung ujaran kebencian, pelecehan, ancaman, atau konten yang melanggar hukum.</li>
                <li>Melakukan aktivitas <em>spamming</em>, menyebarkan tautan promosi yang tidak relevan, atau mengirimkan perangkat lunak berbahaya.</li>
                <li>Mengumpulkan data atau informasi pribadi pengguna lain secara sistematis tanpa izin.</li>
            </ul>

            <h3>Ketersediaan Layanan</h3>
            <p>
                Saya berusaha keras untuk memastikan blog ini dapat diakses setiap saat. Namun, saya tidak bertanggung jawab apabila situs ini menjadi tidak tersedia sementara waktu karena masalah teknis di luar kendali saya atau pemeliharaan server.
            </p>

            <h3>Hubungi Saya</h3>
            <p>
                Jika Anda memiliki pertanyaan mengenai Ketentuan Penggunaan Layanan ini, silakan ajukan pesan Anda melalui <a href="{{ route('contact') }}">Halaman Kontak</a>.
            </p>
            @endif
        </article>

    </main>
</x-layout>
