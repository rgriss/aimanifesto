<div align="center">

# ⭐ AI Manifesto

### A Modern, Curated Directory for AI Tools

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue 3](https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-SSR-9553E9)](https://inertiajs.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**[🌐 Visit Live Site →](https://aimanifesto.net)**

[Features](#-features) •
[Demo](#-demo) •
[Quick Start](#-quick-start) •
[Tech Stack](#-tech-stack) •
[Roadmap](#-roadmap) •
[Contributing](#-contributing)

</div>

---

## 📖 Overview

**AI Manifesto** is a beautifully designed, full-stack application for discovering, organizing, and tracking AI tools. Whether you're building your personal AI toolkit or creating a public directory, this project demonstrates modern web development best practices.

### What Makes This Special?

- 🎨 **Beautiful UI** - Tailwind CSS v4 with Reka UI component library
- 🔒 **Secure** - Full authentication with 2FA support
- ⚡ **Fast** - Server-side rendering (SSR) with Inertia.js
- 📱 **Responsive** - Works perfectly on all devices
- 🎯 **Type-Safe** - TypeScript + Laravel Wayfinder for end-to-end type safety
- 🧪 **Well-Tested** - Comprehensive test suite with Pest
- 📚 **Well-Documented** - Clear code, detailed docs, and inline comments

---

## ✨ Features

### Core Features
- **🗂️ Tool Directory** - Browse and discover AI tools organized by category
- **⭐ Personal Ratings** - Track ratings (1-10 scale) and usage notes
- **🏷️ Rich Metadata** - Features, use cases, integrations, pricing models
- **🔍 Slug-Based URLs** - SEO-friendly routes for tools and categories
- **🌓 Theme Support** - Light, dark, and system theme modes
- **👤 User Management** - Profile settings, password changes, 2FA

### Admin Features
- **🛠️ Tool Management** - Full CRUD operations for tools
- **📂 Category Management** - Organize tools by category
- **📊 Data Management** - Import/export database as JSON
- **🔐 Admin Dashboard** - Dedicated admin interface

### Developer Features
- **🎯 Type-Safe Routes** - Laravel Wayfinder integration
- **🧩 Component Library** - Reka UI (Radix-like components)
- **🔥 Hot Module Reload** - Fast development with Vite
- **📦 Modern Build** - Vite 7 with optimized production builds

---

## 🎬 Demo

**🌐 Live Site:** **[aimanifesto.net](https://aimanifesto.net)**

> _Screenshot coming soon!_

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18 or higher
- MySQL or PostgreSQL

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/rgriss/aimanifesto.git
cd aimanifesto

# 2. Install dependencies
composer install
npm install

# 3. Set up environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env, then migrate
php artisan migrate

# 5. Create an admin user
php artisan admin:create

# 6. Start development server
composer dev
```

Visit **http://localhost:8000** 🎉

### Alternative: Development with SSR

For server-side rendering:

```bash
composer dev:ssr
```

---

## 🛠️ Tech Stack

### Backend
- **[Laravel 12](https://laravel.com)** - Modern PHP framework
- **[Laravel Fortify](https://laravel.com/docs/fortify)** - Authentication with 2FA
- **[Pest](https://pestphp.com)** - Elegant testing framework

### Frontend
- **[Vue 3](https://vuejs.org)** - Progressive JavaScript framework (Composition API)
- **[TypeScript](https://www.typescriptlang.org)** - Type-safe JavaScript
- **[Inertia.js](https://inertiajs.com)** - Modern monolith with SSR support
- **[Vite 7](https://vitejs.dev)** - Next-generation build tool
- **[Tailwind CSS v4](https://tailwindcss.com)** - Utility-first CSS framework
- **[Reka UI](https://reka-ui.com)** - Unstyled, accessible component library
- **[Lucide Icons](https://lucide.dev)** - Beautiful icon library

### DevOps
- **[Laravel Wayfinder](https://github.com/glhd/laravel-wayfinder)** - Type-safe routes
- **[ESLint](https://eslint.org)** + **[Prettier](https://prettier.io)** - Code quality
- **[Laravel Pint](https://laravel.com/docs/pint)** - PHP code formatting

---

## 🗺️ Roadmap

We're actively developing AI Manifesto! Check out our **[GitHub Project Board](https://github.com/rgriss/aimanifesto/projects/2)** to see what we're working on.

### Coming Soon
- 🔍 Search and filtering
- 📊 Analytics dashboard
- 🔌 Public REST API
- 🔐 OAuth social login (Google, GitHub)
- 📝 Comprehensive documentation
- 🧪 Expanded test coverage

### Long-Term Vision
- 🌐 Community-driven contributions
- 🔄 API integrations with popular tools
- 📈 Tool comparison features
- 🎯 Personalized recommendations

---

## 🤝 Contributing

We welcome contributions from developers of all skill levels!

### How to Contribute

1. **Browse Issues** - Check our [Issues](https://github.com/rgriss/aimanifesto/issues) page
2. **Pick a Task** - Look for issues labeled [`good first issue`](https://github.com/rgriss/aimanifesto/labels/good%20first%20issue)
3. **Fork & Branch** - Create a feature branch from `main`
4. **Make Changes** - Write clean, tested code
5. **Submit PR** - Open a pull request with a clear description

### Development Guidelines

```bash
# Code formatting
npm run lint        # Fix JavaScript/TypeScript issues
npm run format      # Format with Prettier
./vendor/bin/pint   # Format PHP code

# Testing
composer test       # Run PHP tests
npm run build       # Verify build works
```

📖 **Read our [Contributing Guide](CONTRIBUTING.md)** for detailed instructions.

---

## 📋 Available Commands

```bash
# Development
composer dev          # Start PHP server + Vite + queue worker
composer dev:ssr      # Start with SSR enabled
npm run dev           # Vite dev server only

# Building
npm run build         # Production build
npm run build:ssr     # Production build with SSR

# Code Quality
npm run lint          # ESLint with auto-fix
npm run format        # Prettier format all files
npm run format:check  # Check formatting
./vendor/bin/pint     # Laravel Pint (PHP)

# Testing
composer test         # Run Pest test suite
php artisan test      # Direct test execution

# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with sample data
php artisan admin:create         # Create admin user
php artisan db:export            # Export database to JSON
php artisan db:import {file}     # Import from JSON
```

---

## 📂 Project Structure

```
aimanifesto/
├── app/
│   ├── Http/Controllers/      # Controllers (public, admin, auth, settings)
│   ├── Models/                 # Eloquent models (Tool, Category, User)
│   └── ...
├── resources/
│   ├── js/
│   │   ├── components/         # Vue components
│   │   ├── layouts/            # Layout components
│   │   ├── pages/              # Inertia.js pages
│   │   └── app.ts              # Frontend entry point
│   └── views/                  # Blade templates
├── routes/
│   ├── web.php                 # Public routes
│   ├── auth.php                # Authentication routes
│   ├── settings.php            # User settings routes
│   └── admin.php               # Admin routes
├── tests/                      # Pest test suite
└── ...
```

---

## 📚 Documentation

- **[Architecture Guide](CLAUDE.md)** - Detailed technical documentation
- **[Contributing Guide](CONTRIBUTING.md)** - How to contribute
- **[Code of Conduct](CODE_OF_CONDUCT.md)** - Community guidelines

---

## 🙏 Acknowledgments

Built with ❤️ by [Ryan](https://github.com/rgriss) at [Polaris Pixels](https://polarispixels.com)

Special thanks to:
- The Laravel community
- The Vue.js team
- All our contributors

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 💬 Questions or Feedback?

- 🐛 **Found a bug?** [Open an issue](https://github.com/rgriss/aimanifesto/issues/new)
- 💡 **Have an idea?** [Start a discussion](https://github.com/rgriss/aimanifesto/discussions)
- 📧 **Need help?** Email us at [Polaris Pixels](https://polarispixels.com)

---

<div align="center">

**[⭐ Star this project](https://github.com/rgriss/aimanifesto)** if you find it useful!

Made with 🚀 by developers, for developers

</div>
