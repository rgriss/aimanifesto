/**
 * TypeScript type definitions for AI Tool Intelligence API
 *
 * These types match the tool_intelligence table schema for business intelligence
 * and market research data.
 *
 * @version 1.0.0
 * @see https://aimanifesto.net/docs/api/tool-intelligence-api
 */

/**
 * Company status options
 */
export type CompanyStatus =
    | 'private'
    | 'public'
    | 'acquired'
    | 'subsidiary'
    | 'open_source';

/**
 * Employee count range options
 */
export type EmployeeCountRange =
    | '1-10'
    | '11-50'
    | '51-200'
    | '201-500'
    | '501-1000'
    | '1000-5000'
    | '5000-10000'
    | '10000+';

/**
 * Estimated user count options
 */
export type EstimatedUsers =
    | '< 10K'
    | '10K-100K'
    | '100K-1M'
    | '1M-10M'
    | '10M-50M'
    | '50M-100M'
    | '100M+';

/**
 * Target market segment options
 */
export type TargetMarketSegment =
    | 'individual'
    | 'small_business'
    | 'mid_market'
    | 'enterprise'
    | 'developer'
    | 'creative_professional';

/**
 * Market position options
 */
export type MarketPosition =
    | 'market_leader'
    | 'major_player'
    | 'challenger'
    | 'niche_specialist'
    | 'emerging';

/**
 * Customer sentiment options
 */
export type CustomerSentiment =
    | 'very_positive'
    | 'positive'
    | 'mixed'
    | 'negative'
    | 'very_negative';

/**
 * Funding stage options
 */
export type FundingStage =
    | 'bootstrapped'
    | 'seed'
    | 'series_a'
    | 'series_b'
    | 'series_c+'
    | 'public'
    | 'profitable'
    | 'acquired';

/**
 * Estimated annual revenue options
 */
export type EstimatedAnnualRevenue =
    | '< $1M'
    | '$1M-$10M'
    | '$10M-$50M'
    | '$50M-$100M'
    | '$100M-$500M'
    | '$500M-$1B'
    | '$1B+';

/**
 * Request payload for updating tool intelligence
 */
export interface UpdateToolIntelligenceRequest {
    /** Tool slug or ID (required to identify the tool) */
    tool_slug?: string;
    tool_id?: number;

    // Company Metadata
    /** Year company was founded */
    founded_year?: number | null;
    /** Year the specific tool was launched */
    tool_launched_year?: number | null;
    /** Company ownership/status type */
    company_status?: CompanyStatus | null;
    /** Stock ticker symbol (for public companies) */
    stock_ticker?: string | null;
    /** Parent company name (for subsidiaries) */
    parent_company?: string | null;
    /** Date of acquisition (for acquired companies) */
    acquisition_date?: string | null;
    /** Headquarters location */
    headquarters?: string | null;
    /** Company employee count range */
    employee_count_range?: EmployeeCountRange | null;

    // Market Position
    /** Estimated user base size */
    estimated_users?: EstimatedUsers | null;
    /** Array of target market segments */
    target_market?: TargetMarketSegment[] | null;
    /** Market position classification */
    market_position?: MarketPosition | null;
    /** Array of primary competitor names */
    primary_competitors?: string[] | null;

    // Momentum & Sentiment
    /** Explanation of momentum score */
    momentum_notes?: string | null;
    /** Customer sentiment assessment */
    customer_sentiment?: CustomerSentiment | null;
    /** Notes about customer sentiment */
    sentiment_notes?: string | null;
    /** Date/description of last major update */
    last_major_update?: string | null;

    // Financial
    /** Current funding stage */
    funding_stage?: FundingStage | null;
    /** Latest funding round amount (e.g., "$50M") */
    latest_funding_amount?: string | null;
    /** Date of latest funding */
    latest_funding_date?: string | null;
    /** Estimated annual revenue range */
    estimated_annual_revenue?: EstimatedAnnualRevenue | null;

