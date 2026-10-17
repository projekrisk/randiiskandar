<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageAndMenuSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Penggunaan Layanan',
                'slug' => 'terms',
                'meta_description' => 'Syarat dan ketentuan penggunaan blog dan portofolio Randi Iskandar.',
                'is_published' => true,
                'body' => '<p>Selamat datang di situs <strong>Randi Iskandar</strong>. Dengan mengakses dan menggunakan situs web ini, Anda menerima dan menyetujui syarat dan ketentuan penggunaan yang berlaku. Jika Anda tidak menyetujui ketentuan ini, Anda disarankan untuk tidak melanjutkan penggunaan situs ini.</p>

<h3>Hak Kekayaan Intelektual</h3>
<p>Kecuali dinyatakan lain, seluruh konten yang dipublikasikan pada situs ini—termasuk artikel, opini, tutorial pemrograman, dokumentasi proyek, dan materi portofolio—merupakan karya dari <strong>Randi Iskandar</strong> dan dilindungi oleh ketentuan hak cipta yang berlaku.</p>
<p>Anda diperbolehkan membaca, mempelajari, dan membagikan kutipan dari konten ini untuk keperluan edukasi dan non-komersial, dengan tetap mencantumkan atribusi nama penulis serta menyertakan tautan aktif menuju artikel asli. Penggandaan massal atau penggunaan materi untuk tujuan komersial tanpa izin tertulis dari pemilik situs tidak diperkenankan.</p>

<h3>Perilaku Pengunjung</h3>
<p>Saat menggunakan fasilitas komentar maupun formulir kontak di situs ini, Anda diharapkan untuk:</p>
<ul>
    <li>Tidak mempublikasikan konten yang mengandung ujaran kebencian, pencemaran nama baik, atau materi yang melanggar hukum.</li>
    <li>Tidak menyebarkan spam, tautan promosi tidak relevan, atau kode berbahaya.</li>
    <li>Menghargai sesama pengguna dalam ruang diskusi.</li>
</ul>

<h3>Ketersediaan Layanan</h3>
<p>Situs ini diupayakan dapat diakses secara optimal setiap saat. Namun demikian, ketersediaan situs dapat sewaktu-waktu mengalami gangguan akibat pemeliharaan sistem atau kendala teknis di luar kendali.</p>

<h3>Kontak</h3>
<p>Pertanyaan terkait ketentuan layanan ini dapat disampaikan melalui formulir kontak yang tersedia di situs ini.</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'privacy',
                'meta_description' => 'Kebijakan privasi dan perlindungan data pengunjung blog dan portofolio Randi Iskandar.',
                'is_published' => true,
                'body' => '<p>Privasi Anda sebagai pengunjung situs <strong>Randi Iskandar</strong> adalah hal yang diutamakan. Dokumen ini menjelaskan jenis data yang dicatat serta bagaimana informasi tersebut dikelola.</p>

<h3>Informasi yang Dikumpulkan</h3>
<p>Informasi yang dapat dicatat saat Anda berinteraksi dengan situs ini meliputi:</p>
<ul>
    <li><strong>Komentar Artikel:</strong> Nama, alamat email, situs web opsional, dan isi tanggapan yang Anda kirimkan.</li>
    <li><strong>Formulir Kontak:</strong> Nama, alamat email, subjek, serta pesan yang dikirimkan langsung kepada penulis.</li>
    <li><strong>Data Akses Server:</strong> Alamat IP, jenis peramban, dan waktu kunjungan yang dicatat untuk keperluan keamanan dan analitik dasar kinerja situs.</li>
</ul>

<h3>Tujuan Penggunaan Informasi</h3>
<p>Informasi yang terkumpul digunakan untuk keperluan berikut:</p>
<ul>
    <li>Menayangkan dan memoderasi tanggapan pada kolom komentar artikel.</li>
    <li>Menjawab pertanyaan atau pesan kerja sama yang dikirimkan pengunjung.</li>
    <li>Memantau stabilitas server dan mencegah aktivitas spam atau penyalahgunaan situs.</li>
</ul>

<h3>Penggunaan Cookie</h3>
<p>Situs ini menggunakan penyimpanan lokal (local storage) dan cookie untuk mengingat preferensi antarmuka Anda, seperti mode tampilan terang atau gelap dan daftar baca nanti.</p>

