<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(
            Tool::class,
            'tool_tag',
            'tag_id',
            'tool_id'
        );
    }
}
