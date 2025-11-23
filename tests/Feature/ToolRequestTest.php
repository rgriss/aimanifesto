<?php

namespace Tests\Feature;

use App\Jobs\ProcessToolRequest;
use App\Models\ToolRequest;
use App\Models\User;
use App\Services\ToolRequestValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class ToolRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_submit_requests()
    {
        $response = $this->postJson(route('tools.request'), [
            'user_input' => 'New Tool',
        ]);

        $response->assertUnauthorized();
    }

    public function test_valid_request_creates_record_and_dispatches_job()
    {
        Queue::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        // Mock Validation Service
        $this->mock(ToolRequestValidationService::class, function ($mock) {
            $mock->shouldReceive('validate')
                ->once()
                ->andReturn([
                    'valid' => true,
                    'reason' => 'Looks good',
                    'software_name' => 'New Tool',
                ]);
        });

        $response = $this->postJson(route('tools.request'), [
            'user_input' => 'I want to add New Tool',
        ]);

        $response->assertOk()
            ->assertJson(['valid' => true]);

        $this->assertDatabaseHas('tool_requests', [
            'user_id' => $user->id,
            'user_input' => 'I want to add New Tool',
            'status' => 'approved',
        ]);

        Queue::assertPushed(ProcessToolRequest::class);
    }

    public function test_invalid_request_is_rejected()
    {
        Queue::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        // Mock Validation Service
        $this->mock(ToolRequestValidationService::class, function ($mock) {
            $mock->shouldReceive('validate')
                ->once()
                ->andReturn([
                    'valid' => false,
                    'reason' => 'Not a software tool',
                    'software_name' => null,
                ]);
        });

        $response = $this->postJson(route('tools.request'), [
            'user_input' => 'Not a tool',
        ]);

        $response->assertStatus(422)
            ->assertJson(['valid' => false]);

        $this->assertDatabaseHas('tool_requests', [
            'user_id' => $user->id,
            'user_input' => 'Not a tool',
            'status' => 'rejected',
            'rejection_reason' => 'Not a software tool',
        ]);

        Queue::assertNotPushed(ProcessToolRequest::class);
    }
}
