<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tool>
 */
class ToolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'link' => fake()->url(),
            'description' => fake()->realText(),
        ];
    }

    public function withTags(?array $tagsIds = null): static
    {
        return $this->afterCreating(function (Tool $tool) use ($tagsIds) {
            $tagsIds = $tagsIds
                ? collect($tagsIds)
                : Tag::factory()->count(3)->create()->pluck('id');

            $tool->tags()->attach($tagsIds);
        });
    }
}
