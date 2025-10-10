# Technical Specification: AI Tool Creation API

## Metadata
- **Spec ID:** SPEC-001
- **Feature ID:** [FEAT-001](../features/FEAT-001-ai-tool-creation-api.md)
- **Author:** Ryan Grissinger (Technical Lead)
- **Created:** 2025-10-10
- **Status:** Draft
- **Reviewers:** [Pending]

---

## Overview

### Summary
We're building a RESTful API endpoint that allows authenticated AI assistants (primarily Claude via MCP) to create Tool records in our database. The system will accept natural language-derived data, validate it, and create properly formatted database entries without requiring manual admin interface interaction.

### Goals
- Enable tool creation in <500ms via API call
- Provide clear, actionable error messages for invalid data
- Maintain data integrity with existing Tool model validation
- Create foundation for future AI-powered features
- Zero impact on existing admin interface functionality

### Non-Goals
- Public API access (only authorized AI assistants)
- Tool updates/deletes via API (create only for v1)
- AI-generated content (AI structures user-provided content)
- Automatic tool verification/scraping
- Real-time webhook notifications

---

## Architecture

### System Context
```
┌─────────────┐
│   Claude    │
│     AI      │
└──────┬──────┘
       │ HTTP POST (JSON)
       │ Token Auth
       ▼
┌─────────────────────────────────────┐
│     Laravel Application             │
│  ┌───────────────────────────────┐  │
│  │  API Route: POST /api/tools   │  │
│  └──────────┬────────────────────┘  │
│             ▼                        │
│  ┌───────────────────────────────┐  │
│  │  Middleware: API Token Auth   │  │
│  └──────────┬────────────────────┘  │
│             ▼                        │
│  ┌───────────────────────────────┐  │
│  │  Controller: ApiToolController│  │
│  │  - Validate Request           │  │
│  │  - Generate Slug              │  │
│  │  - Create Tool Model          │  │
│  └──────────┬────────────────────┘  │
│             ▼                        │
│  ┌───────────────────────────────┐  │
│  │  Tool Model (Eloquent)        │  │
│  └──────────┬────────────────────┘  │
│             ▼                        │
│  ┌───────────────────────────────┐  │
│  │  MySQL Database               │  │
│  │  - tools table                │  │
│  │  - categories table           │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

### Component Diagram
```
API Layer          Business Logic       Data Layer
─────────          ──────────────       ──────────

routes/api.php  →  ApiToolController  →  Tool Model
                   - validate()          - fillable
middleware/        - generateSlug()      - casts
ApiTokenAuth       - findOrCreateCat()   - relationships
                   - createTool()
