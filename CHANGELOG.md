# Changelog

All notable changes to AI Manifesto will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.18.4] - 2025-11-12

### Fixed
- **Featured Tools Card Heights**: Featured tools on home page now have equal heights within each row
  - Cards now stretch to match the tallest card in their row
  - Pricing badges align at the bottom of each card
  - Matches the consistent height behavior of guiding principles section
  - Improved visual consistency and professional appearance

### Technical
- Added `h-full flex` to Link wrapper for full height stretching
- Added `flex-1 flex flex-col` to Card component for flexbox layout
- Added `flex-grow` to description paragraph for expanding space
- Wrapped pricing badge in `mt-auto` container to push to bottom

## [0.18.3] - 2025-11-12

### Changed
- **Home Page Featured Tools**: Now displays all 6 featured tools instead of only 3
  - Removed slice limit that was restricting display to 3 tools
  - Grid layout automatically wraps to show 2 rows of 3 columns on desktop
  - Backend was already fetching 6 tools, frontend now displays all of them
  - Better showcase of curated tool recommendations

## [0.18.2] - 2025-11-12

### Added
- **Business Intelligence Indicator**: Tools with business intelligence data now show a "💼 CTO Insights" badge
  - Badge appears in upper-right corner of tool cards on the directory page
  - Helps users quickly identify which tools have comprehensive CTO-level insights
  - Includes cost analysis, company metadata, market position, and competitive intelligence
  - Blue info badge variant for professional appearance

### Technical
- Added `intelligence` relationship to tools index query
- Updated Tools/Index.vue with conditional badge display
- Badge uses existing `variant="info"` styling

## [0.18.1] - 2025-11-12

### Added
- **Admin Vote Moderation**: Administrators can now manually edit vote counts
  - New "Vote Counts (Admin Override)" section in tool edit form
  - Edit upvotes and downvotes separately
  - View calculated net score in real-time
  - Useful for moderating vote manipulation or adjusting counts
  - Available at `/admin/tools/{slug}/edit`

### Technical
- Added `upvotes` and `downvotes` fields to admin tool edit validation
- Updated TypeScript interfaces in Edit.vue and ToolForm.vue
- New Card section in ToolForm with number inputs (min: 0)
- Backend validation ensures non-negative integer values

## [0.18.0] - 2025-11-12

### Added
- **Public Voting Feature**: Users can now vote on tools without authentication
  - Thumbs up/down voting buttons on tool cards in directory (Tools/Index)
  - Thumbs up/down voting buttons on tool detail pages (Tools/Show)
  - Real-time vote counts with optimistic UI updates
  - Displays upvotes, downvotes, and net score
  - Public API endpoint: `POST /api/tools/{slug}/vote`
  - Rate limited to 10 votes per minute per IP
  - No authentication required - open voting system
  - VoteButtons component with three size variants (sm/md/lg)

### Technical
- Added `upvotes` and `downvotes` columns to tools table
- New VoteButtons.vue component with optimistic updates and error handling
- Vote endpoint uses atomic `increment()` for thread safety
- Votes are anonymous and not tracked to individual users
- Component prevents navigation when clicking vote buttons on tool cards

## [0.17.3] - 2025-11-11

### Fixed
- **Tools Index "Recent" Sort**: Now properly sorts by creation timestamp instead of review date
  - Changed from `first_reviewed_at` to `created_at` for recent sort
  - Fixes issue where tools added on same day appeared in random order
  - `first_reviewed_at` is often set to midnight (00:00:00), losing time precision
  - `created_at` has accurate timestamps for proper chronological sorting

## [0.17.2] - 2025-11-11

### Fixed
- **Frontend Cost Analysis Display**: Tool show page now properly displays mid-market pricing tier
  - Added mid-market column to cost analysis section
  - Updated grid layout from 3 columns to 4 columns (responsive)
  - Changed section title from "Pricing Complexity" to "Cost Analysis"
  - Mid-market data now visible when present in intelligence records

## [0.17.1] - 2025-11-11

