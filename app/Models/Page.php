<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'featured_image',
        'featured_image_caption',
        'meta_description',
        'is_published',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Scope query to published pages only.
     *
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get estimated reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->body));

        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Parse konten body dan sematkan loading="lazy" dan decoding="async" pada semua gambar inline.
     */
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
