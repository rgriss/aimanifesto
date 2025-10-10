# Contributing to AI Manifesto

First off, thank you for considering contributing to AI Manifesto! 🎉

We welcome contributions from developers of all skill levels. Whether you're fixing a typo, improving documentation, or adding a major feature, your help is appreciated.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Commit Messages](#commit-messages)
- [Pull Request Process](#pull-request-process)
- [Issue Guidelines](#issue-guidelines)
- [Questions?](#questions)

---

## 📜 Code of Conduct

This project adheres to a Code of Conduct that we expect all contributors to follow. Please read [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before contributing.

**TL;DR:** Be respectful, inclusive, and professional. We're all here to build something great together.

---

## 🤝 How Can I Contribute?

### Reporting Bugs

Found a bug? Help us fix it!

1. **Check existing issues** - Someone might have already reported it
2. **Create a new issue** - Use our bug report template
3. **Provide details** - Include steps to reproduce, expected vs actual behavior, screenshots if applicable

### Suggesting Features

Have an idea? We'd love to hear it!

1. **Check the roadmap** - See our [GitHub Project Board](https://github.com/rgriss/aimanifesto/projects/2)
2. **Search existing issues** - Someone might have suggested it already
3. **Open an issue** - Describe the feature, why it's needed, and how it should work

### Writing Code

Ready to contribute code?

1. **Find an issue** - Look for issues labeled [`good first issue`](https://github.com/rgriss/aimanifesto/labels/good%20first%20issue) if you're new
2. **Comment on the issue** - Let us know you're working on it
3. **Follow the workflow** - See [Development Workflow](#development-workflow) below

### Improving Documentation

Documentation is just as important as code!

- Fix typos or unclear explanations
- Add examples or screenshots
- Improve setup instructions
- Write tutorials or guides

---

## 🚀 Getting Started

### Prerequisites

- **PHP 8.2+** with required extensions
- **Composer** for PHP dependencies
- **Node.js 18+** and npm for frontend dependencies
- **MySQL or PostgreSQL** database
- **Git** for version control

### Fork and Clone

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR-USERNAME/aimanifesto.git
   cd aimanifesto
   ```
3. **Add upstream remote:**
   ```bash
   git remote add upstream https://github.com/rgriss/aimanifesto.git
   ```

### Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# Then run migrations
php artisan migrate

# Create an admin user (optional)
php artisan admin:create
```

### Start Development Server

```bash
# Start all services (PHP server, Vite, queue worker)
composer dev

# Or with SSR enabled
composer dev:ssr
```

Visit **http://localhost:8000** to see the app running!

---

## 🔄 Development Workflow

### 1. Create a Feature Branch

Always create a new branch for your work:

```bash
# Update your main branch
git checkout main
git pull upstream main

# Create a new branch
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

**Branch naming conventions:**
- `feature/` - New features (e.g., `feature/search-functionality`)
- `fix/` - Bug fixes (e.g., `fix/login-validation`)
- `docs/` - Documentation updates (e.g., `docs/api-guide`)
- `refactor/` - Code refactoring (e.g., `refactor/auth-controllers`)
- `test/` - Adding tests (e.g., `test/tool-crud`)

### 2. Make Your Changes

- Write clean, readable code
- Follow our [Coding Standards](#coding-standards)
- Add tests for new features
- Update documentation as needed

### 3. Test Your Changes

```bash
# Run PHP tests
composer test

# Run linters
npm run lint
./vendor/bin/pint

# Format code
npm run format

# Build assets to ensure no errors
npm run build
```

### 4. Commit Your Changes

```bash
git add .
git commit -m "Add feature: description of what you did"
```

See [Commit Messages](#commit-messages) for guidelines.

### 5. Push and Create Pull Request

```bash
# Push to your fork
git push origin feature/your-feature-name
```

Then open a Pull Request on GitHub!

---

## 🎨 Coding Standards

### PHP (Laravel/Backend)

- Follow **PSR-12** coding standards
- Use **Laravel Pint** for formatting: `./vendor/bin/pint`
- Write **type hints** for all parameters and return types
- Use **meaningful variable names** (no `$x`, `$temp`, etc.)
- Add **PHPDoc blocks** for complex methods
- Follow **Laravel best practices** and conventions

**Example:**
```php
public function store(StoreToolRequest $request): RedirectResponse
{
    $tool = Tool::create($request->validated());
    
    return redirect()->route('admin.tools.index')
        ->with('success', 'Tool created successfully.');
}
```

### JavaScript/TypeScript (Vue/Frontend)

- Use **TypeScript** for all new code
- Follow **Vue 3 Composition API** with `<script setup>`
- Use **ESLint** for linting: `npm run lint`
- Use **Prettier** for formatting: `npm run format`
- Use **meaningful component names** (PascalCase)
- Add **TypeScript types** for all props and emits

**Example:**
```vue
<script setup lang="ts">
import { ref } from 'vue'

interface Props {
  modelValue: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const localValue = ref(props.modelValue)
</script>
```

### CSS (Tailwind)

- Use **Tailwind utility classes** (don't write custom CSS unless absolutely necessary)
- Follow **mobile-first** approach
- Use **Reka UI components** for interactive elements
- Keep classes **organized and readable**

### Testing

- Write **Pest tests** for all new features
- Use **descriptive test names**: `it('allows admin to create a new tool', ...)`
- Test **both happy paths and edge cases**
- Aim for **high test coverage** (80%+)

---

## 💬 Commit Messages

Write clear, descriptive commit messages:

### Format

```
<type>: <subject>

<optional body>
```

### Types

- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code formatting (no functional changes)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

### Examples

**Good:**
```
feat: add search and filtering to tools directory

- Implement real-time search by name and description
- Add filters for category and pricing model
- Persist filter state in URL query parameters
```

**Also Good (simple changes):**
```
fix: correct logo path on login page
docs: update installation instructions
test: add tests for 2FA authentication flow
```

**Bad:**
```
updated stuff
fixes
WIP
asdfasdf
```

---

## 🔀 Pull Request Process

### Before Submitting

- ✅ Tests pass (`composer test`)
- ✅ Code is formatted (`npm run format`, `./vendor/bin/pint`)
- ✅ No linting errors (`npm run lint`)
- ✅ Documentation is updated (if needed)
- ✅ Commits are clean and descriptive

### PR Title and Description

**Title:** Clear and concise summary of changes
```
Add search and filtering to tools directory
```

**Description:** Explain what, why, and how
```markdown
## What
Implements search and filtering functionality for the tools directory.

## Why
Users need better ways to discover tools beyond browsing categories (#1).

## How
- Added real-time search input component
- Implemented filter dropdowns for category and pricing
- Used URL query parameters for shareable filtered views
- Added tests for search and filter logic

## Screenshots
[Include screenshots if UI changes]

## Related Issues
Closes #1
```

### Review Process

1. **Automated checks** will run (tests, linting)
2. **Maintainers will review** your code
3. **Address feedback** if requested
4. **Approval and merge** once everything looks good

### After Merge

- Your PR will be merged to `main`
- Changes will be deployed to production
- You'll be added to our contributors list! 🎉

---

## 📝 Issue Guidelines

### Creating Issues

Use our issue templates when available:
- **Bug Report** - For reporting bugs
- **Feature Request** - For suggesting new features
- **Documentation** - For docs improvements

### Issue Labels

We use labels to organize issues:
- **Type:** `feature`, `bug`, `enhancement`, `documentation`, `spike`
- **Priority:** `priority: high`, `priority: medium`, `priority: low`
- **Area:** `area: frontend`, `area: backend`, `area: admin`, `area: auth`
- **Status:** `good first issue`, `help wanted`

### Good First Issues

New to the project? Look for issues labeled [`good first issue`](https://github.com/rgriss/aimanifesto/labels/good%20first%20issue):
- Clearly defined scope
- Good introduction to the codebase
- Support available if you get stuck

---

## ❓ Questions?

### Get Help

- 💬 **Comment on an issue** - Ask questions directly on the issue
- 📧 **Email us** - Reach out to [Polaris Pixels](https://polarispixels.com)
- 📖 **Read the docs** - Check [CLAUDE.md](CLAUDE.md) for architecture details

### Stay Updated

- ⭐ **Star the repo** - Get notified of updates
- 👀 **Watch the repo** - See all activity
- 🗺️ **Follow the roadmap** - Check our [Project Board](https://github.com/rgriss/aimanifesto/projects/2)

---

## 🙏 Thank You!

Every contribution, no matter how small, makes a difference. Thank you for taking the time to contribute to AI Manifesto!

**Happy coding!** 🚀

---

<div align="center">

**[⬅️ Back to README](README.md)** • **[View Roadmap →](https://github.com/rgriss/aimanifesto/projects/2)**

</div>
