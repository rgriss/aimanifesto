# Tool Creation API Documentation

## Overview

The AI Manifesto Tool Creation API allows authorized AI assistants (like Claude) to create new tool entries in the directory via RESTful API calls.

**Status:** ✅ Implemented and Tested (15/15 tests passing)

---

## Authentication

All requests require a Bearer token in the Authorization header.

### Setup

1. **Generate a secure token:**
```bash
php artisan tinker
>>> Str::random(64)
"your-64-character-token-here"
```

2. **Add to `.env` file:**
```bash
API_TOKEN=your-64-character-token-here
```

3. **Use in requests:**
```bash
Authorization: Bearer your-64-character-token-here
```

---

## Endpoint

### `POST /api/tools`

Create a new tool in the directory.

**URL:** `https://aimanifesto.com/api/tools`
**Method:** `POST`
**Auth:** Required (Bearer token)
**Rate Limit:** 30 requests per minute

---

## Request Format

### Headers
```
Content-Type: application/json
Authorization: Bearer {your-token}
```

### Minimal Request Body
```json
{
  "name": "Claude",
  "description": "AI assistant by Anthropic with 200K context window",
  "website_url": "https://claude.ai",
  "category": "AI Assistants"
}
```

### Complete Request Body (All Fields)
```json
{
  "name": "Claude",
  "description": "AI assistant by Anthropic with 200K context window",
  "long_description": "Claude is an AI assistant created by Anthropic...",
  "website_url": "https://claude.ai",
  "documentation_url": "https://docs.anthropic.com",
  "logo_url": "https://claude.ai/logo.png",
  "category": "AI Assistants",
  "pricing_model": "freemium",
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
  "ryan_rating": 8,
  "ryan_notes": "Best AI assistant for coding tasks",
  "is_featured": false,
  "is_active": true
}
```

---

## Schema Files

**Machine-readable schemas available:**
- **JSON Schema:** [tool-schema.json](./tool-schema.json) - Complete JSON Schema (draft-07)
- **TypeScript:** [tool.d.ts](./tool.d.ts) - TypeScript type definitions

## Field Reference

| Field | Type | Required | Validation | Description |
|-------|------|----------|------------|-------------|
| `name` | string | Yes | max:255, unique | Tool name |
| `description` | string | Yes | - | Brief description |
| `long_description` | text | No | - | Detailed description |
| `website_url` | string | Yes | valid URL, max:500 | Tool website |
| `documentation_url` | string | No | valid URL, max:500 | Documentation link |
| `logo_url` | string | No | valid URL, max:500 | Logo image URL |
| `reddit_url` | string | No | valid URL, max:500 | Reddit community or search link |
| `community_url` | string | No | valid URL, max:500 | Discord, Slack, or Forum link |
| `reviews_url` | string | No | valid URL, max:500 | G2, Capterra, ProductHunt, etc. |
| `category` | string | Yes | max:255 | Category name (auto-created if doesn't exist) |
| `pricing_model` | string | No | enum: free, freemium, paid, enterprise | Pricing type |
| `price_description` | string | No | - | Pricing details |
| `features` | array | No | array of strings | Key features |
| `use_cases` | array | No | array of strings | Use cases |
| `integrations` | array | No | array of strings | Available integrations |
| `ryan_rating` | integer | No | 1-10 | Personal rating |
| `ryan_notes` | string | No | - | Personal notes |
| `is_featured` | boolean | No | default: false | Featured status |
| `is_active` | boolean | No | default: true | Active status |

**Auto-generated fields:**
- `slug` - Generated from name
- `views_count` - Defaults to 0
- `first_reviewed_at` - Set to current date if `ryan_rating` provided
- `created_at`, `updated_at` - Automatic timestamps

---

## Response Format

### Success Response (201 Created)
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
    "pricing_model": "freemium",
    "is_active": true,
    "created_at": "2025-11-11T15:30:00.000000Z",
    "url": "https://aimanifesto.com/tools/claude"
  }
}
```

### Error Responses

**401 Unauthorized** (Invalid/missing token)
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing API token."
}
```

**409 Conflict** (Duplicate tool)
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

**422 Validation Error**
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

**429 Too Many Requests**
```json
{
  "success": false,
  "message": "Rate limit exceeded. Try again in 60 seconds."
}
```

---

## Testing with cURL

### Basic Example
```bash
curl -X POST https://aimanifesto.com/api/tools \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "name": "Test Tool",
    "description": "A test tool",
    "website_url": "https://test.com",
    "category": "Testing"
  }'
```

