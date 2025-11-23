<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToolRequestValidationService
{
    public function validate(string $userInput): array
    {
        // 1. Fetch existing tool names to prevent duplicates
        $existingTools = Tool::pluck('name')->implode(', ');

        // 2. Construct the prompt
        $prompt = <<<EOT
You are a validation assistant for a software directory.
User Input: "{$userInput}"

Existing Software in Database: [{$existingTools}]

Task:
1. Determine if the User Input describes a real, valid software product.
2. Check if it already exists in the database (fuzzy match).
3. If valid and new, extract the canonical software name.

Output JSON only:
{
    "valid": boolean,
    "reason": "string explanation",
    "software_name": "Canonical Name" or null
}
EOT;

        // 3. Call Anthropic API
        try {
            $apiKey = config('services.anthropic.key');
            
            if (empty($apiKey)) {
                Log::error('Anthropic API key is missing.');
                return [
                    'valid' => false,
                    'reason' => 'Validation service unavailable (missing config).',
                    'software_name' => null,
                ];
            }

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-haiku-20240307',
                'max_tokens' => 1024,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Anthropic API call failed', ['status' => $response->status(), 'body' => $response->body()]);
                return [
                    'valid' => false,
                    'reason' => 'Validation service error.',
                    'software_name' => null,
                ];
            }

            $content = $response->json('content.0.text');
            
            // Extract JSON from response (in case of extra text)
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
                return $json ?? [
                    'valid' => false,
                    'reason' => 'Failed to parse validation response.',
                    'software_name' => null,
                ];
            }

            return [
                'valid' => false,
                'reason' => 'Invalid response format from validator.',
                'software_name' => null,
            ];

        } catch (\Exception $e) {
            Log::error('Tool validation exception: ' . $e->getMessage());
            return [
                'valid' => false,
                'reason' => 'Internal validation error.',
                'software_name' => null,
            ];
        }
    }
}
