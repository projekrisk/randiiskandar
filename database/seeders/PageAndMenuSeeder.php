<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageAndMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Default Static Pages
        $pages = [
            [
                'title' => 'Penggunaan Layanan',
                'slug' => 'terms',
                'meta_description' => 'Syarat dan ketentuan penggunaan blog editorial Randi Iskandar Workspace.',
                'is_published' => true,
                'body' => '<p>Selamat datang di blog <strong>Randi Iskandar</strong>. Dengan mengakses dan menggunakan situs web ini, Anda menerima dan setuju untuk terikat oleh Syarat dan Ketentuan Penggunaan Layanan ini. Jika Anda tidak setuju dengan ketentuan ini, harap tidak menggunakan situs web ini.</p>

<h3>Hak Kekayaan Intelektual</h3>
<p>Kecuali dinyatakan lain secara eksplisit, seluruh konten yang ada di blog ini—termasuk namun tidak terbatas pada teks, artikel, esai, desain antarmuka, tata letak, dan grafis tertentu—adalah milik eksklusif <strong>Randi Iskandar</strong> dan dilindungi oleh undang-undang hak cipta dan kekayaan intelektual.</p>
<p>Anda diberikan lisensi terbatas untuk melihat dan membagikan konten ini untuk penggunaan pribadi dan non-komersial, asalkan Anda selalu memberikan atribusi yang tepat berupa penyebutan nama penulis dan menyertakan tautan kembali (<em>hyperlink</em>) ke artikel asli. Reproduksi keseluruhan, modifikasi, atau pendistribusian konten untuk tujuan komersial tanpa izin tertulis dari penulis sangat dilarang.</p>

<h3>Perilaku Pengguna</h3>
<p>Dalam menggunakan situs web ini, khususnya saat meninggalkan komentar atau berinteraksi melalui formulir kontak, Anda setuju untuk tidak:</p>
<ul>
    <li>Memposting komentar yang mengandung ujaran kebencian, pelecehan, ancaman, atau konten yang melanggar hukum.</li>
    <li>Melakukan aktivitas <em>spamming</em>, menyebarkan tautan promosi yang tidak relevan, atau mengirimkan perangkat lunak berbahaya.</li>
    <li>Mengumpulkan data atau informasi pribadi pengguna lain secara sistematis tanpa izin.</li>
</ul>

<h3>Ketersediaan Layanan</h3>
<p>Saya berusaha keras untuk memastikan blog ini dapat diakses setiap saat. Namun, saya tidak bertanggung jawab apabila situs ini menjadi tidak tersedia sementara waktu karena masalah teknis di luar kendali saya atau pemeliharaan server.</p>

<h3>Hubungi Saya</h3>
<p>Jika Anda memiliki pertanyaan mengenai Ketentuan Penggunaan Layanan ini, silakan ajukan pesan Anda melalui formulir Kontak kami.</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'privacy',
                'meta_description' => 'Kebijakan privasi dan perlindungan data pengunjung blog editorial Randi Iskandar Workspace.',
                'is_published' => true,
                'body' => '<p>Privasi Anda sebagai pengunjung adalah hal yang sangat saya hargai di <strong>Randi Iskandar Workspace</strong>. Dokumen Kebijakan Privasi ini menguraikan jenis informasi pribadi yang dikumpulkan dan dicatat oleh situs web ini serta bagaimana informasi tersebut digunakan.</p>

<h3>Informasi yang Kami Kumpulkan</h3>
<p>Saat Anda mengunjungi blog ini, Anda mungkin secara sukarela memberikan informasi pribadi, seperti:</p>
<ul>
    <li><strong>Komentar Artikel:</strong> Nama, alamat email, dan isi komentar yang Anda tinggalkan pada kolom diskusi.</li>
    <li><strong>Formulir Kontak:</strong> Nama, email, subjek, dan pesan saat Anda menghubungi saya secara langsung.</li>
    <li><strong>Data Teknis Otomatis:</strong> Alamat IP, jenis peramban (browser), penyedia layanan internet (ISP), cap waktu kunjungan, dan halaman rujukan yang dikumpulkan secara anonim melalui sistem log server dan analitik internal untuk meningkatkan kualitas bacaan.</li>
</ul>

<h3>Penggunaan Informasi</h3>
<p>Informasi yang terkumpul digunakan semata-mata untuk keperluan berikut:</p>
<ul>
    <li>Menayangkan dan memoderasi komentar publik yang relevan dan membangun.</li>
    <li>Membalas pesan atau pertanyaan yang Anda kirimkan melalui formulir kontak.</li>
    <li>Menganalisis tren bacaan artikel dan keterlibatan pengguna demi menyajikan konten editorial yang lebih bernilai.</li>
    <li>Mencegah aktivitas spam atau serangan keamanan siber terhadap infrastruktur situs.</li>
</ul>

<h3>Penggunaan Cookie & Layanan Pihak Ketiga</h3>
<p>Blog ini menggunakan <em>cookies</em> untuk menyimpan preferensi tampilan pembaca (seperti mode tema gelap atau terang dan daftar artikel baca nanti). Kami juga dapat menampilkan layanan analitik dan jaringan periklanan resmi pihak ketiga (seperti Google AdSense) yang menggunakan cookie untuk menyajikan iklan yang relevan berdasarkan riwayat penjelajahan Anda.</p>

<h3>Hak Pengunjung</h3>
<p>Anda berhak meminta penghapusan riwayat komentar atau data kontak yang pernah Anda kirimkan kapan saja dengan menghubungi kami melalui saluran kontak resmi.</p>',
            ],
            [
                'title' => 'Sanggahan (Disclaimer)',
                'slug' => 'disclaimer',
                'meta_description' => 'Sanggahan dan batasan tanggung jawab konten pada blog editorial Randi Iskandar Workspace.',
                'is_published' => true,
                'body' => '<p>Seluruh informasi dan tulisan yang dipublikasikan di blog <strong>Randi Iskandar</strong> disajikan dengan itikad baik semata-mata untuk tujuan informasi umum, inspirasi desain, dan edukasi seputar teknologi web modern.</p>

<h3>Bukan Nasihat Finansial atau Profesional</h3>
<p>Tulisan, esai, dan opini di blog ini merupakan pandangan pribadi penulis berdasarkan pengalaman langsung dan eksplorasi profesional. Pandangan tersebut tidak boleh dianggap sebagai nasihat hukum, finansial, atau bisnis formal. Setiap tindakan yang Anda ambil berdasarkan informasi di situs ini sepenuhnya menjadi risiko dan tanggung jawab pribadi Anda.</p>

<h3>Akurasi dan Kelengkapan Informasi</h3>
<p>Meskipun saya berupaya semaksimal mungkin untuk menyajikan informasi yang akurat dan relevan pada saat artikel diterbitkan, dunia teknologi dan desain bergerak dengan sangat cepat. Saya tidak memberikan jaminan dalam bentuk apa pun mengenai kelengkapan, ketepatan, atau keandalan informasi yang tertulis.</p>

<h3>Tautan Keluar (External Links)</h3>
<p>Blog ini mungkin memuat tautan menuju situs web eksternal milik pihak ketiga. Meskipun saya berusaha hanya menautkan ke sumber yang terpercaya dan bermanfaat, saya tidak memiliki kendali atas isi atau perubahan konten pada situs-situs eksternal tersebut.</p>',
            ],
            [
                'title' => 'Tentang Randi Iskandar',
                'slug' => 'about',
                'meta_description' => 'Mengenal Randi Iskandar, desainer antarmuka dan penulis esai editorial seputar tipografi dan web modern.',
                'is_published' => true,
                'body' => '<p>Halo! Saya <strong>Randi Iskandar</strong>, seorang desainer antarmuka (UI/UX) dan pengembang web yang memiliki kecintaan mendalam terhadap seni tipografi, ritme visual, dan arsitektur perangkat lunak yang bersih.</p>

<h3>Filosofi Ruang & Tipografi</h3>
<p>Blog editorial ini dirancang sebagai ruang jeda kontemplatif di tengah riuhnya arus informasi digital yang serba cepat. Saya percaya bahwa sebuah halaman bacaan yang bermartabat berakar pada penghormatan terhadap ruang kosong (<em>whitespace</em>), kontras tipografi yang teliti, dan kejelasan pesan di atas ornamen yang berlebihan.</p>

<h3>Apa yang Saya Tulis</h3>
<p>Di blog ini, saya secara berkala mendokumentasikan pemikiran, catatan teknis, dan esai seputar:</p>
<ul>
    <li>Prinsip desain antarmuka berbasis empati dan keterbacaan mata manusia.</li>
    <li>Arsitektur frontend dan performa komputasi web modern.</li>
    <li>Eksplorasi hierarki tipografi editorial digital.</li>
    <li>Catatan perenungan personal mengenai etos kerja dan produktivitas kreatif.</li>
</ul>

<p>Jika Anda tertarik untuk berdiskusi, bertukar gagasan, atau sekadar menyapa hangat, jangan ragu untuk menghubungi saya melalui formulir kontak.</p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Seed Default Navigation Menus
        $menus = [
            // Header Menus
            [
                'label' => 'Esai',
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
            // Footer Menus
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
