<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This seeder calls specialized seeders to populate the database
     * with production-like data for local development and testing.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database with production-like data...');

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ToolSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✓ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('You can now visit your local site to see the seeded data.');
    }
}