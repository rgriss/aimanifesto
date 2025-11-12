# Changelog

All notable changes to AI Manifesto will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.32.1] - 2025-11-12

### Added
- **View All Categories Button**: Added button below category preview grid
  - Shows dynamic count: "View All 17 Categories →"
  - Secondary button styling to complement main CTA
  - Prevents misleading users by showing only 6 of 17 categories

## [0.32.0] - 2025-11-12

### Changed
- **Homepage Category Integration**: Moved category boxes into "Discover AI Tools" section
  - Categories now appear below featured tools with subtle "or browse by category" divider
  - Removed duplicate categories section from bottom of page
  - Simplified CTA - single prominent "Browse All Tools" button
  - Improved visual hierarchy and user flow

### Improved
- More elegant category presentation in hero section
- Reduced page length and scrolling
- Categories are now visible without scrolling on most screens
- Better integration of tools and categories in discovery flow

## [0.31.3] - 2025-11-12

### Changed
- **Tool Show - Related Tools**: Enabled momentum indicator display
  - Changed `:show-momentum="false"` to `:show-momentum="true"`
  - Related tools now show momentum icons (Growing, Declining, Stable, etc.)
  - Provides additional context when comparing tools in same category

## [0.31.2] - 2025-11-12

### Changed
- **Categories Show Page**: Now uses ToolCard component for consistency
  - Replaced inline card markup with ToolCard component
  - Configured with voting and momentum enabled, category display hidden
  - Completes ToolCard rollout across all tool listing pages

### Improved
- Full application consistency - all tool cards now use the same component
- Categories Show page now includes voting buttons and momentum indicators
- Easier to maintain and update tool card design site-wide

## [0.31.1] - 2025-11-12

### Fixed
- **API Schema Documentation**: Updated TypeScript and JSON schemas with community link fields
  - Added `reddit_url`, `community_url`, `reviews_url`, `hn_search_query` to tool.d.ts
  - Added all four fields to tool-schema.json with proper validation rules
  - Updated examples in both schema files to demonstrate new fields
  - Resolves documentation lag behind API implementation

## [0.31.0] - 2025-11-12

### Added
- **Reusable ToolCard Component**: Created unified component for consistent tool display
  - Consolidates tool card markup from Tools Index and Related Tools
  - Flexible props to control display: `showVoting`, `showMomentum`, `showCategory`, `compact`
  - Includes momentum indicators, badges, voting buttons, pricing, and ratings
  - Consistent hover states and transitions across all tool cards

### Changed
- **Tools Index**: Now uses ToolCard component instead of inline markup
- **Tool Show (Related Tools)**: Now uses ToolCard component with simplified props
- Removed duplicate getMomentumDisplay logic from Tools Index

### Improved
- Consistent tool card styling across entire application
- Easier to maintain - one source of truth for tool card design
- DRY principle applied to tool card markup

## [0.30.1] - 2025-11-12

### Fixed
- **Tabs Component v-model Binding**: Fixed non-interactive tabs
  - Added `useForwardPropsEmits` to properly forward emits from TabsRoot
  - Tabs now respond to clicks and state changes
  - Resolves issue where Popular/Recent tabs weren't clickable

## [0.30.0] - 2025-11-12

### Added
- **Hacker News Sort Toggle**: Added tabs to switch between "Popular" and "Recent" discussions
  - Popular tab shows most relevant/discussed threads (default)
  - Recent tab shows newest discussions sorted by date
  - Recent view includes quality filter (minimum 5 points) to avoid spam
  - Results cached per tab for better performance - no refetching when switching
  - Each tab has independent loading states
  - Icons for visual clarity (TrendingUp for Popular, Clock for Recent)

### Technical
- Uses HN Algolia's `search_by_date` endpoint for Recent sorting
- Implements lazy loading - only fetches Recent data when user clicks tab
- Responsive tab UI using existing Tabs component

## [0.29.7] - 2025-11-12

### Changed
- **Branding Updates**: Depersonalized tool ratings and editorial content
  - Changed "Ryan's Rating" to "Our Rating" on tool show pages
  - Changed "Ryan's Take" to "Our Take" for editorial notes
  - Removed "Curated by Ryan Grissinger" from footer
  - Footer now shows only "The AI Manifesto © 2025"

## [0.29.6] - 2025-11-12

### Added
- **Clickable Category in Related Tools**: Category name in "More in {Category}" heading is now a clickable link
  - Takes users directly to the category page
  - Styled with underline on hover for clear affordance

