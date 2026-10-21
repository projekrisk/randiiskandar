<?php

namespace Tests\Feature;

use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\DemoPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAndMenuManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(DemoPostSeeder::class);
    }

    public function test_public_static_pages_load_successfully(): void
    {
        $responseTerms = $this->get('/terms');
        $responseTerms->assertStatus(200);
        $responseTerms->assertSee('Penggunaan Layanan');

        $responseDisclaimer = $this->get('/disclaimer');
        $responseDisclaimer->assertStatus(200);
        $responseDisclaimer->assertSee('Sanggahan');

        $responsePrivacy = $this->get('/privasi');
        $responsePrivacy->assertStatus(200);
        $responsePrivacy->assertSee('Kebijakan Privasi');

        $responseLegacyPrivacy = $this->get('/privacy');
        $responseLegacyPrivacy->assertStatus(301);
        $responseLegacyPrivacy->assertRedirect('/privasi');

        $customPage = Page::create([
            'title' => 'Pedoman Komunitas Pengembang',
            'slug' => 'pedoman-komunitas',
            'body' => '<p>Konten pedoman etika berkomentar dan berdiskusi secara sehat.</p>',
            'meta_description' => 'Pedoman komunitas.',
            'is_published' => true,
        ]);

        $responseCustom = $this->get('/'.$customPage->slug);
        $responseCustom->assertStatus(200);
        $responseCustom->assertSee('Pedoman Komunitas Pengembang');
        $responseCustom->assertSee('Konten pedoman etika berkomentar');

        $responseLegacy = $this->get('/halaman/'.$customPage->slug);
        $responseLegacy->assertRedirect('/'.$customPage->slug);

        $responseTerm = $this->get('/term');
        $responseTerm->assertStatus(200);
        $responseTerm->assertSee('Penggunaan Layanan');
    }

    public function test_navigation_menus_render_on_header_and_footer(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('Beranda');
        $response->assertSee('Arsip');
        $response->assertSee('Tentang');

        $response->assertSee('Layanan');
        $response->assertSee('Privasi');
        $response->assertSee('Sanggahan');
    }

    public function test_guest_cannot_access_admin_pages_and_menus(): void
    {
        $this->get('/admin/pages')->assertRedirect('/admin/login');
        $this->get('/admin/menus')->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_pages_list(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/pages');
        $response->assertStatus(200);
        $response->assertSee('Kelola Halaman Statis');
        $response->assertSee('Penggunaan Layanan');
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('Sanggahan');
    }

    public function test_admin_can_create_edit_and_delete_static_page(): void
    {
        $admin = User::first();

        $responseCreate = $this->actingAs($admin)->post('/admin/pages', [
            'title' => 'Kerja Sama & Kemitraan',
            'slug' => 'kerja-sama',
            'body' => '<p>Informasi sponsorship dan kemitraan editorial.</p>',
            'meta_description' => 'Halaman kemitraan editorial.',
            'is_published' => '1',
        ]);

        $responseCreate->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('pages', [
            'slug' => 'kerja-sama',
            'title' => 'Kerja Sama & Kemitraan',
            'is_published' => true,
        ]);

        $page = Page::where('slug', 'kerja-sama')->firstOrFail();

        $responseEdit = $this->actingAs($admin)->get("/admin/pages/{$page->id}/edit");
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Halaman: Kerja Sama & Kemitraan');

        $responseUpdate = $this->actingAs($admin)->put("/admin/pages/{$page->id}", [
            'title' => 'Kerja Sama dan Kemitraan Editorial',
            'slug' => 'kerja-sama-updated',
            'body' => '<p>Konten yang telah diperbarui oleh admin.</p>',
            'meta_description' => 'Meta deskripsi baru.',
            'is_published' => '1',
        ]);

        $responseUpdate->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'slug' => 'kerja-sama-updated',
            'title' => 'Kerja Sama dan Kemitraan Editorial',
        ]);

        $responseDelete = $this->actingAs($admin)->delete("/admin/pages/{$page->id}");
        $responseDelete->assertRedirect('/admin/pages');
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    public function test_admin_can_create_edit_delete_and_reorder_menu(): void
    {
        $admin = User::first();

        $responseCreate = $this->actingAs($admin)->post('/admin/menus', [
            'label' => 'Newsletter',
            'url' => '/newsletter',
            'location' => 'header',
            'order' => 10,
            'target' => '_blank',
            'is_active' => '1',
        ]);

        $responseCreate->assertRedirect('/admin/menus?tab=header');
        $this->assertDatabaseHas('navigation_menus', [
            'label' => 'Newsletter',
            'url' => '/newsletter',
            'location' => 'header',
            'target' => '_blank',
            'is_active' => true,
        ]);

        $menu = NavigationMenu::where('label', 'Newsletter')->firstOrFail();

        $responseEdit = $this->actingAs($admin)->get("/admin/menus/{$menu->id}/edit");
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Item Menu: Newsletter');

        $responseUpdate = $this->actingAs($admin)->put("/admin/menus/{$menu->id}", [
            'label' => 'Berlangganan Newsletter',
            'url' => '/newsletter-pro',
            'location' => 'footer',
            'order' => 99,
            'target' => '_self',
            'is_active' => '1',
        ]);

        $responseUpdate->assertRedirect('/admin/menus?tab=footer');
        $this->assertDatabaseHas('navigation_menus', [
            'id' => $menu->id,
            'label' => 'Berlangganan Newsletter',
            'location' => 'footer',
        ]);

        $menu1 = NavigationMenu::where('location', 'header')->where('order', 1)->first();
        $menu2 = NavigationMenu::where('location', 'header')->where('order', 2)->first();

        if ($menu1 && $menu2) {
            $this->actingAs($admin)->post('/admin/menus/reorder', [
                'id' => $menu1->id,
                'direction' => 'down',
            ])->assertStatus(302);

            $menu1->refresh();
            $menu2->refresh();
            $this->assertEquals(2, $menu1->order);
            $this->assertEquals(1, $menu2->order);
        }

        $responseDelete = $this->actingAs($admin)->delete("/admin/menus/{$menu->id}");
        $responseDelete->assertRedirect('/admin/menus?tab=footer');
        $this->assertDatabaseMissing('navigation_menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_page_supports_featured_image_and_renders_on_public_view(): void
    {
        $admin = User::first();

        $responseCreate = $this->actingAs($admin)->post('/admin/pages', [
            'title' => 'Panduan Redaksi dan Kurasi Konten',
            'slug' => 'panduan-redaksi',
            'body' => '<p>Prinsip dan standar editorial workspace.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-editorial-guide',
            'featured_image_caption' => 'Ilustrasi panduan kurasi konten editorial',
            'is_published' => '1',
        ]);

        $responseCreate->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('pages', [
            'slug' => 'panduan-redaksi',
            'featured_image' => 'https://images.unsplash.com/photo-editorial-guide',
            'featured_image_caption' => 'Ilustrasi panduan kurasi konten editorial',
        ]);

        $responsePublic = $this->get('/panduan-redaksi');
        $responsePublic->assertStatus(200);
        $responsePublic->assertSee('https://images.unsplash.com/photo-editorial-guide');
        $responsePublic->assertSee('Ilustrasi panduan kurasi konten editorial');

        $responseIndex = $this->actingAs($admin)->get('/admin/pages');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('https://images.unsplash.com/photo-editorial-guide');
        $responseIndex->assertSee('btn-delete-trigger');
    }

    public function test_categories_and_menus_have_modal_popup_elements_and_delete_confirmation(): void
    {
        $admin = User::first();

        $responseCat = $this->actingAs($admin)->get('/admin/categories');
        $responseCat->assertStatus(200);
        $responseCat->assertSee('id="btn-open-create-category"', false);
        $responseCat->assertSee('id="create-category-modal"', false);
        $responseCat->assertSee('id="edit-category-modal"', false);
        $responseCat->assertSee('btn-delete-trigger');

        $responseMenu = $this->actingAs($admin)->get('/admin/menus');
        $responseMenu->assertStatus(200);
        $responseMenu->assertSee('id="btn-open-create-menu"', false);
        $responseMenu->assertSee('id="create-menu-modal"', false);
        $responseMenu->assertSee('id="edit-menu-modal"', false);
        $responseMenu->assertSee('btn-delete-trigger');
    }

    public function test_social_share_og_image_uses_default_banner_when_no_featured_image(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('<meta property="og:image" content="'.asset('og-image.jpg').'">', false);
        $responseHome->assertSee('<meta name="twitter:image" content="'.asset('og-image.jpg').'">', false);

        $pageWithoutImg = Page::create([
            'title' => 'Ketentuan Berkomentar',
            'slug' => 'ketentuan-berkomentar',
            'body' => '<p>Konten panduan komentar.</p>',
            'meta_description' => 'Deskripsi ketentuan.',
            'featured_image' => null,
            'is_published' => true,
        ]);

        $responsePageWithout = $this->get('/'.$pageWithoutImg->slug);
        $responsePageWithout->assertStatus(200);
        $responsePageWithout->assertSee('<meta property="og:image" content="'.asset('og-image.jpg').'">', false);
        $responsePageWithout->assertSee('<meta name="twitter:image" content="'.asset('og-image.jpg').'">', false);

        $pageWithImg = Page::create([
            'title' => 'Panduan Komunitas Visual',
            'slug' => 'panduan-visual',
            'body' => '<p>Konten panduan visual.</p>',
            'meta_description' => 'Deskripsi panduan visual.',
            'featured_image' => 'https://images.unsplash.com/custom-og-preview.jpg',
            'is_published' => true,
        ]);

        $responsePageWith = $this->get('/'.$pageWithImg->slug);
        $responsePageWith->assertStatus(200);
        $responsePageWith->assertSee('<meta property="og:image" content="https://images.unsplash.com/custom-og-preview.jpg">', false);
        $responsePageWith->assertSee('<meta name="twitter:image" content="https://images.unsplash.com/custom-og-preview.jpg">', false);
    }

    public function test_mobile_sidebar_has_redesigned_menus_and_no_workspace_admin_link(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('id="mobile-sidebar"', false);
        $response->assertSee('id="mobile-search-trigger"', false);
        $response->assertSee('Navigasi Utama');
        $response->assertSee('Fitur Membaca');
        $response->assertSee('Daftar Baca Nanti');
        $response->assertSee('Mode Tampilan');
        $response->assertSee('id="mobile-theme-toggle"', false);
        $response->assertSee('id="mobile-bookmark-trigger"', false);

        $response->assertDontSee('Workspace Admin');
    }

    public function test_article_slug_is_direct_without_baca_and_legacy_baca_redirects_301(): void
    {
        $post = Post::published()->firstOrFail();

        $expectedUrl = url($post->slug);
        $this->assertEquals($expectedUrl, route('post.show', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('post.show', $post->slug));

        $directResponse = $this->get('/'.$post->slug);
        $directResponse->assertStatus(200);
        $directResponse->assertSee($post->title);

        $legacyResponse = $this->get('/baca/'.$post->slug);
        $legacyResponse->assertStatus(301);
        $legacyResponse->assertRedirect('/'.$post->slug);

        $this->assertStringNotContainsString('/baca/', route('post.claps', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('post.clap', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('comment.store', $post->slug));

        $this->get(route('post.claps', $post->slug))->assertStatus(200)->assertJson(['success' => true]);
        $this->postJson(route('post.clap', $post->slug))->assertStatus(200)->assertJson(['success' => true]);

        $customPage = Page::create([
            'title' => 'Halaman Pengujian Slug',
            'slug' => 'halaman-pengujian-slug',
            'body' => '<p>Konten halaman pengujian.</p>',
            'is_published' => true,
        ]);
        $this->get('/'.$customPage->slug)->assertStatus(200)->assertSee('Halaman Pengujian Slug');

        $this->get('/slug-yang-tidak-ada-sama-sekali-12345')->assertStatus(404);
    }

    public function test_google_site_name_structured_data_and_sitemap_privacy_url(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);

        // Site Name in meta tags
        $responseHome->assertSee('<meta property="og:site_name" content="Randi Iskandar">', false);
        $responseHome->assertSee('<meta name="application-name" content="Randi Iskandar">', false);

        // WebSite schema must specify name "Randi Iskandar"
        $responseHome->assertSee('"@type": "WebSite"', false);
        $responseHome->assertSee('"name": "Randi Iskandar"', false);

        // WebSite schema must NOT pass domain names as alternateName
        $responseHome->assertDontSee('"randiiskandar.com"', false);
        $responseHome->assertDontSee('"www.randiiskandar.com"', false);

        // Sitemap must contain /privasi and must not contain /privacy
        $responseSitemap = $this->get('/sitemap.xml');
        $responseSitemap->assertStatus(200);
        $responseSitemap->assertSee(url('/privasi'));
        $responseSitemap->assertDontSee(url('/privacy'));
    }

    public function test_favicons_rendered_on_all_pages_and_accessible(): void
    {
        $pagesToTest = [
            '/',
            '/kontak',
            '/terms',
            '/disclaimer',
            '/tentang',
            '/privasi',
            '/admin/login',
        ];

        foreach ($pagesToTest as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('rel="icon"', false);
            $response->assertSee('favicon.ico', false);
            $response->assertSee('favicon-32x32.png', false);
            $response->assertSee('favicon-16x16.png', false);
            $response->assertSee('apple-touch-icon', false);
        }

        // Test static favicon endpoints
        $this->get('/favicon.ico')->assertStatus(200);
        $this->get('/favicon-16x16.png')->assertStatus(200);
        $this->get('/favicon-32x32.png')->assertStatus(200);
        $this->get('/favicon-48x48.png')->assertStatus(200);
        $this->get('/apple-touch-icon.png')->assertStatus(200);
        $this->get('/site.webmanifest')->assertStatus(200);
    }
}
