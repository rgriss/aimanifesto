# Contributing to AI Manifesto

Thank you for your interest in contributing to AI Manifesto! This document provides guidelines and instructions for contributing to the project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Pull Request Process](#pull-request-process)
- [Coding Standards](#coding-standards)
- [Commit Message Guidelines](#commit-message-guidelines)

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code. Please read [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before contributing.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the issue tracker to avoid duplicates. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the issue
- **Expected behavior** vs actual behavior
- **Screenshots** if applicable
- **Environment details** (OS, PHP version, Node version, browser, etc.)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title and description**
- **Use case** - why would this be useful?
- **Examples** from other projects (if applicable)

### Adding New Tools

To add a new AI tool to the directory:

1. Ensure the tool isn't already listed
2. Gather complete information: name, description, URL, category, pricing model, features, use cases
3. Submit via a pull request or create an issue with the tool details

### Code Contributions

We welcome code contributions! Areas where you can help:

- **New features** - Check open issues tagged with "enhancement"
- **Bug fixes** - Check issues tagged with "bug"
- **Documentation** - Improve README, CLAUDE.md, or inline code documentation
- **Tests** - Add or improve test coverage
- **UI/UX improvements** - Enhance the user interface or experience

## Development Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL or PostgreSQL

### Setup Instructions

1. **Fork and clone** the repository:
   ```bash
   git clone https://github.com/YOUR-USERNAME/aimanifesto.git
   cd aimanifesto
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env` file

5. **Run migrations**:
   ```bash
   php artisan migrate
   ```

6. **Start development server**:
   ```bash
   composer dev
   ```

## Pull Request Process

1. **Create a feature branch** from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes** following our coding standards

3. **Test your changes**:
   ```bash
   composer test           # Run PHP tests
   npm run lint           # Check JS/TS code
   npm run format:check   # Check formatting
   ```

4. **Commit your changes** with clear, descriptive commit messages

5. **Push to your fork**:
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Open a Pull Request** with:
   - Clear title describing the change
   - Detailed description of what changed and why
   - Reference to any related issues
   - Screenshots (for UI changes)

7. **Address review feedback** if requested

### PR Requirements

- All tests must pass
- Code must follow our coding standards
- No linting or formatting errors
- Documentation updated (if applicable)
- Commits should be clean and well-organized

## Coding Standards

### PHP

- Follow **PSR-12** coding standards
- Run Laravel Pint before committing:
  ```bash
  ./vendor/bin/pint
  ```
- Write **PHPDoc** comments for classes and methods
- Use **type hints** for parameters and return types

### JavaScript/TypeScript

- Follow the **ESLint** configuration
- Run linter and formatter:
  ```bash
  npm run lint      # Auto-fix issues
  npm run format    # Format code
  ```
- Use **TypeScript** types properly (avoid `any`)
- Use **Vue 3 Composition API** for new components
- Follow **component naming** conventions (PascalCase for components)

### CSS

- Use **Tailwind CSS** utility classes
- Follow the project's design system
- Avoid custom CSS unless absolutely necessary
- Use **Reka UI** components when available

### Testing

- Write **Pest tests** for new PHP features
- Test both happy paths and edge cases
- Aim for meaningful test coverage
- Use descriptive test names

## Commit Message Guidelines

We follow conventional commit format:

```
type(scope): subject

body (optional)

footer (optional)
```

### Types

- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, etc.)
- **refactor**: Code refactoring
- **test**: Adding or updating tests
- **chore**: Maintenance tasks

### Examples

```
feat(tools): add filtering by pricing model

fix(auth): resolve 2FA recovery code validation issue

docs(readme): update installation instructions

test(categories): add tests for category ordering
```

## Questions?

If you have questions about contributing, please:

- Check existing issues and discussions
- Email Ryan at **polarispixels.com**
- Open a discussion on GitHub

Thank you for contributing to AI Manifesto!
