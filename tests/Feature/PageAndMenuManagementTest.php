<?php

namespace Tests\Feature;

use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAndMenuManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
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

        // Test custom dynamic page
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

        // Test legacy /halaman/{slug} redirects to direct /{slug}
        $responseLegacy = $this->get('/halaman/'.$customPage->slug);
        $responseLegacy->assertRedirect('/'.$customPage->slug);

        // Test /term loads terms
        $responseTerm = $this->get('/term');
        $responseTerm->assertStatus(200);
        $responseTerm->assertSee('Penggunaan Layanan');
    }

    public function test_navigation_menus_render_on_header_and_footer(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Header menus
        $response->assertSee('Esai');
        $response->assertSee('Arsip');
        $response->assertSee('Tentang');

        // Footer menus
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

        // 1. Create
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

        // 2. Edit View
        $responseEdit = $this->actingAs($admin)->get("/admin/pages/{$page->id}/edit");
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Halaman: Kerja Sama & Kemitraan');

        // 3. Update
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

        // 4. Delete
        $responseDelete = $this->actingAs($admin)->delete("/admin/pages/{$page->id}");
        $responseDelete->assertRedirect('/admin/pages');
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    public function test_admin_can_create_edit_delete_and_reorder_menu(): void
    {
        $admin = User::first();

        // 1. Create Menu
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

        // 2. Edit View
        $responseEdit = $this->actingAs($admin)->get("/admin/menus/{$menu->id}/edit");
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Item Menu: Newsletter');

        // 3. Update Menu
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

        // 4. Reorder
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

        // 5. Delete Menu
        $responseDelete = $this->actingAs($admin)->delete("/admin/menus/{$menu->id}");
        $responseDelete->assertRedirect('/admin/menus?tab=footer');
        $this->assertDatabaseMissing('navigation_menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_page_supports_featured_image_and_renders_on_public_view(): void
    {
        $admin = User::first();

        // Create page with featured image and caption
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

        // Public page renders featured image and caption via direct slug
        $responsePublic = $this->get('/panduan-redaksi');
        $responsePublic->assertStatus(200);
        $responsePublic->assertSee('https://images.unsplash.com/photo-editorial-guide');
        $responsePublic->assertSee('Ilustrasi panduan kurasi konten editorial');

        // Admin page table contains thumbnail image
        $responseIndex = $this->actingAs($admin)->get('/admin/pages');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('https://images.unsplash.com/photo-editorial-guide');
        $responseIndex->assertSee('btn-delete-trigger');
    }

    public function test_categories_and_menus_have_modal_popup_elements_and_delete_confirmation(): void
    {
        $admin = User::first();

        // 1. Categories index has create modal and delete triggers
        $responseCat = $this->actingAs($admin)->get('/admin/categories');
        $responseCat->assertStatus(200);
        $responseCat->assertSee('id="btn-open-create-category"', false);
        $responseCat->assertSee('id="create-category-modal"', false);
        $responseCat->assertSee('id="edit-category-modal"', false);
        $responseCat->assertSee('btn-delete-trigger');

        // 2. Menus index has create modal, edit modal, and delete triggers
        $responseMenu = $this->actingAs($admin)->get('/admin/menus');
        $responseMenu->assertStatus(200);
        $responseMenu->assertSee('id="btn-open-create-menu"', false);
        $responseMenu->assertSee('id="create-menu-modal"', false);
        $responseMenu->assertSee('id="edit-menu-modal"', false);
        $responseMenu->assertSee('btn-delete-trigger');
    }

    public function test_social_share_og_image_uses_default_banner_when_no_featured_image(): void
    {
        // 1. Homepage uses default og-image banner
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('<meta property="og:image" content="'.asset('og-image.jpg').'">', false);
        $responseHome->assertSee('<meta name="twitter:image" content="'.asset('og-image.jpg').'">', false);

        // 2. Static page without featured image uses default og-image banner
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

        // 3. Static page WITH featured image uses the featured image as og:image
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

        // Sidebar contains redesigned navigation elements
        $response->assertSee('id="mobile-sidebar"', false);
        $response->assertSee('id="mobile-search-trigger"', false);
        $response->assertSee('Navigasi Utama');
        $response->assertSee('Fitur Membaca');
        $response->assertSee('Daftar Baca Nanti');
        $response->assertSee('Mode Tampilan');
        $response->assertSee('id="mobile-theme-toggle"', false);
        $response->assertSee('id="mobile-bookmark-trigger"', false);

        // Workspace Admin is removed from public sidebar
        $response->assertDontSee('Workspace Admin');
    }

    public function test_article_slug_is_direct_without_baca_and_legacy_baca_redirects_301(): void
    {
        $post = Post::published()->firstOrFail();

        // 1. route('post.show') generates direct /{slug} without /baca/
        $expectedUrl = url($post->slug);
        $this->assertEquals($expectedUrl, route('post.show', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('post.show', $post->slug));

        // 2. Direct /{slug} returns 200 with article content
        $directResponse = $this->get('/'.$post->slug);
        $directResponse->assertStatus(200);
        $directResponse->assertSee($post->title);

        // 3. Legacy /baca/{slug} returns 301 Permanent Redirect to /{slug}
        $legacyResponse = $this->get('/baca/'.$post->slug);
        $legacyResponse->assertStatus(301);
        $legacyResponse->assertRedirect('/'.$post->slug);

        // 4. Claps and comments routes use direct /{slug}
        $this->assertStringNotContainsString('/baca/', route('post.claps', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('post.clap', $post->slug));
        $this->assertStringNotContainsString('/baca/', route('comment.store', $post->slug));

        $this->get(route('post.claps', $post->slug))->assertStatus(200)->assertJson(['success' => true]);
        $this->postJson(route('post.clap', $post->slug))->assertStatus(200)->assertJson(['success' => true]);

        // 5. Static page continues to load at /{slug}
        $customPage = Page::create([
            'title' => 'Halaman Pengujian Slug',
            'slug' => 'halaman-pengujian-slug',
            'body' => '<p>Konten halaman pengujian.</p>',
            'is_published' => true,
        ]);
        $this->get('/'.$customPage->slug)->assertStatus(200)->assertSee('Halaman Pengujian Slug');

        // 6. Unknown slug returns 404
        $this->get('/slug-yang-tidak-ada-sama-sekali-12345')->assertStatus(404);
    }
}