```

### Data Flow
1. Claude receives user instruction to add tool
2. Claude constructs JSON payload with tool data
3. Claude sends POST to `/api/tools` with Bearer token
4. Middleware validates token
5. Controller validates request data
6. Controller generates slug from name
7. Controller finds or suggests category
8. Tool model created in database
9. Success response with tool data returned to Claude
10. Claude confirms to user with tool URL

---

## API Design

### Base URL
```
Production: https://aimanifesto.com/api
Development: http://localhost/api
```

### Authentication
All requests require a Bearer token in the Authorization header:
```
Authorization: Bearer {API_TOKEN}
```

Token stored in `.env` as `API_TOKEN`.

---

### Endpoints

#### `POST /api/tools`
**Purpose:** Create a new tool in the directory

**Authentication:** Required (Bearer token)

**Rate Limiting:** 30 requests/minute per token

**Request Headers:**
```
Content-Type: application/json
Authorization: Bearer {API_TOKEN}
```

**Request Body:**
```json
{
  "name": "Claude",
  "description": "AI assistant by Anthropic with 200K context window",
  "long_description": "Claude is an AI assistant created by Anthropic...",
  "website_url": "https://claude.ai",
  "documentation_url": "https://docs.anthropic.com",
  "logo_url": "https://claude.ai/logo.png",
  "category": "AI Assistants",
  "pricing_model": "subscription",
  "price_description": "$20/month for Pro plan, free tier available",
  "features": [
    "200K context window",
    "Artifacts for code generation",
    "Computer use capabilities"
  ],
  "use_cases": [
    "Code generation and review",
    "Research and analysis",
    "Content creation"
  ],
  "integrations": [
    "API",
    "Web interface",
    "Mobile apps"
  ],
  "ryan_rating": 5,
  "ryan_notes": "Best AI assistant for coding tasks",
  "is_featured": false,
  "is_active": true
}
```

**Field Requirements:**

| Field | Required | Type | Validation |
|-------|----------|------|------------|
| name | Yes | string | max:255, unique |
| description | Yes | string | max:500 |
| long_description | No | text | - |
| website_url | Yes | url | valid URL |
| documentation_url | No | url | valid URL or null |
| logo_url | No | url | valid URL or null |
| category | Yes | string | existing category name or creates new |
| pricing_model | No | string | enum: free, freemium, subscription, one-time, enterprise |
| price_description | No | string | max:500 |
| features | No | array | array of strings |
| use_cases | No | array | array of strings |
| integrations | No | array | array of strings |
| ryan_rating | No | integer | 1-5 |
| ryan_notes | No | text | - |
| is_featured | No | boolean | default: false |
| is_active | No | boolean | default: true |

**Automatic Fields:**
- `slug` - Auto-generated from name
- `views_count` - Defaults to 0
- `first_reviewed_at` - Set to current date if ryan_rating provided
- `created_at`, `updated_at` - Automatic timestamps

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Tool created successfully",
  "data": {
    "id": 42,
    "name": "Claude",
    "slug": "claude",
    "description": "AI assistant by Anthropic...",
    "category": {
      "id": 5,
      "name": "AI Assistants",
      "slug": "ai-assistants"
    },
    "website_url": "https://claude.ai",
    "is_active": true,
    "created_at": "2025-10-10T15:30:00.000000Z",
    "url": "https://aimanifesto.com/tools/claude"
  }
}
```

**Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required."],
    "website_url": ["The website url must be a valid URL."]
  }
}
```

**Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing API token."
}
```

**Response (409 Conflict):**
```json
{
  "success": false,
  "message": "A tool with this name already exists.",
  "existing_tool": {
    "id": 15,
    "name": "Claude",
    "slug": "claude",
    "url": "https://aimanifesto.com/tools/claude"
  }
}
```

**Response (429 Too Many Requests):**
```json
{
  "success": false,
  "message": "Rate limit exceeded. Try again in 60 seconds."
}
```

**Response (500 Server Error):**
```json
{
  "success": false,
  "message": "An error occurred while creating the tool.",
  "error": "Database connection failed"
}
```

**Status Codes:**
- `201` - Tool created successfully
- `400` - Validation error
- `401` - Unauthorized (invalid/missing token)
- `409` - Conflict (duplicate tool)
- `429` - Rate limit exceeded
- `500` - Server error

---

## Database Design

### No Schema Changes Required
Existing `tools` table structure already supports all fields. No migrations needed.

### Category Handling
If category doesn't exist:
1. Try to find similar category (fuzzy match)
2. If found, suggest in error message
3. If not found, create new category with slug

**Category Creation Logic:**
```php
// If category name provided doesn't match existing
$category = Category::firstOrCreate(
    ['slug' => Str::slug($categoryName)],
    ['name' => $categoryName]
);
```

---

## Implementation Details

### File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── ApiToolController.php      [NEW]
│   └── Middleware/
│       └── ApiTokenAuthentication.php     [NEW]
└── Http/
    └── Requests/
        └── StoreToolRequest.php            [NEW]

routes/
└── api.php                                 [NEW]

config/
└── api.php                                 [NEW - if needed]
```

### Route Definition
**File:** `routes/api.php`
```php
<?php

