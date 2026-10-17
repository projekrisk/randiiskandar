<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    /**
     * Boot the model and clear related caches on create, update, or delete.
     */
    protected static function booted(): void
    {
        static::saved(function (self $post) {
            Cache::forget('all_published_posts_count');
        });

        static::deleted(function (self $post) {
            Cache::forget('all_published_posts_count');
        });
    }

    // --- RELATIONSHIPS ---

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

    // --- SCOPES ---

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    // --- ACCESSORS & MUTATORS ---

    /**
     * Memastikan konten body selalu bersih dari blok manual BACA JUGA
     * dan format tabel selalu semantic murni tanpa parameter class atau style inline.
     */
    protected function body(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => self::cleanTableHtml(self::stripBacaJuga($value)),
        );
    }

    /**
     * Otomatis membuat meta description dari excerpt atau 150 karakter pertama body.
     */
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

    /**
     * Estimasi waktu baca (rata-rata 200 kata per menit).
     */
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

    /**
     * Format tanggal Indonesia standar editorial (e.g. 16 Oktober 2026).
     */
    protected function formattedPublishedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at
                ? $this->published_at->locale('id')->translatedFormat('d F Y')
                : '-'
        );
    }

    /**
     * Generate otomatis Daftar Isi (TOC) dari tag H2 dan H3 pada konten body.
     *
     * @return array<int, array{id: string, text: string, level: int}>
     */
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

    /**
     * Menyisipkan id pada tag H2 dan H3 agar link TOC dapat melakukan scroll smooth,
     * serta mengoptimalkan tag <img> dengan loading="lazy" dan decoding="async".
     */
    public function getParsedBodyAttribute(): string
    {
        if (empty($this->body)) {
            return '';
        }

        $cleanedBody = static::cleanTableHtml($this->body);

        // Tambahkan id ke tag H2 dan H3 untuk TOC
        $parsed = preg_replace_callback(
            '/<(h[2-3])([^>]*)>(.*?)<\/\1>/i',
            function ($matches) {
                $tag = $matches[1];
                $attrs = $matches[2];
                $content = $matches[3];
                $slug = Str::slug(strip_tags($content));

                // Jika sudah ada id, biarkan. Jika belum, tambahkan id.
                if (! preg_match('/id=[\'"][^\'"]*[\'"]/i', $attrs)) {
                    return "<{$tag}{$attrs} id=\"{$slug}\">{$content}</{$tag}>";
                }

                return "<{$tag}{$attrs}>{$content}</{$tag}>";
            },
            $cleanedBody
        );

        // Optimalkan semua gambar inline dalam artikel dengan loading="lazy" dan decoding="async"
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

    /**
     * Mendapatkan 1 artikel rekomendasi acak (Random) untuk disisipkan sebagai "Baca Juga".
     * Mengutamakan kategori yang sama secara acak, atau fallback ke artikel acak lainnya bila belum ada artikel lain sekategori.
     */
    public function getRelatedCategoryPost(): ?self
    {
        if ($this->relationLoaded('relatedCategoryPost')) {
            return $this->getRelation('relatedCategoryPost');
        }

        $related = null;

        // 1. Coba ambil artikel acak dari kategori yang sama
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

        // 2. Jika tidak ada artikel lain dalam kategori yang sama, ambil artikel acak dari kategori lain
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

    /**
     * Membersihkan blok BACA JUGA dari konten artikel (sehingga tidak muncul di teks editor)
     */
    public static function stripBacaJuga(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        if (stripos($content, 'BACA JUGA') === false) {
            return $content;
        }

        // Pola 1: Div berkelas 'not-prose' yang memuat kartu rekomendasi BACA JUGA beserta seluruh anak elemennya
        $patternNotProse = '/<div\b[^>]*\bnot-prose\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?<rec1><div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?&rec1))*<\/div>))*<\/div>/is';
        $cleaned = preg_replace_callback($patternNotProse, function ($matches) {
            return (stripos($matches[0], 'BACA JUGA') !== false) ? '' : $matches[0];
        }, $content);

        // Pola 2: Div umum yang memuat BACA JUGA (jika tidak menggunakan kelas not-prose)
        if (stripos($cleaned, 'BACA JUGA') !== false) {
            $patternDiv = '/<div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?<rec2><div\b[^>]*>(?:[^<]+|<(?!\/?div\b)|(?&rec2))*<\/div>))*<\/div>/is';
            $cleaned = preg_replace_callback($patternDiv, function ($matches) {
                return (stripos($matches[0], 'BACA JUGA') !== false) ? '' : $matches[0];
            }, $cleaned);
        }

        // Pola 3: Format paragraf manual seperti <p><strong>BACA JUGA:</strong> ...</p>
        if (stripos($cleaned, 'BACA JUGA') !== false) {
            $patternP = '/<p\b[^>]*>\s*(?:<strong>|<b>)?\s*BACA\s+JUGA(?:\s*:)?\s*(?:<\/strong>|<\/b>)?[\s\S]*?<\/p>/i';
            $cleaned = preg_replace($patternP, '', $cleaned);
        }

        return trim((string) $cleaned);
    }

    /**
     * Membersihkan tabel HTML dari parameter class, style, dan atribut styling inline lainnya
     * agar tabel tersimpan sebagai HTML murni semantic (table, thead, tbody, tr, th, td).
     */
    public static function cleanTableHtml(?string $content): string
    {
        if (empty($content) || stripos($content, '<table') === false) {
            return (string) ($content ?? '');
        }

        // 1. Lepas (unwrap) container pembungkus div overflow-x-auto / table-scroll-container / not-prose
        $patternWrapper = '/<div[^>]*class=["\'][^"\']*(?:overflow-x-auto|table-scroll-container|table-responsive|article-table-wrapper)[^"\']*["\'][^>]*>\s*(<table[\s\S]*?<\/table>)\s*<\/div>/i';
        while (preg_match($patternWrapper, $content)) {
            $content = preg_replace($patternWrapper, '$1', $content);
        }

        // 2. Bersihkan atribut class, style, border, dsb dari seluruh tag tabel
        $tags = ['table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'colgroup', 'col', 'caption'];
        foreach ($tags as $tag) {
            $content = preg_replace_callback('/<('.$tag.')\b([^>]*)>/i', function ($m) {
                $tagName = strtolower($m[1]);
                $attrs = $m[2];

                // Pertahankan hanya atribut struktural esensial jika ada (colspan, rowspan, scope)
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

    /**
     * Menyisipkan slot iklan in-article dan kartu rekomendasi "Baca Juga" otomatis
     * dari kategori yang sama ke dalam sela paragraf artikel.
     */
    public function getBodyWithInjectedAdAttribute(): string
    {
        // Pastikan konten dasar bersih dari blok manual BACA JUGA agar selalu otomatis
        $body = static::stripBacaJuga($this->parsed_body);

        $adEnabled = Setting::get('ad_in_article_enabled', '1') !== '0';
        $bacaJugaSettingEnabled = Setting::get('baca_juga_enabled', '1') !== '0';

        $relatedPost = $bacaJugaSettingEnabled
            ? $this->getRelatedCategoryPost()
            : null;
        $bacaJugaEnabled = ($relatedPost !== null);

        // Jika iklan maupun baca juga tidak aktif, kembalikan body asli
        if (! $adEnabled && ! $bacaJugaEnabled) {
            return $body;
        }

        // Cari semua kemunculan penutup </p>
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

        // Tentukan target nomor paragraf untuk Iklan dan Baca Juga
        $targetAdP = -1;
        $targetBacaJugaP = -1;

        if ($bacaJugaEnabled) {
            // Sesuai permintaan: BACA JUGA muncul setelah paragraf 3
            // (Jika artikel memiliki kurang dari 3 paragraf, disisipkan di paragraf terakhir)
            $targetBacaJugaP = ($pCount >= 3) ? 3 : $pCount;
        }

        if ($adEnabled) {
            if ($bacaJugaEnabled) {
                if ($pCount >= 3) {
                    $targetAdP = 1; // Iklan di p1, BACA JUGA setelah p3
                } else {
                    $targetAdP = 1;
                }
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

                // Sisipkan Iklan jika giliran
                if ($adEnabled && ! $adInjected && $currentP === $targetAdP) {
                    $output .= view('components.ad-in-article')->render();
                    $adInjected = true;
                }

                // Sisipkan Baca Juga jika giliran
                if ($bacaJugaEnabled && ! $bacaJugaInjected && $currentP === $targetBacaJugaP) {
                    $output .= view('components.baca-juga', ['post' => $relatedPost])->render();
                    $bacaJugaInjected = true;
                }
            }
        }

        return $output;
    }

    /**
     * Mendapatkan daftar tags sebagai array string rapi.
     *
     * @return array<int, string>
     */
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

    /**
     * Mengambil seluruh daftar tag unik yang pernah dibuat di seluruh artikel.
     *
     * @return array<int, string>
     */
    public static function getAllUsedTags(): array
    {
        $rawTags = static::query()
            ->whereNotNull('tags')
            ->where('tags', '!=', '')
            ->pluck('tags');

        $tagMap = [];
        $defaultTags = ['Editorial', 'Tipografi', 'Desain Web', 'Arsitektur Web', 'Tutorial', 'AI', 'UI/UX', 'Minimalisme', 'PHP', 'Laravel'];

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
