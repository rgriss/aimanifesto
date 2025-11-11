<?php

use App\Models\Category;
use App\Models\Tool;

beforeEach(function () {
    // Set up API token for tests
    config(['services.api.token' => 'test-token-12345']);
});

// ====== GET /api/categories (Index) Tests ======

test('lists all active categories', function () {
    Category::factory()->count(5)->create(['is_active' => true]);
    Category::factory()->create(['is_active' => false]); // Should be excluded

    $response = $this->getJson('/api/categories', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true])
        ->assertJsonCount(5, 'data');
});

test('includes tools count in category listing', function () {
    $category = Category::factory()->create();
    Tool::factory()->count(3)->create(['category_id' => $category->id, 'is_active' => true]);

    $response = $this->getJson('/api/categories', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                [
                    'id' => $category->id,
                    'tools_count' => 3,
                ],
            ],
        ]);
});

test('filters categories by search term', function () {
    Category::factory()->create(['name' => 'AI Assistants']);
    Category::factory()->create(['name' => 'Code Generation']);
    Category::factory()->create(['name' => 'Image Tools']);

    $response = $this->getJson('/api/categories?search=AI', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('paginates categories when requested', function () {
    Category::factory()->count(25)->create();

    $response = $this->getJson('/api/categories?paginate=true&per_page=10', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure(['success', 'data', 'meta' => ['current_page', 'last_page', 'per_page', 'total']]);
});

test('returns all categories without pagination by default', function () {
    Category::factory()->count(25)->create();

    $response = $this->getJson('/api/categories', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(25, 'data')
        ->assertJsonMissing(['meta']);
});

// ====== GET /api/categories/{slug} (Show) Tests ======

test('shows specific category by slug', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'description' => 'Test description',
        'icon' => '🧪',
    ]);

    $response = $this->getJson('/api/categories/test-category', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => 'Test Category',
                'slug' => 'test-category',
                'description' => 'Test description',
                'icon' => '🧪',
            ],
        ]);
});

test('returns 404 for non-existent category', function () {
    $response = $this->getJson('/api/categories/non-existent', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Category not found',
        ]);
});

// ====== POST /api/categories (Store) Tests ======

test('creates category with minimal data', function () {
    $response = $this->postJson('/api/categories', [
        'name' => 'New Category',
        'description' => 'A new category for testing',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => [
                'name' => 'New Category',
                'slug' => 'new-category',
                'description' => 'A new category for testing',
                'icon' => '🔧', // Default icon
                'is_active' => true,
            ],
        ]);

    $this->assertDatabaseHas('categories', [
        'name' => 'New Category',
        'slug' => 'new-category',
    ]);
});

test('creates category with all fields', function () {
    $response = $this->postJson('/api/categories', [
        'name' => 'Complete Category',
        'description' => 'A fully specified category',
        'icon' => '🎨',
        'is_active' => true,
        'sort_order' => 5,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'name' => 'Complete Category',
        'icon' => '🎨',
        'sort_order' => 5,
    ]);
});

test('rejects duplicate category creation', function () {
    Category::factory()->create([
        'name' => 'Existing Category',
        'slug' => 'existing-category',
    ]);

    $response = $this->postJson('/api/categories', [
        'name' => 'Existing Category',
        'description' => 'Attempting to create duplicate',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'success' => false,
            'message' => 'A category with this name already exists.',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'existing_category' => ['id', 'name', 'slug', 'url'],
        ]);
});

test('validates required fields for category creation', function () {
    $response = $this->postJson('/api/categories', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'description']);
});

test('rejects unauthorized category creation', function () {
    $response = $this->postJson('/api/categories', [
        'name' => 'Test Category',
        'description' => 'Test',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing API token.',
        ]);
});

// ====== PUT/PATCH /api/categories/{slug} (Update) Tests ======

test('updates category with partial data', function () {
    $category = Category::factory()->create([
        'name' => 'Original Category',
        'slug' => 'original-category',
        'description' => 'Original description',
        'icon' => '🔧',
    ]);

    $response = $this->putJson('/api/categories/original-category', [
        'name' => 'Updated Category',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => [
                'name' => 'Updated Category',
                'slug' => 'updated-category',
                'description' => 'Original description', // Should remain unchanged
            ],
        ]);
});

test('updates category with all fields', function () {
    $category = Category::factory()->create([
        'name' => 'Category to Update',
        'slug' => 'category-to-update',
    ]);

    $response = $this->patchJson('/api/categories/category-to-update', [
        'description' => 'Updated description',
        'icon' => '🎯',
        'is_active' => false,
        'sort_order' => 10,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200);

    $category->refresh();
    expect($category->description)->toBe('Updated description');
    expect($category->icon)->toBe('🎯');
    expect($category->is_active)->toBeFalse();
    expect($category->sort_order)->toBe(10);
});

test('returns 404 when updating non-existent category', function () {
    $response = $this->putJson('/api/categories/non-existent', [
        'name' => 'Updated',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Category not found',
        ]);
});

test('validates updated fields for category', function () {
    $category = Category::factory()->create();

    $response = $this->putJson("/api/categories/{$category->slug}", [
        'sort_order' => -5, // Invalid - must be 0 or greater
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_order']);
});

// ====== DELETE /api/categories/{slug} (Destroy) Tests ======

test('deletes category without tools', function () {
    $category = Category::factory()->create([
        'name' => 'Category to Delete',
        'slug' => 'category-to-delete',
    ]);

    $response = $this->deleteJson('/api/categories/category-to-delete', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => "Category 'Category to Delete' deleted successfully",
        ]);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('prevents deleting category with associated tools', function () {
    $category = Category::factory()->create([
        'name' => 'Category With Tools',
        'slug' => 'category-with-tools',
    ]);
    Tool::factory()->count(3)->create(['category_id' => $category->id]);

    $response = $this->deleteJson('/api/categories/category-with-tools', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Cannot delete category with 3 associated tools. Please move or delete the tools first.',
        ]);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('returns 404 when deleting non-existent category', function () {
    $response = $this->deleteJson('/api/categories/non-existent', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Category not found',
        ]);
});

// ====== Additional Tests ======

test('generates unique slug for categories', function () {
    Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
    ]);

    // Try to create another with same name - should fail due to duplicate name check
    $response = $this->postJson('/api/categories', [
        'name' => 'Test Category',
        'description' => 'Another test category',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(409); // Duplicate name not allowed
});

test('returns correct category URL in response', function () {
    $response = $this->postJson('/api/categories', [
        'name' => 'URL Test Category',
        'description' => 'Testing URL generation',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $data = $response->json('data');
    expect($data['url'])->toContain('/categories/url-test-category');
});
