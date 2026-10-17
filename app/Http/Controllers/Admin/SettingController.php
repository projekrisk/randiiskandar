<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'profile');
        if (! in_array($tab, ['profile', 'ads', 'password'], true)) {
            $tab = 'profile';
        }
        $user = Auth::user();

        $adInArticleEnabled = Setting::get('ad_in_article_enabled', '1');
        $adInArticleCode = Setting::get('ad_in_article_code', '');
        $adBottomEnabled = Setting::get('ad_bottom_enabled', '1');
        $adBottomCode = Setting::get('ad_bottom_code', '');
        $bacaJugaEnabled = Setting::get('baca_juga_enabled', '1');
        $socialGithub = Setting::get('social_github', 'https://github.com/randiiskandar');
        $socialInstagram = Setting::get('social_instagram', 'https://instagram.com/randi.iskandar');

        return view('admin.settings.index', [
            'user' => $user,
            'currentTab' => $tab,
            'adInArticleEnabled' => $adInArticleEnabled,
            'adInArticleCode' => $adInArticleCode,
            'adBottomEnabled' => $adBottomEnabled,
            'adBottomCode' => $adBottomCode,
            'bacaJugaEnabled' => $bacaJugaEnabled,
            'socialGithub' => $socialGithub,
            'socialInstagram' => $socialInstagram,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:5120'],
            'social_github' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
        ]);

        $avatar = $validated['avatar'] ?? $user->avatar;

        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatar = Storage::url($path);
        } elseif ($request->boolean('remove_avatar')) {
            $avatar = null;
        }

        $user->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'avatar' => $avatar,
        ]);

        if ($request->has('social_github')) {
            Setting::set('social_github', $validated['social_github'] ?: 'https://github.com/randiiskandar');
        }
        if ($request->has('social_instagram')) {
            Setting::set('social_instagram', $validated['social_instagram'] ?: 'https://instagram.com/randi.iskandar');
        }

        return redirect()->route('admin.settings.index', ['tab' => 'profile'])
            ->with('success', 'Profil penulis berhasil diperbarui.');
    }

    public function updateAds(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ad_in_article_enabled' => ['nullable'],
            'ad_in_article_code' => ['nullable', 'string', 'max:10000'],
            'ad_bottom_enabled' => ['nullable'],
            'ad_bottom_code' => ['nullable', 'string', 'max:10000'],
            'baca_juga_enabled' => ['nullable'],
        ]);

        $inArticleEnabled = $request->boolean('ad_in_article_enabled') ? '1' : '0';
        $bottomEnabled = $request->boolean('ad_bottom_enabled') ? '1' : '0';
        $bacaJugaEnabled = $request->boolean('baca_juga_enabled') ? '1' : '0';

        Setting::set('ad_in_article_enabled', $inArticleEnabled);
        Setting::set('ad_in_article_code', $validated['ad_in_article_code'] ?? '');
        Setting::set('ad_bottom_enabled', $bottomEnabled);
        Setting::set('ad_bottom_code', $validated['ad_bottom_code'] ?? '');
        Setting::set('baca_juga_enabled', $bacaJugaEnabled);

        return redirect()->route('admin.settings.index', ['tab' => 'ads'])
            ->with('success', 'Pengaturan iklan dan artikel berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan akun Anda.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.settings.index', ['tab' => 'password'])
            ->with('success', 'Kata sandi akun berhasil diperbarui.');
    }
}