### Complete Example
```bash
curl -X POST https://aimanifesto.com/api/tools \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "name": "Claude",
    "description": "AI assistant by Anthropic",
    "long_description": "Claude is an advanced AI assistant...",
    "website_url": "https://claude.ai",
    "documentation_url": "https://docs.anthropic.com",
    "category": "AI Assistants",
    "pricing_model": "freemium",
    "price_description": "$20/month Pro",
    "features": ["200K context", "Artifacts", "Computer use"],
    "use_cases": ["Coding", "Research", "Writing"],
    "ryan_rating": 9,
    "ryan_notes": "Excellent for development"
  }'
```

---

## Claude MCP Integration

To use with Claude, add this to your MCP configuration:

```json
{
  "tools": {
    "add_ai_tool": {
      "name": "add_ai_tool",
      "description": "Add a new AI tool to the AI Manifesto directory",
      "endpoint": "https://aimanifesto.com/api/tools",
      "method": "POST",
      "headers": {
        "Authorization": "Bearer your-token-here",
        "Content-Type": "application/json"
      }
    }
  }
}
```

### Example Claude Interaction
```
User: "Add Cursor to my directory. It's an AI-powered code editor at cursor.sh,
       costs $20/month, and has features like AI code completion and chat."

Claude: "I've added Cursor to your AI tools directory!

        ✓ Name: Cursor
        ✓ Category: AI Assistants (created)
        ✓ Pricing: $20/month

        View it here: https://aimanifesto.com/tools/cursor"
```

---

## Features

### ✅ Auto-Slug Generation
Automatically generates URL-friendly slugs from tool names. Handles duplicates by appending `-2`, `-3`, etc.

### ✅ Category Auto-Creation
If a category doesn't exist, it's automatically created with a sensible default description.

### ✅ Case-Insensitive Category Matching
"AI Assistants", "ai assistants", and "AI ASSISTANTS" all match the same category.

### ✅ Duplicate Detection
Prevents creating tools with duplicate names and returns the existing tool's URL.

### ✅ Rate Limiting
Limited to 30 requests per minute to prevent abuse.

### ✅ Comprehensive Validation
All fields validated with clear error messages for debugging.

---

## Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 201 | Created | Tool successfully created |
| 401 | Unauthorized | Invalid or missing API token |
| 409 | Conflict | Tool with same name already exists |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Server Error | Unexpected error occurred |

---

## Security

### Token Security
- Token is 64 random characters
- Stored in `.env` (never committed to git)
- Transmitted over HTTPS only
- Can be rotated without code changes

### Input Validation
- All URLs validated
- Arrays must contain strings only
- Enum fields validated against allowed values
- SQL injection prevented via Eloquent ORM

### Rate Limiting
- 30 requests per minute per token
- Prevents accidental infinite loops
- Sufficient for human-driven AI interactions

---

## Troubleshooting

### "Unauthorized. Invalid or missing API token."
- Check that `API_TOKEN` is set in your `.env` file
- Verify the token in your request matches the one in `.env`
- Ensure you're using `Bearer` authorization scheme

### "A tool with this name already exists."
- Tool names must be unique
- Response includes URL to existing tool
- Modify the name slightly or update via admin panel

### "Validation failed"
- Check the `errors` object in response for specific fields
- Ensure all required fields are present
- Verify URLs are valid and arrays contain only strings

### "Rate limit exceeded"
- You've made more than 30 requests in the last minute
- Wait 60 seconds and try again
- For batch operations, add delays between requests

---

## Future Enhancements

Documented in [SPEC-001](../technical-specs/SPEC-001-ai-tool-creation-api.md):

1. **Tool Updates** - `PATCH /api/tools/{slug}`
2. **Tool Search** - `GET /api/tools?search=query`
3. **Bulk Import** - `POST /api/tools/batch`
4. **Logo Auto-fetch** - Automatic logo from favicon
5. **OAuth Support** - For public API access

---

## Testing

Comprehensive test suite with 15 passing tests covering:

- ✅ Basic tool creation
- ✅ Full field tool creation
- ✅ Authentication (valid/invalid tokens)
- ✅ Duplicate detection
- ✅ Category auto-creation
- ✅ Case-insensitive matching
- ✅ Field validation
- ✅ URL validation
- ✅ Enum validation
- ✅ Array validation
- ✅ Rate limiting
- ✅ Slug generation
- ✅ Response structure

Run tests with:
```bash
php artisan test --filter=ApiToolCreationTest
```

---

## Support

For issues or questions:
- Check [FEAT-001](../features/FEAT-001-ai-tool-creation-api.md) for feature details
- Check [SPEC-001](../technical-specs/SPEC-001-ai-tool-creation-api.md) for technical details
- Create an issue on GitHub
- Email: support@aimanifesto.com

---

**Last Updated:** November 11, 2025
**API Version:** 1.0
**Status:** Production Ready