### Added
- **MCP Resources Support**: MCP server now exposes documentation as readable resources
  - `resource:///docs/tool-intelligence-api` - Complete API documentation
  - `resource:///docs/tool-creation-api` - Tool creation guide
  - `resource:///docs/mcp-setup-guide` - Setup and troubleshooting guide
  - `resource:///schemas/tool-intelligence` - TypeScript schema definitions
  - `resource:///schemas/tool` - Tool creation TypeScript schemas
- **Claude Desktop Project Instructions**: Template file for setting up Claude Desktop projects
  - Located at `docs/CLAUDE_DESKTOP_PROJECT_INSTRUCTIONS.md`
  - Includes MCP connector usage, documentation resources, and key concepts
  - Ready to copy-paste into Claude Desktop Project settings

### Changed
- MCP server version bumped to 1.1.0
- MCP server now declares `resources` capability

### Technical
- Added `ListResourcesRequestSchema` and `ReadResourceRequestSchema` handlers
- Resources read directly from filesystem using `readFileSync`
- Supports both markdown and TypeScript file types

## [0.17.0] - 2025-11-11

### Added
- **Mid-Market Pricing Tier**: New tier for organizations with 50-500 users
  - Added `pricing_midmarket_cost` field (1-5 dollar signs)
  - Added `pricing_midmarket_range` field for typical spend ranges
  - Database migration to add new fields to `tool_intelligence` table

### Changed
- **"Pricing Complexity" renamed to "Cost Analysis"** throughout the system
  - Updated API documentation to reflect holistic cost assessment concept
  - Cost Analysis now represents: raw cost + implementation + value + flexibility + predictability
  - NOT just subscription price - it's a comprehensive rating like story points in agile
- **Updated Cost Analysis Scale Definitions**:
  - Individual: Emphasizes value, flexibility, and complexity
  - SMB (10-50 users): Highlights value proposition and implementation complexity
  - Mid-Market (50-500 users): New tier with $5K-$100K+/month ranges
  - Enterprise (500+ users): Strategic investment level assessment
- Updated all documentation:
  - `docs/api/tool-intelligence-api.md` - Complete rewrite of cost analysis section
  - `docs/api/tool-intelligence.d.ts` - TypeScript definitions with mid-market fields
  - MCP server tool descriptions with holistic cost analysis explanations
- Updated MCP server response formatting to display "Cost Analysis" instead of "Pricing Complexity"
- API controller comments updated to reflect holistic assessment approach

### Technical
- Updated `ToolIntelligence` model with new fillable fields and casts
- Updated API validation rules for mid-market tier
- Updated API response formatter to include mid-market fields
- All existing tests continue to pass (4 tests, 24 assertions)

## [0.16.3] - 2025-11-11

### Fixed
- **CRITICAL**: Pricing complexity fields now properly saved and returned by API
  - Added validation rules for pricing fields in API controller
  - Added pricing fields to API response formatter
  - Fields were being silently dropped before this fix

### Added
- Comprehensive test suite for pricing complexity API endpoints (4 tests, 24 assertions)

## [0.16.2] - 2025-11-11

### Fixed
- Migration compatibility: Removed column position constraints in pricing complexity migration for better cross-environment compatibility

## [0.16.1] - 2025-11-11

### Added
- **CHANGELOG.md**: Complete version history from v0.14.1 onwards
- **Tool Intelligence API Documentation**: Comprehensive API docs at `docs/api/tool-intelligence-api.md`
  - Complete field reference with all enum options
  - Pricing complexity assessment guide with examples
  - cURL examples and MCP integration examples
  - Troubleshooting guide and best practices
- Updated `docs/README.md` with API documentation section and quick links
- Updated `CLAUDE.md` with documentation references and business intelligence overview

### Fixed
- MCP server `get_tool_intelligence` now includes pricing complexity fields in response
- Pricing complexity data now displays with dollar signs ($-$$$$$) and ranges

## [0.16.0] - 2025-11-11

