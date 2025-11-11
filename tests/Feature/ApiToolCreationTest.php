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

    // Make 31 requests (limit is 30 per minute)
    for ($i = 0; $i < 31; $i++) {
        $response = $this->postJson('/api/tools', [
            'name' => "Tool {$i}",
            'description' => 'Test',
            'website_url' => "https://test{$i}.com",
            'category' => $category->name,
        ], [
            'Authorization' => 'Bearer test-token-12345',
        ]);

        if ($i < 30) {
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
