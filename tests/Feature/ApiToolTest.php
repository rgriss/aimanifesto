<?php

use App\Models\Category;
use App\Models\Tool;

beforeEach(function () {
    // Set up API token for tests
    config(['services.api.token' => 'test-token-12345']);
});

test('creates tool with minimal data', function () {
    $category = Category::factory()->create();

    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'A test tool for API testing',
        'website_url' => 'https://test.com',
        'category' => $category->name,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Tool created successfully',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'category',
                'website_url',
                'is_active',
                'created_at',
                'url',
            ],
        ]);

    $this->assertDatabaseHas('tools', [
        'name' => 'Test Tool',
        'slug' => 'test-tool',
        'website_url' => 'https://test.com',
    ]);
});

test('creates tool with all fields', function () {
    $category = Category::factory()->create();

    $response = $this->postJson('/api/tools', [
        'name' => 'Complete Tool',
        'description' => 'A fully featured tool',
        'long_description' => 'This is a long description with more details',
        'website_url' => 'https://complete.com',
        'documentation_url' => 'https://docs.complete.com',
        'logo_url' => 'https://complete.com/logo.png',
        'category' => $category->name,
        'pricing_model' => 'freemium',
        'price_description' => 'Free tier available, $20/month Pro',
        'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
        'use_cases' => ['Use case 1', 'Use case 2'],
        'integrations' => ['API', 'Web', 'Mobile'],
        'ryan_rating' => 8,
        'ryan_notes' => 'Great tool, highly recommended',
        'is_featured' => true,
        'is_active' => true,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('tools', [
        'name' => 'Complete Tool',
        'pricing_model' => 'freemium',
        'ryan_rating' => 8,
        'is_featured' => true,
    ]);

    $tool = Tool::where('name', 'Complete Tool')->first();
    expect($tool->features)->toBe(['Feature 1', 'Feature 2', 'Feature 3']);
    expect($tool->use_cases)->toBe(['Use case 1', 'Use case 2']);
    expect($tool->integrations)->toBe(['API', 'Web', 'Mobile']);
});

test('rejects unauthorized request without token', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'Testing',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing API token.',
        ]);
});

test('rejects unauthorized request with invalid token', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'Testing',
    ], [
        'Authorization' => 'Bearer wrong-token',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing API token.',
        ]);
});

test('rejects duplicate tool creation', function () {
    $category = Category::factory()->create();
    Tool::factory()->create([
        'name' => 'Existing Tool',
        'slug' => 'existing-tool',
    ]);

    $response = $this->postJson('/api/tools', [
        'name' => 'Existing Tool',
        'description' => 'Attempting to create duplicate',
        'website_url' => 'https://duplicate.com',
        'category' => $category->name,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'success' => false,
            'message' => 'A tool with this name already exists.',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'existing_tool' => ['id', 'name', 'slug', 'url'],
        ]);
});

test('creates new category if it does not exist', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Tool With New Category',
        'description' => 'Testing category creation',
        'website_url' => 'https://newcat.com',
        'category' => 'Brand New Category',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'name' => 'Brand New Category',
        'slug' => 'brand-new-category',
    ]);
});

test('uses existing category with case-insensitive match', function () {
    $category = Category::factory()->create(['name' => 'AI Assistants']);

    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'ai assistants', // lowercase
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $tool = Tool::where('name', 'Test Tool')->first();
    expect($tool->category_id)->toBe($category->id);

    // Should not create a new category
    expect(Category::count())->toBe(1);
});

test('validates required fields', function () {
    $response = $this->postJson('/api/tools', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'description', 'website_url', 'category']);
});

test('validates url format', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'not-a-valid-url',
        'category' => 'Testing',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_url']);
});

test('validates pricing model enum', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'Testing',
        'pricing_model' => 'invalid-model',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['pricing_model']);
});

test('validates rating range', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'Testing',
        'ryan_rating' => 15, // Out of range
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['ryan_rating']);
});

