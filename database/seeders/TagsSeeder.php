<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'php'],
            ['name' => 'organization'],
            ['name' => 'planning'],
            ['name' => 'collaboration'],
            ['name' => 'writing'],
            ['name' => 'calendar'],
            ['name' => 'api'],
            ['name' => 'json'],
            ['name' => 'schema'],
            ['name' => 'node'],
            ['name' => 'github'],
            ['name' => 'rest'],
            ['name' => 'web'],
            ['name' => 'framework'],
            ['name' => 'http2'],
            ['name' => 'https'],
            ['name' => 'localhost']
        ];

        Tag::query()->insert($tags);
    }
}
