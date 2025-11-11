# Tool Intelligence API Documentation

## Overview

The AI Manifesto Tool Intelligence API allows authorized AI assistants (like Claude) to add business intelligence, market research, and competitive analysis data to tool entries in the directory.

**Status:** ✅ Implemented and Production Ready

---

## What is Tool Intelligence?

Tool Intelligence is a comprehensive business intelligence layer for AI tools that includes:

- **Company Metadata**: Founding year, headquarters, employee count, ownership status
- **Market Position**: User base size, target markets, competitive positioning
- **Momentum & Sentiment**: Growth trajectory, customer feedback, recent updates
- **Financial Data**: Funding stage, revenue estimates, latest funding rounds
- **Cost Analysis**: Holistic cost assessment (1-5 dollar signs) combining raw cost, implementation, value, and flexibility
- **Competitive Intelligence**: Differentiators, SWOT analysis, market opportunities
- **Analyst Notes**: Strategic analysis and expert summaries

This data helps CTOs, product managers, and decision-makers quickly assess tools for their organization.

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

### `PUT /api/tool-intelligence`

Update or create intelligence data for a tool.

**URL:** `https://aimanifesto.net/api/tool-intelligence`
**Method:** `PUT`
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
  "tool_slug": "claude",
  "founded_year": 2021,
  "headquarters": "San Francisco, CA"
}
```

### Complete Request Body (All Fields)
```json
{
  "tool_slug": "claude",

  "founded_year": 2021,
  "tool_launched_year": 2023,
  "company_status": "private",
  "stock_ticker": null,
  "parent_company": null,
  "acquisition_date": null,
  "headquarters": "San Francisco, CA",
  "employee_count_range": "201-500",

  "estimated_users": "1M-10M",
  "target_market": ["individual", "developer", "enterprise"],
  "market_position": "major_player",
  "primary_competitors": ["ChatGPT", "Gemini", "Copilot"],

  "momentum_notes": "Strong growth in developer and enterprise segments",
  "customer_sentiment": "very_positive",
  "sentiment_notes": "Users praise context window and code generation",
  "last_major_update": "October 2024 - Computer Use feature",

  "funding_stage": "series_c+",
  "latest_funding_amount": "$450M",
  "latest_funding_date": "2024-03-15",
  "estimated_annual_revenue": "$100M-$500M",

  "pricing_individual_cost": 2,
  "pricing_smb_cost": 3,
  "pricing_midmarket_cost": 4,
  "pricing_enterprise_cost": 5,
  "pricing_cost_notes": "Pro at $20/mo (great value). API usage-based with unpredictable spikes. Enterprise contracts $100K+ with complex implementation.",
  "pricing_individual_range": "$20/month",
  "pricing_smb_range": "$500-10,000/month",
  "pricing_midmarket_range": "$15K-75K/month",
  "pricing_enterprise_range": "$100K-5M+/year",

  "key_differentiators": [
    "200K context window",
    "Constitutional AI safety approach",
    "Computer Use capabilities"
  ],
  "strengths": [
    "Best-in-class context handling",
    "Strong reasoning capabilities",
    "Enterprise-grade security"
  ],
  "weaknesses": [
    "Higher API costs than competitors",
    "Smaller model selection than OpenAI"
  ],
  "market_threats": "OpenAI dominance, Google's infrastructure advantage",
  "growth_opportunities": "Enterprise adoption, developer tools integration",

  "strategic_notes": "Anthropic positioned as safety-first alternative to OpenAI",
  "analyst_summary": "Strong product-market fit in enterprise and developer segments",

  "last_researched_at": "2025-11-11T15:30:00Z"
}
```

---

## Schema Files

**Machine-readable schemas available:**
- **TypeScript:** [tool-intelligence.d.ts](./tool-intelligence.d.ts) - Complete type definitions

---

## Field Reference

### Identification
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `tool_slug` | string | Yes* | Tool slug identifier |
| `tool_id` | integer | Yes* | Tool ID (alternative to slug) |

*One of `tool_slug` or `tool_id` is required

### Company Metadata
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `founded_year` | integer | null, 1900-current year | Year company was founded |
| `tool_launched_year` | integer | null, 1900-current year | Year specific tool launched |
| `company_status` | enum | null, see options below | Company ownership/status type |
| `stock_ticker` | string | null, max:10 | Stock ticker (for public companies) |
| `parent_company` | string | null, max:255 | Parent company name |
| `acquisition_date` | string | null, ISO 8601 date | Date of acquisition |
| `headquarters` | string | null, max:255 | Headquarters location |
| `employee_count_range` | enum | null, see options below | Employee count range |

**Company Status Options:**
- `private` - Privately held company
- `public` - Publicly traded company
- `acquired` - Acquired by another company
- `subsidiary` - Subsidiary of larger company
- `open_source` - Open source project/foundation

**Employee Count Range Options:**
- `1-10`, `11-50`, `51-200`, `201-500`, `501-1000`, `1000-5000`, `5000-10000`, `10000+`

### Market Position
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `estimated_users` | enum | null, see options below | Estimated user base size |
| `target_market` | array | null, array of enums | Target market segments |
| `market_position` | enum | null, see options below | Market position classification |
| `primary_competitors` | array | null, array of strings | Primary competitor names |

**Estimated Users Options:**
- `< 10K`, `10K-100K`, `100K-1M`, `1M-10M`, `10M-50M`, `50M-100M`, `100M+`

**Target Market Options:**
- `individual`, `small_business`, `mid_market`, `enterprise`, `developer`, `creative_professional`

**Market Position Options:**
- `market_leader`, `major_player`, `challenger`, `niche_specialist`, `emerging`

### Momentum & Sentiment
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `momentum_notes` | text | null | Explanation of momentum/growth |
| `customer_sentiment` | enum | null, see options below | Customer sentiment assessment |
| `sentiment_notes` | text | null | Notes about customer sentiment |
| `last_major_update` | string | null, max:500 | Date/description of last major update |

**Customer Sentiment Options:**
- `very_positive`, `positive`, `mixed`, `negative`, `very_negative`

### Financial Data
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `funding_stage` | enum | null, see options below | Current funding stage |
| `latest_funding_amount` | string | null, max:100 | Latest funding amount (e.g., "$50M") |
| `latest_funding_date` | string | null, ISO 8601 date | Date of latest funding |
| `estimated_annual_revenue` | enum | null, see options below | Estimated annual revenue range |

**Funding Stage Options:**
- `bootstrapped`, `seed`, `series_a`, `series_b`, `series_c+`, `public`, `profitable`, `acquired`

**Estimated Annual Revenue Options:**
- `< $1M`, `$1M-$10M`, `$10M-$50M`, `$50M-$100M`, `$100M-$500M`, `$500M-$1B`, `$1B+`

### Cost Analysis

**Holistic cost assessment combining raw cost, implementation effort, value delivered, flexibility, and predictability.**

This is NOT just the subscription price - it's a comprehensive rating (like story points in agile) that considers:
- Raw monthly/annual cost
- Implementation and onboarding costs
- Value delivered relative to price
- Flexibility (monthly vs forced annual contracts)
- Predictability (fixed pricing vs usage-based that can spike)

**Restaurant-style dollar sign ratings (1-5 scale) for four organizational tiers:**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `pricing_individual_cost` | integer | null, 1-5 | Individual cost analysis (1-5 $) |
| `pricing_smb_cost` | integer | null, 1-5 | SMB (10-50 users) cost analysis (1-5 $) |
| `pricing_midmarket_cost` | integer | null, 1-5 | Mid-Market (50-500 users) cost analysis (1-5 $) |
| `pricing_enterprise_cost` | integer | null, 1-5 | Enterprise (500+ users) cost analysis (1-5 $) |
| `pricing_cost_notes` | text | null | Notes about pricing, implementation costs, value, flexibility |
| `pricing_individual_range` | string | null, max:255 | Typical spend for individuals |
| `pricing_smb_range` | string | null, max:255 | Typical spend for SMB (10-50 users) |
| `pricing_midmarket_range` | string | null, max:255 | Typical spend for Mid-Market (50-500 users) |
| `pricing_enterprise_range` | string | null, max:255 | Typical spend for Enterprise (500+ users) |

**Cost Analysis Scale:**

**Individual Users:**
- `1` ($): $0-20/mo - Free or low cost, high value, simple pricing
- `2` ($$): $20-50/mo - Standard pricing, good value proposition
- `3` ($$$): $50-100/mo - Premium pricing or more complex
- `4` ($$$$): $100-250/mo - High cost or low flexibility/value
- `5` ($$$$$): $250+/mo - Very expensive or very inflexible/low value

**SMB (10-50 users):**
- `1` ($): <$1K/mo - Budget-friendly, high value, straightforward
- `2` ($$): $1K-5K/mo - Standard team pricing, good flexibility
- `3` ($$$): $5K-15K/mo - Mid-tier solutions, moderate complexity
- `4` ($$$$): $15K-40K/mo - Premium or complex implementation
- `5` ($$$$$): $40K+/mo - Very expensive or poor value proposition

**Mid-Market (50-500 users):**
- `1` ($): <$5K/mo - Exceptional value for mid-market scale
- `2` ($$): $5K-20K/mo - Good value proposition, flexible
- `3` ($$$): $20K-50K/mo - Standard mid-market pricing
- `4` ($$$$): $50K-100K/mo - Premium or inflexible terms
- `5` ($$$$$): $100K+/mo - Very expensive or complex/inflexible

**Enterprise (500+ users):**
- `1` ($): <$50K/yr - Entry-level enterprise, good value
- `2` ($$): $50K-150K/yr - Standard enterprise pricing
- `3` ($$$): $150K-500K/yr - Mid-tier enterprise solutions
- `4` ($$$$): $500K-1M/yr - Premium enterprise pricing
- `5` ($$$$$): $1M+/yr - Strategic investment level pricing

### Competitive Intelligence
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `key_differentiators` | array | null, array of strings | Key differentiating features |
| `strengths` | array | null, array of strings | Tool strengths |
| `weaknesses` | array | null, array of strings | Tool weaknesses |
| `market_threats` | text | null | Market threats description |
| `growth_opportunities` | text | null | Growth opportunities description |

### Analyst Notes
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `strategic_notes` | text | null | Strategic analysis notes |
| `analyst_summary` | text | null | Executive summary |
| `last_researched_at` | string | null, ISO 8601 datetime | When data was last researched |

---

## Response Format

### Success Response (200 OK)
```json
{
  "success": true,
  "message": "Tool intelligence updated successfully",
  "data": {
    "id": 1,
    "tool_id": 5,
    "founded_year": 2021,
    "tool_launched_year": 2023,
    "company_status": "private",
    "headquarters": "San Francisco, CA",
    "employee_count_range": "201-500",
    "estimated_users": "1M-10M",
    "target_market": ["individual", "developer", "enterprise"],
    "market_position": "major_player",
    "pricing_individual_cost": 2,
    "pricing_smb_cost": 3,
    "pricing_midmarket_cost": 4,
    "pricing_enterprise_cost": 5,
    "pricing_individual_range": "$20/month",
    "pricing_smb_range": "$500-10,000/month",
    "pricing_midmarket_range": "$15K-75K/month",
    "pricing_enterprise_range": "$100K-5M+/year",
    "data_completeness_score": 75,
    "created_at": "2025-11-11T12:00:00.000000Z",
    "updated_at": "2025-11-11T15:30:00.000000Z"
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

**404 Not Found** (Tool doesn't exist)
```json
{
  "success": false,
  "message": "Tool not found"
}
```

**422 Validation Error**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "founded_year": ["The founded year must not be greater than 2025."],
    "pricing_individual_cost": ["The pricing individual cost must be between 1 and 5."]
  }
}
```

---

## Testing with cURL

### Basic Example
```bash
curl -X PUT https://aimanifesto.net/api/tool-intelligence \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "tool_slug": "claude",
    "founded_year": 2021,
    "headquarters": "San Francisco, CA",
    "pricing_individual_cost": 2
  }'
