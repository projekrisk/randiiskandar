<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()->with(['category', 'categories'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->paginate(10);

        return view('admin.posts.index', [
            'posts' => $posts,
        ]);
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suggestedTags = Post::getAllUsedTags();

        return view('admin.posts.create', [
            'categories' => $categories,
            'suggestedTags' => $suggestedTags,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug', Rule::notIn(['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up', 'tentang', 'about', 'term', 'terms', 'disclaimer', 'privasi'])],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category_ids' => ['nullable', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:500'],
            'featured_image_caption' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:10240'],
            'tags' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'is_featured_image_wide' => ['nullable', 'boolean'],
        ]);

        $categoryIds = collect($validated['category_ids'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($categoryIds->isEmpty() && ! empty($validated['category_id'])) {
            $categoryIds = collect([(int) $validated['category_id']]);
        }

        if ($categoryIds->isEmpty()) {
            return back()->withInput()->withErrors(['category_ids' => 'Pilih minimal satu kategori untuk artikel ini.']);
        }

        $primaryCategoryId = $categoryIds->first();

        $featuredImage = $validated['featured_image'] ?? null;
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('posts/featured', 'public');
            $featuredImage = Storage::url($path);
        }

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $publishedAt = null;
        if ($request->filled('published_at')) {
            $publishedAt = Carbon::parse($validated['published_at']);
        } elseif ($validated['status'] === 'published') {
            $publishedAt = Carbon::now();
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'category_id' => $primaryCategoryId,
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => Post::cleanTableHtml(Post::stripBacaJuga($validated['body'])),
            'featured_image' => $featuredImage,
            'featured_image_caption' => $validated['featured_image_caption'] ?? null,
            'is_featured_image_wide' => $request->boolean('is_featured_image_wide'),
            'tags' => $request->filled('tags') ? trim($request->input('tags')) : null,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
            'claps_count' => 0,
            'views_count' => 0,
        ]);

        $post->categories()->sync($categoryIds->all());

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Post $post): View
    {
        $post->load('categories');
        $categories = Category::orderBy('name')->get();
        $suggestedTags = Post::getAllUsedTags();

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $categories,
            'suggestedTags' => $suggestedTags,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug,'.$post->id, Rule::notIn(['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up', 'tentang', 'about', 'term', 'terms', 'disclaimer', 'privasi'])],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category_ids' => ['nullable', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:500'],
            'featured_image_caption' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:10240'],
            'tags' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'is_featured_image_wide' => ['nullable', 'boolean'],
        ]);

        $categoryIds = collect($validated['category_ids'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($categoryIds->isEmpty() && ! empty($validated['category_id'])) {
            $categoryIds = collect([(int) $validated['category_id']]);
        }

        if ($categoryIds->isEmpty()) {
            return back()->withInput()->withErrors(['category_ids' => 'Pilih minimal satu kategori untuk artikel ini.']);
        }

        $primaryCategoryId = $categoryIds->first();

        $featuredImage = $validated['featured_image'] ?? $post->featured_image;
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('posts/featured', 'public');
            $featuredImage = Storage::url($path);
        } elseif ($request->boolean('remove_featured_image')) {
            $featuredImage = null;
        }

        $publishedAt = $post->published_at;
        if ($request->filled('published_at')) {
            $publishedAt = Carbon::parse($validated['published_at']);
        } elseif ($validated['status'] === 'published' && is_null($publishedAt)) {
            $publishedAt = Carbon::now();
        } elseif ($validated['status'] === 'draft' && ! $request->filled('published_at')) {
            $publishedAt = null;
        }

        $post->update([
            'category_id' => $primaryCategoryId,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => Post::cleanTableHtml(Post::stripBacaJuga($validated['body'])),
            'featured_image' => $featuredImage,
            'featured_image_caption' => $validated['featured_image_caption'] ?? null,
            'is_featured_image_wide' => $request->boolean('is_featured_image_wide'),
            'tags' => $request->filled('tags') ? trim($request->input('tags')) : null,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
        ]);

        $post->categories()->sync($categoryIds->all());

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:10240'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts/content', 'public');
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'message' => 'Gambar berhasil diunggah.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file gambar yang diunggah.',
        ], 400);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
