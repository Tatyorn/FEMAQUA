<?php

use App\Models\Tag;
use App\Models\Tool;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('should list and filter tools', function () {
    $tools = Tool::factory()
        ->withTags()
        ->count(5)
        ->create();

    $tools = $tools->pluck('title', 'link', 'description', 'tags')->toArray();

    get(route('tools.index'))
        ->assertSeeInOrder($tools)
        ->assertOk();
});

it('should create tool successfully', function () {
    $data = [
        'title' => 'Test Tool',
        'link' => 'https://example.com',
        'description' => 'This is a test tool.',
        'tags' => ['react', 'calendar'],
    ];

    $tags = Tag::query()
        ->whereIn('name', $data['tags'])
        ->get()
        ->toArray();

    $response = post(route('tools.store'), $data)
        ->assertCreated();

    $tool = $data;
    unset($tool['tags']);

    assertDatabaseHas('tools', $tool);

    expect($response->json('data.tags'))->toBe($tags)->toHaveCount(2);
});

it('should validate fields and return 422', function (array $expectedError) {
    $data = [
        'title' => 'Test Tool',
        'link' => 'https://example.com',
        'description' => 'This is a test tool.',
        'tags' => ['react', 'calendar'],
    ];

    post(route('tools.store'), $data)
        ->assertSessionHasErrors([$expectedError]);

    unset($data['tags']);
    assertDatabaseMissing('tools', $data);
});

it('should delete tool successfully', function () {
    $tool = Tool::factory()->create();

    delete(route('tools.index'))
        ->assertOk();

    assertDatabaseMissing('tools', $tool);
    assertDatabaseMissing('tag_tool', ['tool_id' => $tool->id]);
});