### Fixed
- **Related Tools Card Height**: Fixed inconsistent card heights in Related Tools grid
  - Added flexbox layout to Link wrapper and Card component
  - Added `flex-grow` to description paragraph to push badges to bottom
  - All cards now align properly regardless of content length

### Changed
- **SectionHeading Component**: Now supports both prop and slot for title
  - Title prop is now optional
  - Added `title` slot for custom title content
  - Falls back to prop if slot not provided (backward compatible)

## [0.29.5] - 2025-11-12

### Fixed
- **Tool Show Page Spacing**: Added proper margin above Related Tools section
  - Added `mt-12` class to Related Tools container
  - Fixes spacing issue where section appeared squished after sidebar was added

## [0.29.4] - 2025-11-12

### Fixed
- **MCP Server Schema**: Added community link fields to MCP tool schemas
  - Added `reddit_url`, `community_url`, `reviews_url`, `hn_search_query` to both `add_ai_tool` and `update_ai_tool` schemas
  - MCP server now exposes these fields for Claude to use
  - Requires MCP server restart to pick up new schema

### Technical
- Updated mcp-server.js with four new field definitions in tool schemas

## [0.29.3] - 2025-11-12

### Fixed
- **API Validation for Community Links**: API controller now accepts community link fields
  - Added validation rules for `reddit_url`, `community_url`, `reviews_url`, `hn_search_query`
  - MCP server can now update tools with community links via PATCH requests
  - Fixes issue where MCP updates were silently rejected

### Technical
- Updated ApiToolController validateToolRequest() method with four new field validations

## [0.29.2] - 2025-11-12

### Fixed
- **Tool Model Fillable Fields**: Added missing fields to Tool model's $fillable array
  - Added `reddit_url`, `community_url`, `reviews_url`, `hn_search_query`
  - These fields were in database but couldn't be mass-assigned
  - Community & Reviews card now displays properly when URLs are provided

### Technical
- Updated Tool model $fillable array with four new fields

## [0.29.1] - 2025-11-12

### Added
- **Custom Hacker News Search Queries**: New `hn_search_query` field on Tool model
  - Allows overriding HN search term for tools with generic names
  - For example: "Clay" → "Clay automation" to avoid irrelevant results
  - HackerNewsDiscussions component now accepts optional `customQuery` prop
  - Falls back to tool name if no custom query provided
  - Helps filter out false positives (e.g., "clay tablets" when searching for Clay.com)

### Database
- Added `hn_search_query` (nullable string, max 255) - Custom search term for HN API

### Technical
- Created migration: `2025_11_12_175810_add_hn_search_query_to_tools_table`
- Updated HackerNewsDiscussions.vue to use computed searchQuery
- Updated Tool Show page to pass hn_search_query to component

## [0.29.0] - 2025-11-12

### Added
- **External Research Resources Sidebar**: Tool show pages now include sidebar with external resources
  - **HackerNewsDiscussions.vue** component fetches and displays recent HN discussions via Algolia API
  - Shows discussion titles, points, comment counts, and dates
  - Links to individual discussions and HN search results
  - Fully client-side - no backend API required
  - Includes loading, error, and empty states
  - **CommunityLinks.vue** component displays curated community resources
  - Three new optional Tool fields: `reddit_url`, `community_url`, `reviews_url`
  - Only renders if at least one community link is provided
  - Each link type has appropriate icon (Reddit, Discord/Slack, Reviews)
  - Modular design allows easy repositioning of components
  - Responsive layout: 2/3 main + 1/3 sidebar on desktop, stacked on mobile

### Database
- Added `reddit_url` (nullable string) - Link to subreddit or Reddit search
- Added `community_url` (nullable string) - Link to Discord, Slack, or Forum
- Added `reviews_url` (nullable string) - Link to G2, Capterra, ProductHunt, etc.

### Technical
- Created migration: `2025_11_12_173339_add_community_links_to_tools_table`
- New components are self-contained and can be repositioned for different layouts
- HN API integration uses public Algolia search endpoint

## [0.28.3] - 2025-11-12

### Changed
- **Two-Tier Voting Philosophy System**: Escalating reminders for rapid voting
  - Tier 1 (5 clicks in 1 minute): First-time users see philosophy dialog
  - Tier 2 (20 clicks in 1 minute): Dialog re-appears even for acknowledged users
  - Resets localStorage flag to force re-acknowledgment at Tier 2
  - Acts as gentle "you're really pushing it" reminder
  - Extended tracking window from 30s to 60s for proper Tier 2 detection
  - Progressive warnings help prevent spam while encouraging genuine enthusiasm

