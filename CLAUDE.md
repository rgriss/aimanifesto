# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Tech Stack

This is a **Laravel 12 + Vue 3 + Inertia.js** application with TypeScript and Tailwind CSS v4. It uses:
- **Backend:** Laravel 12, Laravel Fortify (authentication), Laravel Wayfinder (route helpers)
- **Frontend:** Vue 3 (Composition API), Inertia.js (SSR-capable), TypeScript
- **Styling:** Tailwind CSS v4 (via @tailwindcss/vite), Reka UI components, lucide-vue-next icons
- **Build:** Vite 7 with SSR support
- **Testing:** Pest (PHP)

## Core Domain

This application is a **tool directory/catalog** for AI tools:
- **Categories** (Model at app/Models/Category.php) - Organize tools by category
- **Tools** (Model at app/Models/Tool.php) - Individual AI tools with features including:
  - Personal ratings ("ryan_rating" 1-10 scale) and usage notes
  - Pricing models (free, freemium, paid, enterprise)
  - JSON fields: features, use_cases, integrations
  - View tracking, featured status, active/inactive state

Key relationships:
- Category `hasMany` Tools
- Tool `belongsTo` Category
- Both models use slug-based routing (`getRouteKeyName()`)

## Architecture

### Backend Structure
- **Routes:** Separated into web.php (public + dashboard), auth.php (authentication), settings.php (user settings)
- **Controllers:** Resource controllers for Tool and Category, separate auth controllers in Auth/, settings in Settings/
- **Inertia Middleware:** HandleInertiaRequests shares global props: `auth.user`, `name`, `quote` (inspirational), `sidebarOpen` (cookie-based state)
- **Appearance Handling:** HandleAppearance middleware manages theme (light/dark/system) via cookies

### Frontend Structure
- **Entry:** resources/js/app.ts - Creates Inertia app, initializes theme on page load
- **Pages:** resources/js/pages/ - Auto-loaded by Inertia (auth/, settings/, Tools/, Categories/, Dashboard.vue, Home.vue)
- **Layouts:** resources/js/layouts/ - Multiple layouts:
  - AppLayout.vue, AuthLayout.vue
  - Sublayouts: app/AppSidebarLayout.vue, app/AppHeaderLayout.vue, auth/AuthCardLayout.vue, etc.
  - settings/Layout.vue for settings pages
- **Components:** resources/js/components/ - App-specific components + ui/ (Reka UI components)
- **Composables:** resources/js/composables/ - useAppearance (theme), useTwoFactorAuth, useInitials
- **Types:** resources/js/types/ - TypeScript definitions

### UI Component System
Uses Reka UI (Radix-like) components in resources/js/components/ui/:
- Each component has index.ts barrel export
- Pre-built components: Button, Card, Dialog, Dropdown, Sidebar, Tooltip, Badge, Alert, etc.
- Styled with Tailwind + class-variance-authority (CVA)

## Development Commands

### Start Development Environment
```bash
composer dev
```
Runs concurrently: PHP server, queue listener, and Vite dev server

### Start with SSR
```bash
composer dev:ssr
```
Runs: PHP server, queue, logs (pail), and Inertia SSR server

### Frontend Only
```bash
npm run dev          # Vite dev server
npm run build        # Production build
npm run build:ssr    # Build with SSR
```

### Code Quality
```bash
npm run lint         # ESLint (auto-fix)
npm run format       # Prettier format
npm run format:check # Prettier check only
```

### PHP Code Quality
```bash
./vendor/bin/pint    # Laravel Pint (auto-format)
```

### Testing
```bash
composer test        # Runs Pest tests (clears config first)
php artisan test     # Direct test execution
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with seeders
```

## Key Conventions

### Route Model Binding
Both Tool and Category use slug-based routing. Routes use `{tool:slug}` and `{category:slug}` syntax.

### Scopes
- Tool: `active()`, `featured()`, `highestRated()`, `inCategory($categoryId)`
- Category: `active()`, `ordered()`

### Shared Inertia Props
Always available in Vue components via `$page.props`:
- `auth.user` - Current authenticated user
- `name` - App name from config
- `quote` - Random inspirational quote (message + author)
- `sidebarOpen` - Sidebar state from cookie

### Theme/Appearance
Managed via `appearance` cookie (values: 'light', 'dark', 'system'). Initialized client-side in app.ts via `initializeTheme()`. Server-side available in blade templates via shared `appearance` variable.

### Two-Factor Authentication
Uses Laravel Fortify with custom Inertia views. Recovery codes handled in TwoFactorRecoveryCodes.vue component.

## Testing Structure

Tests in tests/:
- **Feature/Auth/** - Authentication flow tests (login, register, 2FA, password reset, email verification)
- **Feature/Settings/** - User settings tests (profile, password, 2FA)
- **Feature/** - HomepageTest, ToolsTest, CategoriesTest, DashboardTest
- Uses Pest syntax with Laravel-specific helpers

## Important Files

- **vite.config.ts** - Vite config with Wayfinder plugin, Vue, Tailwind v4
- **components.json** - UI component configuration (aliases, paths)
- **eslint.config.js** - ESLint flat config with Vue + TypeScript rules
- **phpunit.xml** - PHP test configuration
- **tsconfig.json** - TypeScript configuration