    // Pricing Complexity (1-5 scale, like restaurant dollar signs)
    /** Pricing complexity for individuals (1-5) */
    pricing_individual_cost?: number | null;
    /** Pricing complexity for SMB 10-50 users (1-5) */
    pricing_smb_cost?: number | null;
    /** Pricing complexity for Enterprise 500+ users (1-5) */
    pricing_enterprise_cost?: number | null;
    /** Notes about pricing structure and complexity */
    pricing_cost_notes?: string | null;
    /** Typical spend range for individuals */
    pricing_individual_range?: string | null;
    /** Typical spend range for SMB */
    pricing_smb_range?: string | null;
    /** Typical spend range for Enterprise */
    pricing_enterprise_range?: string | null;

    // Competitive Intelligence
    /** Array of key differentiators */
    key_differentiators?: string[] | null;
    /** Array of strengths */
    strengths?: string[] | null;
    /** Array of weaknesses */
    weaknesses?: string[] | null;
    /** Market threats description */
    market_threats?: string | null;
    /** Growth opportunities description */
    growth_opportunities?: string | null;

    // Analyst Notes
    /** Strategic analysis notes */
    strategic_notes?: string | null;
    /** Analyst summary */
    analyst_summary?: string | null;

    /** When this data was last researched (ISO 8601) */
    last_researched_at?: string | null;
}

/**
 * Tool Intelligence data response
 */
export interface ToolIntelligence {
    id: number;
    tool_id: number;

    // Company Metadata
    founded_year: number | null;
    tool_launched_year: number | null;
    company_status: CompanyStatus | null;
    stock_ticker: string | null;
    parent_company: string | null;
    acquisition_date: string | null;
    headquarters: string | null;
    employee_count_range: EmployeeCountRange | null;

    // Market Position
    estimated_users: EstimatedUsers | null;
    target_market: TargetMarketSegment[] | null;
    market_position: MarketPosition | null;
    primary_competitors: string[] | null;

    // Momentum & Sentiment
    momentum_notes: string | null;
    customer_sentiment: CustomerSentiment | null;
    sentiment_notes: string | null;
    last_major_update: string | null;

    // Financial
    funding_stage: FundingStage | null;
    latest_funding_amount: string | null;
    latest_funding_date: string | null;
    estimated_annual_revenue: EstimatedAnnualRevenue | null;

    // Pricing Complexity
    pricing_individual_cost: number | null;
    pricing_smb_cost: number | null;
    pricing_enterprise_cost: number | null;
    pricing_cost_notes: string | null;
    pricing_individual_range: string | null;
    pricing_smb_range: string | null;
    pricing_enterprise_range: string | null;

    // Competitive Intelligence
    key_differentiators: string[] | null;
    strengths: string[] | null;
    weaknesses: string[] | null;
    market_threats: string | null;
    growth_opportunities: string | null;

    // Analyst Notes
    strategic_notes: string | null;
    analyst_summary: string | null;

    // Metadata
    data_completeness_score: number;
    last_researched_at: string | null;
    created_at: string;
    updated_at: string;
}

/**
 * Successful API response
 */
export interface ToolIntelligenceSuccess {
    success: true;
    message: string;
    data: ToolIntelligence;
}

/**
 * Validation error response
 */
export interface ToolIntelligenceValidationError {
    success: false;
    message: string;
    errors: { [field: string]: string[] };
}

/**
 * Union type of all possible API responses
 */
export type ToolIntelligenceResponse =
    | ToolIntelligenceSuccess
    | ToolIntelligenceValidationError;

/**
 * Example: Updating tool intelligence
 *
 * ```typescript
 * const intelligence: UpdateToolIntelligenceRequest = {
 *   tool_slug: "claude",
 *   founded_year: 2021,
 *   company_status: "private",
 *   headquarters: "San Francisco, CA",
 *   funding_stage: "series_c+",
 *   estimated_users: "1M-10M",
 *   target_market: ["individual", "developer", "enterprise"],
 *   market_position: "major_player",
 *   customer_sentiment: "very_positive",
 *   key_differentiators: ["200K context window", "Constitutional AI"],
 * };
 *
 * const response = await fetch('https://aimanifesto.net/api/tool-intelligence', {
 *   method: 'PUT',
 *   headers: {
 *     'Authorization': 'Bearer YOUR_TOKEN',
 *     'Content-Type': 'application/json',
 *   },
 *   body: JSON.stringify(intelligence),
 * });
 *
 * const result: ToolIntelligenceResponse = await response.json();
 * ```
 */
