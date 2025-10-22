# Tool List Maintenance System

**Document Type:** Technical Architecture Plan
**Version:** 1.0
**Last Updated:** October 22, 2025
**Status:** Planning Phase
**Owner:** Development Team

---

## Overview

This document outlines the architecture and implementation plan for the AI Manifesto Tool List Maintenance System. The system addresses database synchronization between local and production environments while establishing the foundation for future features including community submissions and conversational tool management via MCP (Model Context Protocol).

## Vision

Create a comprehensive content management system that:
1. Solves immediate local ↔ production database synchronization
2. Enables community-driven tool submissions
3. Supports conversational tool management through Claude/ChatGPT
4. Demonstrates architectural best practices
5. Scales from single-maintainer to community-driven platform

## Design Principles

- **API-First Architecture:** HTTP endpoints serve multiple clients (web, MCP, ChatGPT)
- **Content as Code:** Snapshots in git provide audit trail and version history
- **Idempotent Operations:** Import commands can be safely run repeatedly
- **Separation of Concerns:** MCP server is thin wrapper over core API
- **Schema Versioning:** Exports include metadata for future migration support
- **Validation Layers:** API validates payloads before database writes
- **Security by Default:** All endpoints authenticated and authorized

---

## Three-Phase Implementation Plan

### Phase 1: Export/Import Foundation (Immediate Priority)

**Goal:** Enable seamless database synchronization between environments.

#### Commands

```bash
# Export current database state to timestamped snapshot
php artisan content:export

# Import from latest snapshot (upsert mode)
php artisan content:import

# Import specific snapshot
php artisan content:import database/content/snapshots/snapshot-2025-10-22.json
```

#### Directory Structure

```
database/
  content/
    snapshots/
      snapshot-2025-10-22.json    # Timestamped, immutable snapshots
      snapshot-2025-10-29.json
      snapshot-2025-11-05.json
    latest.json                   # Symlink to most recent snapshot
    .gitkeep
```

#### Export Format

```json
{
  "metadata": {
    "version": "1.0",
    "exported_at": "2025-10-22T14:30:00Z",
    "schema_version": "1.0",
    "exported_by": "php artisan content:export",
    "record_counts": {
      "categories": 12,
      "tools": 87
    }
  },
  "categories": [
    {
      "name": "Code Assistants",
      "slug": "code-assistants",
      "description": "AI-powered coding tools",
      "icon": "💻",
      "sort_order": 1,
      "is_active": true
    }
  ],
  "tools": [
    {
      "name": "GitHub Copilot",
      "slug": "github-copilot",
      "category_slug": "code-assistants",
      "description": "AI pair programmer",
      "website": "https://github.com/features/copilot",
      "pricing_model": "paid",
      "ryan_rating": 9,
      "features": ["Code completion", "Chat interface"],
      "use_cases": ["Coding", "Documentation"],
      "integrations": ["VS Code", "JetBrains"],
      "is_featured": true,
      "is_active": true
    }
  ]
}
```

#### Implementation Details

**Export Command (`app/Console/Commands/ContentExport.php`):**
- Queries all active categories and tools
- Serializes with relationships intact
- Includes metadata for validation and versioning
- Generates timestamped filename
- Saves to `database/content/snapshots/`
- Updates `latest.json` symlink
- Outputs summary statistics

**Import Command (`app/Console/Commands/ContentImport.php`):**
- Reads snapshot file (latest or specified)
- Validates schema version compatibility
- Uses **upsert strategy** keyed by slug:
  1. Import categories first (required for foreign keys)
  2. Import tools with category relationships
  3. Preserves IDs when possible, creates new if needed
- Wraps in database transaction (all-or-nothing)
- Reports statistics: added, updated, skipped, errors
- Idempotent: safe to run multiple times

#### Git Integration

**Snapshot Commit Strategy:**
- Snapshots committed to git manually after review
- Provides audit trail of content changes
- Enables rollback to previous states
- Shows what changed and when via git diff
- Optional: Automated weekly snapshot commits via CI/CD

**Benefits:**
- Content history visible in version control
- Can bisect to find when tool was added
- Team members can see content evolution
- Disaster recovery: restore from any snapshot

---

### Phase 2: API Endpoint for Tool Creation (Next Priority)

**Goal:** Create RESTful API for programmatic tool submission, laying groundwork for MCP and community features.

#### Endpoint Specification

