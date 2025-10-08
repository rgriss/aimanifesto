<?php

use App\Models\Category;
use App\Models\Tool;
use Inertia\Testing\AssertableInertia as Assert;

test('homepage loads successfully', function () {
    $response = $this->get('/');
    
    $response->assertStatus(200);
});

test('homepage displays navigation with tools and categories links', function () {
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Home')
    );
});

test('homepage displays featured tools', function () {
    // Create a category and featured tool
    $category = Category::factory()->create(['name' => 'Code Assistants']);
    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'GitHub Copilot',
        'is_featured' => true,
    ]);
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Home')
        ->has('featuredTools', 1)
        ->where('featuredTools.0.name', 'GitHub Copilot')
    );
});

test('homepage displays categories', function () {
    $categories = Category::factory()->count(3)->create();
    
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Home')
        ->has('categories', 3)
    );
});