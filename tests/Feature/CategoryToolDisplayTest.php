<?php

use App\Models\Category;
use App\Models\Tool;

test('category page shows tools without ratings', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'is_active' => true,
    ]);

    // Create tools without ratings
    $tool1 = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Tool Without Rating',
        'slug' => 'tool-without-rating',
        'is_active' => true,
        'ryan_rating' => null,
    ]);

    $tool2 = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Another Tool Without Rating',
        'slug' => 'another-tool-without-rating',
        'is_active' => true,
        'ryan_rating' => null,
    ]);

    // Visit category page
    $response = $this->get(route('categories.show', $category));

    $response->assertStatus(200);

    // Both tools should appear even without ratings
    $response->assertSee('Tool Without Rating');
    $response->assertSee('Another Tool Without Rating');
});

test('category page orders tools by rating when available', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'is_active' => true,
    ]);

    // Create tools with different ratings
    $lowRated = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Low Rated Tool',
        'slug' => 'low-rated-tool',
        'is_active' => true,
        'ryan_rating' => 5,
    ]);

    $highRated = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'High Rated Tool',
        'slug' => 'high-rated-tool',
        'is_active' => true,
        'ryan_rating' => 10,
    ]);

    $unrated = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Unrated Tool',
        'slug' => 'unrated-tool',
        'is_active' => true,
        'ryan_rating' => null,
    ]);

    $response = $this->get(route('categories.show', $category));

    $response->assertStatus(200);

    // All tools should appear
    $response->assertSee('High Rated Tool');
    $response->assertSee('Low Rated Tool');
    $response->assertSee('Unrated Tool');

    // Load the actual data to verify ordering
    $category->load(['activeTools' => function ($query) {
        $query->highestRated();
    }]);

    $tools = $category->activeTools;

    // First tool should be highest rated (10)
    expect($tools->first()->ryan_rating)->toBe(10);

    // Last tools should be unrated or lower rated
    // (NULL values will be ordered after non-NULL in DESC order)
});

test('inactive tools do not appear on category page', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'is_active' => true,
    ]);

    $activeTool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Active Tool',
        'slug' => 'active-tool',
        'is_active' => true,
    ]);

    $inactiveTool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Inactive Tool',
        'slug' => 'inactive-tool',
        'is_active' => false,
    ]);

    $response = $this->get(route('categories.show', $category));

    $response->assertStatus(200);
    $response->assertSee('Active Tool');
    $response->assertDontSee('Inactive Tool');
});
