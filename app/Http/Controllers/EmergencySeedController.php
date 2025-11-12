<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class EmergencySeedController extends Controller
{
    /**
     * Seed the database with sample data (emergency backdoor for local dev)
     */
    public function seed(Request $request): JsonResponse
    {
        // Only allow in local/staging environments if enabled
        if (! config('app.emergency_seed_enabled', false)) {
            abort(404);
        }

        // Rate limiting: 5 attempts per hour
        $key = 'emergency-seed:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => "Too many seeding attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        try {
            // Log the seeding attempt
            Log::info('Emergency database seeding triggered', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Run the database seeder
            Artisan::call('db:seed', [
                '--force' => true,
            ]);

            return response()->json([
                'message' => 'Database seeded successfully! Refresh the page to see the data.',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Emergency database seeding failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Seeding failed: '.$e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