```http
POST /api/tools/submit
Content-Type: application/json
Authorization: Bearer {api_token}

{
  "name": "Cursor",
  "slug": "cursor",
  "category": "code-assistants",
  "description": "AI-powered code editor with chat interface",
  "website": "https://cursor.sh",
  "pricing_model": "freemium",
  "features": [
    "Auto-completion",
    "Chat interface",
    "Codebase understanding"
  ],
  "use_cases": [
    "Coding",
    "Refactoring",
    "Learning"
  ],
  "integrations": [
    "VS Code fork",
    "OpenAI API"
  ],
  "ryan_rating": null,
  "is_featured": false
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Tool submitted successfully",
  "data": {
    "id": 123,
    "slug": "cursor",
    "status": "pending_review",
    "submitted_at": "2025-10-22T15:45:00Z"
  }
}
```

#### Implementation Components

**Route (`routes/api.php`):**
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tools/submit', [ToolSubmissionController::class, 'store']);
    Route::get('/tools', [ToolController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
});
```

**Controller (`app/Http/Controllers/Api/ToolSubmissionController.php`):**
- Validates incoming payload against schema
- Checks for duplicate tools (by slug or website)
- Creates tool record with `status: pending_review`
- Sends notification to maintainer
- Returns standardized JSON response
- Logs submission for audit

**Request Validation:**
```php
// app/Http/Requests/ToolSubmissionRequest.php
'name' => 'required|string|max:255|unique:tools,name',
'slug' => 'required|string|max:255|unique:tools,slug',
'category' => 'required|exists:categories,slug',
'description' => 'required|string|max:1000',
'website' => 'required|url|max:500',
'pricing_model' => 'required|in:free,freemium,paid,enterprise',
'features' => 'array|max:10',
'features.*' => 'string|max:100',
'use_cases' => 'array|max:10',
'use_cases.*' => 'string|max:100'
```

#### Authentication Strategy

**Laravel Sanctum Token-Based:**
- Personal access tokens for maintainers
- Rate-limited to prevent abuse
- Revocable if compromised
- Scoped permissions (submit vs. approve)

**Future: OAuth for Community:**
- GitHub OAuth for developer submissions
- Track submission history per user
- Reputation system for trusted contributors

#### Moderation Workflow

**Auto-Approval Rules (Future):**
- Submissions from maintainers → auto-approved
- Submissions from trusted contributors (high reputation) → auto-approved
- First-time submissions → pending review

**Review Interface (Future):**
- Dashboard showing pending tools
- Side-by-side diff for edits
- Approve/Reject/Request Changes actions
- Notification to submitter on decision

---

### Phase 3: MCP Server Wrapper (Future Enhancement)

**Goal:** Enable conversational tool management through Claude Desktop and other MCP-compatible clients.

#### Architecture

```
┌─────────────────┐
│  Claude Desktop │
│   (MCP Client)  │
└────────┬────────┘
         │ MCP Protocol
         ▼
┌─────────────────┐
│   MCP Server    │
│  (Node.js/TS)   │
└────────┬────────┘
         │ HTTP/REST
         ▼
┌─────────────────┐
│ Laravel API     │
│ aimanifesto.dev │
└─────────────────┘
```

#### MCP Server Implementation

**Location:** `/mcp-server` directory in repository

**Technology Stack:**
- Node.js + TypeScript
- `@modelcontextprotocol/sdk` package
- Axios for HTTP requests
- Environment-based configuration

**Exposed Tools:**

```typescript
// mcp-server/src/index.ts
import { Server } from '@modelcontextprotocol/sdk/server/index.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';

const server = new Server({
  name: 'aimanifesto-tools',
  version: '1.0.0',
}, {
  capabilities: {
    tools: {},
  },
});

// Tool: Add new AI tool
server.setRequestHandler('tools/call', async (request) => {
  if (request.params.name === 'add_ai_tool') {
    const response = await fetch('https://aimanifesto.dev/api/tools/submit', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${process.env.API_TOKEN}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(request.params.arguments),
    });

    return {
      content: [
        {
          type: 'text',
          text: await response.json(),
        },
      ],
    };
  }
});

