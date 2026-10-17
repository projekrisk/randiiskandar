<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'featured_image_caption',
        'is_featured_image_wide',
        'status',
        'is_featured',
        'views_count',
        'claps_count',
        'tags',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_featured_image_wide' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'claps_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $post) {
            Cache::forget('all_published_posts_count');
            Cache::forget('sitemap_xml_content');
            Cache::forget('feed_rss_content');
        });

        static::deleted(function (self $post) {
            Cache::forget('all_published_posts_count');
            Cache::forget('sitemap_xml_content');
            Cache::forget('feed_rss_content');
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function approvedRootComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with(['replies' => fn ($q) => $q->where('is_approved', true)->with('author')]);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('posts.status', 'published')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now())
            ->orderByDesc('posts.published_at');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => self::cleanTableHtml(self::stripBacaJuga($value)),
        );
    }

    protected function metaDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! empty($this->excerpt)) {
                    return Str::limit(strip_tags($this->excerpt), 150);
                }

                $cleanText = strip_tags($this->body);
                $cleanText = preg_replace('/\s+/', ' ', $cleanText);

                return Str::limit(trim($cleanText), 150);
            }
        );
    }

    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $wordCount = str_word_count(strip_tags($this->body));
                $minutes = ceil($wordCount / 200);

                return max(1, (int) $minutes);
            }
        );
    }

    protected function formattedPublishedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at
                ? $this->published_at->locale('id')->translatedFormat('d F Y')
                : '-'
        );
    }

    public function getTableOfContentsAttribute(): array
    {
        $toc = [];
        if (empty($this->body)) {
            return $toc;
        }

        preg_match_all('/<(h[2-3])(?:[^>]*)>(.*?)<\/\1>/i', $this->body, $matches, PREG_SET_ORDER);

        foreach ($matches as $index => $match) {
            $tag = strtolower($match[1]);
            $title = strip_tags($match[2]);
            $slug = Str::slug($title) ?: 'section-'.($index + 1);

            $toc[] = [
                'id' => $slug,
                'text' => $title,
                'level' => $tag === 'h2' ? 2 : 3,
            ];
        }

        return $toc;
    }

    public function getParsedBodyAttribute(): string
    {
        if (empty($this->body)) {
            return '';
        }

        $cleanedBody = static::cleanTableHtml($this->body);

        $parsed = preg_replace_callback(
            '/<(h[2-3])([^>]*)>(.*?)<\/\1>/i',
            function ($matches) {
                $tag = $matches[1];
                $attrs = $matches[2];
                $content = $matches[3];
                $slug = Str::slug(strip_tags($content));

                if (! preg_match('/id=[\'"][^\'"]*[\'"]/i', $attrs)) {
                    return "<{$tag}{$attrs} id=\"{$slug}\">{$content}</{$tag}>";
                }

                return "<{$tag}{$attrs}>{$content}</{$tag}>";
            },
            $cleanedBody
        );

        return preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) {
            $attrs = $matches[1];

            if (! preg_match('/\bloading=[\'"][^\'"]*[\'"]/i', $attrs)) {
                $attrs .= ' loading="lazy"';
            }

            if (! preg_match('/\bdecoding=[\'"][^\'"]*[\'"]/i', $attrs)) {
                $attrs .= ' decoding="async"';
            }

            return "<img{$attrs}>";
        }, $parsed);
    }

    public function getRelatedCategoryPost(): ?self
    {
        if ($this->relationLoaded('relatedCategoryPost')) {
            return $this->getRelation('relatedCategoryPost');
        }

        $related = null;

        if ($this->category_id) {
            $related = self::query()
                ->published()
                ->where('category_id', $this->category_id)
                ->where('id', '!=', $this->id)
                ->with(['category'])
                ->reorder()
                ->inRandomOrder()
                ->first();
        }

        if (! $related) {
            $related = self::query()
                ->published()
                ->where('id', '!=', $this->id)
                ->with(['category'])
                ->reorder()
                ->inRandomOrder()
                ->first();
        }

        $this->setRelation('relatedCategoryPost', $related);

        return $related;
    }

    public static function stripBacaJuga(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        if (stripos($content, 'BACA JUGA') === false) {
            return $content;
        }

        $patternNotProse = '/<div\b[^>]*\bnot-prose\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?<rec1><div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?&rec1))*<\/div>))*<\/div>/is';
        $cleaned = preg_replace_callback($patternNotProse, function ($matches) {
            return (stripos($matches[0], 'BACA JUGA') !== false) ? '' : $matches[0];
        }, $content);

        if (stripos($cleaned, 'BACA JUGA') !== false) {
            $patternDiv = '/<div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?<rec2><div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?&rec2))*<\/div>))*<\/div>/is';
            $cleaned = preg_replace_callback($patternDiv, function ($matches) {
                return (stripos($matches[0], 'BACA JUGA') !== false) ? '' : $matches[0];
            }, $cleaned);
        }

        if (stripos($cleaned, 'BACA JUGA') !== false) {
            $patternP = '/<p\b[^>]*>\s*(?:<strong>|<b>)?\s*BACA\s+JUGA(?:\s*:)?\s*(?:<\/strong>|<\/b>)?[\s\S]*?<\/p>/i';
            $cleaned = preg_replace($patternP, '', $cleaned);
        }

        return trim((string) $cleaned);
    }

    public static function cleanTableHtml(?string $content): string
    {
        if (empty($content) || stripos($content, '<table') === false) {
            return (string) ($content ?? '');
        }

        $patternWrapper = '/<div[^>]*class=["\'][^"\']*(?:overflow-x-auto|table-scroll-container|table-responsive|article-table-wrapper)[^"\']*["\'][^>]*>\s*(<table[\s\S]*?<\/table>)\s*<\/div>/i';
        while (preg_match($patternWrapper, $content)) {
            $content = preg_replace($patternWrapper, '$1', $content);
        }

        $tags = ['table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'colgroup', 'col', 'caption'];
        foreach ($tags as $tag) {
            $content = preg_replace_callback('/<('.$tag.')\b([^>]*)>/i', function ($m) {
                $tagName = strtolower($m[1]);
                $attrs = $m[2];

                $kept = '';
                if (preg_match('/colspan=["\'](\d+)["\']/i', $attrs, $cs)) {
                    $kept .= ' colspan="'.$cs[1].'"';
                }
                if (preg_match('/rowspan=["\'](\d+)["\']/i', $attrs, $rs)) {
                    $kept .= ' rowspan="'.$rs[1].'"';
                }
                if (preg_match('/scope=["\'](col|row|colgroup|rowgroup)["\']/i', $attrs, $sc)) {
                    $kept .= ' scope="'.$sc[1].'"';
                }

                return "<{$tagName}{$kept}>";
            }, $content);
        }

        return trim((string) $content);
    }

    public function getBodyWithInjectedAdAttribute(): string
    {
        $body = static::stripBacaJuga($this->parsed_body);

        $adEnabled = Setting::get('ad_in_article_enabled', '1') !== '0';
        $bacaJugaSettingEnabled = Setting::get('baca_juga_enabled', '1') !== '0';

        $relatedPost = $bacaJugaSettingEnabled
            ? $this->getRelatedCategoryPost()
            : null;
        $bacaJugaEnabled = ($relatedPost !== null);

        if (! $adEnabled && ! $bacaJugaEnabled) {
            return $body;
        }

        $parts = preg_split('/(<\/p>)/i', $body, -1, PREG_SPLIT_DELIM_CAPTURE);
        $pCount = 0;
        foreach ($parts as $part) {
            if (strtolower($part) === '</p>') {
                $pCount++;
            }
        }

        if ($pCount === 0) {
            if ($bacaJugaEnabled && $relatedPost) {
                return $body.view('components.baca-juga', ['post' => $relatedPost])->render();
            }

            return $body;
        }

        $targetAdP = -1;
        $targetBacaJugaP = -1;

        if ($bacaJugaEnabled) {
            $targetBacaJugaP = ($pCount >= 3) ? 3 : $pCount;
        }

        if ($adEnabled) {
            if ($bacaJugaEnabled) {
                $targetAdP = 1;
            } else {
                $targetAdP = ($pCount >= 3) ? 3 : (($pCount >= 2) ? 2 : 1);
            }
        }

        $currentP = 0;
        $output = '';
        $adInjected = false;
        $bacaJugaInjected = false;

        for ($i = 0; $i < count($parts); $i++) {
            $output .= $parts[$i];

            if (strtolower($parts[$i]) === '</p>') {
                $currentP++;

                if ($adEnabled && ! $adInjected && $currentP === $targetAdP) {
                    $output .= view('components.ad-in-article')->render();
                    $adInjected = true;
                }

                if ($bacaJugaEnabled && ! $bacaJugaInjected && $currentP === $targetBacaJugaP) {
                    $output .= view('components.baca-juga', ['post' => $relatedPost])->render();
                    $bacaJugaInjected = true;
                }
            }
        }

        return $output;
    }

    public function getTagsListAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        if (is_array($this->tags)) {
            return array_values(array_filter(array_map('trim', $this->tags)));
        }

        $decoded = json_decode($this->tags, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }

    public static function getAllUsedTags(): array
    {
        $rawTags = static::query()
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->pluck('tags');

        $tagMap = [];
        $defaultTags = ['Opini', 'Teknologi', 'Tutorial', 'Laravel', 'PHP', 'JavaScript', 'Web'];

        foreach ($defaultTags as $dt) {
            $tagMap[trim($dt)] = true;
        }

        foreach ($rawTags as $tagStr) {
            if (empty($tagStr)) {
                continue;
            }

            if (is_array($tagStr)) {
                foreach ($tagStr as $t) {
                    $cleaned = trim((string) $t);
                    if ($cleaned !== '') {
                        $tagMap[$cleaned] = true;
                    }
                }

                continue;
            }

            $decoded = json_decode((string) $tagStr, true);
            if (is_array($decoded)) {
                foreach ($decoded as $t) {
                    $cleaned = trim((string) $t);
                    if ($cleaned !== '') {
                        $tagMap[$cleaned] = true;
                    }
                }

                continue;
            }

            $parts = explode(',', (string) $tagStr);
            foreach ($parts as $t) {
                $cleaned = trim($t);
                if ($cleaned !== '') {
                    $tagMap[$cleaned] = true;
                }
            }
        }

        $allTags = array_keys($tagMap);
        natcasesort($allTags);

        return array_values($allTags);
    }
}