## [0.28.2] - 2025-11-12

### Changed
- **Voting Rate Limit Increased**: API rate limit raised from 10 to 100 votes per minute
  - Allows enthusiastic multi-clicking as intended by voting philosophy
  - Prevents legitimate users from hitting artificial limits

### Technical
- Added console debugging to VoteButtons component for troubleshooting
- Logs localStorage state, click counts, and dialog trigger logic

## [0.28.1] - 2025-11-12

### Changed
- **Voting Philosophy Dialog Refinements**: Improved messaging and clarity
  - Added clear voting guidance section with bullet points
  - Removed repetitive phrases for more concise warning
  - Consolidated community experiment message
  - Maintained casual tone while improving focus

## [0.28.0] - 2025-11-12

### Added
- **Voting Philosophy Dialog**: Community-focused approach to public voting
  - Tracks vote clicks with timestamps (30-second window)
  - Shows dialog after 5+ rapid clicks (first time only)
  - Stores acknowledgment in localStorage
  - Explains system is intentionally insecure/imperfect
  - Emphasizes multiple clicks show motivation (interesting data)
  - Transparent about future plans and "not social media" philosophy
  - Gentle reminder to avoid spam/obnoxious behavior
  - "Got It, I'll Be Cool" acknowledgment button

## [0.27.4] - 2025-11-12

### Added
- **Dynamic Help Wanted Modal CTAs**: Authentication-aware call-to-action buttons
  - Non-logged-in users see "Sign the Manifesto" as primary CTA
  - Logged-in users see "Email Us" as primary CTA
  - Added GitHub buttons for both auth states:
    - "View on GitHub" links to main repository
    - "Report Issues & Contribute" links to issues page
  - Responsive button layout with mobile-friendly stacking

## [0.20.1] - 2025-11-12

### Changed
- **Help Wanted Sign Readability Improvements**: Enhanced text sizing and layout
  - Increased cursive handwritten text size dramatically (text-base/lg → text-2xl/3xl)
  - Added font-semibold to cursive text for better readability
  - Combined "HELP" and "WANTED" onto single line for cleaner composition
  - Reduced main text size slightly (text-4xl/5xl → text-3xl/4xl)
  - Increased white box padding (px-4 py-2 → px-6 py-3) to accommodate larger text
  - Cursive text now highly readable and prominent instead of subtle

## [0.20.0] - 2025-11-12

### Added
- **Handwritten Note Section to Help Wanted Sign**: Added white rounded section with cursive text
  - White background with rounded corners (rounded-lg)
  - Black cursive text using Brush Script MT with Lucida Handwriting fallback
  - Displays "reviews, coders, educators" in handwritten style
  - Positioned between main "HELP WANTED" text and "click here" link
  - Mimics handwritten ink annotation on vintage sign
  - Adds personal, approachable touch to call-to-action

### Technical
- Added handwritten note div with bg-background and rounded-lg styling
- Used cursive font stack: 'Brush Script MT', 'Lucida Handwriting', cursive
- Applied italic styling for enhanced handwritten effect
- Responsive text sizing: text-base on mobile, text-lg on desktop

## [0.19.9] - 2025-11-12

### Changed
- **Help Wanted Sign Styling Improvements**: Enhanced visual impact and readability
  - Increased font weight to font-black (900 weight) for bolder appearance
  - Changed font family to Impact with Arial Black and sans-serif fallbacks
  - Reduced padding around text (py-8 → py-4) for tighter composition
  - Changed line-height to leading-none for more compact text
  - Added small spacing (mt-1) between "HELP" and "WANTED" lines
  - Adjusted letter spacing to 0.05em for optimal Impact font rendering
  - Overall more impactful, vintage help wanted sign aesthetic

## [0.19.8] - 2025-11-12

### Added
- **Help Wanted Sign Component**: Eye-catching call-to-action component
  - Red background with white inset border for vintage sign aesthetic
  - Bold white uppercase "HELP WANTED" text across two lines
  - Subtle "click here" text below for user guidance
  - Hover animation with scale effect
  - Clickable with support for both internal routes and external links
  - Placed prominently on homepage between audience sections and core values
  - Links to contact email with "I Want to Help" subject line

### Technical
- Created reusable HelpWantedSign.vue component
- Supports both Link (internal) and anchor (external) elements via props
- Added to components/index.ts barrel export
- Styled with Tailwind using danger color for red background
- Inset border achieved with absolute positioning and border classes
- Responsive sizing: max-w-xs on mobile, max-w-sm on desktop