// Tool: Search existing tools
server.setRequestHandler('tools/call', async (request) => {
  if (request.params.name === 'search_ai_tools') {
    const { query } = request.params.arguments;
    const response = await fetch(
      `https://aimanifesto.dev/api/tools?search=${encodeURIComponent(query)}`
    );

    return {
      content: [
        {
          type: 'text',
          text: JSON.stringify(await response.json(), null, 2),
        },
      ],
    };
  }
});
```

**Tool Definitions:**
```typescript
server.setRequestHandler('tools/list', async () => {
  return {
    tools: [
      {
        name: 'add_ai_tool',
        description: 'Add a new AI tool to the AI Manifesto directory',
        inputSchema: {
          type: 'object',
          properties: {
            name: { type: 'string', description: 'Tool name' },
            category: { type: 'string', description: 'Category slug' },
            description: { type: 'string', description: 'Tool description' },
            website: { type: 'string', format: 'uri' },
            pricing_model: {
              type: 'string',
              enum: ['free', 'freemium', 'paid', 'enterprise']
            },
            features: {
              type: 'array',
              items: { type: 'string' }
            }
          },
          required: ['name', 'category', 'description', 'website', 'pricing_model']
        }
      },
      {
        name: 'search_ai_tools',
        description: 'Search the AI Manifesto tool directory',
        inputSchema: {
          type: 'object',
          properties: {
            query: { type: 'string', description: 'Search query' }
          },
          required: ['query']
        }
      }
    ]
  };
});
```

#### User Experience

**Conversational Workflow:**

```
User: "Add Cursor as a code assistant tool. It's an AI-powered code editor
       at cursor.sh with a freemium pricing model."

Claude: [Uses add_ai_tool MCP function]
        "I've submitted Cursor to the AI Manifesto directory.
         The tool is pending review and will appear once approved."

User: "What other code assistants do you have listed?"

Claude: [Uses search_ai_tools MCP function with query="code assistants"]
        "Here are the code assistant tools currently in the directory:
         1. GitHub Copilot (rated 9/10)
         2. Tabnine (rated 7/10)
         3. Amazon CodeWhisperer (rated 6/10)
         ..."
```

#### Installation for Users

```bash
# Clone MCP server
git clone https://github.com/rgriss/aimanifesto
cd aimanifesto/mcp-server

# Install dependencies
npm install

# Configure
cp .env.example .env
# Add API token to .env

# Build
npm run build

