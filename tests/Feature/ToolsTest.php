<?php

use App\Models\Tool;
use Database\Seeders\TagsSeeder;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('should list and filter tools', function () {
    $tools = Tool::factory()
        ->withTags()
        ->count(5)
        ->create();

    $tools = $tools->load('tags')
        ->map(fn ($tool) => [
            'title'       => $tool->title,
            'link'        => $tool->link,
            'description' => $tool->description,
            'tags'        => $tool->tags->pluck('name')->toArray(),
        ])->toArray();

    $response = get(route('tools.index'))
        ->assertOk();

    expect($response->json('data'))
        ->toHaveCount(count($tools))
        ->toContain(...$tools);
});

it('should create tool successfully', function (array $data) {
    $this->seed(TagsSeeder::class);

    $response = post(route('tools.store'), $data);

    $tool = $data;
    unset($tool['tags']);

    assertDatabaseHas('tools', $tool);

    expect($response->json('data.tags'))->toContain(...$data['tags'])->toHaveCount(2);
})->with('valid tool data');

it('should validate fields and return 422', function (array $data, string $fieldError) {
    $this->seed(TagsSeeder::class);

    postJson('tools', $data)
        ->assertStatus(422)
        ->assertInvalid([$fieldError]);

    unset($data['tags']);
    assertDatabaseMissing('tools', $data);
})->with('invalid tool data');

//it('should delete tool successfully', function () {
//    $tool = Tool::factory()->create();
//
//    delete(route('tools.index'))
//        ->assertOk();
//
//    assertDatabaseMissing('tools', $tool);
//    assertDatabaseMissing('tag_tool', ['tool_id' => $tool->id]);
//});
