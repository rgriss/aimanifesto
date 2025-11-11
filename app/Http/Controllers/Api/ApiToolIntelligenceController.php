<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolIntelligence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiToolIntelligenceController extends Controller
{
    /**
     * Get intelligence data for a tool
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $tool = Tool::where('slug', $slug)->with('intelligence')->first();

            if (! $tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tool not found',
                ], 404);
            }

            // If no intelligence data exists, return empty structure
            if (! $tool->intelligence) {
                return response()->json([
                    'success' => true,
                    'message' => 'No intelligence data found for this tool',
                    'data' => null,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatIntelligenceResponse($tool->intelligence),
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve intelligence data', $e);
        }
    }

    /**
     * Update or create intelligence data for a tool
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        try {
            $tool = Tool::where('slug', $slug)->first();

            if (! $tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tool not found',
                ], 404);
            }

            // Validate the request
            $validated = $this->validateIntelligenceRequest($request);

            // Get or create intelligence record
            $intelligence = $tool->intelligence()->firstOrNew(['tool_id' => $tool->id]);

            // Update fields
            $intelligence->fill($validated);
            $intelligence->save();

            // Completeness score is auto-calculated by model event

            return response()->json([
                'success' => true,
                'message' => 'Intelligence data updated successfully',
                'data' => $this->formatIntelligenceResponse($intelligence->fresh()),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while updating intelligence data', $e);
        }
    }

    /**
     * Validate intelligence request
     */
    private function validateIntelligenceRequest(Request $request): array
    {
        return $request->validate([
            // Company Metadata
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:'.date('Y')],
            'tool_launched_year' => ['nullable', 'integer', 'min:1990', 'max:'.date('Y')],
            'company_status' => ['nullable', 'string', 'in:private,public,acquired,subsidiary,open_source'],
            'stock_ticker' => ['nullable', 'string', 'max:10'],
            'parent_company' => ['nullable', 'string', 'max:255'],
            'acquisition_date' => ['nullable', 'string', 'max:255'],
            'headquarters' => ['nullable', 'string', 'max:255'],
            'employee_count_range' => ['nullable', 'string', 'in:1-10,11-50,51-200,201-500,501-1000,1000-5000,5000-10000,10000+'],

            // Market Position
            'estimated_users' => ['nullable', 'string', 'in:< 10K,10K-100K,100K-1M,1M-10M,10M-50M,50M-100M,100M+'],
            'target_market' => ['nullable', 'array'],
            'target_market.*' => ['string', 'in:individual,small_business,mid_market,enterprise,developer,creative_professional'],
            'market_position' => ['nullable', 'string', 'in:market_leader,major_player,challenger,niche_specialist,emerging'],
            'primary_competitors' => ['nullable', 'array'],
            'primary_competitors.*' => ['string'],

            // Momentum & Sentiment
            'momentum_notes' => ['nullable', 'string'],
            'customer_sentiment' => ['nullable', 'string', 'in:very_positive,positive,mixed,negative,very_negative'],
            'sentiment_notes' => ['nullable', 'string'],
            'last_major_update' => ['nullable', 'string', 'max:255'],

            // Financial
            'funding_stage' => ['nullable', 'string', 'in:bootstrapped,seed,series_a,series_b,series_c+,public,profitable,acquired'],
            'latest_funding_amount' => ['nullable', 'string', 'max:255'],
            'latest_funding_date' => ['nullable', 'string', 'max:255'],
            'estimated_annual_revenue' => ['nullable', 'string', 'in:< $1M,$1M-$10M,$10M-$50M,$50M-$100M,$100M-$500M,$500M-$1B,$1B+'],

            // Cost Analysis (holistic assessment: raw cost + implementation + value + flexibility)
            'pricing_individual_cost' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pricing_smb_cost' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pricing_midmarket_cost' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pricing_enterprise_cost' => ['nullable', 'integer', 'min:1', 'max:5'],
            'pricing_cost_notes' => ['nullable', 'string'],
            'pricing_individual_range' => ['nullable', 'string', 'max:255'],
            'pricing_smb_range' => ['nullable', 'string', 'max:255'],
            'pricing_midmarket_range' => ['nullable', 'string', 'max:255'],
            'pricing_enterprise_range' => ['nullable', 'string', 'max:255'],

            // Competitive Intelligence
            'key_differentiators' => ['nullable', 'array'],
            'key_differentiators.*' => ['string'],
            'strengths' => ['nullable', 'array'],
            'strengths.*' => ['string'],
            'weaknesses' => ['nullable', 'array'],
            'weaknesses.*' => ['string'],
            'market_threats' => ['nullable', 'string'],
            'growth_opportunities' => ['nullable', 'string'],

            // Analyst Notes
            'strategic_notes' => ['nullable', 'string'],
            'analyst_summary' => ['nullable', 'string'],

            // Metadata
            'last_researched_at' => ['nullable', 'date'],
        ]);
    }

    /**
     * Format intelligence data for API response
     */
    private function formatIntelligenceResponse(ToolIntelligence $intelligence): array
    {
        return [
            'id' => $intelligence->id,
            'tool_id' => $intelligence->tool_id,

            // Company Metadata
            'founded_year' => $intelligence->founded_year,
            'tool_launched_year' => $intelligence->tool_launched_year,
            'company_status' => $intelligence->company_status,
            'stock_ticker' => $intelligence->stock_ticker,
            'parent_company' => $intelligence->parent_company,
            'acquisition_date' => $intelligence->acquisition_date,
            'headquarters' => $intelligence->headquarters,
            'employee_count_range' => $intelligence->employee_count_range,

            // Market Position
            'estimated_users' => $intelligence->estimated_users,
            'target_market' => $intelligence->target_market,
            'market_position' => $intelligence->market_position,
            'primary_competitors' => $intelligence->primary_competitors,

            // Momentum & Sentiment
            'momentum_notes' => $intelligence->momentum_notes,
            'customer_sentiment' => $intelligence->customer_sentiment,
            'sentiment_notes' => $intelligence->sentiment_notes,
            'last_major_update' => $intelligence->last_major_update,

            // Financial
            'funding_stage' => $intelligence->funding_stage,
            'latest_funding_amount' => $intelligence->latest_funding_amount,
            'latest_funding_date' => $intelligence->latest_funding_date,
            'estimated_annual_revenue' => $intelligence->estimated_annual_revenue,

            // Cost Analysis (holistic assessment combining cost, implementation, value, flexibility)
            'pricing_individual_cost' => $intelligence->pricing_individual_cost,
            'pricing_smb_cost' => $intelligence->pricing_smb_cost,
            'pricing_midmarket_cost' => $intelligence->pricing_midmarket_cost,
            'pricing_enterprise_cost' => $intelligence->pricing_enterprise_cost,
            'pricing_cost_notes' => $intelligence->pricing_cost_notes,
            'pricing_individual_range' => $intelligence->pricing_individual_range,
            'pricing_smb_range' => $intelligence->pricing_smb_range,
            'pricing_midmarket_range' => $intelligence->pricing_midmarket_range,
            'pricing_enterprise_range' => $intelligence->pricing_enterprise_range,

            // Competitive Intelligence
            'key_differentiators' => $intelligence->key_differentiators,
            'strengths' => $intelligence->strengths,
            'weaknesses' => $intelligence->weaknesses,
            'market_threats' => $intelligence->market_threats,
            'growth_opportunities' => $intelligence->growth_opportunities,

            // Analyst Notes
            'strategic_notes' => $intelligence->strategic_notes,
            'analyst_summary' => $intelligence->analyst_summary,

            // Metadata
            'data_completeness_score' => $intelligence->data_completeness_score,
            'last_researched_at' => $intelligence->last_researched_at,
            'created_at' => $intelligence->created_at,
            'updated_at' => $intelligence->updated_at,
        ];
    }

    /**
     * Return a standardized error response
     */
    private function errorResponse(string $message, \Exception $e): JsonResponse
    {
        \Log::error('API Error: '.$message, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
        ], 500);
    }
}