use App\Http\Controllers\Api\ApiToolController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->group(function () {
    Route::post('/tools', [ApiToolController::class, 'store']);
});
```

### Middleware Implementation
**File:** `app/Http/Middleware/ApiTokenAuthentication.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token || $token !== config('services.api.token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API token.'
            ], 401);
        }
        
        return $next($request);
    }
}
```

### Controller Implementation
**File:** `app/Http/Controllers/Api/ApiToolController.php`
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolRequest;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Str;

class ApiToolController extends Controller
{
    public function store(StoreToolRequest $request)
    {
        try {
            // Check for duplicate by name
            $existing = Tool::where('name', $request->name)->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'A tool with this name already exists.',
                    'existing_tool' => [
                        'id' => $existing->id,
                        'name' => $existing->name,
                        'slug' => $existing->slug,
                        'url' => route('tools.show', $existing->slug)
                    ]
                ], 409);
            }
            
            // Find or create category
            $category = $this->findOrCreateCategory($request->category);
            
            // Generate slug
            $slug = $this->generateUniqueSlug($request->name);
            
            // Create tool
            $tool = Tool::create([
                'category_id' => $category->id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'long_description' => $request->long_description,
                'website_url' => $request->website_url,
                'documentation_url' => $request->documentation_url,
                'logo_url' => $request->logo_url,
                'pricing_model' => $request->pricing_model,
                'price_description' => $request->price_description,
                'features' => $request->features ?? [],
                'use_cases' => $request->use_cases ?? [],
                'integrations' => $request->integrations ?? [],
                'ryan_rating' => $request->ryan_rating,
                'ryan_notes' => $request->ryan_notes,
                'is_featured' => $request->is_featured ?? false,
                'is_active' => $request->is_active ?? true,
                'first_reviewed_at' => $request->ryan_rating ? now() : null,
            ]);
            
            $tool->load('category');
            
            return response()->json([
                'success' => true,
                'message' => 'Tool created successfully',
                'data' => [
                    'id' => $tool->id,
                    'name' => $tool->name,
                    'slug' => $tool->slug,
                    'description' => $tool->description,
                    'category' => [
                        'id' => $tool->category->id,
                        'name' => $tool->category->name,
                        'slug' => $tool->category->slug,
                    ],
                    'website_url' => $tool->website_url,
                    'is_active' => $tool->is_active,
                    'created_at' => $tool->created_at,
                    'url' => route('tools.show', $tool->slug)
                ]
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('API Tool Creation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the tool.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    
    private function findOrCreateCategory(string $categoryName): Category
    {
        // Try exact match first
        $category = Category::where('name', $categoryName)->first();
        
        if ($category) {
            return $category;
        }
        
        // Create new category
        return Category::create([
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
            'description' => "Tools in the {$categoryName} category",
            'is_active' => true,
        ]);
    }
    
    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Tool::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
```

