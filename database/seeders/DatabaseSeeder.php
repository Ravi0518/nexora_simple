<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\SnakeSeeder::class,
            \Database\Seeders\ExpertSeeder::class,
            \Database\Seeders\FactSeeder::class,
        ]);
    }
}