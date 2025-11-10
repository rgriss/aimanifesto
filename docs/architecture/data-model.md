# Data Model & Database Schema

**Document Version:** 1.0
**Last Updated:** November 10, 2025
**Status:** Current
**Database:** MySQL 8.0+ / PostgreSQL 13+
**Related:** [System Overview](./system-overview.md), [Authentication Flow](./authentication-flow.md)

---

## Table of Contents

1. [Entity-Relationship Diagram](#entity-relationship-diagram)
2. [Core Entities](#core-entities)
3. [Authentication Tables](#authentication-tables)
4. [Supporting Tables](#supporting-tables)
5. [Indexes & Performance](#indexes--performance)
6. [JSON Field Structures](#json-field-structures)
7. [Data Validation Rules](#data-validation-rules)
8. [Migration History](#migration-history)
9. [Query Patterns](#query-patterns)
10. [Future Schema Changes](#future-schema-changes)

---

## Entity-Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     Core Domain Model                           │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────┐              ┌──────────────────────┐
│   categories     │              │       tools          │
├──────────────────┤              ├──────────────────────┤
│ id (PK)          │              │ id (PK)              │
│ name             │              │ category_id (FK)     │──┐
│ slug (UNIQUE)    │◄─────────────│ name                 │  │
│ description      │    1     *   │ slug (UNIQUE)        │  │
│ icon             │              │ description          │  │
│ sort_order       │              │ long_description     │  │
│ is_active        │              │ website_url          │  │
│ created_at       │              │ documentation_url    │  │
│ updated_at       │              │ logo_url             │  │
└──────────────────┘              │ pricing_model (ENUM) │  │
                                  │ price_description    │  │
                                  │ ryan_rating          │  │
                                  │ ryan_notes           │  │
                                  │ ryan_last_used       │  │
                                  │ features (JSON)      │  │
                                  │ use_cases (JSON)     │  │
                                  │ integrations (JSON)  │  │
                                  │ is_featured          │  │
                                  │ is_active            │  │
                                  │ views_count          │  │
                                  │ first_reviewed_at    │  │
                                  │ created_at           │  │
                                  │ updated_at           │  │
                                  └──────────────────────┘  │
                                                            │
                                                            │ ON DELETE CASCADE
                                                            │
┌──────────────────────────────────────────────────────────────────┐
│                    Authentication & Sessions                      │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────┐              ┌──────────────────────┐
│      users       │              │      sessions        │
├──────────────────┤              ├──────────────────────┤
│ id (PK)          │              │ id (PK)              │
│ name             │              │ user_id (FK)         │──┐
│ email (UNIQUE)   │◄─────────────│ ip_address           │  │
│ email_verified_at│    1     *   │ user_agent           │  │
│ password         │              │ payload (LONGTEXT)   │  │
│ remember_token   │              │ last_activity        │  │
│ is_admin         │              └──────────────────────┘  │
│ two_factor_secret│                                        │
│ two_factor_...   │                                        │
│ created_at       │                                        │
│ updated_at       │                                        │
└──────────────────┘                                        │
         │                                                  │
         │ 1                                                │
         │                                                  │
         │ *                                                │
         ▼                                                  │
┌──────────────────────────────┐                           │
│  password_reset_tokens       │                           │
├──────────────────────────────┤                           │
│ email (PK)                   │                           │
│ token                        │                           │
│ created_at                   │                           │
└──────────────────────────────┘                           │
                                                            │
┌──────────────────────────────────────────────────────────┘
│
│ ON DELETE SET NULL (soft link)
│

┌─────────────────────────────────────────────────────────────────┐
│                      Supporting Tables                          │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────┐    ┌──────────────────┐    ┌──────────────┐
│      cache       │    │   cache_locks    │    │     jobs     │
├──────────────────┤    ├──────────────────┤    ├──────────────┤
│ key (PK)         │    │ key (PK)         │    │ id (PK)      │
│ value            │    │ owner            │    │ queue        │
│ expiration       │    │ expiration       │    │ payload      │
└──────────────────┘    └──────────────────┘    │ attempts     │
                                                 │ ...          │
                                                 └──────────────┘
```

### Relationship Summary

| Relationship | Type | Foreign Key | On Delete |
|-------------|------|-------------|-----------|
| Category → Tools | One-to-Many | tools.category_id | CASCADE |
| User → Sessions | One-to-Many | sessions.user_id | SET NULL |
| User → Password Resets | One-to-Many | password_reset_tokens.email | CASCADE |

---

## Core Entities

### Categories Table

**Purpose:** Organize tools into logical groupings (e.g., "Code Assistants", "Image Generation").

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX categories_is_active_index (is_active)
);
```

**Fields:**

| Field | Type | Nullable | Default | Description |
|-------|------|----------|---------|-------------|
| `id` | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `name` | VARCHAR(255) | No | - | Display name (e.g., "Code Assistants") |
| `slug` | VARCHAR(255) | No | - | URL-friendly identifier (e.g., "code-assistants") |
| `description` | TEXT | Yes | NULL | Category description for SEO/display |
| `icon` | VARCHAR(255) | Yes | NULL | Emoji or icon identifier (e.g., "💻", "fa-code") |
| `sort_order` | INT | No | 0 | Display order (lower = first) |
| `is_active` | BOOLEAN | No | TRUE | Whether category is visible on site |
| `created_at` | TIMESTAMP | Yes | NULL | Record creation timestamp |
| `updated_at` | TIMESTAMP | Yes | NULL | Last update timestamp |

**Constraints:**
- `UNIQUE` on `slug` - Ensures URL uniqueness
- No foreign keys (top-level entity)

**Model Scopes:**
```php
// Category::active() - Only active categories
// Category::ordered() - Sort by sort_order ASC
```

**Example Data:**
```json
{
  "id": 1,
  "name": "Code Assistants",
  "slug": "code-assistants",
  "description": "AI-powered tools that help you write, review, and refactor code",
  "icon": "💻",
  "sort_order": 1,
  "is_active": true,
  "created_at": "2025-10-08 12:00:00",
  "updated_at": "2025-10-08 12:00:00"
}
```

---

### Tools Table

**Purpose:** Individual AI tools with detailed metadata, ratings, and personal notes.

```sql
CREATE TABLE tools (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,

    -- Basic Info
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    long_description TEXT NULL,

    -- Links & Resources
    website_url VARCHAR(255) NOT NULL,
    documentation_url VARCHAR(255) NULL,
    logo_url VARCHAR(255) NULL,

    -- Pricing
    pricing_model ENUM('free', 'freemium', 'paid', 'enterprise') NOT NULL DEFAULT 'freemium',
    price_description VARCHAR(255) NULL,

    -- Ryan's Personal Rating
    ryan_rating INT NULL,
    ryan_notes TEXT NULL,
    ryan_last_used DATE NULL,

    -- Technical Details (JSON)
    features JSON NULL,
    use_cases JSON NULL,
    integrations JSON NULL,

    -- Metadata
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    views_count INT NOT NULL DEFAULT 0,
    first_reviewed_at DATE NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    -- Foreign Keys
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,

    -- Indexes
    INDEX tools_category_id_index (category_id),
    INDEX tools_is_featured_index (is_featured),
    INDEX tools_ryan_rating_index (ryan_rating),
    INDEX tools_is_active_index (is_active)
);
```

**Fields:**

| Field | Type | Nullable | Default | Description |
|-------|------|----------|---------|-------------|
| `id` | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `category_id` | BIGINT UNSIGNED | No | - | Foreign key to categories |
| **Basic Info** |
| `name` | VARCHAR(255) | No | - | Tool name (e.g., "GitHub Copilot") |
| `slug` | VARCHAR(255) | No | - | URL-friendly identifier |
| `description` | TEXT | No | - | Short description (1-2 sentences) |
| `long_description` | TEXT | Yes | NULL | Detailed description (markdown supported) |
| **Links & Resources** |
| `website_url` | VARCHAR(255) | No | - | Official website URL |
| `documentation_url` | VARCHAR(255) | Yes | NULL | Docs URL |
| `logo_url` | VARCHAR(255) | Yes | NULL | Logo image URL |
| **Pricing** |
| `pricing_model` | ENUM | No | 'freemium' | One of: free, freemium, paid, enterprise |
| `price_description` | VARCHAR(255) | Yes | NULL | Human-readable pricing (e.g., "$20/mo") |
| **Personal Rating** |
| `ryan_rating` | INT | Yes | NULL | 1-10 scale rating |
| `ryan_notes` | TEXT | Yes | NULL | Personal commentary/review |
| `ryan_last_used` | DATE | Yes | NULL | Last time tool was used |
| **Technical Details (JSON)** |
| `features` | JSON | Yes | NULL | Array of key features |
| `use_cases` | JSON | Yes | NULL | Array of use cases |
| `integrations` | JSON | Yes | NULL | Array of integrations |
| **Metadata** |
| `is_featured` | BOOLEAN | No | FALSE | Show on homepage/featured section |
| `is_active` | BOOLEAN | No | TRUE | Whether visible on site |
| `views_count` | INT | No | 0 | Page view counter |
| `first_reviewed_at` | DATE | Yes | NULL | Date of first review |
| `created_at` | TIMESTAMP | Yes | NULL | Record creation |
| `updated_at` | TIMESTAMP | Yes | NULL | Last update |

**Constraints:**
- `UNIQUE` on `slug`
- `FOREIGN KEY` on `category_id` with `CASCADE DELETE`
- `ENUM` validation on `pricing_model`

**Model Scopes:**
```php
// Tool::active() - Only active tools
// Tool::featured() - Only featured tools
// Tool::highestRated() - Order by ryan_rating DESC
// Tool::inCategory($categoryId) - Filter by category
```

**Example Data:**
```json
{
  "id": 42,
  "category_id": 1,
  "name": "GitHub Copilot",
  "slug": "github-copilot",
  "description": "AI pair programmer that helps you write code faster",
  "long_description": "GitHub Copilot is an AI-powered code completion...",
  "website_url": "https://github.com/features/copilot",
  "documentation_url": "https://docs.github.com/copilot",
  "logo_url": "/storage/logos/copilot.png",
  "pricing_model": "paid",
  "price_description": "$10/month for individuals",
  "ryan_rating": 9,
  "ryan_notes": "Incredible for boilerplate. Works best with clear comments.",
  "ryan_last_used": "2025-11-09",
  "features": ["Code completion", "Chat interface", "CLI integration"],
  "use_cases": ["Writing boilerplate", "Learning new APIs", "Code review"],
  "integrations": ["VS Code", "JetBrains", "Neovim", "GitHub CLI"],
  "is_featured": true,
  "is_active": true,
  "views_count": 1542,
  "first_reviewed_at": "2025-08-15",
  "created_at": "2025-10-08 14:30:00",
  "updated_at": "2025-11-09 10:15:00"
}
```

---

## Authentication Tables

### Users Table

**Purpose:** User accounts with authentication credentials and settings.

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    two_factor_secret TEXT NULL,
    two_factor_recovery_codes TEXT NULL,
    two_factor_confirmed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX users_email_index (email)
);
```

**Fields:**

| Field | Type | Nullable | Default | Description |
|-------|------|----------|---------|-------------|
| `id` | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `name` | VARCHAR(255) | No | - | Full name |
| `email` | VARCHAR(255) | No | - | Email address (login identifier) |
| `email_verified_at` | TIMESTAMP | Yes | NULL | Email verification timestamp |
| `password` | VARCHAR(255) | No | - | Bcrypt hashed password |
| `remember_token` | VARCHAR(100) | Yes | NULL | "Remember me" token |
| `is_admin` | BOOLEAN | No | FALSE | Admin privileges flag |
| `two_factor_secret` | TEXT | Yes | NULL | Encrypted TOTP secret |
| `two_factor_recovery_codes` | TEXT | Yes | NULL | Encrypted JSON array of recovery codes |
| `two_factor_confirmed_at` | TIMESTAMP | Yes | NULL | When 2FA was enabled |
| `created_at` | TIMESTAMP | Yes | NULL | Account creation |
| `updated_at` | TIMESTAMP | Yes | NULL | Last update |

**Security Notes:**
- `password`: Always use `Hash::make()`, never store plaintext
- `two_factor_secret`: Encrypted via Laravel's encryption
- `two_factor_recovery_codes`: Hashed before storage, one-time use

---

### Sessions Table

**Purpose:** Store active user sessions (database driver).

```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,

    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);
```

**Fields:**

| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| `id` | VARCHAR(255) | No | Session identifier (random string) |
| `user_id` | BIGINT UNSIGNED | Yes | Foreign key to users (NULL if guest) |
| `ip_address` | VARCHAR(45) | Yes | Client IP address (IPv4 or IPv6) |
| `user_agent` | TEXT | Yes | Browser user agent string |
| `payload` | LONGTEXT | No | Serialized session data (encrypted) |
| `last_activity` | INT | No | Unix timestamp of last request |

**Payload Contents:**
- User ID (if authenticated)
- CSRF token
- Flash messages
- Previous/intended URLs
- 2FA challenge data (temporary)

**Garbage Collection:**
- Laravel automatically deletes sessions older than `session.lifetime` (120 minutes)
- Runs via `php artisan schedule:run` or on request

---

### Password Reset Tokens Table

**Purpose:** Temporary tokens for password reset functionality.

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);
```

**Fields:**

| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| `email` | VARCHAR(255) | No | User's email address (primary key) |
| `token` | VARCHAR(255) | No | Hashed reset token |
| `created_at` | TIMESTAMP | Yes | Token generation timestamp |

**Behavior:**
- Token expires after 60 minutes (configurable)
- One token per email (new request overwrites old)
- Token deleted after successful password reset
- Token hashed for security (using `Hash::make()`)

---

## Supporting Tables

### Cache Table

**Purpose:** Store cached data (if using database cache driver).

```sql
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
);
```

**Usage:**
- Query result caching
- View caching
- Config caching
- Atomic lock operations

---

### Jobs Table

**Purpose:** Queue background jobs (email sending, exports, etc.).

```sql
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,

    INDEX jobs_queue_index (queue)
);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
);

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

---

## Indexes & Performance

### Primary Keys

All tables use `BIGINT UNSIGNED AUTO_INCREMENT` primary keys for:
- Efficient joins
- Scalability (up to 18 quintillion records)
- Consistency across tables

### Unique Indexes

| Table | Column | Purpose |
|-------|--------|---------|
| `categories` | `slug` | URL routing, uniqueness |
| `tools` | `slug` | URL routing, uniqueness |
| `users` | `email` | Login identifier, prevent duplicates |

### Foreign Key Indexes

| Table | Column | References | On Delete |
|-------|--------|------------|-----------|
| `tools` | `category_id` | `categories.id` | CASCADE |
| `sessions` | `user_id` | `users.id` | SET NULL |

### Query Optimization Indexes

**tools table:**
```sql
INDEX tools_category_id_index (category_id)        -- Filter by category
INDEX tools_is_featured_index (is_featured)        -- Homepage queries
INDEX tools_ryan_rating_index (ryan_rating)        -- Sorting by rating
INDEX tools_is_active_index (is_active)            -- Filter active tools
```

**sessions table:**
```sql
INDEX sessions_user_id_index (user_id)             -- Find user sessions
INDEX sessions_last_activity_index (last_activity) -- Garbage collection
```

### Composite Indexes (Future Optimization)

```sql
-- If filtering active tools by category becomes common:
CREATE INDEX tools_active_category ON tools(is_active, category_id);

-- If searching by name frequently:
CREATE FULLTEXT INDEX tools_name_description ON tools(name, description);
```

---

## JSON Field Structures

### tools.features

**Type:** Array of strings
**Purpose:** List of key features/capabilities

```json
[
  "Code completion",
  "Multi-language support",
  "Context-aware suggestions",
  "Inline documentation"
]
```

**Validation:**
```php
'features' => 'nullable|array|max:20',
'features.*' => 'string|max:100'
```

---

### tools.use_cases

**Type:** Array of strings
**Purpose:** Practical scenarios where tool excels

```json
[
  "Writing boilerplate code",
  "Learning new APIs",
  "Refactoring legacy code",
  "Pair programming sessions"
]
```

**Validation:**
```php
'use_cases' => 'nullable|array|max:20',
'use_cases.*' => 'string|max:150'
```

---

### tools.integrations

**Type:** Array of strings
**Purpose:** Platforms/tools it integrates with

```json
[
  "VS Code",
  "JetBrains IDEs",
  "Neovim",
  "GitHub CLI",
  "GitLab"
]
```

**Validation:**
```php
'integrations' => 'nullable|array|max:30',
'integrations.*' => 'string|max:100'
```

---

### users.two_factor_recovery_codes

**Type:** Array of hashed strings (encrypted at storage level)
**Purpose:** One-time use backup codes for 2FA

```json
[
  "$2y$10$abcd...",  // Hashed version of "HXf9k2j4nP-QZ3mL7wRt1v"
  "$2y$10$efgh...",  // Hashed version of "TY6pN8vKq2-LM9fJ4hS5xW"
  // ... 8 more codes
]
```

**Properties:**
- 10 codes generated initially
- Each code usable once
- Removed from array after use
- Entire field encrypted via Laravel's encryption
- User warned when < 3 codes remain

---

## Data Validation Rules

### Category Validation

```php
[
    'name' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:categories,slug',
    'description' => 'nullable|string|max:1000',
    'icon' => 'nullable|string|max:255',
    'sort_order' => 'integer|min:0',
    'is_active' => 'boolean',
]
```

### Tool Validation (Create/Update)

```php
[
    'category_id' => 'required|exists:categories,id',
    'name' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:tools,slug',
    'description' => 'required|string|max:500',
    'long_description' => 'nullable|string|max:5000',
    'website_url' => 'required|url|max:500',
    'documentation_url' => 'nullable|url|max:500',
    'logo_url' => 'nullable|url|max:500',
    'pricing_model' => 'required|in:free,freemium,paid,enterprise',
    'price_description' => 'nullable|string|max:255',
    'ryan_rating' => 'nullable|integer|min:1|max:10',
    'ryan_notes' => 'nullable|string|max:5000',
    'ryan_last_used' => 'nullable|date',
    'features' => 'nullable|array|max:20',
    'features.*' => 'string|max:100',
    'use_cases' => 'nullable|array|max:20',
    'use_cases.*' => 'string|max:150',
    'integrations' => 'nullable|array|max:30',
    'integrations.*' => 'string|max:100',
    'is_featured' => 'boolean',
    'is_active' => 'boolean',
]
```

### User Validation (Registration)

```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users,email',
    'password' => 'required|string|min:8|confirmed',
]
```

---

## Migration History

| Date | Migration | Description |
|------|-----------|-------------|
| 2025-08-14 | `create_users_table` | User authentication base |
| 2025-08-14 | `create_cache_table` | Cache storage |
| 2025-08-14 | `create_jobs_table` | Queue system |
| 2025-08-14 | `add_two_factor_columns_to_users_table` | 2FA support |
| 2025-10-08 | `create_categories_table` | Tool categories |
| 2025-10-08 | `create_tools_table` | AI tools directory |
| 2025-10-09 | `add_is_admin_to_users_table` | Admin authorization |

**Running Migrations:**
```bash
php artisan migrate              # Run pending migrations
php artisan migrate:fresh        # Drop all tables and re-run
php artisan migrate:refresh      # Rollback and re-run
php artisan migrate:rollback     # Undo last batch
```

---

## Query Patterns

### Common Queries

**Get all active tools in a category:**
```php
$tools = Tool::where('category_id', $categoryId)
    ->where('is_active', true)
    ->with('category')
    ->get();
```

**Get featured tools with category:**
```php
$featured = Tool::where('is_featured', true)
    ->where('is_active', true)
    ->with('category')
    ->orderBy('views_count', 'desc')
    ->limit(6)
    ->get();
```

**Get highest rated tools:**
```php
$topTools = Tool::whereNotNull('ryan_rating')
    ->where('is_active', true)
    ->with('category')
    ->orderBy('ryan_rating', 'desc')
    ->paginate(24);
```

**Increment view count (atomic):**
```php
$tool->increment('views_count');
// Generates: UPDATE tools SET views_count = views_count + 1 WHERE id = ?
```

**Search tools by name/description (future):**
```sql
SELECT * FROM tools
WHERE (name LIKE '%copilot%' OR description LIKE '%copilot%')
AND is_active = TRUE;
```

### N+1 Query Prevention

**❌ Bad (N+1 problem):**
```php
$tools = Tool::all(); // 1 query
foreach ($tools as $tool) {
    echo $tool->category->name; // N queries!
}
```

**✅ Good (Eager loading):**
```php
$tools = Tool::with('category')->get(); // 2 queries total
foreach ($tools as $tool) {
    echo $tool->category->name; // No additional queries
}
```

---

## Future Schema Changes

### Phase 2: API Endpoints

**Add to users table:**
```sql
ALTER TABLE users ADD COLUMN api_token VARCHAR(80) NULL UNIQUE AFTER is_admin;
```

Or use `personal_access_tokens` table (Laravel Sanctum):
```sql
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX personal_access_tokens_tokenable_index (tokenable_type, tokenable_id)
);
```

### Phase 3: Community Submissions

**New table: tool_submissions**
```sql
CREATE TABLE tool_submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    website_url VARCHAR(255) NOT NULL,
    pricing_model ENUM('free', 'freemium', 'paid', 'enterprise') NOT NULL,
    features JSON NULL,
    use_cases JSON NULL,
    integrations JSON NULL,
    rejection_reason TEXT NULL,
    approved_by BIGINT UNSIGNED NULL,
    submitted_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,

    INDEX tool_submissions_status_index (status),
    INDEX tool_submissions_user_id_index (user_id)
);
```

### Future: Analytics & Tracking

**tool_views table (detailed analytics):**
```sql
CREATE TABLE tool_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tool_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    referrer VARCHAR(500) NULL,
    viewed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (tool_id) REFERENCES tools(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,

    INDEX tool_views_tool_id_index (tool_id),
    INDEX tool_views_viewed_at_index (viewed_at)
);
```

### Future: Tags System (Many-to-Many)

```sql
CREATE TABLE tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE tool_tag (
    tool_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (tool_id, tag_id),
    FOREIGN KEY (tool_id) REFERENCES tools(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
```

---

## Database Backups & Snapshots

### Automated Backups

**Production (recommended):**
```bash
# Daily automated backup via cron
0 2 * * * mysqldump -u user -p database > /backups/daily/db_$(date +\%Y\%m\%d).sql
```

### Content Snapshots (Phase 1)

**JSON exports of tools/categories:**
```bash
php artisan content:export
# Creates: database/content/snapshots/snapshot-YYYY-MM-DD.json
```

**Benefits:**
- Version controlled content
- Rollback capability
- Sync between environments
- Audit trail

---

## Related Documentation

- [System Overview](./system-overview.md) - Architecture patterns
- [Authentication Flow](./authentication-flow.md) - Auth implementation
- [../TOOL_LIST_MAINTENANCE_SYSTEM.md](../TOOL_LIST_MAINTENANCE_SYSTEM.md) - Export/Import system
- [../../CLAUDE.md](../../CLAUDE.md) - Quick reference

---

**Document Maintenance:**
- Update after each migration
- Review quarterly for optimization opportunities
- Document new indexes when added
- Keep query patterns up to date