### Form Request Validation
**File:** `app/Http/Requests/StoreToolRequest.php`
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth handled by middleware
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'long_description' => 'nullable|string',
            'website_url' => 'required|url',
            'documentation_url' => 'nullable|url',
            'logo_url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'pricing_model' => 'nullable|string|in:free,freemium,subscription,one-time,enterprise',
            'price_description' => 'nullable|string|max:500',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'use_cases' => 'nullable|array',
            'use_cases.*' => 'string',
            'integrations' => 'nullable|array',
            'integrations.*' => 'string',
            'ryan_rating' => 'nullable|integer|min:1|max:5',
            'ryan_notes' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The tool name is required.',
            'name.max' => 'The tool name cannot exceed 255 characters.',
            'description.required' => 'A brief description is required.',
            'description.max' => 'The description cannot exceed 500 characters.',
            'website_url.required' => 'The website URL is required.',
            'website_url.url' => 'The website URL must be a valid URL.',
            'category.required' => 'A category is required.',
            'ryan_rating.min' => 'Rating must be between 1 and 5.',
            'ryan_rating.max' => 'Rating must be between 1 and 5.',
        ];
    }
}
```

---

## Business Logic

### Slug Generation Algorithm
1. Convert name to lowercase
2. Replace spaces and special chars with hyphens
3. Remove consecutive hyphens
4. Check if slug exists in database
5. If exists, append `-{counter}` and increment until unique
6. Return final slug

### Category Resolution
1. Search for exact category name match (case-insensitive)
2. If found, use existing category_id
3. If not found, create new category with:
   - `name` = provided name
   - `slug` = generated from name
   - `description` = auto-generated
   - `is_active` = true

### Duplicate Detection
Before creating tool:
1. Check if tool with same name exists
2. If exists, return 409 with existing tool info
3. Allow user to:
   - Update existing tool (future feature)
   - Cancel operation
   - Force create with modified name

### Edge Cases

**1. Tool with same name but different URL:**
- Current: Reject as duplicate
- Future: Allow with slug variation (e.g., "claude-2")

**2. Invalid category:**
- Auto-create new category
- Log for review

**3. Missing optional fields:**
- Store as NULL or empty array
- No error, graceful handling

**4. URL validation fails:**
- Return clear error message
- Suggest URL format

**5. Array fields with non-string values:**
- Validation rejects
- Clear error message

---

## Security Considerations

### Authentication Strategy
- **Bearer Token:** Simple, stateless, sufficient for single-user API
- Token stored in `.env` file: `API_TOKEN=your-secret-token-here`
- Token should be:
  - At least 32 characters
  - Randomly generated
  - Never committed to git
  - Rotatable without code changes

**Generating Token:**
```bash
php artisan tinker
>>> Str::random(64)
```

### Authorization
- No per-user permissions (single authorized user)
- All authenticated requests have full access
- Future: Consider scopes if API expands

### Input Sanitization
- Laravel's validation handles type coercion
- URL validation prevents javascript: and data: URIs
- HTML stripped from text fields in display (existing XSS protection)
- Array validation prevents nested injection

### Rate Limiting
- 30 requests per minute per token
- Prevents accidental infinite loops
- Sufficient for human-driven AI interactions
- Can be increased if needed

**Implementation:**
```php
Route::middleware(['api.token', 'throttle:30,1'])->group(function () {
    Route::post('/tools', [ApiToolController::class, 'store']);
});
```

### CORS (If needed)
- Not required if Claude makes server-side requests
- If adding web-based AI client later, configure CORS in `config/cors.php`

### HTTPS
- Enforce HTTPS in production
- Token transmitted in header (encrypted in transit)

### Logging
- Log all API requests (success and failure)
- Log token usage (but not the token itself)
- Include request IP, timestamp, result

---

## Testing Strategy

### Unit Tests
**File:** `tests/Unit/ApiToolControllerTest.php`

```php
- testGeneratesUniqueSlug()
- testFindsExistingCategory()
- testCreatesNewCategory()
- testDetectsDuplicateTools()
- testValidatesRequiredFields()
- testValidatesUrlFormats()
- testValidatesArrayFields()
```

### Integration Tests
**File:** `tests/Feature/ApiToolCreationTest.php`

```php
- testCreatesToolWithMinimalData()
- testCreatesToolWithAllFields()
- testRejectsUnauthorizedRequest()
- testRejectsInvalidToken()
- testRejectsDuplicateTool()
- testCreatesNewCategory()
- testUsesExistingCategory()
- testHandlesInvalidUrlGracefully()
- testRateLimitingWorks()
- testReturnsCorrectToolUrl()
```

### Manual Testing Checklist
- [ ] Create tool via Claude with minimal fields
- [ ] Create tool with all fields populated
- [ ] Attempt duplicate tool creation
- [ ] Test with invalid URLs
- [ ] Test with missing required fields
- [ ] Test with invalid token
- [ ] Test without token
- [ ] Test category auto-creation
- [ ] Verify tool appears in admin panel
- [ ] Verify tool appears on public site
- [ ] Test rate limiting (>30 requests/min)
- [ ] Verify slug uniqueness with similar names

### Test Data
**Minimal Valid Request:**
```json
{
  "name": "Test Tool",
  "description": "A test tool for API testing",
  "website_url": "https://test.com",
  "category": "Testing"
}
```

**Complete Valid Request:**
```json
{
  "name": "ChatGPT",
  "description": "AI chatbot by OpenAI",
  "long_description": "ChatGPT is a large language model...",
  "website_url": "https://chat.openai.com",
  "documentation_url": "https://platform.openai.com/docs",
  "logo_url": "https://openai.com/logo.png",
  "category": "AI Assistants",
  "pricing_model": "freemium",
  "price_description": "Free tier, $20/month Plus",
  "features": ["Conversational AI", "Code generation", "Image analysis"],
  "use_cases": ["Customer support", "Content creation", "Programming help"],
  "integrations": ["API", "Web", "Mobile apps"],
  "ryan_rating": 4,
  "ryan_notes": "Great for general use, sometimes hallucinates",
  "is_featured": true,
  "is_active": true
}
```

---

## Performance

### Expected Load
- **Peak:** 10 requests/hour
- **Average:** 2-3 requests/day
- **Bottleneck:** Database writes (negligible at this scale)

### Optimization Strategies
1. **Database Indexes:** Already exist on `slug`, `name`, `category_id`
2. **Caching:** Not needed at this scale
3. **Query Optimization:** Use `firstOrCreate` to minimize queries
4. **Lazy Loading:** Load category only when returning response

### Response Time Targets
- **Target:** <200ms (database write + response)
- **Acceptable:** <500ms
- **Monitor:** If consistently >500ms, investigate

### Monitoring
Add to application monitoring:
- API request count
- Success/failure rate
- Average response time
- Error types and frequency

---

## MCP Integration

### MCP Tool Configuration
**File:** `~/.config/Claude/mcp_config.json` (or equivalent)

```json
{
  "tools": {
    "add_ai_tool": {
      "name": "add_ai_tool",
      "description": "Add a new AI tool to the AI Manifesto directory",
      "endpoint": "https://aimanifesto.com/api/tools",
      "method": "POST",
      "headers": {
        "Authorization": "Bearer ${API_TOKEN}",
        "Content-Type": "application/json"
      },
      "parameters": {
        "name": {
          "type": "string",
          "required": true,
          "description": "Tool name"
        },
        "description": {
          "type": "string",
          "required": true,
          "description": "Brief description (max 500 chars)"
        },
        "website_url": {
          "type": "string",
          "required": true,
          "description": "Tool website URL"
        },
        "category": {
          "type": "string",
          "required": true,
          "description": "Tool category"
        }
      }
    }
  }
}
```

**Alternative: Custom MCP Server**

If Claude doesn't support direct HTTP tools, we'll need a lightweight MCP server:

```javascript
// mcp-aimanifesto-server.js
import axios from 'axios';

