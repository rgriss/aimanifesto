<?php

use App\Models\Category;
use App\Models\Tool;
use Inertia\Testing\AssertableInertia as Assert;

test('categories index page loads successfully', function () {
    $response = $this->get('/categories');
    
    $response->assertStatus(200);
});

test('categories index displays all active categories', function () {
    $categories = Category::factory()->count(4)->create([
        'is_active' => true,
    ]);
    
    // Create inactive category that shouldn't show
    Category::factory()->create([
        'is_active' => false,
        'name' => 'Inactive Category',
    ]);
    
    $response = $this->get('/categories');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Categories/Index')
        ->has('categories', 4) // Only the 4 active ones
    );
});

test('categories are displayed in sort order', function () {
    Category::factory()->create(['name' => 'Zebra', 'sort_order' => 3]);
    Category::factory()->create(['name' => 'Alpha', 'sort_order' => 1]);
    Category::factory()->create(['name' => 'Beta', 'sort_order' => 2]);
    
    $response = $this->get('/categories');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Categories/Index')
        ->where('categories.0.name', 'Alpha')
        ->where('categories.1.name', 'Beta')
        ->where('categories.2.name', 'Zebra')
    );
});

test('individual category page loads successfully', function () {
    $category = Category::factory()->create([
        'slug' => 'code-assistants',
        'name' => 'Code Assistants',
    ]);
    
    $response = $this->get('/categories/code-assistants');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Categories/Show')
        ->where('category.name', 'Code Assistants')
    );
});

test('category page shows tools in that category', function () {
    $category = Category::factory()->create();
    $tools = Tool::factory()->count(3)->create([
        'category_id' => $category->id,
    ]);
    
    $response = $this->get('/categories/' . $category->slug);
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Categories/Show')
        ->has('category.active_tools', 3)
    );
});

test('category page shows tool count', function () {
    $category = Category::factory()->create();
    Tool::factory()->count(5)->create([
        'category_id' => $category->id,
        'is_active' => true,
    ]);
    
    $response = $this->get('/categories/' . $category->slug);
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Categories/Show')
        ->where('toolCount', 5)
    );
});