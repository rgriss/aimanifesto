<?php

use App\Models\User;

test('non-admin users cannot access dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(403);
});

test('admin users can access dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertStatus(200);
});

test('guest users cannot access dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
