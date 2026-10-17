<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'featured_image',
        'featured_image_caption',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $page) {
            Cache::forget('sitemap_xml_content');
        });

        static::deleted(function (self $page) {
            Cache::forget('sitemap_xml_content');
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->body));

        return max(1, (int) ceil($wordCount / 200));
    }

    public function getParsedBodyAttribute(): string
    {
        if (empty($this->body)) {
            return '';
        }

        return preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) {
            $attrs = $matches[1];

            if (! preg_match('/\bloading=[\'"][^\'"]*[\'"]/i', $attrs)) {
                $attrs .= ' loading="lazy"';
            }

            if (! preg_match('/\bdecoding=[\'"][^\'"]*[\'"]/i', $attrs)) {
                $attrs .= ' decoding="async"';
            }

            return "<img{$attrs}>";
        }, $this->body);
    }
}
