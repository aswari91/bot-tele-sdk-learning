<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TgConfig;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Aswari',
            'email' => 'aswari91@gmail.com',
            'password' => bcrypt('qwerty'),
        ]);

        TgConfig::create([
            'key' => 'bot_token',
            'value' => null,
        ]);

        TgConfig::create([
            'key' => 'bot_username',
            'value' => null,
        ]);

        TgConfig::create([
            'key' => 'webhook_url',
            'value' => null,
        ]);
    }
}