### Added
- **Pricing Complexity Indicators**: Restaurant-style dollar sign ratings ($-$$$$$) for three organizational tiers:
  - Individual users (1-5 scale, $0-20/mo to $250+/mo)
  - SMB 10-50 users (1-5 scale, <$1K/mo to $40K+/mo)
  - Enterprise 500+ users (1-5 scale, <$50K/yr to $1M+/yr)
- Added 7 new fields to `tool_intelligence` table for pricing complexity data
- Updated MCP server with pricing complexity parameters
- Updated TypeScript definitions in `docs/api/tool-intelligence.d.ts`
- Added pricing complexity display section on tool show pages with visual dollar signs
- Added example data for Claude tool demonstrating pricing complexity

### Technical
- Migration: `2025_11_11_202619_add_pricing_complexity_to_tool_intelligence_table.php`
- Updated `ToolIntelligence` model with new fillable fields and casts
- Added `formatPricingComplexity()` helper in Show.vue
- Updated `resources/js/pages/Tools/Show.vue` with pricing complexity display

## [0.15.1] - 2025-11-11

### Added
- View/show button on admin tools index page
- Eye icon button opens tool's public page in new tab from admin panel

### Changed
- Updated `resources/js/pages/Admin/Tools/Index.vue` with view button

## [0.15.0] - 2025-11-11

### Added
- **Screenshot Upload Feature**: Admins can now upload screenshot files directly
- File upload support with image preview in admin tool forms
- Dual system: supports both file uploads and external screenshot URLs
- Storage symlink configuration for public file access
- Admin edit button on tool show pages for quick editing

### Fixed
- Method spoofing for PUT requests with file uploads (added `_method: 'put'` to form data)
- Route model binding: Changed all admin routes from `tool.id` to `tool.slug`
- File upload handling in `Admin\ToolController` for both create and update operations

### Changed
- Updated `ToolForm.vue` with file input and preview functionality
- Updated `Edit.vue` and `Create.vue` with file upload support
- Modified `Admin\ToolController` to handle file storage and cleanup
- Added edit button to `Show.vue` for authenticated admins

### Technical
- Added `screenshot` validation rule (image, max 5MB)
- Implemented automatic cleanup of old screenshots on update
- Fixed form submission to use `forceFormData: true` for multipart uploads

## [0.14.1] - 2025-11-11

### Added
- **UserSeeder**: Automatically creates admin user during database seeding
- Environment-based admin user configuration (`ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_NAME`)
- Documentation for `php artisan admin:create` command in CLAUDE.md

### Changed
- Updated `DatabaseSeeder` to call `UserSeeder`
- Enhanced CLAUDE.md with database seeding instructions

### Fixed
- Admin access restoration after database resets

## [0.10.3] - 2025-11-11

### Added
- "Last Updated" date display on tool show pages
- Human-readable date formatting for tool updates

## [0.10.2] - 2025-11-11

### Fixed
- Category pages now correctly display tools without ratings
- Removed unnecessary rating filter from category tool queries

## [0.10.1] - 2025-11-11

### Added
- Category diagnostic tools for troubleshooting
- Fix tools for category-related issues

## [0.10.0] - 2025-11-11

### Added
- Interactive Tool Schema documentation page at `/docs/tools/schema`
- Visual schema browser with expandable sections
- Type definitions and field descriptions

## [0.9.0] - 2025-11-11

### Added
- Developer section in home page footer navigation
- Quick links to API docs, MCP setup, and tool schema

## Earlier Versions

See git history for changes prior to v0.14.1.

---

## Version Format

- **Major.Minor.Patch** (e.g., 0.16.0)
- **Major**: Breaking changes or major feature releases
- **Minor**: New features, backward-compatible
- **Patch**: Bug fixes and minor improvements

## Categories

- **Added**: New features
- **Changed**: Changes to existing functionality
- **Deprecated**: Soon-to-be removed features
- **Removed**: Removed features
- **Fixed**: Bug fixes
- **Security**: Security improvements
- **Technical**: Implementation details for developers