export const tools = [{
  name: 'add_ai_tool',
  description: 'Add a new AI tool to the AI Manifesto directory',
  inputSchema: {
    type: 'object',
    properties: {
      name: { type: 'string', description: 'Tool name' },
      description: { type: 'string', description: 'Brief description' },
      website_url: { type: 'string', description: 'Website URL' },
      category: { type: 'string', description: 'Category' },
      // ... other optional fields
    },
    required: ['name', 'description', 'website_url', 'category']
  }
}];

export async function callTool(name, args) {
  if (name === 'add_ai_tool') {
    try {
      const response = await axios.post(
        'https://aimanifesto.com/api/tools',
        args,
        {
          headers: {
            'Authorization': `Bearer ${process.env.API_TOKEN}`,
            'Content-Type': 'application/json'
          }
        }
      );
      return response.data;
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || error.message
      };
    }
  }
}
```

---

## Rollout Plan

### Phase 1: Development & Testing
**Timeline:** Day 1
1. Create API routes file
2. Implement middleware
3. Create controller and request validation
4. Write unit tests
5. Write integration tests
6. Manual testing with Postman/curl

### Phase 2: MCP Integration
**Timeline:** Day 1-2
1. Configure MCP tool (or create MCP server)
2. Test Claude → API flow
3. Refine based on actual usage
4. Document example interactions

### Phase 3: Production Deployment
**Timeline:** Day 2
1. Generate secure API token
2. Add to production `.env`
3. Deploy code to production
4. Test in production environment
5. Monitor for errors

### Feature Flags
Not needed for this feature - it's isolated and doesn't affect existing functionality.

### Rollback Plan
1. Remove `routes/api.php` include from bootstrap
2. Existing site continues unaffected
3. Or: Set `API_TOKEN` to null to disable

---

## Environment Variables

### Required in `.env`
```bash
# API Configuration
API_TOKEN=your-64-character-random-token-here

