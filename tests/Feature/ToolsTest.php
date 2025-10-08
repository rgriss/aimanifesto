<?php

use App\Models\Category;
use App\Models\Tool;
use Inertia\Testing\AssertableInertia as Assert;

test('tools index page loads successfully', function () {
    $response = $this->get('/tools');
    
    $response->assertStatus(200);
});

test('tools index displays all active tools', function () {
    $category = Category::factory()->create();
    $tools = Tool::factory()->count(5)->create([
        'category_id' => $category->id,
        'is_active' => true,
    ]);
    
    // Create an inactive tool that shouldn't show
    Tool::factory()->create([
        'category_id' => $category->id,
        'is_active' => false,
        'name' => 'Inactive Tool',
    ]);
    
    $response = $this->get('/tools');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Tools/Index')
        ->has('tools.data', 5) // 5 active tools, not the inactive one
    );
});

test('tools can be filtered by category', function () {
    $category1 = Category::factory()->create(['name' => 'Code Assistants', 'slug' => 'code-assistants']);
    $category2 = Category::factory()->create(['name' => 'Chat Tools', 'slug' => 'chat-tools']);
    
    $tool1 = Tool::factory()->create([
        'category_id' => $category1->id,
        'name' => 'GitHub Copilot',
    ]);
    
    $tool2 = Tool::factory()->create([
        'category_id' => $category2->id,
        'name' => 'ChatGPT',
    ]);
    
    $response = $this->get('/tools?category=' . $category1->slug);
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Tools/Index')
        ->has('tools.data', 1)
        ->where('tools.data.0.name', 'GitHub Copilot')
    );
});

test('individual tool page loads successfully', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'slug' => 'github-copilot',
        'name' => 'GitHub Copilot',
    ]);
    
    $response = $this->get('/tools/github-copilot');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Tools/Show')
        ->where('tool.name', 'GitHub Copilot')
        ->has('tool.description')
    );
});

test('tool page increments view count', function () {
    $category = Category::factory()->create();
    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'views_count' => 5,
    ]);
    
    $this->get('/tools/' . $tool->slug);
    
    $tool->refresh();
    expect($tool->views_count)->toBe(6);
});