# Add to Claude Desktop config
# ~/.config/claude/config.json (Linux/Mac)
# %APPDATA%\Claude\config.json (Windows)
{
  "mcpServers": {
    "aimanifesto": {
      "command": "node",
      "args": ["/path/to/aimanifesto/mcp-server/dist/index.js"]
    }
  }
}
```

---

## Best Practices Demonstrated

### 1. Content as Code
- Database content versioned in git
- Changes trackable via commits and diffs
- Rollback capability through git history
- Collaborative content management

### 2. Upsert Pattern
- Idempotent import operations
- Safe to run repeatedly without duplication
- Handles both new records and updates
- Reduces operational risk

### 3. API-First Design
- RESTful HTTP endpoints
- Multiple client support (web, MCP, mobile)
- Standardized request/response formats
- Decoupled frontend and backend

### 4. Separation of Concerns
- MCP server is thin client wrapper
- Business logic lives in Laravel API
- Single source of truth for validation
- Easy to maintain and extend

### 5. Schema Versioning
- Export metadata includes schema version
- Import validates compatibility
- Migration path for schema changes
- Forward and backward compatibility

### 6. Validation Layers
- Request validation at API boundary
- Database constraints as last defense
- Type safety in TypeScript MCP server
- Clear error messages for debugging

### 7. Security by Design
- Authentication required for all mutations
- Rate limiting to prevent abuse
- Input sanitization and validation
- CORS configuration for web clients
- Token-based authorization

### 8. Observability
- Logging at each layer (import, API, MCP)
- Audit trail for all submissions
- Statistics and metrics collection
- Error tracking and alerting

---

## Implementation Timeline

### Phase 1: Export/Import (Week 1)
- [ ] Create `ContentExport` artisan command
- [ ] Create `ContentImport` artisan command
- [ ] Add JSON schema validation
- [ ] Test round-trip export → import
- [ ] Document usage in README
- [ ] Create initial snapshot and commit

### Phase 2: API Endpoint (Week 2-3)
- [ ] Create API routes and controllers
- [ ] Implement request validation
- [ ] Add Sanctum authentication
- [ ] Build moderation workflow
- [ ] Write API documentation (OpenAPI spec)
- [ ] Add rate limiting middleware
- [ ] Create Postman collection for testing

### Phase 3: MCP Server (Week 4-6)
- [ ] Initialize Node.js project in `/mcp-server`
- [ ] Implement MCP protocol handlers
- [ ] Create tool definitions for Claude
- [ ] Add configuration management
- [ ] Write installation documentation
- [ ] Publish to npm (optional)
- [ ] Create demo video

---

## Technical Considerations

### Data Integrity
- Foreign key constraints in database
- Transaction wrapping for imports
- Validation at multiple layers
- Duplicate detection logic

### Performance
- Batch inserts for large imports
- Database indexing on slug fields
- API response caching
- Rate limiting to prevent overload

### Scalability
- Queued processing for large imports
- Async job handling for submissions
- CDN for export file distribution
- Database read replicas for API

### Monitoring
- Import success/failure metrics
- API endpoint response times
- MCP server uptime tracking
- Error rate alerting

---

## Future Enhancements

### Community Features
- User accounts and profiles
- Tool submission history
- Reputation system for contributors
- Voting and favorites
- Comments and reviews

### Advanced Search
- Full-text search across tools
- Faceted filtering (category, pricing, rating)
- "Similar tools" recommendations
- Personalized suggestions

### Analytics
- Tool popularity tracking
- Category usage statistics
- User journey analytics
- A/B testing framework

### Integrations
- ChatGPT Custom Actions
- Slack bot for team notifications
- Discord bot for community
- Zapier webhooks
- GitHub Actions for CI/CD

### Content Management
- Web-based admin panel
- Bulk edit operations
- Import from external sources (Product Hunt, etc.)
- Scheduled content audits
- Automated tool status checking (is site still live?)

---

## Success Metrics

### Phase 1
- Export/import success rate > 99.9%
- Round-trip data integrity (no loss)
- Import time < 5 seconds for 100 tools
- Documentation clarity (peer review)

### Phase 2
- API uptime > 99.5%
- Average response time < 200ms
- Successful submission rate > 95%
- API documentation completeness

### Phase 3
- MCP server installation success rate > 90%
- Tool call success rate > 99%
- User satisfaction (survey)
- Adoption by Claude Desktop users

---

## Resources and References

### Documentation
- [Laravel Artisan Console](https://laravel.com/docs/artisan)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Model Context Protocol Specification](https://spec.modelcontextprotocol.io/)
- [MCP TypeScript SDK](https://github.com/modelcontextprotocol/typescript-sdk)

### Similar Implementations
- WordPress XML export/import
- Statamic flat-file CMS
- Ghost content API
- Prismic content management

### Community
- AI Manifesto GitHub Issues
- Laravel Discord server
- MCP community discussions

---

## Questions and Decisions

### Open Questions
1. Should snapshots be committed automatically or manually after review?
2. Should the API auto-approve tools or flag all for moderation initially?
3. What authentication strategy for community submissions (OAuth, email, etc.)?
4. Should we support incremental exports (changes since last export)?

### Decisions Made
- ✅ Use JSON for export format (human-readable, git-friendly)
- ✅ Slug-based upsert strategy (stable identifier)
- ✅ Laravel Sanctum for API authentication
- ✅ Three-phase implementation approach
- ✅ MCP server as separate package

### Future Decisions Needed
- Snapshot retention policy (keep all forever vs. prune old ones)
- API versioning strategy (URL-based vs. header-based)
- Community moderation policies and guidelines
- Tool quality standards and acceptance criteria

---

## Appendix

### Example Export Command Output

```
$ php artisan content:export

Exporting content...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

 ✓ Exported 12 categories
 ✓ Exported 87 tools
 ✓ Saved to database/content/snapshots/snapshot-2025-10-22.json
 ✓ Updated database/content/latest.json

Export completed in 1.23 seconds
```

### Example Import Command Output

```
$ php artisan content:import

Importing content from database/content/latest.json...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Validating snapshot...
 ✓ Schema version: 1.0 (compatible)
 ✓ Exported at: 2025-10-22 14:30:00

Processing categories...
 ✓ Created: 2
 ✓ Updated: 10
 ✓ Skipped: 0

Processing tools...
 ✓ Created: 15
 ✓ Updated: 72
 ✓ Skipped: 0

Import completed in 3.45 seconds
```

---

**Document Status:** ✅ Ready for Implementation
**Next Steps:** Begin Phase 1 implementation with export/import commands