## [0.19.7] - 2025-11-12

### Added
- **Centralized Contact Email Configuration**: Contact email now managed via environment variable
  - Added `CONTACT_EMAIL` environment variable (default: info@polarispixels.com)
  - New config value in `config/app.php` as `contact_email`
  - Shared globally via Inertia middleware as `$page.props.contactEmail`
  - Available to all Vue components throughout the site
  - Easy to update site-wide by changing single .env value

### Changed
- **Email Address Updates**: Changed all contact emails to info@polarispixels.com
  - Updated "Share Your AI Story" mailto link in Business Leaders section on homepage
  - Updated MAIL_FROM_ADDRESS default in .env.example
  - Previous email: hello@aimanifesto.dev

### Technical
- Added `contactEmail` to HandleInertiaRequests shared props
- Updated Home.vue to use dynamic email from `$page.props.contactEmail`
- Added CONTACT_EMAIL to .env.example with default value
- Updated CLAUDE.md documentation:
  - Added Configuration section with Environment Variables subsection
  - Documented CONTACT_EMAIL in shared Inertia props
  - Updated middleware props list to include contactEmail

## [0.19.6] - 2025-11-12

### Added
- **Momentum Icons on Homepage**: Added visual momentum indicators to featured tools section
  - Matches implementation from Tools Index page
  - Displays dynamic icons (TrendingUp, Activity, TrendingDown, Circle) based on momentum_score
  - Icon positioned to left of tool name with consistent spacing
  - Provides quick visual feedback on tool growth trajectory
  - Maintains alignment when no momentum data is available

### Technical
- Imported Lucide icons (TrendingUp, TrendingDown, Activity, Circle) in Home.vue
- Added `getMomentumDisplay()` helper function matching Tools/Index.vue implementation
- Updated featured tools card layout with momentum icon component
- Icon size: 18px with 8px gap for consistent visual weight

## [0.19.5] - 2025-11-12

### Removed
- **Data Completeness Badge**: Removed unreliable completeness percentage badge from Business Intelligence section
  - Badge was frequently showing 0% despite having valid data in database
  - Actual intelligence data fields are more valuable than a percentage score
  - Cleaner section header without distracting badge
  - Users can see what specific information is available directly in the sections

### Technical
- Simplified Business Intelligence section header in Tools/Show.vue
- Removed conditional Badge component and flex-between wrapper

## [0.19.4] - 2025-11-12

### Added
- **Momentum Icons on Tools Index**: Visual momentum indicators for each tool card
  - Displays TrendingUp icon (green) for growing tools (scores 4-5)
  - Displays Activity icon (gray) for stable tools (score 3)
  - Displays TrendingDown icon (red/yellow) for declining tools (scores 1-2)
  - Shows neutral Circle icon (light gray) when no momentum data available
  - Icon positioned to the left of tool name for easy scanning
  - Tooltip shows momentum status on hover
  - Maintains consistent spacing across all cards

### Technical
- Imported TrendingUp, TrendingDown, Activity, Circle icons from lucide-vue-next
- Added `getMomentumDisplay()` helper function to map scores to icon/color
- Dynamic component rendering based on momentum_score
- Icon size: 18px, positioned with 8px gap from tool name
- Added flex-shrink-0 to badge container to prevent wrapping

## [0.19.3] - 2025-11-12

### Fixed
- **Tools Index Card Heights**: Tool cards now have equal heights within each row
  - Cards stretch to match the tallest card in their row
  - Category, rating, and pricing badges align at the bottom of each card
  - Voting buttons, description, and footer section properly positioned
  - Consistent with equal height behavior across the entire site
  - Improved visual consistency on tools directory page

### Technical
- Added `h-full flex` to Link wrapper for full height stretching
- Added `flex-1 flex flex-col` to Card component for flexbox layout
- Added `flex-grow` to description paragraph for expanding space
- Added `mt-auto` wrapper around bottom section to push to bottom

## [0.19.2] - 2025-11-12

### Changed
- **Tool Show Page Vote Buttons Position**: Moved voting buttons to top right of page header
  - Voting buttons now positioned in the actions area (top right)
  - Stacked vertically with Featured badge when present
  - Changed size from medium to small for better header proportions
  - More prominent and conventional placement for interactive elements
  - Removed from metadata section for cleaner layout

### Technical
- Updated Tools/Show.vue template to move VoteButtons to #actions slot
- Applied flex-col and items-end for proper vertical stacking
- Simplified metadata template by removing vote section

## [0.19.1] - 2025-11-12

