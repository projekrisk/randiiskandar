<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $headerMenus = NavigationMenu::whereIn('location', ['header', 'both'])
            ->orderBy('order', 'asc')
            ->get();

        $footerMenus = NavigationMenu::whereIn('location', ['footer', 'both'])
            ->orderBy('order', 'asc')
            ->get();

        $pages = Page::published()->select('id', 'title', 'slug')->orderBy('title')->get();

        $systemRoutes = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Arsip Publikasi', 'url' => '/arsip'],
            ['label' => 'Tentang Penulis', 'url' => '/tentang'],
            ['label' => 'Hubungi Saya (Kontak)', 'url' => '/kontak'],
            ['label' => 'Ketentuan Layanan', 'url' => '/terms'],
            ['label' => 'Kebijakan Privasi', 'url' => '/privasi'],
            ['label' => 'Sanggahan (Disclaimer)', 'url' => '/disclaimer'],
        ];

        return view('admin.menus.index', [
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus,
            'pages' => $pages,
            'systemRoutes' => $systemRoutes,
            'activeTab' => $request->input('tab', 'header'),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.menus.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'location' => ['required', 'in:header,footer,both'],
            'order' => ['nullable', 'integer', 'min:0'],
            'target' => ['required', 'in:_self,_blank'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $order = $request->filled('order')
            ? (int) $request->input('order')
            : (NavigationMenu::whereIn('location', [$validated['location'], 'both'])->max('order') ?? 0) + 1;

        NavigationMenu::create([
            'label' => $validated['label'],
            'url' => $validated['url'],
            'location' => $validated['location'],
            'order' => $order,
            'target' => $validated['target'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menus.index', ['tab' => $validated['location'] === 'footer' ? 'footer' : 'header'])
            ->with('success', "Item menu '{$validated['label']}' berhasil ditambahkan.");
    }

    public function edit(NavigationMenu $menu): View
    {
        $pages = Page::published()->select('id', 'title', 'slug')->orderBy('title')->get();

        $systemRoutes = [
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Arsip Publikasi', 'url' => '/arsip'],
            ['label' => 'Tentang Penulis', 'url' => '/tentang'],
            ['label' => 'Hubungi Saya (Kontak)', 'url' => '/kontak'],
            ['label' => 'Ketentuan Layanan', 'url' => '/terms'],
            ['label' => 'Kebijakan Privasi', 'url' => '/privasi'],
            ['label' => 'Sanggahan (Disclaimer)', 'url' => '/disclaimer'],
        ];

        return view('admin.menus.edit', [
            'menu' => $menu,
            'pages' => $pages,
            'systemRoutes' => $systemRoutes,
        ]);
    }

    public function update(Request $request, NavigationMenu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'location' => ['required', 'in:header,footer,both'],
            'order' => ['required', 'integer', 'min:0'],
            'target' => ['required', 'in:_self,_blank'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $menu->update([
            'label' => $validated['label'],
            'url' => $validated['url'],
            'location' => $validated['location'],
            'order' => (int) $validated['order'],
            'target' => $validated['target'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.menus.index', ['tab' => $validated['location'] === 'footer' ? 'footer' : 'header'])
            ->with('success', "Item menu '{$menu->label}' berhasil diperbarui.");
    }

    public function destroy(NavigationMenu $menu): RedirectResponse
    {
        $label = $menu->label;
        $tab = $menu->location === 'footer' ? 'footer' : 'header';
        $menu->delete();

        return redirect()->route('admin.menus.index', ['tab' => $tab])
            ->with('success', "Item menu '{$label}' berhasil dihapus.");
    }

    public function reorder(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:navigation_menus,id'],
            'direction' => ['required', 'in:up,down'],
        ]);

        $current = NavigationMenu::findOrFail($validated['id']);
        $location = $current->location;

        $query = NavigationMenu::whereIn('location', [$location, 'both']);

        if ($validated['direction'] === 'up') {
            $swapWith = (clone $query)->where('order', '<', $current->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $swapWith = (clone $query)->where('order', '>', $current->order)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($swapWith) {
            $currentOrder = $current->order;
            $current->order = $swapWith->order;
            $swapWith->order = $currentOrder;
            $current->save();
            $swapWith->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Urutan menu berhasil diperbarui.');
    }
}
