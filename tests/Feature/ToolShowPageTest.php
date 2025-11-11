<?php

use App\Models\Category;
use App\Models\Tool;

test('tool show page displays updated_at timestamp', function () {
    $category = Category::factory()->create();

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Tool',
        'slug' => 'test-tool',
        'is_active' => true,
    ]);

    $response = $this->get(route('tools.show', $tool));

    $response->assertStatus(200);

    // Check that the updated_at is in the Inertia props
    $response->assertInertia(fn ($page) => $page
        ->component('Tools/Show')
        ->has('tool.updated_at')
        ->where('tool.name', 'Test Tool')
    );
});

test('tool updated_at changes when tool is modified', function () {
    $category = Category::factory()->create();

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Original Name',
        'slug' => 'test-tool',
        'is_active' => true,
    ]);

    $originalUpdatedAt = $tool->updated_at;

    // Wait a moment to ensure timestamp changes
    sleep(1);

    // Update the tool
    $tool->update(['name' => 'Updated Name']);
    $tool->refresh();

    // Verify updated_at changed
    expect($tool->updated_at->gt($originalUpdatedAt))->toBeTrue();
    expect($tool->name)->toBe('Updated Name');
});

test('tool show page includes all timestamp metadata', function () {
    $category = Category::factory()->create();

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Tool',
        'slug' => 'test-tool',
        'is_active' => true,
        'views_count' => 100,
    ]);

    $response = $this->get(route('tools.show', $tool));

    $response->assertStatus(200);
    $response->assertSee($tool->name);

    // The page should have access to timestamps through Inertia props
    $response->assertInertia(fn ($page) => $page
        ->component('Tools/Show')
        ->has('tool.created_at')
        ->has('tool.updated_at')
    );
});