### Fixed
- **Category Show Page Card Heights**: Tool cards now have equal heights within each row
  - Cards stretch to match the tallest card in their row
  - Pricing and rating badges align at the bottom of each card
  - Consistent with equal height behavior across the entire site
  - Improved visual consistency on category detail pages

### Technical
- Added `h-full flex` to Link wrapper for full height stretching
- Added `flex-1 flex flex-col` to Card component for flexbox layout
- Added `flex-grow` to description paragraph for expanding space
- Added `mt-auto` to badge container to push badges to bottom

## [0.19.0] - 2025-11-12

### Changed
- **Brand Name Update**: Updated all instances of "AI Manifesto" to "The AI Manifesto"
  - Navigation bar now displays "The AI Manifesto"
  - Page titles updated across all pages
  - Meta tags (Open Graph, Twitter cards) updated
  - Footer copyright updated
  - Section headings updated ("What is The AI Manifesto?", "Who is The AI Manifesto for?")
  - Home page hero updated to "The Artificial Intelligence Manifesto"
  - Admin dashboard references updated
  - All alt text for logos updated

### Technical
- Updated 15+ Vue components and pages
- Updated app.blade.php meta tags
- Updated GuestLayout navigation and footer
- Consistent "The AI Manifesto" branding throughout application

## [0.18.9] - 2025-11-12

### Added
- **Navigation Bar Logo**: Added Wave logo to main navigation beside "The AI Manifesto" text
  - Theme-aware display (blue in light mode, white in dark mode)
  - Appears on all public pages using GuestLayout
  - 32px height with auto width to maintain aspect ratio
  - Consistent with brand identity across the application

### Technical
- Updated GuestLayout.vue navigation header
- Added dual-image approach with theme-responsive CSS classes

## [0.18.8] - 2025-11-12

### Changed
- **Brand Identity Update**: Replaced Polaris Pixels logo with new Wave logo
  - New Wave logo in three variants: blue (primary), black, and white
  - Optimized logo files from 3600x3600 (170-181KB) to 800x800 (8-11KB)
  - Theme-aware logo display: blue in light mode, white in dark mode
  - Updated across all application surfaces:
    - Sidebar (AppLogo.vue)
    - Home page hero
    - Admin dashboard header
    - Brand guidelines page
    - Meta tags (Open Graph, Twitter cards)
    - Apple touch icon
  - Blue wave logo chosen as primary brand identity

### Technical
- Created optimization script to resize logos from 3600x3600px to 800x800px
- Added sharp package for image optimization
- Implemented dual-image approach with CSS classes (dark:hidden, hidden dark:block)
- Updated meta author from "Polaris Pixels" to "AI Manifesto"

## [0.18.7] - 2025-11-12

### Fixed
- **Dashboard/Admin Sidebar Logo Link**: Master logo link now navigates to site root instead of dashboard
  - Changed AppSidebar.vue logo link from `dashboard()` route to `/` (site root)
  - Affects both `/dashboard` and `/admin` layouts which share the same sidebar
  - Clicking the logo now returns users to the main homepage
  - Dashboard and Admin navigation links remain in sidebar for quick access

### Technical
- Updated `resources/js/components/AppSidebar.vue` line 65
- Changed from `:href="dashboard()"` to `href="/"`

## [0.18.6] - 2025-11-12

### Fixed
- **Categories Index Card Heights**: Category cards now have equal heights within each row
  - Cards stretch to match the tallest card in their row
  - Tool count badges align at the bottom of each card
  - Consistent with equal height behavior across the entire site
  - Improved visual consistency on categories listing page

### Technical
- Added `h-full flex` to Link wrapper for full height stretching
- Added `flex-1 flex flex-col` to Card component for flexbox layout
- Added `flex-grow` to description paragraph for expanding space
- Wrapped badge in `mt-auto` container to push to bottom

## [0.18.5] - 2025-11-12

### Fixed
- **Card Heights on Home Page**: All card sections now have equal heights within each row
  - "What is AI Manifesto?" section: 3 informational cards now have consistent heights
  - "Explore by Category" section: Category cards now stretch to equal heights
  - Matches the professional appearance of other card sections on the page
  - Improved visual consistency across all home page sections

### Technical
- Added `h-full` classes to Link wrappers in "What is AI Manifesto?" section
- Added flexbox layout to category cards (h-full flex, flex-1 flex flex-col)
- Added `flex-grow` to category names and `mt-auto` to badges for bottom alignment
- Consistent flexbox pattern applied across all card sections

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
