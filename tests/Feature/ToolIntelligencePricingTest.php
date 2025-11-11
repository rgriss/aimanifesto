<?php

use App\Models\Tool;
use App\Models\ToolIntelligence;

beforeEach(function () {
    // Set up API token for tests
    config(['services.api.token' => 'test-token-12345']);
});

test('pricing complexity fields can be updated and retrieved via API', function () {
    $tool = Tool::factory()->create(['slug' => 'test-tool']);

    // Create intelligence with pricing data
    $response = $this->putJson("/api/tools/{$tool->slug}/intelligence", [
        'pricing_individual_cost' => 2,
        'pricing_smb_cost' => 3,
        'pricing_enterprise_cost' => 5,
        'pricing_individual_range' => '$20/month',
        'pricing_smb_range' => '$500-10,000/month',
        'pricing_enterprise_range' => '$100K-5M+/year',
        'pricing_cost_notes' => 'Test pricing notes',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    // Verify data in database
    $intelligence = $tool->fresh()->intelligence;
    expect($intelligence)->not->toBeNull();
    expect($intelligence->pricing_individual_cost)->toBe(2);
    expect($intelligence->pricing_smb_cost)->toBe(3);
    expect($intelligence->pricing_enterprise_cost)->toBe(5);
    expect($intelligence->pricing_individual_range)->toBe('$20/month');
    expect($intelligence->pricing_smb_range)->toBe('$500-10,000/month');
    expect($intelligence->pricing_enterprise_range)->toBe('$100K-5M+/year');
    expect($intelligence->pricing_cost_notes)->toBe('Test pricing notes');

    // Verify fields are returned in API response
    $getResponse = $this->getJson("/api/tools/{$tool->slug}/intelligence", [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $getResponse->assertStatus(200);
    $getResponse->assertJson([
        'success' => true,
        'data' => [
            'pricing_individual_cost' => 2,
            'pricing_smb_cost' => 3,
            'pricing_enterprise_cost' => 5,
            'pricing_individual_range' => '$20/month',
            'pricing_smb_range' => '$500-10,000/month',
            'pricing_enterprise_range' => '$100K-5M+/year',
            'pricing_cost_notes' => 'Test pricing notes',
        ],
    ]);
});

test('pricing complexity fields are nullable', function () {
    $tool = Tool::factory()->create(['slug' => 'test-tool']);

    // Create intelligence without pricing data
    $response = $this->putJson("/api/tools/{$tool->slug}/intelligence", [
        'founded_year' => 2020,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200);

    // Verify pricing fields are null
    $getResponse = $this->getJson("/api/tools/{$tool->slug}/intelligence", [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $getResponse->assertStatus(200);
    $getResponse->assertJson([
        'success' => true,
        'data' => [
            'pricing_individual_cost' => null,
            'pricing_smb_cost' => null,
            'pricing_enterprise_cost' => null,
            'pricing_individual_range' => null,
            'pricing_smb_range' => null,
            'pricing_enterprise_range' => null,
            'pricing_cost_notes' => null,
        ],
    ]);
});

test('pricing complexity validates range correctly', function () {
    $tool = Tool::factory()->create(['slug' => 'test-tool']);

    // Test invalid pricing cost (outside 1-5 range)
    $response = $this->putJson("/api/tools/{$tool->slug}/intelligence", [
        'pricing_individual_cost' => 6, // Invalid
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['pricing_individual_cost']);
});

test('pricing complexity can be updated separately', function () {
    $tool = Tool::factory()->create(['slug' => 'test-tool']);

    // First create with some data
    $this->putJson("/api/tools/{$tool->slug}/intelligence", [
        'founded_year' => 2020,
        'pricing_individual_cost' => 2,
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    // Then update only pricing fields
    $response = $this->putJson("/api/tools/{$tool->slug}/intelligence", [
        'pricing_smb_cost' => 3,
        'pricing_enterprise_cost' => 5,
        'pricing_cost_notes' => 'Updated notes',
    ], [
        'Authorization' => 'Bearer test-token-12345',
    ]);

    $response->assertStatus(200);

    // Verify all fields are preserved
    $intelligence = $tool->fresh()->intelligence;
    expect($intelligence->founded_year)->toBe(2020); // Original data preserved
    expect($intelligence->pricing_individual_cost)->toBe(2); // Original pricing preserved
    expect($intelligence->pricing_smb_cost)->toBe(3); // New pricing added
    expect($intelligence->pricing_enterprise_cost)->toBe(5); // New pricing added
    expect($intelligence->pricing_cost_notes)->toBe('Updated notes');
});