<h3>Permintaan Penghapusan Data</h3>
<p>Anda dapat mengajukan permohonan penghapusan riwayat komentar atau pesan yang pernah Anda kirimkan dengan menghubungi kami melalui halaman kontak.</p>',
            ],
            [
                'title' => 'Sanggahan (Disclaimer)',
                'slug' => 'disclaimer',
                'meta_description' => 'Sanggahan dan batasan tanggung jawab konten pada blog dan portofolio Randi Iskandar.',
                'is_published' => true,
                'body' => '<p>Seluruh materi dan artikel yang dipublikasikan di blog <strong>Randi Iskandar</strong> disajikan untuk tujuan edukasi, dokumentasi teknis, portofolio karya, dan opini umum seputar dunia teknologi.</p>

<h3>Tanggung Jawab Teknis</h3>
<p>Kode pemrograman, tutorial, konfigurasi server, dan panduan teknis yang dibagikan telah diuji pada lingkungan pengembangan penulis saat penulisan dilakukan. Namun, perkembangan ekosistem perangkat lunak berlangsung sangat cepat. Penulis tidak bertanggung jawab atas kerugian atau kerusakan sistem yang timbul dari penerapan instruksi tanpa penyesuaian pada lingkungan kerja masing-masing pengguna.</p>

<h3>Pandangan Pribadi</h3>
<p>Artikel opini dan esai yang dimuat di blog ini merupakan refleksi dan pandangan pribadi penulis, serta tidak mewakili entitas bisnis atau organisasi lain mana pun.</p>

<h3>Tautan Pihak Ketiga</h3>
<p>Artikel dapat memuat tautan menuju situs atau repositori eksternal sebagai referensi tambahan. Penulis tidak memiliki kendali atas perubahan isi maupun kebijakan privasi pada situs-situs pihak ketiga tersebut.</p>',
            ],
            [
                'title' => 'Tentang Randi Iskandar',
                'slug' => 'about',
                'meta_description' => 'Portofolio dan blog personal Randi Iskandar yang membagikan proyek, opini teknologi, dan tutorial pemrograman.',
                'is_published' => true,
                'body' => '<p>Halo! Saya <strong>Randi Iskandar</strong>, seorang pengembang web (web developer) dan penulis yang gemar mengeksplorasi teknologi digital serta membangun solusi berbasis web yang efisien.</p>

<h3>Portofolio & Catatan Digital</h3>
<p>Situs ini merupakan ruang personal sekaligus portofolio saya untuk mendokumentasikan karya, proses rekayasa perangkat lunak, serta catatan pemikiran seputar dunia digital yang terus berkembang.</p>

<h3>Apa yang Saya Tulis</h3>
<p>Di sini Anda dapat menemukan tulisan bebas yang mencakup berbagai topik:</p>
<ul>
    <li><strong>Portofolio & Proyek:</strong> Cerita di balik perancangan dan implementasi aplikasi atau platform web.</li>
    <li><strong>Teknologi:</strong> Pembahasan tren arsitektur perangkat lunak, infrastruktur, dan ekosistem open-source.</li>
    <li><strong>Tutorial & Panduan Teknis:</strong> Panduan langkah demi langkah memecahkan masalah pemrograman praktis.</li>
    <li><strong>Opini & Esai Bebas:</strong> Catatan perenungan, etos kerja, serta pandangan kritis mengenai perkembangan lanskap teknologi informasi.</li>
</ul>

<p>Jika Anda tertarik untuk berkolaborasi, mendiskusikan peluang proyek, atau sekadar bertukar gagasan, silakan hubungi saya melalui halaman kontak.</p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        $menus = [
            [
                'label' => 'Beranda',
                'url' => '/',
                'location' => 'header',
                'order' => 1,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Arsip',
                'url' => '/arsip',
                'location' => 'header',
                'order' => 2,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Tentang',
                'url' => '/tentang',
                'location' => 'header',
                'order' => 3,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Tentang',
                'url' => '/tentang',
                'location' => 'footer',
                'order' => 1,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Layanan',
                'url' => '/terms',
                'location' => 'footer',
                'order' => 2,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Privasi',
                'url' => '/privasi',
                'location' => 'footer',
                'order' => 3,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Sanggahan',
                'url' => '/disclaimer',
                'location' => 'footer',
                'order' => 4,
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'label' => 'Kontak',
                'url' => '/kontak',
                'location' => 'footer',
                'order' => 5,
                'target' => '_self',
                'is_active' => true,
            ],
        ];

        foreach ($menus as $m) {
            NavigationMenu::updateOrCreate([
                'label' => $m['label'],
                'location' => $m['location'],
            ], $m);
        }
    }
}
