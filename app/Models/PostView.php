<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'viewed_date',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'viewed_date' => 'date',
            'views' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public static function recordView(int $postId, ?string $date = null): void
    {
        $viewedDate = $date ?? Carbon::today()->toDateString();

        $log = static::firstOrCreate(
            ['post_id' => $postId, 'viewed_date' => $viewedDate],
            ['views' => 0]
        );

        $log->increment('views');
    }
}
