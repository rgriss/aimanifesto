# AI Manifesto

A modern tool directory and catalog for AI tools, built with Laravel 12, Vue 3, and Inertia.js.

## Overview

AI Manifesto is a curated directory of AI tools featuring:

- **Tool Directory** - Browse and discover AI tools organized by category
- **Personal Ratings & Notes** - Track personal ratings (1-10 scale) and usage notes for each tool
- **Rich Metadata** - Features, use cases, integrations, pricing models, and more
- **Modern Stack** - Laravel 12 backend with Vue 3 + TypeScript + Inertia.js frontend
- **Beautiful UI** - Tailwind CSS v4 with Reka UI component library

## Tech Stack

- **Backend:** Laravel 12 with Fortify authentication
- **Frontend:** Vue 3 (Composition API) + TypeScript + Inertia.js
- **Styling:** Tailwind CSS v4, Reka UI components, Lucide icons
- **Build Tool:** Vite 7 with SSR support
- **Testing:** Pest (PHP), ESLint + Prettier (JS/TS)

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

### Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd aimanifesto
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`, then run migrations:
```bash
php artisan migrate
```

6. Start development server:
```bash
composer dev
```

This runs the PHP server, queue listener, and Vite dev server concurrently.

Visit `http://localhost:8000` to see the application.

### Development with SSR

To run with Server-Side Rendering enabled:

```bash
composer dev:ssr
```

## Key Features

- **Authentication** - Full auth flow with 2FA support (Laravel Fortify)
- **Tool Management** - Create, edit, and organize AI tools
- **Category System** - Organize tools by category with slug-based routing
- **Theme Support** - Light, dark, and system theme modes
- **User Settings** - Profile, password, 2FA, and appearance settings
- **Type-Safe Routes** - Laravel Wayfinder for type-safe route helpers

## Project Structure

- `app/Models/` - Eloquent models (Tool, Category, User)
- `resources/js/pages/` - Inertia.js page components
- `resources/js/components/` - Vue components and Reka UI library
- `resources/js/layouts/` - Layout components
- `tests/` - Pest test suite

## Available Commands

```bash
# Development
composer dev          # Start dev environment
composer dev:ssr      # Start with SSR
npm run dev           # Vite dev server only

# Building
npm run build         # Production build
npm run build:ssr     # Build with SSR

# Code Quality
npm run lint          # ESLint with auto-fix
npm run format        # Prettier format
./vendor/bin/pint     # Laravel Pint (PHP formatting)

# Testing
composer test         # Run Pest tests
php artisan test      # Direct test execution
```

## Documentation

See [CLAUDE.md](CLAUDE.md) for detailed architecture documentation and AI code assistant guidance.

## Questions?

For questions or support, please email Ryan at **polarispixels.com**

## License

[Add your license here]
