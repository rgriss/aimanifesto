<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ToolResearchService
{
    public function process(ToolRequest $toolRequest): void
    {
        try {
            $categories = Category::pluck('name', 'id');
            $categoriesList = $categories->implode(', ');
            $prompt = $this->buildResearchPrompt($toolRequest, $categoriesList);
            $apiKey = config('services.anthropic.key');
            
            $response = Http::timeout(60)->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-haiku-20240307',
                'max_tokens' => 4096,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->failed()) {
                throw new \Exception('Research API call failed: ' . $response->body());
            }

            $content = $response->json('content.0.text');
            $json = [];
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
            }

            if (empty($json) || empty($json['tool'])) {
                throw new \Exception('Failed to parse research response');
            }

            $toolData = $json['tool'];
            $intelligenceData = $json['intelligence'] ?? [];

            $categoryName = $toolData['category_name'] ?? 'Uncategorized';
            $categoryId = $categories->search($categoryName);
            if ($categoryId === false) {
                $uncategorized = Category::where('name', 'Uncategorized')->first();
                $categoryId = $uncategorized ? $uncategorized->id : Category::first()->id;
            }

            $pricingModel = strtolower($toolData['pricing_model'] ?? 'freemium');
            if (!in_array($pricingModel, ['free', 'freemium', 'paid', 'enterprise'])) {
                $pricingModel = match($pricingModel) {
                    'open source', 'opensource' => 'free',
                    'subscription', 'saas' => 'paid',
                    default => 'freemium'
                };
            }

            $tool = Tool::create([
                'category_id' => $categoryId,
                'name' => $toolData['name'],
                'slug' => Str::slug($toolData['name']),
                'description' => $toolData['description'],
                'long_description' => $toolData['long_description'] ?? '',
                'website_url' => $toolData['website_url'] ?? '',
                'documentation_url' => $toolData['documentation_url'] ?? null,
                'pricing_model' => $pricingModel,
                'price_description' => $toolData['price_description'] ?? null,
                'company_name' => $toolData['company_name'] ?? null,
                'popularity_tier' => $this->normalizeEnum($toolData['popularity_tier'] ?? null, 
                    ['mainstream', 'well_known', 'growing', 'niche', 'emerging']),
                'momentum_score' => $toolData['momentum_score'] ?? null,
                'features' => $toolData['features'] ?? [],
                'use_cases' => $toolData['use_cases'] ?? [],
                'integrations' => $toolData['integrations'] ?? [],
                'is_active' => true,
            ]);

            // Create comprehensive intelligence record
            $tool->intelligence()->create([
                'founded_year' => $intelligenceData['founded_year'] ?? null,
                'tool_launched_year' => $intelligenceData['tool_launched_year'] ?? null,
                'company_status' => $this->normalizeEnum($intelligenceData['company_status'] ?? null,
                    ['private', 'public', 'acquired', 'subsidiary', 'open_source']),
                'stock_ticker' => $intelligenceData['stock_ticker'] ?? null,
                'parent_company' => $intelligenceData['parent_company'] ?? null,
                'acquisition_date' => $intelligenceData['acquisition_date'] ?? null,
                'headquarters' => $intelligenceData['headquarters'] ?? null,
                'employee_count_range' => $this->normalizeEnum($intelligenceData['employee_count_range'] ?? null,
                    ['1-10', '11-50', '51-200', '201-500', '501-1000', '1000-5000', '5000-10000', '10000+']),
                'estimated_users' => $this->normalizeEnum($intelligenceData['estimated_users'] ?? null,
                    ['< 10K', '10K-100K', '100K-1M', '1M-10M', '10M-50M', '50M-100M', '100M+']),
                'target_market' => $intelligenceData['target_market'] ?? null,
                'market_position' => $this->normalizeEnum($intelligenceData['market_position'] ?? null,
                    ['market_leader', 'major_player', 'challenger', 'niche_specialist', 'emerging']),
                'primary_competitors' => $intelligenceData['primary_competitors'] ?? null,
                'momentum_notes' => $intelligenceData['momentum_notes'] ?? null,
                'customer_sentiment' => $this->normalizeEnum($intelligenceData['customer_sentiment'] ?? null,
                    ['very_positive', 'positive', 'mixed', 'negative', 'very_negative']),
                'sentiment_notes' => $intelligenceData['sentiment_notes'] ?? null,
                'last_major_update' => $intelligenceData['last_major_update'] ?? null,
                'funding_stage' => $this->normalizeEnum($intelligenceData['funding_stage'] ?? null,
                    ['bootstrapped', 'seed', 'series_a', 'series_b', 'series_c+', 'public', 'profitable', 'acquired']),
                'latest_funding_amount' => $intelligenceData['latest_funding_amount'] ?? null,
                'latest_funding_date' => $intelligenceData['latest_funding_date'] ?? null,
                'estimated_annual_revenue' => $this->normalizeEnum($intelligenceData['estimated_annual_revenue'] ?? null,
                    ['< $1M', '$1M-$10M', '$10M-$50M', '$50M-$100M', '$100M-$500M', '$500M-$1B', '$1B+']),
                'pricing_individual_cost' => $intelligenceData['pricing_individual_cost'] ?? null,
                'pricing_smb_cost' => $intelligenceData['pricing_smb_cost'] ?? null,
                'pricing_midmarket_cost' => $intelligenceData['pricing_midmarket_cost'] ?? null,
                'pricing_enterprise_cost' => $intelligenceData['pricing_enterprise_cost'] ?? null,
                'pricing_cost_notes' => $intelligenceData['pricing_cost_notes'] ?? null,
                'pricing_individual_range' => $intelligenceData['pricing_individual_range'] ?? null,
                'pricing_smb_range' => $intelligenceData['pricing_smb_range'] ?? null,
                'pricing_midmarket_range' => $intelligenceData['pricing_midmarket_range'] ?? null,
                'pricing_enterprise_range' => $intelligenceData['pricing_enterprise_range'] ?? null,
                'key_differentiators' => $intelligenceData['key_differentiators'] ?? null,
                'strengths' => $intelligenceData['strengths'] ?? null,
                'weaknesses' => $intelligenceData['weaknesses'] ?? null,
                'market_threats' => $intelligenceData['market_threats'] ?? null, // TEXT, not array
                'growth_opportunities' => $intelligenceData['growth_opportunities'] ?? null, // TEXT, not array
                'strategic_notes' => $intelligenceData['strategic_notes'] ?? null,
                'analyst_summary' => $intelligenceData['analyst_summary'] ?? null,
                'last_researched_at' => now(),
            ]);

            $toolRequest->update([
                'status' => 'completed',
                'tool_id' => $tool->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Tool research failed: ' . $e->getMessage());
            $toolRequest->update([
                'status' => 'failed',
                'rejection_reason' => 'Research failed: ' . $e->getMessage(),
            ]);
        }
    }

    private function buildResearchPrompt(ToolRequest $toolRequest, string $categoriesList): string
    {
        $softwareName = $toolRequest->validation_result['software_name'];
        
        return <<<EOT
You are a comprehensive AI software researcher. Research "{$softwareName}" thoroughly.

Categories: [{$categoriesList}]

Output JSON with "tool" and "intelligence" sections:
{
  "tool": {
    "name": "Canonical Name",
    "description": "1-2 sentence pitch",
    "long_description": "2-3 paragraph overview",
    "website_url": "https://...",
    "documentation_url": "https://... or null",
    "category_name": "Match from list or Uncategorized",
    "pricing_model": "free|freemium|paid|enterprise",
    "price_description": "e.g., 'Free, Pro from \$20/mo'",
    "company_name": "Parent company",
    "popularity_tier": "mainstream|well_known|growing|niche|emerging",
    "momentum_score": 1-5,
    "features": ["Feature 1", "Feature 2"],
    "use_cases": ["Use 1", "Use 2"],
    "integrations": ["Slack", "API"]
  },
  "intelligence": {
    "founded_year": 2021,
    "tool_launched_year": 2022,
    "company_status": "private|public|acquired|subsidiary|open_source",
    "headquarters": "City, State",
    "employee_count_range": "1-10|11-50|51-200|201-500|501-1000|1000-5000|5000-10000|10000+",
    "estimated_users": "< 10K|10K-100K|100K-1M|1M-10M|10M-50M|50M-100M|100M+",
    "target_market": ["individual", "small_business", "mid_market", "enterprise"],
    "market_position": "market_leader|major_player|challenger|niche_specialist|emerging",
    "primary_competitors": ["Comp 1", "Comp 2"],
    "momentum_notes": "Trajectory explanation",
    "customer_sentiment": "very_positive|positive|mixed|negative|very_negative",
    "sentiment_notes": "Key drivers",
    "last_major_update": "Recent update",
    "funding_stage": "bootstrapped|seed|series_a|series_b|series_c+|public|profitable|acquired",
    "latest_funding_amount": "\$50M",
    "latest_funding_date": "2023-11",
    "estimated_annual_revenue": "< \$1M|\$1M-\$10M|\$10M-\$50M|\$50M-\$100M|\$100M-\$500M|\$500M-\$1B|\$1B+",
    "pricing_individual_cost": 1-5,
    "pricing_smb_cost": 1-5,
    "pricing_midmarket_cost": 1-5,
    "pricing_enterprise_cost": 1-5,
    "pricing_cost_notes": "Value analysis",
    "pricing_individual_range": "\$0-20/mo",
    "pricing_smb_range": "\$600-2,250/mo",
    "pricing_midmarket_range": "\$15K-40K/mo",
    "pricing_enterprise_range": "\$270K+/yr",
    "key_differentiators": ["Diff 1", "Diff 2"],
    "strengths": ["Strength 1", "Strength 2"],
    "weaknesses": ["Weak 1", "Weak 2"],
    "market_threats": "Text description of threats",
    "growth_opportunities": "Text description of opportunities",
    "strategic_notes": "Strategic positioning",
    "analyst_summary": "Executive summary"
  }
}

Be thorough. Use null for unknown fields.
EOT;
    }

    private function normalizeEnum(?string $value, array $validValues): ?string
    {
        if ($value === null) return null;
        $normalized = strtolower(str_replace(' ', '_', trim($value)));
        return in_array($normalized, $validValues) ? $normalized : null;
    }
}
