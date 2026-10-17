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
                'meta_description' => 'Syarat dan ketentuan penggunaan blog Randi Iskandar.',
                'is_published' => true,
                'body' => '<p>Selamat datang di situs <strong>Randi Iskandar</strong>. Dengan mengakses dan menggunakan situs web ini, Anda menerima dan menyetujui syarat dan ketentuan penggunaan yang berlaku. Jika Anda tidak menyetujui ketentuan ini, Anda disarankan untuk tidak melanjutkan penggunaan situs ini.</p>

<h3>Hak Kekayaan Intelektual</h3>
<p>Kecuali dinyatakan lain, seluruh konten yang dipublikasikan pada situs ini—termasuk artikel, opini, tutorial, dokumentasi, dan materi merupakan karya dari <strong>Randi Iskandar</strong> dan dilindungi oleh ketentuan hak cipta yang berlaku.</p>
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
                'meta_description' => 'Kebijakan privasi dan perlindungan data pengunjung blog Randi Iskandar.',
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
                'meta_description' => 'Sanggahan dan batasan tanggung jawab konten pada blog Randi Iskandar.',
                'is_published' => true,
                'body' => '<p>Seluruh materi dan artikel yang dipublikasikan di blog <strong>Randi Iskandar</strong> disajikan untuk tujuan edukasi, dokumentasi teknis, dan opini umum seputar dunia teknologi.</p>

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
                'meta_description' => 'Blog personal Randi Iskandar yang membagikan proyek, opini teknologi, dan tutorial pemrograman.',
                'is_published' => true,
                'body' => '<p>Halo! Perkenalkan, saya <strong>Randi Iskandar</strong>, saya senang mengeksplorasi teknologi digital, mencoba hal-hal baru, dan berbagi cerita tentang apa saja yang menarik untuk dibahas.</p>

<h3>Tentang Blog Ini</h3>
<p>Situs ini merupakan ruang personal tempat saya berbagi pemikiran, informasi, dan hal-hal yang saya pelajari. Blog ini menjadi ruang untuk menuangkan ide, berbagi pengetahuan, dan mendokumentasikan berbagai catatan yang mungkin bermanfaat bagi pembaca.</p>

<h3>Apa yang Saya Tulis</h3>
<p>Di sini Anda dapat menemukan berbagai tulisan, di antaranya:</p>
<ul>
    <li><strong>Opini:</strong> Pandangan dan pemikiran tentang berbagai hal.</li>
    <li><strong>Teknologi:</strong> Informasi dan pembahasan seputar perkembangan teknologi digital.</li>
    <li><strong>Tutorial:</strong> Panduan praktis untuk membantu menyelesaikan berbagai kebutuhan teknis.</li>
    <li><strong>Eksplorasi:</strong> Catatan, pengalaman, dan hal-hal menarik dari proses mempelajari serta mencoba berbagai teknologi dan layanan digital.</li>
</ul>

<p>Semoga ada hal menarik yang bisa Anda temukan di sini. Selamat membaca, dan semoga tulisan-tulisan di blog ini bisa menambah wawasan atau memberikan inspirasi baru.</p>',
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
