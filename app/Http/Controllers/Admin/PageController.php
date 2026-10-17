<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Page::query()->latest('updated_at');

        if ($request->filled('status')) {
            if ($request->input('status') === 'published') {
                $query->where('is_published', true);
            } elseif ($request->input('status') === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $pages = $query->paginate(10);

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug', Rule::notIn(['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up'])],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:500'],
            'featured_image_caption' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:10240'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $featuredImage = $validated['featured_image'] ?? null;
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('pages/featured', 'public');
            $featuredImage = Storage::url($path);
        }

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        if (in_array($slug, ['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up'])) {
            $slug .= '-page';
        }

        // Pastikan slug unik
        $originalSlug = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        Page::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'featured_image' => $featuredImage,
            'featured_image_caption' => $validated['featured_image_caption'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman baru berhasil dipublikasikan.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page->id), Rule::notIn(['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up'])],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:500'],
            'featured_image_caption' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:10240'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $featuredImage = $validated['featured_image'] ?? $page->featured_image;
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('pages/featured', 'public');
            $featuredImage = Storage::url($path);
        } elseif ($request->boolean('remove_featured_image')) {
            $featuredImage = null;
        }

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        if (in_array($slug, ['admin', 'login', 'baca', 'kategori', 'arsip', 'cari', 'kontak', 'up'])) {
            $slug .= '-page';
        }

        // Pastikan slug unik jika diubah
        $originalSlug = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'featured_image' => $featuredImage,
            'featured_image_caption' => $validated['featured_image_caption'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman '{$title}' berhasil dihapus.");
    }
}
