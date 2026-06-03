<?php

use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Database\Seeders\TagsSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

describe('without authentication', function () {
    it('should list tools', function () {
        $tools = Tool::factory()
            ->withTags()
            ->count(5)
            ->create();

        $tools = $tools->load('tags')
            ->map(fn ($tool) => [
                'title' => $tool->title,
                'link' => $tool->link,
                'description' => $tool->description,
                'tags' => $tool->tags->pluck('name')->toArray(),
            ])->toArray();

        $response = get(route('tools.index'))
            ->assertOk();

        expect($response->json('data'))
            ->toHaveCount(count($tools))
            ->toContain(...$tools);
    });

    it('should list and filter tools', function () {
        $this->seed(TagsSeeder::class);

        $tagsPhp = Tag::query()
            ->whereIn('name', ['web', 'php'])
            ->pluck('id')
            ->toArray();

        $toolsForFilterLaravel = Tool::factory()
            ->withTags($tagsPhp)
            ->count(2)
            ->create()
            ->load('tags')
            ->map(fn ($tool) => [
                'title' => $tool->title,
                'link' => $tool->link,
                'description' => $tool->description,
                'tags' => $tool->tags->pluck('name')->toArray(),
            ])->toArray();

        $tagsNode = Tag::query()
            ->whereIn('name', ['node', 'web'])
            ->pluck('id')
            ->toArray();

        $toolsForFilterNode = Tool::factory()
            ->withTags($tagsNode)
            ->count(3)
            ->create()
            ->load('tags')
            ->map(fn ($tool) => [
                'title' => $tool->title,
                'link' => $tool->link,
                'description' => $tool->description,
                'tags' => $tool->tags->pluck('name')->toArray(),
            ])->toArray();

        $response = get(route('tools.index', ['tag' => 'php']))
            ->assertOk();

        expect($response->json('data'))
            ->toHaveCount(2)
            ->toContain(...$toolsForFilterLaravel)
            ->and(route('tools.index', ['tag' => 'php']))
            ->toBe(config('app.url').'/tools?tag=php');

        $response = get(route('tools.index', ['tag' => 'node']));

        expect($response->json('data'))
            ->toHaveCount(3)
            ->toContain(...$toolsForFilterNode)
            ->and(route('tools.index', ['tag' => 'node']))
            ->toBe(config('app.url').'/tools?tag=node');

        $allTools = array_merge($toolsForFilterLaravel, $toolsForFilterNode);

        $response = get(route('tools.index', ['tag' => 'web']));

        expect($response->json('data'))
            ->toHaveCount(5)
            ->toContain(...$allTools)
            ->and(route('tools.index', ['tag' => 'web']))
            ->toBe(config('app.url').'/tools?tag=web');

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

    it('should delete tool successfully', function () {
        $tool = Tool::factory()->create();

        delete(route('tools.destroy', $tool))
            ->assertOk();

        assertDatabaseMissing('tools', ['id' => $tool->id]);
        assertDatabaseMissing('tag_tool', ['tool_id' => $tool->id]);
    });
});

describe('with authentication', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();

        actingAs($this->user);
    });

    it('should list tools with authentication', function () {
        $user = User::factory()->create();

        Tool::factory()
            ->for($user)
            ->withTags()
            ->count(3)
            ->create();

        $tools = Tool::factory()
            ->for($this->user)
            ->withTags()
            ->count(5)
            ->create();

        $tools = $tools->load('tags')
            ->map(fn ($tool) => [
                'title' => $tool->title,
                'link' => $tool->link,
                'description' => $tool->description,
                'tags' => $tool->tags->pluck('name')->toArray(),
            ])->toArray();

        $response = get(route('auth.tools.index'))
            ->assertOk();

        expect($response->json('data'))
            ->toHaveCount(5)
            ->toContain(...$tools);
    });

    it('should create tool successfully with authentication', function (array $data) {
        $this->seed(TagsSeeder::class);

        $response = post(route('auth.tools.store'), $data);

        $tool = $data;
        unset($tool['tags']);

        assertDatabaseHas('tools', $tool);

        expect($response->json('data.tags'))->toContain(...$data['tags'])->toHaveCount(2);
    })->with('valid tool data');

    it('should delete tool successfully with authentication', function () {
        $tool = Tool::factory()->for($this->user)->create();

        delete(route('auth.tools.destroy', $tool))
            ->assertOk();

        assertDatabaseMissing('tools', ['id' => $tool->id]);
        assertDatabaseMissing('tag_tool', ['tool_id' => $tool->id]);
    });

    it('should return 403 when user try to delete tool from another', function () {
        $user = User::factory()->create();
        $tool = Tool::factory()->withTags()->for($user)->create();

        assertDatabaseCount('users', 2);
        delete(route('auth.tools.destroy', $tool))
            ->assertForbidden();

        assertDatabaseHas('tools', ['id' => $tool->id]);
        assertDatabaseHas('tag_tool', ['tool_id' => $tool->id]);
    });
});
