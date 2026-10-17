<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BookmarkAndOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_bookmark_popover_is_rendered_like_table_of_contents_and_drawer_is_removed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Memastikan popover bookmark hadir
        $response->assertSee('id="bookmark-popover"', false);
        $response->assertSee('Daftar Baca Nanti');
        $response->assertSee('id="close-bookmark-popover"', false);
        $response->assertSee('id="bookmark-header-trigger"', false);
        // Memastikan drawer samping lama sudah tidak ada
        $response->assertDontSee('id="bookmark-drawer"', false);
    }

    public function test_navigation_menus_are_supplied_via_view_composer(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Esai');
        $response->assertSee('Arsip');
    }

    public function test_settings_are_cached_and_invalidated_on_set(): void
    {
        Cache::flush();
        $this->assertFalse(Cache::has('setting_test_key'));

        Setting::set('test_key', 'initial_value');
        $value = Setting::get('test_key');
        $this->assertEquals('initial_value', $value);
        $this->assertTrue(Cache::has('setting_test_key'));

        // Update nilai dan pastikan cache diperbarui
        Setting::set('test_key', 'updated_value');
        $this->assertEquals('updated_value', Setting::get('test_key'));
    }

    public function test_post_parsed_body_automatically_injects_lazyload_and_decoding(): void
    {
        $post = new Post([
            'body' => '<p>Artikel tes</p><img src="/img/sample.jpg" alt="Contoh"><p>Akhir</p>',
        ]);

        $parsed = $post->parsed_body;

        $this->assertStringContainsString('loading="lazy"', $parsed);
        $this->assertStringContainsString('decoding="async"', $parsed);
    }

    public function test_homepage_and_detail_have_optimal_image_loading_attributes(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        // Hero image di homepage memiliki fetchpriority high & loading eager
        $response->assertSee('fetchpriority="high"', false);
        $response->assertSee('loading="eager"', false);
        // Post card image memiliki lazy load & decoding async
        $response->assertSee('loading="lazy"', false);
        $response->assertSee('decoding="async"', false);

        $post = Post::published()->first();
        if ($post) {
            $detailResponse = $this->get(route('post.show', $post->slug));
            $detailResponse->assertStatus(200);
            $detailResponse->assertSee('loading="lazy"', false);
            $detailResponse->assertSee('decoding="async"', false);
        }
    }

    public function test_sitemap_xml_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee(url('/'), false);
    }

    public function test_public_filesystem_disk_points_to_public_storage(): void
    {
        $root = config('filesystems.disks.public.root');
        $this->assertNotEmpty($root);
        $this->assertStringContainsString('storage', $root);
    }

    public function test_layout_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->getContent(), 'css/app.css') ||
            str_contains($response->getContent(), '/build/assets/')
        );
    }
}