# Generate with: php artisan tinker → Str::random(64)
```

### Optional Configuration
```bash
# API Rate Limiting (requests per minute)
API_RATE_LIMIT=30

# API Throttle Window (minutes)
API_THROTTLE_WINDOW=1
```

---

## Dependencies

### External Services
None. Self-contained within Laravel application.

### Libraries/Packages
All dependencies already installed:
- `laravel/framework` - Core framework
- `illuminate/http` - Request/Response
- `illuminate/validation` - Input validation
- `illuminate/routing` - API routing

### Environment Requirements
- PHP 8.1+
- MySQL 5.7+
- Composer

---

## Documentation

### User Documentation
**Location:** `/docs/features/FEAT-001-ai-tool-creation-api.md`

Already created. No additional user docs needed (single user).

### API Documentation
**Create:** `/docs/api/tool-creation.md`

Should include:
- Quick start guide
- Authentication setup
- Request/response examples
- Error codes reference
- MCP configuration

### Code Comments
Focus on:
- Why we chose certain approaches
- Non-obvious business logic
- Gotchas and edge cases
- NOT what the code does (should be self-evident)

**Example:**
```php
// We use firstOrCreate instead of updateOrCreate because categories
// should never be modified via API - only created if missing.
// This prevents accidental category renaming.
$category = Category::firstOrCreate(/* ... */);
```

---

## Open Questions

### Resolved During Spec
1. **Q:** Should the API allow updating existing tools?
   **A:** No. Create-only for v1. Updates via admin panel.

2. **Q:** Validate URLs are accessible?
   **A:** No. Format validation only. Manual verification by user.

3. **Q:** Draft status for AI-created tools?
   **A:** No. Tools created as active. User can deactivate via admin if needed.

4. **Q:** Categories - auto-create or require valid ID?
   **A:** Auto-create with sanitized slug. Reduces friction.

5. **Q:** Log all API calls?
   **A:** Yes. Include timestamp, IP, request data, result for audit.

### Still Open
None currently. All decisions made during spec creation.

---

## Alternatives Considered

### Alternative 1: GraphQL API
**Approach:** Use GraphQL instead of REST
**Pros:**
- Flexible queries
- Single endpoint
- Better for complex relationships

**Cons:**
- Overkill for single mutation
- Added complexity
- Laravel REST is simpler

**Decision:** REST is simpler and sufficient for this use case.

### Alternative 2: Existing Admin Form Automation
**Approach:** Have AI control browser to fill admin form
**Pros:**
- No new code needed
- Uses existing validation

**Cons:**
- Brittle (breaks if UI changes)
- Slow (requires page loads)
- Complex error handling
- Requires browser automation

**Decision:** API is cleaner and more maintainable.

### Alternative 3: Email-to-Create
**Approach:** Email tool data, parser creates record
**Pros:**
- No authentication needed
- Simple for user

**Cons:**
- Delayed processing
- Email parsing complexity
- No immediate feedback
- Error handling difficult

**Decision:** Real-time API provides better UX.

### Alternative 4: Webhook from AI Provider
**Approach:** Claude/OpenAI webhook triggers creation
**Pros:**
- No MCP server needed
- Direct integration

**Cons:**
- Dependent on provider features
- Less control over flow
- Provider-specific implementation

**Decision:** Direct API call via MCP is more flexible and provider-agnostic.

---

## Implementation Checklist

### Code
- [ ] Create `routes/api.php`
- [ ] Create `ApiTokenAuthentication` middleware
- [ ] Register middleware in `app/Http/Kernel.php`
- [ ] Create `ApiToolController`
- [ ] Create `StoreToolRequest` form request
- [ ] Add API token to `.env.example`
- [ ] Generate production API token

### Testing
- [ ] Write unit tests for controller methods
- [ ] Write feature tests for API endpoint
- [ ] Test authentication middleware
- [ ] Test rate limiting
- [ ] Manual testing with Postman
- [ ] Manual testing with Claude MCP

### Documentation
- [ ] Create API documentation file
- [ ] Add example requests/responses
- [ ] Document MCP setup
- [ ] Update README with API info

### Deployment
- [ ] Code review
- [ ] Merge to main
- [ ] Deploy to production
- [ ] Add API token to production env
- [ ] Verify production functionality
- [ ] Update feature status to "Completed"

### Post-Deployment
- [ ] Monitor error logs first 24 hours
- [ ] Create first tool via API
- [ ] Document any issues encountered
- [ ] Update spec with learnings

---

## Success Criteria

Feature is considered successful when:
1. ✅ Tool can be created via API call from Claude
2. ✅ Response time <500ms consistently
3. ✅ All validation errors return helpful messages
4. ✅ Zero unauthorized access attempts succeed
5. ✅ Created tools appear correctly in admin panel
6. ✅ Created tools appear correctly on public site
7. ✅ Category auto-creation works reliably
8. ✅ Duplicate detection works correctly
9. ✅ User (Ryan) reports workflow feels natural
10. ✅ No bugs reported in first week of use

---

## Future Enhancements

*Not in scope for SPEC-001, but documented for consideration:*

1. **Tool Updates:** `PATCH /api/tools/{slug}` endpoint
2. **Tool Search:** `GET /api/tools?search=query` endpoint
3. **Bulk Import:** `POST /api/tools/batch` for multiple tools
4. **Webhook Notifications:** Notify on tool creation
5. **Logo Auto-fetch:** Grab logo from website favicon
6. **AI-Assisted Descriptions:** Enhance short descriptions
7. **OAuth for Third Parties:** Public API access
8. **Category Suggestions:** AI recommends best category
9. **Duplicate Detection:** Fuzzy matching on name/URL
10. **Async Processing:** Queue tool creation for heavy loads

---

## Notes

### Design Philosophy Applied
This spec follows "code to the interface, not the implementation":
- API contract clearly defined before implementation
- Database details abstracted behind model
- Business logic separated from routing
- Validation isolated in form request
- Extensible without breaking changes

### Key Decisions
1. **Simple Bearer Token:** Sufficient for single-user MVP
2. **Auto-Create Categories:** Reduces friction, can review later
3. **No URL Verification:** Trust but verify (manual check)
4. **Generous Rate Limit:** 30/min allows rapid tool addition sessions
5. **Detailed Errors:** Help debug issues during development

### Implementation Notes
- Keep controller thin - delegate to service classes if logic grows
- Use form request validation - keeps controller clean
- Log everything - debugging is easier
- Return useful URLs - makes Claude's responses better
- Test edge cases - they will happen in production

---

## Approval

**Technical Lead:** [Pending]
**Product Owner:** [Pending]
**Security Review:** [Pending]

**Approved for Implementation:** [Date]
