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

    protected $fillable = [
        'label',
        'url',
        'location',
        'order',
        'target',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeHeader(Builder $query): Builder
    {
        return $query->whereIn('location', ['header', 'both'])
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }

    public function scopeFooter(Builder $query): Builder
    {
        return $query->whereIn('location', ['footer', 'both'])
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }

    public function isCurrent(): bool
    {
        $path = trim($this->url, '/');

        if ($path === '' || $path === '#') {
            return request()->is('/');
        }

        return request()->is($path) || request()->is($path.'/*') || request()->url() === url($this->url);
    }
}
