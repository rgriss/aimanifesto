<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class EmergencyAdminController extends Controller
{
    /**
     * Emergency admin creation endpoint.
     *
     * This is a recovery mechanism for when admin access is lost.
     * Only works if ADMIN_EMAIL and ADMIN_PASSWORD are set in .env
     *
     * Security measures:
     * - Rate limited (5 attempts per hour)
     * - Only creates if admin doesn't exist
     * - Logs all attempts
     * - Can be disabled via .env (EMERGENCY_ADMIN_ENABLED=false)
     */
    public function store(Request $request): JsonResponse
    {
        // Check if emergency admin creation is enabled
        if (! config('app.emergency_admin_enabled', false)) {
            abort(404);
        }

        // Rate limiting - 5 attempts per hour per IP
        $key = 'emergency-admin:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again in '.ceil($seconds / 60).' minutes.',
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        // Get credentials from environment
        $email = config('app.admin_email');
        $password = config('app.admin_password');
        $name = config('app.admin_name', 'Admin User');

        if (! $email || ! $password) {
            Log::warning('Emergency admin creation attempted but credentials not configured in .env');

            return response()->json([
                'success' => false,
                'message' => 'Admin credentials not configured.',
            ], 400);
        }

        // Check if admin already exists
        $existingAdmin = User::where('email', $email)->first();

        if ($existingAdmin) {
            if ($existingAdmin->is_admin) {
                Log::info('Emergency admin creation attempted but admin already exists', [
                    'ip' => $request->ip(),
                    'email' => $email,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Admin user already exists.',
                ]);
            }

            // User exists but is not admin - promote them
            $existingAdmin->is_admin = true;
            $existingAdmin->save();

            Log::warning('User promoted to admin via emergency endpoint', [
                'ip' => $request->ip(),
                'email' => $email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User promoted to admin successfully.',
            ]);
        }

        // Create new admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        Log::warning('Admin user created via emergency endpoint', [
            'ip' => $request->ip(),
            'email' => $email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully.',
        ]);
    }
}
