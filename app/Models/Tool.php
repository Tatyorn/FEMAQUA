<?php

namespace App\Models;

use App\Observers\ToolObserver;
use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
