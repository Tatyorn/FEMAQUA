<?php

namespace App\Models;

use App\Observers\ToolObserver;
use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(ToolObserver::class)]
class Tool extends Model
{
    /** @use HasFactory<ToolFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'description',
        'user_id',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'tag_tool',
            'tool_id',
            'tag_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    public function byTag(Builder $query, string|array $tags): void
    {
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))
                ->map(fn ($tag) => trim(strtolower($tag)))
                ->toArray();
        }

        $query->whereHas(
            'tags',
            fn (Builder $q) => $q->whereIn('tags.name', $tags)
        );
    }

    #[Scope]
    public function byUser(Builder $query): void
    {
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('user_id', null);
        }

    }
}
