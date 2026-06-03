<?php

namespace App\Models;

use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
            'tool_tag',
            'tool_id',
            'tag_id'
        );
    }
}
