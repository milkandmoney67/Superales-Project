<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample tasks.
     */
    public function run(): void
    {
        $this->call(TaskSeeder::class);
    }
}
