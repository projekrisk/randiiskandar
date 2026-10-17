<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DemoPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(DemoPostSeeder::class);
    }

    public function test_homepage_loads_successfully_with_author_and_posts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Randi Iskandar');
        $response->assertSee('Harmoni dalam Kekosongan');
        $response->assertSee('Terbitan Terbaru');
    }

    public function test_detail_article_loads_with_toc_and_comments(): void
    {
        $post = Post::published()->first();

        $response = $this->get(route('post.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee('Komentar');
        $response->assertSee('SPONSOR EDITORIAL'); // In-article ad
        $response->assertSee('max-w-3xl');
        $response->assertSee('ph-whatsapp-logo');
        $response->assertSee('ph-facebook-logo');
        $response->assertSee('ph-instagram-logo');
        $response->assertSee('ph-x-logo');
        $response->assertSee('reply-slot-');
        $response->assertSee('clap-trigger');
        $response->assertSee('listen-article-btn'); // Fitur Dengarkan
        $response->assertSee('audio-player-bar');
        $response->assertSee('ph-pen-nib'); // Ikon penulis
        $response->assertSee('save_identity');
    }

    public function test_post_clap_endpoint_increments_claps_count(): void
    {
        $post = Post::published()->first();
        $initialClaps = $post->claps_count;

        $response = $this->postJson('/baca/'.$post->slug.'/apresiasi');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'claps_count' => $initialClaps + 1,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'claps_count' => $initialClaps + 1,
        ]);
    }

    public function test_category_archive_loads_successfully(): void
    {
        $category = Category::first();

        $response = $this->get('/kategori/'.$category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_static_pages_render_successfully(): void
    {
        $responseAbout = $this->get('/tentang');
        $responseAbout->assertStatus(200);
        $responseAbout->assertSee('Tentang Randi Iskandar');
        $responseAbout->assertDontSee('Potret Randi Iskandar');
        $responseContact = $this->get('/kontak');
        $responseContact->assertStatus(200);
        $responseContact->assertSee('Mari Terhubung.');
        $responseContact->assertSee('Surel Elektronik (Email)');
        $responseContact->assertSee('');
        $responseContact->assertSee('Media Sosial');
        $responseContact->assertDontSee('<form action="', false);
        $this->get('/terms')->assertStatus(200)->assertSee('Penggunaan Layanan');
        $this->get('/disclaimer')->assertStatus(200)->assertSee('Sanggahan');
    }

    public function test_search_endpoint_returns_json(): void
    {
        $response = $this->getJson('/cari?q=Harmoni');

        $response->assertStatus(200);
        $response->assertJsonStructure(['query', 'count', 'results']);
        $response->assertJsonFragment(['slug' => 'harmoni-dalam-kekosongan-memaksimalkan-whitespace']);
    }

    public function test_comment_submission_with_validation_and_honeypot(): void
    {
        $post = Post::published()->first();

        $response = $this->post('/baca/'.$post->slug.'/komentar', [
            'name' => 'Komentator Baru',
            'email' => 'baru@example.com',
            'comment' => 'Artikel ini sangat mendalam dan bermanfaat.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'name' => 'Komentator Baru',
            'is_approved' => false,
        ]);
    }

    public function test_comment_honeypot_silently_discards_bot(): void
    {
        $post = Post::published()->first();

        $this->post('/baca/'.$post->slug.'/komentar', [
            'hp_company_name' => 'Spam Bot LLC',
            'name' => 'Spammer',
            'email' => 'spam@bot.com',
            'comment' => 'Buy cheap links now',
        ]);

        $this->assertDatabaseMissing('comments', [
            'name' => 'Spammer',
        ]);
    }

    public function test_admin_dashboard_requires_auth(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::first();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')->assertStatus(200)->assertSee('Ikhtisar Dasbor');
    }

    public function test_admin_can_create_post(): void
    {
        $admin = User::first();
        $category = Category::first();

        $response = $this->actingAs($admin)->post('/admin/posts', [
            'title' => 'Tulisan Baru dari Admin',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan singkat tulisan.',
            'body' => '<p>Konten artikel...</p><h2>Sub Judul</h2><p>Paragraf kedua.</p>',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $response->assertRedirect('/admin/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'Tulisan Baru dari Admin',
            'slug' => 'tulisan-baru-dari-admin',
            'is_featured' => true,
            'status' => 'published',
        ]);
    }

    public function test_admin_can_approve_and_delete_comment(): void
    {
        $admin = User::first();
        $pendingComment = Comment::create([
            'post_id' => Post::first()->id,
            'name' => 'Pending Tester',
            'email' => 'pending@test.com',
            'comment' => 'Komentar untuk moderasi',
            'is_approved' => false,
        ]);

        $this->actingAs($admin)->post("/admin/comments/{$pendingComment->id}/approve")
            ->assertRedirect();
        $this->assertTrue($pendingComment->fresh()->is_approved);

        $this->actingAs($admin)->delete("/admin/comments/{$pendingComment->id}")
            ->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $pendingComment->id]);
    }

    public function test_admin_can_create_post_with_tags_and_featured_image_file(): void
    {
        Storage::fake('public');
        $admin = User::first();
        $category = Category::first();
        $file = UploadedFile::fake()->image('thumbnail.png', 1200, 630);

        $response = $this->actingAs($admin)->post('/admin/posts', [
            'title' => 'Tulisan dengan Tag dan Gambar Unggahan',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan esai',
            'body' => '<p>Konten artikel dengan tag.</p>',
            'tags' => 'Tipografi, Desain Web, Laravel',
            'featured_image_file' => $file,
            'status' => 'published',
            'is_featured' => true,
        ]);

        $response->assertRedirect('/admin/posts');

        $post = Post::where('title', 'Tulisan dengan Tag dan Gambar Unggahan')->first();
        $this->assertNotNull($post);
        $this->assertStringContainsString('Tipografi', $post->tags);
        $this->assertEquals(['Tipografi', 'Desain Web', 'Laravel'], $post->tags_list);
        $this->assertNotNull($post->featured_image);

        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('#Tipografi');
        $detailResponse->assertSee('#Desain Web');
        $detailResponse->assertSee('#Laravel');
    }

    public function test_admin_can_upload_inline_image_for_editor(): void
    {
        Storage::fake('public');
        $admin = User::first();
        $file = UploadedFile::fake()->image('content-figure.jpg', 800, 600);

        $response = $this->actingAs($admin)->postJson('/admin/posts/upload-image', [
            'image' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure(['success', 'url', 'message']);
    }

    public function test_favicon_is_present_in_layout_and_admin(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('icon.png');

        $admin = User::first();
        $adminResponse = $this->actingAs($admin)->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('icon.png');
        $adminResponse->assertSee('universal-delete-modal');
    }

    public function test_admin_can_update_category_with_description(): void
    {
        $admin = User::first();
        $category = Category::first();

        $response = $this->actingAs($admin)->put('/admin/categories/'.$category->id, [
            'name' => 'Kategori Diperbarui',
            'slug' => 'kategori-diperbarui',
            'description' => 'Keterangan lengkap untuk direktori arsip editorial.',
        ]);

        $response->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kategori Diperbarui',
            'slug' => 'kategori-diperbarui',
            'description' => 'Keterangan lengkap untuk direktori arsip editorial.',
        ]);
    }

    public function test_archive_displays_category_filters(): void
    {
        $category = Category::whereNotNull('description')->first();
        $this->assertNotNull($category);

        $response = $this->get('/arsip');
        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertDontSee('Direktori & Keterangan Kategori');

        $responseCategory = $this->get('/kategori/'.$category->slug);
        $responseCategory->assertStatus(200);
        $responseCategory->assertSee($category->description);
    }

    public function test_comment_is_blocked_when_containing_banned_words(): void
    {
        $post = Post::published()->first();
        Setting::set('banned_words', 'judi, slot, spam, penipuan');

        $response = $this->post('/baca/'.$post->slug.'/komentar', [
            'name' => 'Spammer Bot',
            'email' => 'spammer@example.com',
            'comment' => 'Daftar sekarang untuk bermain judi slot terbaik hari ini!',
        ]);

        $response->assertSessionHasErrors('comment');
        $this->assertDatabaseMissing('comments', [
            'email' => 'spammer@example.com',
        ]);
    }

    public function test_admin_can_update_banned_words_filter(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->post('/admin/comments/filter', [
            'banned_words' => 'kasino, togel, bot, hack, obat palsu',
        ]);

        $response->assertRedirect('/admin/comments?tab=filter');
        $this->assertEquals('kasino, togel, bot, hack, obat palsu', Setting::get('banned_words'));
    }

    public function test_admin_comments_page_displays_filter_tab(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/comments?tab=filter');
        $response->assertStatus(200);
        $response->assertSee('Filter Kata Terlarang');
        $response->assertSeeText('Filter Anti-Spam & Kata Terlarang');
        $response->assertSee('Daftar Kata Terlarang');
    }

    public function test_previously_created_tags_are_collected_and_suggested_in_editor(): void
    {
        $admin = User::first();
        $category = Category::first();

        Post::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'title' => 'Artikel Berisi Tag Kustom',
            'slug' => 'artikel-berisi-tag-kustom',
            'body' => '<p>Konten artikel</p>',
            'status' => 'published',
            'tags' => 'CustomFramework, ReactModern, InovasiDesign',
            'published_at' => now(),
        ]);

        $allTags = Post::getAllUsedTags();
        $this->assertContains('CustomFramework', $allTags);
        $this->assertContains('ReactModern', $allTags);
        $this->assertContains('InovasiDesign', $allTags);

        $responseCreate = $this->actingAs($admin)->get('/admin/posts/create');
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('CustomFramework');
        $responseCreate->assertSee('ReactModern');
        $responseCreate->assertSee('InovasiDesign');
    }

    public function test_admin_posts_and_comments_paginate_ten_items_per_page(): void
    {
        $admin = User::first();
        $category = Category::first();

        while (Post::count() < 12) {
            Post::create([
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'title' => 'Artikel Tambahan '.uniqid(),
                'slug' => 'artikel-tambahan-'.uniqid(),
                'body' => '<p>Konten</p>',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $responsePosts = $this->actingAs($admin)->get('/admin/posts');
        $responsePosts->assertStatus(200);
        $this->assertEquals(10, $responsePosts->viewData('posts')->perPage());

        $post = Post::first();
        while (Comment::count() < 12) {
            Comment::create([
                'post_id' => $post->id,
                'name' => 'Tester '.uniqid(),
                'email' => 'test'.uniqid().'@example.com',
                'comment' => 'Komentar dummy',
                'is_approved' => true,
            ]);
        }

        $responseComments = $this->actingAs($admin)->get('/admin/comments');
        $responseComments->assertStatus(200);
        $this->assertEquals(10, $responseComments->viewData('comments')->perPage());
    }

    public function test_admin_views_have_full_width_containers(): void
    {
        $admin = User::first();
        $post = Post::first();

        $this->actingAs($admin)->get('/admin/posts/create')
            ->assertStatus(200)
            ->assertDontSee('max-w-4xl mx-auto');

        $this->actingAs($admin)->get("/admin/posts/{$post->id}/edit")
            ->assertStatus(200)
            ->assertDontSee('max-w-4xl mx-auto');

        $this->actingAs($admin)->get('/admin/comments')
            ->assertStatus(200)
            ->assertDontSee('max-w-5xl mx-auto');

        $this->actingAs($admin)->get('/admin/categories')
            ->assertStatus(200)
            ->assertDontSee('max-w-4xl mx-auto');
    }

    public function test_admin_can_access_settings_page(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Workspace');
        $response->assertSeeText('Profil & Bio Penulis');
        $response->assertSeeText('Iklan & Artikel');
    }

    public function test_admin_can_update_profile_avatar_and_bio(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->post('/admin/settings/profile', [
            'name' => 'Randi Iskandar Senior',
            'bio' => 'Penulis esai editorial dengan fokus reduksi visual dan tipografi modern.',
            'avatar' => 'https://images.unsplash.com/photo-custom-avatar.jpg',
        ]);

        $response->assertRedirect('/admin/settings?tab=profile');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Randi Iskandar Senior',
            'bio' => 'Penulis esai editorial dengan fokus reduksi visual dan tipografi modern.',
            'avatar' => 'https://images.unsplash.com/photo-custom-avatar.jpg',
        ]);

        $post = Post::published()->first();
        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Randi Iskandar Senior');
        $detailResponse->assertSee('Penulis esai editorial dengan fokus reduksi visual dan tipografi modern.');
    }

    public function test_admin_can_update_ads_settings_and_toggle_visibility(): void
    {
        $admin = User::first();
        $post = Post::published()->first();

        $responseDisable = $this->actingAs($admin)->post('/admin/settings/ads', [
            'ad_in_article_enabled' => 0,
            'ad_bottom_enabled' => 0,
            'ad_in_article_code' => '',
            'ad_bottom_code' => '',
            'baca_juga_enabled' => 0,
        ]);
        $responseDisable->assertRedirect('/admin/settings?tab=ads');
        $this->assertEquals('0', Setting::get('ad_in_article_enabled'));
        $this->assertEquals('0', Setting::get('ad_bottom_enabled'));
        $this->assertEquals('0', Setting::get('baca_juga_enabled'));

        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertDontSee('adsense-in-article-container');
        $detailResponse->assertDontSee('adsense-bottom-container');

        $customInArticle = '<div id="custom-in-article-sponsor">Iklan Sponsor Tengah</div>';
        $customBottom = '<div id="custom-bottom-sponsor">Iklan Sponsor Bawah</div>';

        $responseEnable = $this->actingAs($admin)->post('/admin/settings/ads', [
            'ad_in_article_enabled' => 1,
            'ad_bottom_enabled' => 1,
            'ad_in_article_code' => $customInArticle,
            'ad_bottom_code' => $customBottom,
            'baca_juga_enabled' => 1,
        ]);
        $responseEnable->assertRedirect('/admin/settings?tab=ads');
        $this->assertEquals('1', Setting::get('ad_in_article_enabled'));
        $this->assertEquals('1', Setting::get('ad_bottom_enabled'));
        $this->assertEquals('1', Setting::get('baca_juga_enabled'));

        $detailWithAds = $this->get(route('post.show', $post->slug));
        $detailWithAds->assertStatus(200);
        $detailWithAds->assertSee('custom-in-article-sponsor', false);
        $detailWithAds->assertSee('custom-bottom-sponsor', false);
    }

    public function test_admin_layout_has_collapsible_sidebar_controls(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Tulisan Terbaru');
        $response->assertDontSee('Komentar Terakhir');
        $response->assertSee('id="topbar-sidebar-toggle"', false);
        $response->assertSee('admin_sidebar_collapsed');
        $response->assertSee('sidebar-tooltip');
    }

    public function test_in_article_baca_juga_is_automatically_injected_from_same_category(): void
    {
        Setting::set('baca_juga_enabled', '1');
        $category = Category::create([
            'name' => 'Kategori Riset '.uniqid(),
            'slug' => 'kategori-riset-'.uniqid(),
        ]);
        $admin = User::first();

        $postA = Post::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'title' => 'Artikel Induk Riset '.uniqid(),
            'slug' => 'artikel-induk-riset-'.uniqid(),
            'body' => '<p>Paragraf 1 yang cukup panjang.</p><p>Paragraf 2 membahas metodologi.</p><p>Paragraf 3 memaparkan hasil pengujian antarmuka.</p><p>Paragraf 4 tentang kognisi visual.</p><p>Paragraf 5 tentang ruang negatif.</p><p>Paragraf 6 kesimpulan akhir riset.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $postB = Post::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'title' => 'Rekomendasi Terkait Senada '.uniqid(),
            'slug' => 'rekomendasi-terkait-senada-'.uniqid(),
            'body' => '<p>Konten artikel pendamping yang berada pada kategori yang sama.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('post.show', $postA->slug));
        $response->assertStatus(200);
        $response->assertSee('BACA JUGA');
        $response->assertSee($postB->title);
        $response->assertSee(route('post.show', $postB->slug));

        $html = $response->getContent();
        $this->assertEquals(1, substr_count($html, 'BACA JUGA'));

        $articleHtml = $postA->body_with_injected_ad;
        $posP3 = strpos($articleHtml, 'Paragraf 3 memaparkan hasil pengujian antarmuka.');
        $posBacaJuga = strpos($articleHtml, 'BACA JUGA');
        $posP4 = strpos($articleHtml, 'Paragraf 4 tentang kognisi visual.');

        $this->assertNotFalse($posP3);
        $this->assertNotFalse($posBacaJuga);
        $this->assertNotFalse($posP4);
        $this->assertTrue($posP3 < $posBacaJuga, 'Paragraf 3 harus muncul sebelum BACA JUGA');
        $this->assertTrue($posBacaJuga < $posP4, 'BACA JUGA harus muncul sebelum Paragraf 4');
    }

    public function test_baca_juga_is_random_and_falls_back_when_single_post_in_category(): void
    {
        Setting::set('baca_juga_enabled', '1');
        $admin = User::first();

        $isolatedCategory = Category::create([
            'name' => 'Kategori Solo '.uniqid(),
            'slug' => 'kategori-solo-'.uniqid(),
        ]);

        $soloPost = Post::create([
            'user_id' => $admin->id,
            'category_id' => $isolatedCategory->id,
            'title' => 'Artikel Tunggal '.uniqid(),
            'slug' => 'artikel-tunggal-'.uniqid(),
            'body' => '<p>Paragraf 1 pendahuluan.</p><p>Paragraf 2 isi tulisan.</p><p>Paragraf 3 ulasan mendalam.</p><p>Paragraf 4 evaluasi hasil.</p><p>Paragraf 5 penutup.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $related = $soloPost->getRelatedCategoryPost();
        $this->assertNotNull($related);
        $this->assertNotEquals($soloPost->id, $related->id);

        $response = $this->get(route('post.show', $soloPost->slug));
        $response->assertStatus(200);
        $response->assertSee('BACA JUGA');

        $articleHtml = $soloPost->body_with_injected_ad;
        $posP3 = strpos($articleHtml, 'Paragraf 3 ulasan mendalam.');
        $posBacaJuga = strpos($articleHtml, 'BACA JUGA');
        $posP4 = strpos($articleHtml, 'Paragraf 4 evaluasi hasil.');

        $this->assertNotFalse($posP3);
        $this->assertNotFalse($posBacaJuga);
        $this->assertNotFalse($posP4);
        $this->assertTrue($posP3 < $posBacaJuga, 'Paragraf 3 harus muncul sebelum BACA JUGA');
        $this->assertTrue($posBacaJuga < $posP4, 'BACA JUGA harus muncul sebelum Paragraf 4');
    }

    public function test_admin_can_access_analytics_page(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/analytics');
        $response->assertStatus(200);
        $response->assertSeeText('Analitik Tayangan, Komentar & Artikel');
        $response->assertSee('Rekapitulasi Bulanan');
        $response->assertSee('monthlyTrendChart');
        $response->assertSee('categoryDistributionChart');
        $response->assertSee('Cetak Laporan');
        $response->assertSee('Ekspor CSV');
    }

    public function test_admin_can_filter_analytics_by_year_and_month(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/analytics?year=2026&month=10');
        $response->assertStatus(200);
        $response->assertSee('Oktober');
        $response->assertSee('Tahun 2026');
    }

    public function test_admin_can_export_analytics_csv(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/analytics/export?year=2026');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_post_view_is_recorded_in_post_views_table(): void
    {
        $post = Post::published()->first();
        $date = now()->toDateString();

        $initialViews = PostView::where('post_id', $post->id)->where('viewed_date', $date)->value('views') ?? 0;

        $this->get(route('post.show', $post->slug))->assertStatus(200);

        $newViews = PostView::where('post_id', $post->id)->whereDate('viewed_date', $date)->value('views');
        $this->assertGreaterThanOrEqual($initialViews + 1, $newViews);
    }

    public function test_baca_juga_does_not_appear_in_post_editor_and_is_stripped_on_save(): void
    {
        $admin = User::first();
        $category = Category::first();

        $post = Post::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'title' => 'Artikel Uji Editor Rekomendasi '.uniqid(),
            'slug' => 'artikel-uji-editor-rekomendasi-'.uniqid(),
            'body' => '<p>Paragraf awal.</p><div class="not-prose"><span class="font-bold">BACA JUGA</span><a href="#">Link Rekomendasi</a></div><p>Paragraf akhir.</p>',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.posts.edit', $post->id));
        $response->assertStatus(200);
        $response->assertDontSee('BACA JUGA');
        $response->assertSee('Paragraf awal.');
        $response->assertSee('Paragraf akhir.');

        $updateResponse = $this->actingAs($admin)->put(route('admin.posts.update', $post->id), [
            'title' => $post->title,
            'slug' => $post->slug,
            'category_id' => $category->id,
            'status' => 'published',
            'body' => '<p>Konten baru paragraf 1.</p><div class="not-prose">BACA JUGA artikel lain</div><p>Konten baru paragraf 2.</p>',
        ]);

        $updateResponse->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertStringNotContainsString('BACA JUGA', $post->body);
        $this->assertStringContainsString('Konten baru paragraf 1.', $post->body);
        $this->assertStringContainsString('Konten baru paragraf 2.', $post->body);

        $createResponse = $this->actingAs($admin)->get(route('admin.posts.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertDontSee('BACA JUGA');

        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Baru Uji Stripping '.uniqid(),
            'category_id' => $category->id,
            'status' => 'draft',
            'body' => '<p>Paragraf pembuka.</p><div class="not-prose">BACA JUGA: Artikel lama</div><p>Paragraf penutup.</p>',
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));
        $createdPost = Post::latest('id')->first();
        $this->assertStringNotContainsString('BACA JUGA', $createdPost->body);
        $this->assertStringContainsString('Paragraf pembuka.', $createdPost->body);
        $this->assertStringContainsString('Paragraf penutup.', $createdPost->body);
    }

    public function test_featured_image_wide_setting_and_article_image_preview(): void
    {
        $admin = User::first();
        $category = Category::first();

        $createPageResponse = $this->actingAs($admin)->get(route('admin.posts.create'));
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSee('Lebar Tampilan Gambar Utama di Halaman Baca');
        $createPageResponse->assertSee('name="is_featured_image_wide"', false);

        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Gambar Penuh '.uniqid(),
            'category_id' => $category->id,
            'status' => 'published',
            'body' => '<p>Paragraf artikel pertama yang menarik.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=1200',
            'featured_image_caption' => 'Kamera Analog Minimalis di Atas Meja',
            'is_featured_image_wide' => '1',
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));

        $postWide = Post::latest('id')->first();
        $this->assertTrue($postWide->is_featured_image_wide);

        $detailResponse = $this->get(route('post.show', $postWide->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('max-w-5xl', false);
        $detailResponse->assertSee('image-preview-modal');
        $detailResponse->assertSee('data-image-preview', false);
        $detailResponse->assertSee('Kamera Analog Minimalis di Atas Meja');

        $updateResponse = $this->actingAs($admin)->put(route('admin.posts.update', $postWide->id), [
            'title' => $postWide->title,
            'slug' => $postWide->slug,
            'category_id' => $category->id,
            'status' => 'published',
            'body' => '<p>Paragraf artikel pertama yang menarik.</p>',
            'featured_image' => $postWide->featured_image,
            'featured_image_caption' => $postWide->featured_image_caption,
            'is_featured_image_wide' => '0',
        ]);
        $updateResponse->assertRedirect(route('admin.posts.index'));

        $postWide->refresh();
        $this->assertFalse($postWide->is_featured_image_wide);

        $detailResponseStandard = $this->get(route('post.show', $postWide->slug));
        $detailResponseStandard->assertStatus(200);
        $detailResponseStandard->assertSee('max-w-3xl', false);
    }

    public function test_article_table_html_is_clean_semantic_without_css_parameters(): void
    {
        $admin = User::first();
        $category = Category::first();

        $clutteredBody = '
            <p>Paragraf pembuka artikel tentang analisis data antarmuka pengguna.</p>
            <div class="overflow-x-auto my-8 border border-zinc-200 dark:border-zinc-800 rounded-xs not-prose">
                <table class="w-full text-left font-sans text-xs border-collapse" style="background-color: #fff; width: 100%;">
                    <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-900" style="padding: 10px;">
                        <tr class="divide-x divide-zinc-200" style="height: 40px;">
                            <th class="px-4 py-3 font-bold" style="color: black;" colspan="2">Header Gabungan</th>
                            <th class="px-4 py-3 font-bold">Kolom 3</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        <tr class="hover:bg-zinc-50" style="margin: 0;">
                            <td class="px-4 py-3" style="font-weight: normal;">Data Baris 1</td>
                            <td class="px-4 py-3">Nilai A</td>
                            <td class="px-4 py-3">Keterangan X</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p>Paragraf penutup pembahasan data antarmuka.</p>
        ';

        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Uji Tabel Semantik '.uniqid(),
            'category_id' => $category->id,
            'status' => 'published',
            'body' => $clutteredBody,
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));

        $post = Post::latest('id')->first();

        $this->assertStringNotContainsString('class="overflow-x-auto', $post->body);
        $this->assertStringNotContainsString('style="background-color', $post->body);
        $this->assertStringNotContainsString('class="w-full text-left', $post->body);
        $this->assertStringNotContainsString('style="padding: 10px;"', $post->body);

        $this->assertStringContainsString('<table>', $post->body);
        $this->assertStringContainsString('<thead>', $post->body);
        $this->assertStringContainsString('<tr>', $post->body);
        $this->assertStringContainsString('<th colspan="2">Header Gabungan</th>', $post->body);
        $this->assertStringContainsString('<th>Kolom 3</th>', $post->body);
        $this->assertStringContainsString('<tbody>', $post->body);
        $this->assertStringContainsString('<td>Data Baris 1</td>', $post->body);

        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('<table>', false);
        $detailResponse->assertSee('Header Gabungan');
        $detailResponse->assertSee('Data Baris 1');
    }

    public function test_new_article_starts_with_zero_claps_and_supports_realtime_claps_sync(): void
    {
        $admin = User::first();
        $category = Category::first();

        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Baru Apresiasi Realtime '.uniqid(),
            'category_id' => $category->id,
            'status' => 'published',
            'body' => '<p>Paragraf pembuka artikel baru yang segar.</p>',
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));

        $post = Post::latest('id')->first();

        $this->assertSame(0, (int) $post->claps_count);

        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('<span class="clap-count font-semibold text-xs sm:text-sm text-zinc-900 dark:text-white">0</span>', false);
        $detailResponse->assertSee('<span class="clap-count font-bold text-sm md:text-base text-zinc-900 dark:text-white">0</span>', false);

        $getClapsResponse = $this->getJson(route('post.claps', $post->slug));
        $getClapsResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'claps_count' => 0,
            ]);

        $postClapResponse = $this->postJson(route('post.clap', $post->slug));
        $postClapResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'claps_count' => 1,
            ]);

        $post->refresh();
        $this->assertSame(1, (int) $post->claps_count);

        $getClapsResponse2 = $this->getJson(route('post.claps', $post->slug));
        $getClapsResponse2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'claps_count' => 1,
            ]);
    }

    public function test_article_renders_bullets_and_numbering_for_audio_narration(): void
    {
        $admin = User::first();
        $category = Category::first();

        $contentWithLists = '
            <p>Paragraf pembuka artikel panduan desain.</p>
            <ul>
                <li>Prinsip kesederhanaan ruang negatif</li>
                <li>Kontras tipografi yang seimbang</li>
            </ul>
            <ol>
                <li>Langkah pertama riset pengguna</li>
                <li>Langkah kedua membuat purwarupa wireframe</li>
            </ol>
            <p>Paragraf penutup panduan desain antarmuka.</p>
        ';

        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Panduan Desain dengan Bullets dan Numbering '.uniqid(),
            'category_id' => $category->id,
            'status' => 'published',
            'body' => $contentWithLists,
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));

        $post = Post::latest('id')->first();

        $detailResponse = $this->get(route('post.show', $post->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('<ul>', false);
        $detailResponse->assertSee('<ol>', false);
        $detailResponse->assertSee('<li>Prinsip kesederhanaan ruang negatif</li>', false);
        $detailResponse->assertSee('<li>Langkah pertama riset pengguna</li>', false);
    }

    public function test_article_and_homepage_og_image_meta_tags_fallback_to_og_image_banner(): void
    {
        $user = User::first();
        $category = Category::first();

        $postWithoutImage = Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Artikel Tanpa Gambar Utama '.uniqid(),
            'slug' => 'artikel-tanpa-gambar-'.uniqid(),
            'excerpt' => 'Kutipan artikel tanpa gambar.',
            'body' => '<p>Isi artikel esai tanpa gambar unggulan.</p>',
            'featured_image' => null,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $responseNoImg = $this->get(route('post.show', $postWithoutImage->slug));
        $responseNoImg->assertStatus(200);
        $responseNoImg->assertSee('<meta property="og:image" content="'.asset('og-image.jpg').'">', false);
        $responseNoImg->assertSee('<meta name="twitter:image" content="'.asset('og-image.jpg').'">', false);

        $customImageUrl = 'https://images.unsplash.com/photo-custom-post-image';
        $postWithImage = Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Artikel Dengan Gambar Utama '.uniqid(),
            'slug' => 'artikel-dengan-gambar-'.uniqid(),
            'excerpt' => 'Kutipan artikel dengan gambar.',
            'body' => '<p>Isi artikel esai dengan gambar unggulan.</p>',
            'featured_image' => $customImageUrl,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $responseWithImg = $this->get(route('post.show', $postWithImage->slug));
        $responseWithImg->assertStatus(200);
        $responseWithImg->assertSee('<meta property="og:image" content="'.$customImageUrl.'">', false);
        $responseWithImg->assertSee('<meta name="twitter:image" content="'.$customImageUrl.'">', false);
    }

    public function test_article_lists_paginate_five_items_with_load_more_button(): void
    {
        $category = Category::first();
        $user = User::first();

        while (Post::published()->where('is_featured', false)->count() < 6) {
            Post::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => 'Artikel Tambahan Feed '.uniqid(),
                'slug' => 'artikel-tambahan-feed-'.uniqid(),
                'excerpt' => 'Kutipan artikel.',
                'body' => '<p>Konten artikel.</p>',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subMinutes(5),
            ]);
        }

        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $this->assertEquals(5, $responseHome->viewData('latestPosts')->perPage());
        $responseHome->assertSee('id="btn-load-more"', false);
        $responseHome->assertSee('Muat Lainnya');
        $responseHome->assertDontSee('Jelajahi Arsip Lengkap');

        $responseArchive = $this->get('/arsip');
        $responseArchive->assertStatus(200);
        $this->assertEquals(5, $responseArchive->viewData('posts')->perPage());
        $responseArchive->assertSee('id="btn-load-more"', false);
        $responseArchive->assertSee('Muat Lainnya');

        $category = Category::first();
        $user = User::first();
        while ($category->posts()->published()->count() < 6) {
            Post::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => 'Artikel Kategori Tambahan '.uniqid(),
                'slug' => 'artikel-kategori-'.uniqid(),
                'excerpt' => 'Kutipan artikel.',
                'body' => '<p>Konten artikel.</p>',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $responseCat = $this->get('/kategori/'.$category->slug);
        $responseCat->assertStatus(200);
        $this->assertEquals(5, $responseCat->viewData('posts')->perPage());
        $responseCat->assertSee('id="btn-load-more"', false);
        $responseCat->assertSee('Muat Lainnya');
    }

    public function test_homepage_displays_profile_section_with_instagram_and_github_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Randi Iskandar');
        $response->assertSee('Pengembang & Penulis', false);
        $response->assertSee('ph-github-logo');
        $response->assertSee('ph-instagram-logo');
        $response->assertSee('GitHub');
        $response->assertSee('Instagram');
        $response->assertSee('https://github.com/randiiskandar');
        $response->assertSee('https://instagram.com/r.and.i');
    }

    public function test_admin_can_update_social_links(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->post('/admin/settings/profile', [
            'name' => 'Randi Iskandar',
            'bio' => 'Bio baru.',
            'social_github' => 'https://github.com/customgithub',
            'social_instagram' => 'https://instagram.com/custominstagram',
        ]);

        $response->assertRedirect('/admin/settings?tab=profile');
        $this->assertEquals('https://github.com/customgithub', Setting::get('social_github'));
        $this->assertEquals('https://instagram.com/custominstagram', Setting::get('social_instagram'));

        $homeResponse = $this->get('/');
        $homeResponse->assertSee('https://github.com/customgithub');
        $homeResponse->assertSee('https://instagram.com/custominstagram');
    }

    public function test_admin_settings_page_renders_password_tab(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/settings?tab=password');

        $response->assertStatus(200);
        $response->assertSee('Kata Sandi & Keamanan', false);
        $response->assertSee('Ganti Kata Sandi Akun');
        $response->assertSee('name="current_password"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
    }

    public function test_admin_can_update_password(): void
    {
        $admin = User::first();
        $admin->update(['password' => Hash::make('old-password-123')]);

        $response = $this->actingAs($admin)->post('/admin/settings/password', [
            'current_password' => 'old-password-123',
            'password' => 'new-secret-secure-password',
            'password_confirmation' => 'new-secret-secure-password',
        ]);

        $response->assertRedirect('/admin/settings?tab=password');
        $response->assertSessionHas('success', 'Kata sandi akun berhasil diperbarui.');

        $this->assertTrue(
            Hash::check('new-secret-secure-password', $admin->fresh()->password)
        );
    }

    public function test_password_update_fails_with_invalid_current_password(): void
    {
        $admin = User::first();
        $admin->update(['password' => Hash::make('correct-old-password')]);

        $response = $this->actingAs($admin)->post('/admin/settings/password', [
            'current_password' => 'wrong-old-password',
            'password' => 'new-secret-secure-password',
            'password_confirmation' => 'new-secret-secure-password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertFalse(
            Hash::check('new-secret-secure-password', $admin->fresh()->password)
        );
    }

    public function test_password_update_fails_with_unmatched_confirmation(): void
    {
        $admin = User::first();
        $admin->update(['password' => Hash::make('correct-old-password')]);

        $response = $this->actingAs($admin)->post('/admin/settings/password', [
            'current_password' => 'correct-old-password',
            'password' => 'new-secret-secure-password',
            'password_confirmation' => 'mismatched-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertFalse(
            Hash::check('new-secret-secure-password', $admin->fresh()->password)
        );
    }

    public function test_admin_can_set_custom_published_at_date_and_time_on_create_and_edit(): void
    {
        $admin = User::first();
        $category = Category::first();

        $createPageResponse = $this->actingAs($admin)->get(route('admin.posts.create'));
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSee('name="published_at"', false);

        $customDate = '2026-05-20T14:30';
        $storeResponse = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Artikel Terbit Terjadwal '.uniqid(),
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => $customDate,
            'body' => '<p>Konten artikel dengan waktu terbit khusus.</p>',
        ]);
        $storeResponse->assertRedirect(route('admin.posts.index'));

        $post = Post::latest('id')->first();
        $this->assertEquals('2026-05-20 14:30:00', $post->published_at->format('Y-m-d H:i:s'));

        $editPageResponse = $this->actingAs($admin)->get(route('admin.posts.edit', $post));
        $editPageResponse->assertStatus(200);
        $editPageResponse->assertSee('2026-05-20T14:30');

        $updatedDate = '2026-06-15T09:15';
        $updateResponse = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => $post->title,
            'slug' => $post->slug,
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => $updatedDate,
            'body' => '<p>Konten diperbarui.</p>',
        ]);
        $updateResponse->assertRedirect(route('admin.posts.index'));

        $this->assertEquals('2026-06-15 09:15:00', $post->fresh()->published_at->format('Y-m-d H:i:s'));
    }
}
