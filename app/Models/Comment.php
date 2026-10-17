<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'website',
        'comment',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    protected function isAuthorReply(): Attribute
    {
        return Attribute::make(
            get: fn () => ! is_null($this->user_id)
        );
    }

    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at ? $this->created_at->locale('id')->diffForHumans() : '-'
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->user_id && $this->author?->avatar) {
                    return $this->author->avatar;
                }

                $hash = md5(strtolower(trim($this->email)));
                $fallback = urlencode('https://ui-avatars.com/api/'.urlencode($this->name).'/150/27272a/ffffff');

                return "https://www.gravatar.com/avatar/{$hash}?s=150&d={$fallback}";
            }
        );
    }
}