test('validates array fields contain strings', function () {
    $response = $this->postJson('/api/tools', [
        'name' => 'Test Tool',
        'description' => 'Test',
        'website_url' => 'https://test.com',
        'category' => 'Testing',
        'features' => [123, 456], // Should be strings
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['features.0', 'features.1']);
});

test('generates unique slug for duplicate names', function () {
    $category = Category::factory()->create();

    // Create first tool
    Tool::factory()->create([
        'name' => 'Duplicate Name',
        'slug' => 'duplicate-name',
    ]);

    // This should fail because name must be unique
    $response = $this->postJson('/api/tools', [
        'name' => 'Duplicate Name',
        'description' => 'Another tool with same name',
        'website_url' => 'https://different.com',
        'category' => $category->name,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(409); // Conflict - duplicate name not allowed
});

test('rate limiting works', function () {
    $category = Category::factory()->create();

    // Make 61 requests (limit is 60 per minute)
    for ($i = 0; $i < 61; $i++) {
        $response = $this->postJson('/api/tools', [
            'name' => "Tool {$i}",
            'description' => 'Test',
            'website_url' => "https://test{$i}.com",
            'category' => $category->name,
        ], [
            'Authorization' => 'Bearer test-token-12345',
        ]);

        if ($i < 60) {
            $response->assertStatus(201);
        } else {
            $response->assertStatus(429); // Too Many Requests
        }
    }
});

test('returns correct tool URL in response', function () {
    $category = Category::factory()->create();

    $response = $this->postJson('/api/tools', [
        'name' => 'URL Test Tool',
        'description' => 'Testing URL generation',
        'website_url' => 'https://test.com',
        'category' => $category->name,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(201);

    $data = $response->json('data');
    expect($data['url'])->toContain('/tools/url-test-tool');
});

// ====== GET /api/tools (Index) Tests ======

test('lists all active tools', function () {
    $category = Category::factory()->create();
    Tool::factory()->count(5)->create(['category_id' => $category->id, 'is_active' => true]);
    Tool::factory()->create(['category_id' => $category->id, 'is_active' => false]); // Should be excluded

    $response = $this->getJson('/api/tools', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true])
        ->assertJsonCount(5, 'data');
});

test('filters tools by category', function () {
    $category1 = Category::factory()->create(['name' => 'Category One', 'slug' => 'category-one']);
    $category2 = Category::factory()->create(['name' => 'Category Two', 'slug' => 'category-two']);

    Tool::factory()->count(3)->create(['category_id' => $category1->id, 'is_active' => true]);
    Tool::factory()->count(2)->create(['category_id' => $category2->id, 'is_active' => true]);

    $response = $this->getJson('/api/tools?category=category-one', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('filters tools by search term', function () {
    $category = Category::factory()->create();
    Tool::factory()->create(['name' => 'Claude Code', 'category_id' => $category->id]);
    Tool::factory()->create(['name' => 'ChatGPT', 'category_id' => $category->id]);
    Tool::factory()->create(['name' => 'GitHub Copilot', 'category_id' => $category->id]);

    $response = $this->getJson('/api/tools?search=Claude', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('filters featured tools', function () {
    $category = Category::factory()->create();
    Tool::factory()->count(2)->create(['category_id' => $category->id, 'is_featured' => true]);
    Tool::factory()->count(3)->create(['category_id' => $category->id, 'is_featured' => false]);

    $response = $this->getJson('/api/tools?featured=true', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('paginates tools', function () {
    $category = Category::factory()->create();
    Tool::factory()->count(25)->create(['category_id' => $category->id]);

    $response = $this->getJson('/api/tools?per_page=10', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure(['success', 'data', 'meta' => ['current_page', 'last_page', 'per_page', 'total']]);
});

// ====== GET /api/tools/{slug} (Show) Tests ======

test('shows specific tool by slug', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create([
        'name' => 'Test Tool',
        'slug' => 'test-tool',
        'category_id' => $category->id,
    ]);

    $response = $this->getJson('/api/tools/test-tool', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $tool->id,
                'name' => 'Test Tool',
                'slug' => 'test-tool',
            ],
        ]);
});

test('returns 404 for non-existent tool', function () {
    $response = $this->getJson('/api/tools/non-existent-tool', [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Tool not found',
        ]);
});

// ====== PUT/PATCH /api/tools/{slug} (Update) Tests ======

test('updates tool with partial data', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create([
        'name' => 'Original Name',
        'slug' => 'original-name',
        'description' => 'Original description',
        'category_id' => $category->id,
    ]);

    $response = $this->putJson('/api/tools/original-name', [
        'name' => 'Updated Name',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tool updated successfully',
            'data' => [
                'name' => 'Updated Name',
                'slug' => 'updated-name',
                'description' => 'Original description', // Should remain unchanged
            ],
        ]);
});

test('updates tool with all fields', function () {
    $category1 = Category::factory()->create(['name' => 'Old Category']);
    $category2 = Category::factory()->create(['name' => 'New Category']);

    $tool = Tool::factory()->create([
        'name' => 'Tool to Update',
        'slug' => 'tool-to-update',
        'category_id' => $category1->id,
        'ryan_rating' => null,
    ]);

    $response = $this->patchJson('/api/tools/tool-to-update', [
        'description' => 'Updated description',
        'category' => 'New Category',
        'ryan_rating' => 9,
        'ryan_notes' => 'Excellent after updates',
        'is_featured' => true,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200);

    $tool->refresh();
    expect($tool->description)->toBe('Updated description');
    expect($tool->category_id)->toBe($category2->id);
    expect($tool->ryan_rating)->toBe(9);
    expect($tool->is_featured)->toBeTrue();
    expect($tool->first_reviewed_at)->not->toBeNull(); // Should be set when rating is added
});

test('returns 404 when updating non-existent tool', function () {
    $response = $this->putJson('/api/tools/non-existent', [
        'name' => 'Updated',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Tool not found',
        ]);
});

test('validates updated fields', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create(['category_id' => $category->id]);

    $response = $this->putJson("/api/tools/{$tool->slug}", [
        'website_url' => 'not-a-url',
        'ryan_rating' => 15,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_url', 'ryan_rating']);
});

// ====== DELETE /api/tools/{slug} (Destroy) Tests ======

test('deletes tool', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create([
        'name' => 'Tool to Delete',
        'slug' => 'tool-to-delete',
        'category_id' => $category->id,
    ]);

    $response = $this->deleteJson('/api/tools/tool-to-delete', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => "Tool 'Tool to Delete' deleted successfully",
        ]);

    $this->assertDatabaseMissing('tools', ['id' => $tool->id]);
});

test('returns 404 when deleting non-existent tool', function () {
    $response = $this->deleteJson('/api/tools/non-existent', [], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Tool not found',
        ]);
});
