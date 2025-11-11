<?php

test('developer tool schema page loads successfully', function () {
    $response = $this->get(route('developer.tool-schema'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Developer/ToolSchema')
        ->has('schema')
        ->has('schema.title')
        ->has('schema.description')
        ->has('schema.grouped')
        ->has('schema.examples')
    );
});

test('tool schema includes required fields group', function () {
    $response = $this->get(route('developer.tool-schema'));

    $response->assertInertia(fn ($page) => $page
        ->component('Developer/ToolSchema')
        ->where('schema.grouped.required.title', 'Required Fields')
        ->has('schema.grouped.required.properties')
    );
});

test('tool schema groups properties correctly', function () {
    $response = $this->get(route('developer.tool-schema'));

    $response->assertInertia(fn ($page) => $page
        ->component('Developer/ToolSchema')
        ->has('schema.grouped.required')
        ->has('schema.grouped.pricing')
        ->has('schema.grouped.technical')
        ->has('schema.grouped.personal')
    );
});

test('tool schema includes complete examples', function () {
    $response = $this->get(route('developer.tool-schema'));

    $response->assertInertia(fn ($page) => $page
        ->component('Developer/ToolSchema')
        ->has('schema.examples')
    );

    // Additionally verify examples contain data
    $examples = $response->viewData('page')['props']['schema']['examples'];
    expect($examples)->toBeArray()
        ->and(count($examples))->toBeGreaterThan(0);
});

test('required fields are properly marked', function () {
    $response = $this->get(route('developer.tool-schema'));

    $response->assertInertia(fn ($page) => $page
        ->component('Developer/ToolSchema')
        ->has('schema.grouped.required.properties')
    );

    // Verify required fields
    $properties = $response->viewData('page')['props']['schema']['grouped']['required']['properties'];
    $names = array_column($properties, 'name');

    expect($names)->toContain('name')
        ->toContain('description')
        ->toContain('website_url')
        ->toContain('category');

    // Verify all are marked as required
    foreach ($properties as $prop) {
        expect($prop['required'])->toBeTrue();
    }
});
