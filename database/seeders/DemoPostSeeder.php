<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'randi@example.com')->first() ?? User::first();

        if (! $author) {
            $author = User::create([
                'name' => 'Randi Iskandar',
                'email' => 'randi@example.com',
                'password' => bcrypt('password'),
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop',
                'bio' => 'Pengembang web dan penulis lepas. Membagikan portofolio, opini seputar teknologi, tutorial pemrograman, dan catatan eksplorasi digital.',
            ]);
        }

        $catUX = Category::firstOrCreate(['slug' => 'desain-ux'], [
            'name' => 'Desain UX',
            'description' => 'Eksplorasi antarmuka, tata letak, dan interaksi manusia-komputer yang intuitif.',
        ]);

        $catWeb = Category::firstOrCreate(['slug' => 'arsitektur-web'], [
            'name' => 'Arsitektur Web',
            'description' => 'Pembahasan teknis performa web, state management modern, dan efisiensi komputasi klien.',
        ]);

        $catTypo = Category::firstOrCreate(['slug' => 'tipografi'], [
            'name' => 'Tipografi',
            'description' => 'Seni, keterbacaan, dan matematika proporsi di balik pemilihan tipografi digital.',
        ]);

        $catEssay = Category::firstOrCreate(['slug' => 'esai-personal'], [
            'name' => 'Esai Personal',
            'description' => 'Catatan perenungan, produktivitas, dan filosofi hidup seorang pengembang.',
        ]);

        $postFeatured = Post::firstOrCreate(['slug' => 'harmoni-dalam-kekosongan-memaksimalkan-whitespace'], [
            'user_id' => $author->id,
            'category_id' => $catUX->id,
            'title' => 'Harmoni dalam Kekosongan: Memaksimalkan Whitespace',
            'excerpt' => 'Seringkali kita tergoda untuk mengisi setiap ruang kosong di layar. Padahal, ruang negatif adalah elemen desain yang bernapas, memandu mata pembaca tanpa kata-kata.',
            'body' => <<<'HTML'
<p class="drop-cap">Seringkali kita tergoda untuk mengisi setiap ruang kosong di layar gawai dengan informasi, tombol aksi, atau ornamen visual tambahan. Rasa takut akan ruang kosong—atau yang sering disebut <em>horror vacui</em>—adalah musuh terbesar dalam perancangan antarmuka yang elegan.</p>

<p>Dalam desain antarmuka, ruang negatif bukanlah kekosongan pasif yang terbuang percuma. Ruang negatif adalah instrumen aktif yang bernapas, memandu ritme membaca, dan memberikan bobot emosional pada setiap kata yang tersaji.</p>

<p>Kognisi manusia memiliki batas kapasitas memori kerja (<em>cognitive load</em>). Ketika kita disuguhi tata letak yang padat, otak dipaksa memfilter gangguan visual sebelum sempat mencerna makna esensial konten.</p>

<div class="my-10 text-center py-6 border-y border-zinc-200 dark:border-zinc-800 not-prose">
    <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-zinc-400 block mb-2 font-sans">SPONSOR EDITORIAL</span>
    <p class="font-serif italic text-sm md:text-base text-zinc-600 dark:text-zinc-400">Desain minimalis bukan tren sesaat, melainkan komitmen pada substansi murni.</p>
</div>

<h2>Mendefinisikan Ulang Kekosongan</h2>
<p>Ruang negatif bukan sekadar ketiadaan elemen visual; ia adalah jeda yang sengaja diberikan di antara elemen-elemen desain. Jeda ini memandu pandangan mata dan menetapkan ritme membaca yang nyaman dan tenang bagi pembaca.</p>

<blockquote class="my-8 pl-6 border-l-2 border-zinc-900 dark:border-white not-italic">
    <p class="font-serif italic text-lg sm:text-xl text-zinc-800 dark:text-zinc-200 mb-1">
        “Whitespace is like air: it is necessary for design to breathe.”
    </p>
    <cite class="not-italic text-xs md:text-sm font-sans text-zinc-500 font-medium">— Jan Tschichold</cite>
</blockquote>

<h3>Perbandingan Kerapatan Elemen</h3>
<p>Tabel berikut membandingkan efisiensi waktu retensi pembaca pada beberapa jenis antarmuka dengan kepadatan elemen yang berbeda. Data menunjukkan bahwa ruang kosong yang lebih luas berkorelasi positif dengan fokus pembaca.</p>

<table>
    <thead>
        <tr>
            <th>Jenis Antarmuka</th>
            <th>Kepadatan Elemen</th>
            <th>Waktu Retensi User</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Portal Berita Tradisional</td>
            <td>&gt; 60 elemen/layar</td>
            <td>45 detik</td>
        </tr>
        <tr>
            <td>Medium</td>
            <td>20 - 30 elemen</td>
            <td>2.5 menit</td>
        </tr>
        <tr>
            <td>Minimalist Editorial</td>
            <td>&lt; 20 elemen</td>
            <td>4.1 menit</td>
        </tr>
    </tbody>
</table>

<h2>Penerapan pada Layar Sentuh</h2>
<p>Pada perangkat bergerak (mobile devices), whitespace bukan hanya tentang estetika visual, melainkan juga ergonomi fisik. Jarak antar elemen interaktif mencegah kesalahan sentuh (fat-finger syndrome) yang sering terjadi pada tata letak padat.</p>

<div class="my-8 rounded-sm bg-[#121214] text-white p-8 sm:p-12 flex flex-col items-center justify-center text-center aspect-[16/9] relative overflow-hidden not-prose border border-zinc-800">
    <div class="max-w-xs flex flex-col items-center">
        <i class="ph ph-video-camera-slash text-3xl text-zinc-500 mb-2"></i>
        <h4 class="font-sans font-bold text-base md:text-lg text-white mb-1">Video player configuration error</h4>
        <p class="text-xs md:text-sm text-zinc-400 mb-5">Error code: 232011</p>
        <button type="button" class="px-5 py-2.5 bg-white text-zinc-950 font-bold text-xs md:text-sm rounded-xs hover:bg-zinc-200 transition-colors">
            Muat Ulang Video
        </button>
    </div>
</div>
<p class="text-xs md:text-sm text-zinc-400 text-center -mt-4 mb-8 italic font-sans">Video: Demonstrasi rasio area sentuh pada perangkat mobile.</p>

<div class="text-center my-10 not-prose">
    <a href="#" class="inline-flex items-center gap-2 px-6 py-3.5 bg-zinc-900 !text-white dark:bg-white dark:!text-zinc-900 !no-underline rounded-xs font-sans font-bold text-xs md:text-sm uppercase tracking-wider hover:opacity-90 transition-opacity">
        <i class="ph ph-download-simple text-base"></i> Unduh File Desain Lengkap
    </a>
</div>

<h2>Kesimpulan</h2>
<p>Pada akhirnya, rancangan antarmuka yang baik bukanlah tentang seberapa banyak ornamen yang bisa Anda masukkan ke dalam satu kanvas, melainkan seberapa berani Anda menyisihkan ruang bernapas agar apa yang paling esensial mendapatkan sorotan yang paling layak.</p>

<div class="flex flex-wrap items-center justify-center gap-2 my-10 pt-8 border-t border-zinc-200 dark:border-zinc-800 font-sans text-xs md:text-sm not-prose">
    <span class="text-zinc-400">Kategori & Label:</span>
    <a href="/kategori/desain-ux" class="font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">#DesainUX</a>
    <a href="/arsip" class="font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">#Minimalisme</a>
    <a href="/arsip" class="font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">#Tipografi</a>
</div>
HTML,
            'featured_image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=1200&auto=format&fit=crop',
            'featured_image_caption' => 'Ilustrasi ruang negatif: Kamera analog di atas meja kerja yang lapang.',
            'status' => 'published',
            'is_featured' => true,
            'views_count' => 1420,
            'claps_count' => 142,
            'published_at' => Carbon::now()->subDays(4),
        ]);

        Post::firstOrCreate(['slug' => 'meninggalkan-redux-menyambut-zaman-sinyal'], [
            'user_id' => $author->id,
            'category_id' => $catWeb->id,
            'title' => 'Meninggalkan Redux, Menyambut Zaman Sinyal',
            'excerpt' => 'Mengapa paradigma reaktivitas berbasis sinyal (Signals) merevolusi cara kita mengelola state pada aplikasi web modern tanpa overhead re-rendering berlebih.',
            'body' => <<<'HTML'
<p class="lead">Selama bertahun-tahun, pengelolaan state global di frontend didominasi oleh pendekatan immutability ketat ala Redux. Namun, harga komputasi yang harus dibayar berupa re-render komponen yang tidak perlu seringkali menjadi beban tak kasat mata.</p>

<h2>Pergeseran Menuju Reaktivitas Berbutir Halus</h2>
<p>Zaman sekarang menyaksikan kebangkitan paradigma <strong>Signals</strong> yang diadopsi oleh Solid, Preact, Vue, hingga Angular. Berbeda dengan mekanisme <em>diffing</em> virtual DOM tradisional, sinyal menghubungkan mutasi data langsung ke simpul DOM terkecil secara presisi.</p>

<pre><code class="language-javascript">const count = signal(0);
effect(() => {
    console.log("Nilai saat ini:", count.value);
});

count.value++;</code></pre>

<h3>Efisiensi Tanpa Kompromi</h3>
<p>Dengan mengurangi ketergantungan pada siklus re-render menyeluruh, aplikasi web terasa jauh lebih responsif, hemat memori, dan menyenangkan untuk dikembangkan.</p>
HTML,
            'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=1200&auto=format&fit=crop',
            'status' => 'published',
            'is_featured' => false,
            'views_count' => 3200,
            'published_at' => Carbon::now()->subWeeks(2),
        ]);

        Post::firstOrCreate(['slug' => 'memilih-font-sebuah-pendekatan-matematis-dan-emosional'], [
            'user_id' => $author->id,
            'category_id' => $catTypo->id,
            'title' => 'Memilih Font: Sebuah Pendekatan Matematis & Emosional',
            'excerpt' => 'Tipografi bukan sekadar memilih mana yang terlihat bagus. Ada rasio emas, x-height, dan psikologi bentuk di balik setiap huruf yang tampil di layar gawai pengguna.',
            'body' => <<<'HTML'
<p class="lead">Tipografi adalah arsitektur kata-kata. Saat kita memilih jenis huruf untuk publikasi editorial, kita sedang menetapkan nada suara dan atmosfer perbincangan batin pembaca.</p>

<h2>Rasio Geometris dan Keterbacaan</h2>
<p>Memadukan font sans-serif modern seperti <em>Inter</em> untuk judul dengan font serif klasik seperti <em>Georgia</em> untuk teks narasi menciptakan kontras harmonis antara presisi teknis dan kehangatan literatur.</p>
HTML,
            'featured_image' => 'https://images.unsplash.com/photo-1563452675059-efa1e2e7a787?q=80&w=1200&auto=format&fit=crop',
            'status' => 'published',
            'is_featured' => false,
            'views_count' => 2150,
            'published_at' => Carbon::now()->subMonths(3),
        ]);

        Post::firstOrCreate(['slug' => 'miskonsepsi-seputar-desain-minimalis'], [
            'user_id' => $author->id,
            'category_id' => $catUX->id,
            'title' => 'Miskonsepsi Seputar Desain Minimalis',
            'excerpt' => 'Mendefinisikan ulang makna minimalisme dalam antarmuka. Mengapa menyembunyikan fitur di balik menu dropdown yang rumit bukan berarti desain Anda sudah minimalis.',
            'body' => '<p class="lead">Minimalisme sering disalahartikan sebagai sekadar serba putih dan membuang elemen penting. Padahal, minimalisme yang sejati adalah kejelasan fungsi.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1200&auto=format&fit=crop',
            'status' => 'published',
            'is_featured' => false,
            'views_count' => 1890,
            'published_at' => Carbon::now()->subMonths(6),
        ]);

        Post::firstOrCreate(['slug' => 'evolusi-arsitektur-web-mengapa-sinyal-menang'], [
            'user_id' => $author->id,
            'category_id' => $catWeb->id,
            'title' => 'Evolusi Arsitektur Web: Mengapa Sinyal Menang?',
            'excerpt' => 'Analisis mendalam mengenai masa depan state management di web frontend.',
            'body' => '<p>Draf artikel yang sedang dalam tahap penulisan lanjutan.</p>',
            'status' => 'draft',
            'is_featured' => false,
            'views_count' => 0,
            'published_at' => null,
        ]);

        if ($postFeatured) {
            $parentComment1 = Comment::firstOrCreate(
                ['post_id' => $postFeatured->id, 'email' => 'budi@example.com'],
                [
                    'name' => 'Budi Santoso',
                    'website' => 'https://budisantoso.id',
                    'comment' => 'Tulisan yang sangat membuka wawasan. Terkadang saya terlalu fokus memadati layar klien karena takut dianggap tidak bekerja maksimal bila ada banyak ruang kosong.',
                    'is_approved' => true,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 Chrome/128.0',
                    'created_at' => Carbon::now()->subHours(5),
                ]
            );

            Comment::firstOrCreate(
                ['post_id' => $postFeatured->id, 'user_id' => $author->id],
                [
                    'parent_id' => $parentComment1->id,
                    'name' => 'Randi Iskandar',
                    'email' => 'randi@example.com',
                    'website' => null,
                    'comment' => 'Terima kasih, Budi! Mengedukasi klien tentang fungsi whitespace memang butuh kesabaran, namun hasilnya selalu terbayar dengan keterbacaan yang jauh lebih baik.',
                    'is_approved' => true,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 Chrome/128.0',
                    'created_at' => Carbon::now()->subHours(2),
                ]
            );

            Comment::firstOrCreate(
                ['post_id' => $postFeatured->id, 'email' => 'annisa@example.com'],
                [
                    'name' => 'Annisa N.',
                    'website' => null,
                    'comment' => 'Setuju sekali. Khususnya pada bagian area sentuh (touch target) di perangkat mobile, whitespace adalah penyelamat pengalaman pengguna.',
                    'is_approved' => true,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 Chrome/128.0',
                    'created_at' => Carbon::now()->subDay(),
                ]
            );

            Comment::firstOrCreate(
                ['post_id' => $postFeatured->id, 'email' => 'rian@example.com'],
                [
                    'name' => 'Rian Hidayat',
                    'website' => null,
                    'comment' => 'Komentar ini menunggu persetujuan admin untuk menguji antarmuka moderasi pada dasbor workspace.',
                    'is_approved' => false,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 Chrome/128.0',
                    'created_at' => Carbon::now()->subMinutes(30),
                ]
            );
        }
    }
}
