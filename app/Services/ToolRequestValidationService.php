<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Support\Facades\Http;
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
<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Support\Facades\Http;
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

    /**
     * Check for duplicate tools using case-insensitive fuzzy matching
     * Returns the existing tool name if a match is found, null otherwise
     */
    private function checkForDuplicates(string $userInput): ?string
    {
        $normalizedInput = strtolower(trim($userInput));
        
        // Get all tool names
        $existingTools = Tool::pluck('name')->toArray();
        
        foreach ($existingTools as $toolName) {
            $normalizedTool = strtolower($toolName);
            
            // Exact match (case-insensitive)
            if ($normalizedInput === $normalizedTool) {
                return $toolName;
            }
            
            // Check if user input is contained in tool name
            // e.g., "photoshop" matches "Adobe Photoshop"
            if (str_contains($normalizedTool, $normalizedInput)) {
                return $toolName;
            }
            
            // Check if tool name is contained in user input
            // e.g., "Adobe Photoshop CC 2024" might match existing "Photoshop"
            if (str_contains($normalizedInput, $normalizedTool)) {
                return $toolName;
            }
            
            // Check for similar words (remove common prefixes/suffixes)
            $inputWords = preg_split('/\s+/', $normalizedInput);
            $toolWords = preg_split('/\s+/', $normalizedTool);
            
            // If they share the main keyword (longest word)
            $mainInputWord = $this->getLongestWord($inputWords);
            $mainToolWord = $this->getLongestWord($toolWords);
            
            if (strlen($mainInputWord) > 3 && strlen($mainToolWord) > 3) {
                // Check if one contains the other
                if (str_contains($mainToolWord, $mainInputWord) || str_contains($mainInputWord, $mainToolWord)) {
                    return $toolName;
                }
            }
        }
        
        return null;
    }

    /**
     * Get the longest word from an array of words
     */
    private function getLongestWord(array $words): string
    {
        $longest = '';
        foreach ($words as $word) {
            if (strlen($word) > strlen($longest)) {
                $longest = $word;
            }
        }
        return $longest;
    }
}
