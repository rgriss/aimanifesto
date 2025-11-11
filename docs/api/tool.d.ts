/**
 * TypeScript type definitions for AI Tool API
 *
 * These types match the API request/response schemas for tool creation.
 * Generated from: docs/api/tool-schema.json
 *
 * @version 1.0.0
 * @see https://aimanifesto.net/docs/api/tool-creation-api
 */

/**
 * Pricing model options for AI tools
 */
export type PricingModel = 'free' | 'freemium' | 'paid' | 'enterprise';

/**
 * Popularity tier options for AI tools
 * Indicates market recognition level
 */
export type PopularityTier =
  | 'mainstream'  // Household name (ChatGPT, Photoshop)
  | 'well_known'  // Known in industry (Cursor, Midjourney)
  | 'growing'     // Gaining recognition (Bolt, Perplexity)
  | 'niche'       // Specialized audience (Remove.bg)
  | 'emerging';   // New/unknown

/**
 * Request payload for creating a new tool via API
 */
export interface CreateToolRequest {
  /** Name of the AI tool (required, max 255 chars) */
  name: string;

  /** Brief description of the tool (required, 1-2 sentences) */
  description: string;

  /** Detailed description with more context (optional) */
  long_description?: string | null;

  /** Main website URL for the tool (required, must be valid URL) */
  website_url: string;

  /** Documentation URL (optional, must be valid URL) */
  documentation_url?: string | null;

  /** Logo image URL (optional, must be valid URL) */
  logo_url?: string | null;

  /** Category name - will be created if doesn't exist (required) */
  category: string;

  /** Pricing model type (optional) */
  pricing_model?: PricingModel | null;

  /** Details about pricing (optional) */
  price_description?: string | null;

  /** Parent company/organization behind the tool (required for new tools, max 255 chars) */
  company_name?: string | null;

  /** Market recognition level (optional) */
  popularity_tier?: PopularityTier | null;

  /** Trajectory assessment 1-5 (1=Strongly declining, 2=Declining, 3=Stable, 4=Growing, 5=Rapidly growing) (optional) */
  momentum_score?: number | null;

  /** Array of key features (optional) */
  features?: string[] | null;

  /** Array of use cases (optional) */
  use_cases?: string[] | null;

  /** Array of available integrations (optional) */
  integrations?: string[] | null;

  /** Personal rating from 1-10 (optional) */
  ryan_rating?: number | null;

  /** Personal notes about the tool (optional) */
  ryan_notes?: string | null;

  /** Date last used in YYYY-MM-DD format (optional) */
  ryan_last_used?: string | null;

  /** Whether to feature this tool (optional, default: false) */
  is_featured?: boolean | null;

  /** Whether the tool is active (optional, default: true) */
  is_active?: boolean | null;
}

/**
 * Category information in API responses
 */
export interface ToolCategory {
  /** Category ID */
  id: number;

  /** Category name */
  name: string;

  /** URL-friendly slug */
  slug: string;
}

/**
 * Tool data returned in successful API responses
 */
export interface ToolResponse {
  /** Tool ID */
  id: number;

  /** Tool name */
  name: string;

  /** URL-friendly slug */
  slug: string;

  /** Brief description */
  description: string;

  /** Category information */
  category: ToolCategory;

  /** Website URL */
  website_url: string;

  /** Pricing model */
  pricing_model: PricingModel | null;

  /** Whether tool is active */
  is_active: boolean;

  /** Creation timestamp (ISO 8601) */
  created_at: string;

  /** Public URL to view the tool */
  url: string;
}

/**
 * Successful API response (201 Created)
 */
export interface CreateToolSuccess {
  success: true;
  message: string;
  data: ToolResponse;
}

/**
 * Validation error details
 */
export interface ValidationErrors {
  [field: string]: string[];
}

/**
 * Validation error response (422 Unprocessable Entity)
 */
export interface CreateToolValidationError {
  success: false;
  message: string;
  errors: ValidationErrors;
}

/**
 * Information about existing tool (duplicate detected)
 */
export interface ExistingTool {
  id: number;
  name: string;
  slug: string;
  url: string;
}

/**
 * Conflict error response (409 Conflict)
 */
export interface CreateToolConflict {
  success: false;
  message: string;
  existing_tool: ExistingTool;
}

/**
 * Unauthorized error response (401 Unauthorized)
 */
export interface CreateToolUnauthorized {
  success: false;
  message: string;
}

/**
 * Rate limit error response (429 Too Many Requests)
 */
export interface CreateToolRateLimited {
  success: false;
  message: string;
}

/**
 * Server error response (500 Internal Server Error)
 */
export interface CreateToolServerError {
  success: false;
  message: string;
  error?: string;
}

/**
 * Union type of all possible API responses
 */
export type CreateToolResponse =
  | CreateToolSuccess
  | CreateToolValidationError
  | CreateToolConflict
  | CreateToolUnauthorized
  | CreateToolRateLimited
  | CreateToolServerError;

/**
 * Example: Creating a tool with fetch
 *
 * ```typescript
 * const newTool: CreateToolRequest = {
 *   name: "Claude",
 *   description: "AI assistant by Anthropic",
 *   website_url: "https://claude.ai",
 *   category: "AI Assistants",
 *   pricing_model: "freemium",
 *   price_description: "$20/month for Pro",
 *   features: ["200K context", "Artifacts", "Computer use"],
 *   ryan_rating: 9,
 * };
 *
 * const response = await fetch('https://aimanifesto.net/api/tools', {
 *   method: 'POST',
 *   headers: {
 *     'Authorization': 'Bearer YOUR_TOKEN',
 *     'Content-Type': 'application/json',
 *   },
 *   body: JSON.stringify(newTool),
 * });
 *
 * const result: CreateToolResponse = await response.json();
 *
 * if (result.success) {
 *   console.log(`Created tool: ${result.data.url}`);
 * } else {
 *   console.error(`Error: ${result.message}`);
 * }
 * ```
 */

/**
 * Complete tool object (matches database model)
 */
export interface Tool {
  id: number;
  category_id: number;
  name: string;
  slug: string;
  description: string;
  long_description: string | null;
  website_url: string;
  documentation_url: string | null;
  logo_url: string | null;
  pricing_model: PricingModel | null;
  price_description: string | null;
  company_name: string | null;
  popularity_tier: PopularityTier | null;
  momentum_score: number | null;
  features: string[];
  use_cases: string[];
  integrations: string[];
  ryan_rating: number | null;
  ryan_notes: string | null;
  ryan_last_used: string | null;
  is_featured: boolean;
  is_active: boolean;
  views_count: number;
  first_reviewed_at: string | null;
  created_at: string;
  updated_at: string;
  category?: ToolCategory;
  intelligence?: import('./tool-intelligence').ToolIntelligence;
}
