<?php

use App\Models\Category;
use App\Models\Tool;

test('tool show page includes updated_at in its props', function () {
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

test('viewing a tool increments views_count without changing updated_at', function () {
    $tool = Tool::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'is_active' => true,
        'views_count' => 5,
    ]);

    $this->travel(3)->days();

    $this->get(route('tools.show', $tool))->assertStatus(200);

    $fresh = $tool->fresh();
    expect($fresh->views_count)->toBe(6);
    expect($fresh->updated_at->toDateTimeString())->toBe($tool->updated_at->toDateTimeString());
});

test('tool show page exposes the research date and not a "Last Updated" date', function () {
    $tool = Tool::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'is_active' => true,
    ]);
    $tool->intelligence()->create(['last_researched_at' => '2026-03-15 12:00:00']);

    $this->get(route('tools.show', $tool))
        ->assertInertia(fn ($page) => $page
            ->component('Tools/Show')
            ->where('tool.intelligence.last_researched_at', fn ($v) => str_starts_with($v, '2026-03-15'))
        );

    expect(file_get_contents(resource_path('js/pages/Tools/Show.vue')))
        ->not->toContain('tool.updated_at')
        ->not->toContain('Last Updated');
});
