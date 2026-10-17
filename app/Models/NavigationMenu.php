<?php

namespace App\Models;

use Database\Factories\NavigationMenuFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    /** @use HasFactory<NavigationMenuFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'url',
        'location',
        'order',
        'target',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope query for header navigation menus.
     *
     * @param  Builder<NavigationMenu>  $query
     * @return Builder<NavigationMenu>
     */
    public function scopeHeader($query)
    {
        return $query->whereIn('location', ['header', 'both'])
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }

    /**
     * Scope query for footer navigation menus.
     *
     * @param  Builder<NavigationMenu>  $query
     * @return Builder<NavigationMenu>
     */
    public function scopeFooter($query)
    {
        return $query->whereIn('location', ['footer', 'both'])
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }

    /**
     * Determine if this menu item matches the current request route or path.
     */
    public function isCurrent(): bool
    {
        $path = trim($this->url, '/');

        if ($path === '' || $path === '#') {
            return request()->is('/');
        }

        return request()->is($path) || request()->is($path.'/*') || request()->url() === url($this->url);
    }
}
