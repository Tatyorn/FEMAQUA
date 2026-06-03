<?php

namespace App\Actions;

use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateToolAction
{
    /**
     * @throws Throwable
     */
    public function handle(array $data): Tool
    {
        return DB::transaction(function () use ($data) {
            $tool = Tool::query()->create($data);

            if (! empty($data['tags'])) {
                $tagIds = Tag::query()
                    ->whereIn('name', $data['tags'])
                    ->pluck('id');

                $tool->tags()->sync($tagIds);
            }

            return $tool;
        });
    }
}
