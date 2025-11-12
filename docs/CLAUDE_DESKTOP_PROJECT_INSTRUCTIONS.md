# AI Manifesto Project Instructions for Claude Desktop

Copy and paste these instructions into your Claude Desktop **Project Instructions** when working on this project.

---

## Project Overview

In this project we maintain the **aimanifesto.net** website - a directory of AI tools with business intelligence, market research, and competitive analysis data.

## MCP Connectors

You have access to two MCP connectors for this project:

- **`aimanifesto-local`** - Local development environment (http://localhost:8000)
- **`aimanifesto-production`** - Production API (https://aimanifesto.net)

Use these connectors to:
- Create and manage AI tool entries
- Update business intelligence and market research data
- Query tool information
- Manage categories

## Documentation Resources

The MCP server provides the following documentation resources that you can read on-demand:

### API Documentation
- **Tool Intelligence API**: `resource:///docs/tool-intelligence-api`
  - Complete guide for updating cost analysis, company metadata, market position, financial data
  - Includes all field definitions, validation rules, and examples

- **Tool Creation API**: `resource:///docs/tool-creation-api`
  - Guide for creating and managing AI tool entries
  - CRUD operations, validation, and best practices

- **MCP Setup Guide**: `resource:///docs/mcp-setup-guide`
  - Setup and configuration instructions
  - Troubleshooting and best practices

### Schema Definitions
- **Tool Intelligence Schema**: `resource:///schemas/tool-intelligence`
  - TypeScript type definitions for the intelligence API
  - All field types, enums, and request/response interfaces

- **Tool Schema**: `resource:///schemas/tool`
  - TypeScript type definitions for the tool creation API
  - Complete type system for tool entries

## Key Concepts

### Cost Analysis (Not Just Pricing)
When working with tool intelligence data, remember that **Cost Analysis** is a holistic assessment combining:
- Raw monthly/annual cost
- Implementation and onboarding costs
- Value delivered relative to price
- Flexibility (monthly vs forced annual contracts)
- Predictability (fixed vs usage-based pricing that can spike)

Think of it like **story points in agile** - it's relative complexity, not just the subscription price.

### Organization Tiers
The system tracks cost analysis for four organization sizes:
- **Individual**: 1 user ($0-$250+/month)
- **SMB**: 10-50 users (<$1K-$40K+/month)
- **Mid-Market**: 50-500 users (<$5K-$100K+/month)
- **Enterprise**: 500+ users (<$50K/yr-$1M+/year)

### Dollar Sign Scale
Cost analysis uses restaurant-style dollar signs (1-5 scale):
- **$** (1): Low cost, high value, simple/flexible
- **$$** (2): Standard pricing, good value
- **$$$** (3): Mid-tier or moderate complexity
- **$$$$** (4): Premium pricing or low flexibility
- **$$$$$** (5): Very expensive or poor value proposition

## Tech Stack

- **Backend**: Laravel 12, Laravel Fortify (auth), Laravel Wayfinder (routes)
- **Frontend**: Vue 3 (Composition API), Inertia.js (SSR), TypeScript
- **Styling**: Tailwind CSS v4, Reka UI components
- **Database**: SQLite (local), MySQL (production)
- **Testing**: Pest (PHP)

## Development Workflow

### Starting the Application
```bash
composer dev        # PHP server + queue + Vite
composer dev:ssr    # Same + Inertia SSR server
```

### Running Tests
```bash
composer test       # All tests via Pest
php artisan test    # Direct test execution
```

### Code Quality
```bash
npm run lint        # ESLint auto-fix
npm run format      # Prettier format
./vendor/bin/pint   # PHP formatting
```

## Important Guidelines

1. **Always read relevant documentation resources** before making changes to API-related code
2. **Use the MCP connectors** to interact with the API rather than making direct HTTP requests
3. **Follow the existing patterns** in CLAUDE.md for authentication, migrations, and testing
4. **Update CHANGELOG.md** when making significant changes
5. **Run tests** before committing changes
6. **Use descriptive commit messages** following the established format

## Getting Help

- Check `CLAUDE.md` for comprehensive project documentation
- Read the MCP Setup Guide resource for integration issues
- Consult API documentation resources for schema and validation details
- Review CHANGELOG.md for recent changes and version history

---

**Current Version**: 0.17.0 (Cost Analysis + Mid-Market Tier)