```

### Complete Example
```bash
curl -X PUT https://aimanifesto.net/api/tool-intelligence \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "tool_slug": "claude",
    "founded_year": 2021,
    "company_status": "private",
    "headquarters": "San Francisco, CA",
    "employee_count_range": "201-500",
    "estimated_users": "1M-10M",
    "target_market": ["individual", "developer", "enterprise"],
    "market_position": "major_player",
    "customer_sentiment": "very_positive",
    "funding_stage": "series_c+",
    "pricing_individual_cost": 2,
    "pricing_smb_cost": 3,
    "pricing_enterprise_cost": 5,
    "pricing_individual_range": "$20/month",
    "pricing_smb_range": "$500-10,000/month",
    "pricing_enterprise_range": "$100K-5M+/year",
    "key_differentiators": ["200K context window", "Constitutional AI"],
    "strengths": ["Best context handling", "Strong reasoning"],
    "analyst_summary": "Leading enterprise AI assistant"
  }'
```

---

## Claude MCP Integration

The MCP server includes the `update_tool_intelligence` tool. Example usage:

### Example Claude Interaction
```
User: "Add intelligence data for Claude:
       - Founded in 2021 by Anthropic
       - Headquarters in San Francisco
       - 200-500 employees
       - 1-10 million users
       - Pricing: $20/month individual, $500-10K/month for teams"

