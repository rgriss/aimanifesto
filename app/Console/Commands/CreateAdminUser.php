<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--email= : The email address of the admin user}
                            {--password= : The password for the admin user}
                            {--name= : The name of the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user or promote existing user to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating admin user...');

        // Get or ask for email
        $email = $this->option('email') ?: $this->ask('Email address');

        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address.');
            return Command::FAILURE;
        }

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // User exists, promote to admin
            if ($user->is_admin) {
                $this->info("User {$email} is already an admin.");
                return Command::SUCCESS;
            }

            $user->is_admin = true;
            $user->save();

            $this->info("✓ User {$email} has been promoted to admin.");
            return Command::SUCCESS;
        }

        // Create new user
        $name = $this->option('name') ?: $this->ask('Name');
        $password = $this->option('password') ?: $this->secret('Password (min 8 characters)');

        // Validate password
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return Command::FAILURE;
        }

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
            'email_verified_at' => now(), // Auto-verify admin users
        ]);

        $this->newLine();
        $this->info('✓ Admin user created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $user->name],
                ['Email', $user->email],
                ['Admin', 'Yes'],
                ['Email Verified', 'Yes'],
            ]
        );

        $this->newLine();
        $this->info("You can now login at: " . url('/login'));

        return Command::SUCCESS;
    }
}
