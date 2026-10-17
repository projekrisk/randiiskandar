<x-layout 
    :title="($page?->title ?: 'Penggunaan Layanan') . ' - Randi Iskandar'"
    :description="$page?->meta_description ?: 'Syarat dan ketentuan penggunaan situs portofolio dan blog Randi Iskandar.'"
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
                    Selamat datang di situs <strong>Randi Iskandar</strong>. Dengan mengakses dan menggunakan situs web ini, Anda menerima dan setuju untuk terikat oleh Syarat dan Ketentuan Penggunaan Layanan ini. Jika Anda tidak setuju dengan ketentuan ini, harap tidak menggunakan situs web ini.
                </p>

                <h3>Hak Kekayaan Intelektual</h3>
                <p>
                    Kecuali dinyatakan lain secara eksplisit, seluruh konten yang ada di blog ini—termasuk tulisan opini, materi teknologi, tutorial pemrograman, dan dokumentasi proyek portofolio—adalah milik <strong>Randi Iskandar</strong> dan dilindungi oleh ketentuan hak cipta.
                </p>
                <p>
                    Anda diberikan izin untuk membaca dan membagikan konten ini untuk tujuan edukasi atau non-komersial dengan tetap mencantumkan atribusi yang jelas serta tautan ke sumber asli. Penggunaan kembali kode sumber contoh mengikuti lisensi terbuka yang relevan.
                </p>

                <h3>Perilaku Pengguna</h3>
                <p>
                    Dalam menggunakan situs web ini, khususnya saat meninggalkan komentar atau berinteraksi melalui formulir kontak, Anda setuju untuk tidak:
                </p>
                <ul>
                    <li>Memposting komentar yang mengandung ujaran kebencian, pelecehan, ancaman, atau konten yang melanggar hukum.</li>
                    <li>Melakukan aktivitas spam, menyebarkan tautan promosi yang tidak relevan, atau mengirimkan skrip berbahaya.</li>
                    <li>Mengganggu kenyamanan atau privasi pembaca lain.</li>
                </ul>

                <h3>Ketersediaan Layanan</h3>
                <p>
                    Pengelola berupaya menjaga situs ini agar dapat diakses setiap saat, namun tidak bertanggung jawab atas gangguan sementara karena pemeliharaan teknis atau masalah jaringan di luar kendali.
                </p>

                <h3>Hubungi Saya</h3>
                <p>
                    Jika Anda memiliki pertanyaan mengenai ketentuan layanan ini, silakan ajukan pesan melalui <a href="{{ route('contact') }}">Halaman Kontak</a>.
                </p>
            @endif
        </article>
    </main>
</x-layout>
