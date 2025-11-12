<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::query();

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by admin status
        if ($request->has('is_admin') && $request->input('is_admin') !== '') {
            $query->where('is_admin', $request->boolean('is_admin'));
        }

        // Filter by email verification
        if ($request->has('email_verified') && $request->input('email_verified') !== '') {
            if ($request->boolean('email_verified')) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'is_admin', 'email_verified']),
            'stats' => [
                'total' => User::count(),
                'admins' => User::where('is_admin', true)->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
                'newsletter_subscribed' => User::where('newsletter_subscribed', true)->count(),
            ],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // Prevent self-demotion from admin
        if ($user->id === $request->user()->id && $request->has('is_admin') && ! $request->boolean('is_admin')) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $validated = $request->validate([
            'is_admin' => ['sometimes', 'boolean'],
            'email_verified_at' => ['sometimes', 'nullable', 'date'],
        ]);

        if (isset($validated['is_admin'])) {
            $user->is_admin = $validated['is_admin'];
        }

        if ($request->has('email_verified_at')) {
            $user->email_verified_at = $validated['email_verified_at'] ? now() : null;
        }

        $user->save();

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account from here.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
