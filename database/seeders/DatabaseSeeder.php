<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(['email' => 'admin@email.com'],[
            'name' => 'Test User',
            'email' => 'admin@email.com',
            'password' => 'biztrip',
        ]);

        $this->call([
            TagsSeeder::class,
        ]);
    }
}
