<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin credentials from environment or use defaults
        $email = env('ADMIN_EMAIL', 'admin@aimanifesto.net');
        $password = env('ADMIN_PASSWORD', 'password');
        $name = env('ADMIN_NAME', 'Admin User');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->command->info("Admin user already exists: {$email}");
            return;
        }

        // Create admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $this->command->info("Admin user created successfully!");
        $this->command->info("Email: {$email}");
        $this->command->info("Password: {$password}");
        $this->command->warn("Please change the password after first login!");
    }
}