Claude: "I've updated the intelligence data for Claude!

        ✓ Company: Anthropic (2021)
        ✓ Location: San Francisco, CA
        ✓ Employees: 201-500
        ✓ Users: 1M-10M
        ✓ Pricing Complexity:
          - Individual: $$ ($20/month)
          - SMB: $$$ ($500-10K/month)

        View it here: https://aimanifesto.net/tools/claude"
```

---

## Features

### ✅ Flexible Tool Identification
Identify tools by either slug or ID for maximum flexibility.

### ✅ Partial Updates
Update only the fields you want - all fields are optional except tool identifier.

### ✅ Auto-Creation
Intelligence records are automatically created if they don't exist for a tool.

### ✅ Data Completeness Score
Automatically calculated score (0-100) based on how many fields are populated.

### ✅ Comprehensive Validation
All enum fields, date ranges, and numeric ranges validated with clear error messages.

### ✅ Array Field Support
Target markets, competitors, differentiators, strengths, and weaknesses all support arrays.

---

## Pricing Complexity Assessment Guide

When assessing pricing complexity, consider:

1. **Base Cost**: What's the starting price point?
2. **Complexity**: How many tiers, add-ons, or variables?
3. **Typical Spend**: What do real customers actually pay?
4. **Hidden Costs**: API usage, overages, seat licenses?

### Examples

**Claude:**
- Individual: `2` ($$) - Simple $20/month Pro plan
- SMB: `3` ($$$) - API usage can range $500-10K depending on volume
- Enterprise: `5` ($$$$$) - Large contracts can exceed $1M annually

**Figma:**
- Individual: `1` ($) - Free tier robust, Pro is $15/month
- SMB: `2` ($$) - $45/user/month for organizations
- Enterprise: `3` ($$$) - Custom pricing but typically $200-400K for 500+ users

**Salesforce:**
- Individual: `3` ($$$) - $75-150/user/month minimum
- SMB: `4` ($$$$) - $20-30K/month for full team deployment
- Enterprise: `5` ($$$$$) - Multi-million dollar implementations common

---

## Best Practices

1. **Research Thoroughly**: Use official pricing pages, case studies, and industry reports
2. **Be Conservative**: When uncertain, round down pricing complexity
3. **Update Regularly**: Pricing and market positions change - track `last_researched_at`
4. **Add Context**: Use notes fields to explain complexity and nuances
5. **Cross-Reference**: Check competitor pricing to ensure relative accuracy

---

## Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Intelligence data updated successfully |
| 401 | Unauthorized | Invalid or missing API token |
| 404 | Not Found | Tool not found |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Server Error | Unexpected error occurred |

---

## Troubleshooting

### "Tool not found"
- Verify the tool slug or ID is correct
- Check that the tool exists in the database
- Try using tool ID instead of slug (or vice versa)

### "Validation failed"
- Check the `errors` object in response for specific fields
- Ensure enum values match allowed options exactly
- Verify date formats are ISO 8601
- Check numeric ranges (1-5 for pricing, 1900-current for years)

### "Rate limit exceeded"
- You've made more than 30 requests in the last minute
- Wait 60 seconds and try again

---

## Support

For issues or questions:
- Check the [TypeScript definitions](./tool-intelligence.d.ts) for field details
- Review pricing complexity examples in this document
- Create an issue on GitHub
- Email: support@aimanifesto.com

---

**Last Updated:** November 11, 2025
**API Version:** 1.0
**Status:** Production Ready
