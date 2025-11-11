# System Architecture Overview

**Document Version:** 1.1
**Last Updated:** November 11, 2025
**Status:** Current
**Audience:** Developers, Architects, Technical Stakeholders

---

## Table of Contents

1. [High-Level Architecture](#high-level-architecture)
2. [Technology Stack Rationale](#technology-stack-rationale)
3. [Request Lifecycle](#request-lifecycle)
4. [Data Flow](#data-flow)
5. [Frontend Architecture](#frontend-architecture)
6. [Backend Architecture](#backend-architecture)
7. [Authentication & Authorization](#authentication--authorization)
8. [State Management](#state-management)
9. [Build & Deployment](#build--deployment)
10. [Scalability Considerations](#scalability-considerations)
11. [Security Model](#security-model)
12. [Performance Optimizations](#performance-optimizations)

---

## High-Level Architecture

### System Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                         Client Browser                          │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │              Vue 3 Application (SPA-like)                 │  │
│  │                                                            │  │
│  │  ┌──────────┐  ┌──────────┐  ┌────────────┐             │  │
│  │  │  Pages   │  │ Layouts  │  │ Components │             │  │
│  │  └──────────┘  └──────────┘  └────────────┘             │  │
│  │                                                            │  │
│  │  ┌──────────────────────────────────────────────────┐    │  │
│  │  │         Inertia.js Client Adapter                │    │  │
│  │  └──────────────────────────────────────────────────┘    │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ HTTP/HTTPS
                              │ (Inertia Protocol)
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Laravel Application                        │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    HTTP Layer                            │  │
│  │  ┌───────────┐  ┌──────────┐  ┌──────────────┐         │  │
│  │  │   Routes  │→ │Middleware│→ │ Controllers  │         │  │
│  │  └───────────┘  └──────────┘  └──────────────┘         │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │              Inertia.js Server Adapter                   │  │
│  │         (Renders Vue pages + shares data)                │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                  Business Logic Layer                    │  │
│  │  ┌───────────┐  ┌────────┐  ┌────────────┐             │  │
│  │  │  Models   │  │ Services│  │  Commands  │             │  │
│  │  └───────────┘  └────────┘  └────────────┘             │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │              Authentication (Fortify)                    │  │
│  │         Session-Based + 2FA + Email Verification         │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ Eloquent ORM
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MySQL/PostgreSQL                           │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │    users     │  │  categories  │  │    tools     │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘

                    Supporting Infrastructure
                              │
        ┌─────────────────────┼──────────────────────┐
        │                     │                      │
        ▼                     ▼                      ▼
┌──────────────┐     ┌──────────────┐      ┌──────────────┐
│ Queue Worker │     │  File System │      │  Cache/Redis │
│  (Laravel)   │     │  (Uploads)   │      │  (Sessions)  │
└──────────────┘     └──────────────┘      └──────────────┘
```

### Architecture Pattern: Modern Monolith

AI Manifesto follows the **"Modern Monolith"** pattern using Inertia.js:

- **Single Codebase** - Backend and frontend live together
- **SPA Experience** - Client-side navigation without page reloads
- **Server-Side Rendering** - Initial page load rendered by Laravel
- **Type-Safe Routes** - Laravel Wayfinder generates TypeScript route helpers
- **No API Layer Needed** - Inertia acts as the bridge between Laravel and Vue

**Why this pattern?**
- Simpler than separate API + SPA
- Faster development velocity
- Single deployment target
- Easier to maintain
- SEO-friendly with SSR support

---

## Technology Stack Rationale

### Backend: Laravel 12

**Why Laravel?**
- ✅ **Mature ecosystem** - Battle-tested framework with 10+ years of development
- ✅ **Developer productivity** - Artisan commands, Eloquent ORM, migrations
- ✅ **Security built-in** - CSRF protection, SQL injection prevention, XSS filtering
- ✅ **Authentication** - Laravel Fortify provides robust auth with 2FA
- ✅ **Testing** - First-class support for Pest and PHPUnit
- ✅ **Documentation** - Excellent official documentation

**Alternatives considered:**
- Symfony: More complex, steeper learning curve
- CodeIgniter: Less feature-rich
- Raw PHP: Too much boilerplate

### Frontend: Vue 3 + TypeScript

**Why Vue 3?**
- ✅ **Composition API** - Better TypeScript support, more reusable logic
- ✅ **Progressive** - Can be adopted incrementally
- ✅ **Performance** - Virtual DOM with optimized reactivity
- ✅ **Developer experience** - Single-file components, hot reload
- ✅ **Ecosystem** - Rich component libraries (Reka UI, Vite, etc.)

**Why TypeScript?**
- ✅ **Type safety** - Catch errors at compile time
- ✅ **IntelliSense** - Better IDE support
- ✅ **Refactoring** - Safer code changes
- ✅ **Documentation** - Types serve as inline documentation

**Alternatives considered:**
- React: More boilerplate, JSX mixing concerns
- Svelte: Smaller ecosystem, less mature
- Vanilla JavaScript: No type safety

### Bridge: Inertia.js

**Why Inertia?**
- ✅ **No API needed** - Direct controller-to-page data flow
- ✅ **SPA experience** - Client-side routing without AJAX boilerplate
- ✅ **SSR support** - SEO-friendly initial page loads
- ✅ **Simple mental model** - Think of Vue pages as Blade templates
- ✅ **Type safety** - Works seamlessly with TypeScript

**Alternatives considered:**
- Traditional MPA (Blade only): Poor UX, full page reloads
- REST API + SPA: More complex, requires API versioning
- GraphQL: Overkill for this use case
- Livewire: Limited to PHP, no Vue

### Styling: Tailwind CSS v4

**Why Tailwind v4?**
- ✅ **Utility-first** - Rapid UI development
- ✅ **Design system** - Consistent spacing, colors, typography
- ✅ **Performance** - New CSS-based engine (no JavaScript runtime)
- ✅ **Purge built-in** - Automatically removes unused styles
- ✅ **Responsive** - Mobile-first utilities

**Why Reka UI?**
- ✅ **Unstyled primitives** - Full design control
- ✅ **Accessibility** - WAI-ARIA compliant components
- ✅ **Radix-like** - Familiar API from React ecosystem
- ✅ **Composable** - Build complex components from primitives

**Design Philosophy: Minimalism & High Contrast**
- ✅ **Black & White** - Primary palette of near-black (#1A1A1A) and pure white (#FFFFFF)
- ✅ **Semantic Color Only** - Reserved for functional purposes (badges: success, info, warning, danger)
- ✅ **High Contrast** - WCAG AA compliant (4.5:1 minimum for text)
- ✅ **Clean & Unbusy** - Reduced visual complexity for better focus
- ✅ **Theme Support** - Full light/dark mode with system preference detection

**Alternatives considered:**
- Bootstrap: Too opinionated, harder to customize
- Custom CSS: More maintainability burden
- Styled components: Not idiomatic for Vue

### Build Tool: Vite 7

**Why Vite?**
- ✅ **Lightning fast** - Native ESM, no bundling in dev
- ✅ **Hot module reload** - Instant updates
- ✅ **Production optimized** - Rollup-based builds
- ✅ **TypeScript native** - No additional config
- ✅ **Laravel integration** - First-class Laravel plugin

**Alternatives considered:**
- Webpack: Slower, more complex configuration
- Parcel: Less ecosystem support
- esbuild: Less mature for large apps

---

## Request Lifecycle

### 1. Traditional Page Load (SSR)

```
User visits /tools
    │
    ├─→ Browser requests HTML from Laravel
    │
    ├─→ Laravel Router matches route
    │
    ├─→ Middleware stack executes
    │       ├─→ HandleInertiaRequests (shares global data)
    │       ├─→ HandleAppearance (theme management)
    │       └─→ Auth middleware (if protected)
    │
    ├─→ Controller method executes
    │       └─→ Inertia::render('Tools/Index', ['tools' => $tools])
    │
    ├─→ Inertia Server Adapter:
    │       ├─→ Serializes data to JSON
    │       ├─→ Renders SSR HTML (if enabled)
    │       └─→ Returns HTML with embedded JSON
    │
    ├─→ Browser receives HTML
    │       ├─→ Vue app hydrates from JSON
    │       └─→ Becomes interactive SPA
    │
    └─→ User sees fully rendered page
```

### 2. Inertia Navigation (Client-Side)

```
User clicks link to /tools/github-copilot
    │
    ├─→ Inertia intercepts click (prevents default)
    │
    ├─→ XHR request with headers:
    │       X-Inertia: true
    │       X-Inertia-Version: <asset-version>
    │
    ├─→ Laravel processes normally (same route/controller)
    │
    ├─→ Inertia Server Adapter returns JSON (not HTML):
    │       {
    │         "component": "Tools/Show",
    │         "props": { "tool": {...} },
    │         "url": "/tools/github-copilot",
    │         "version": "..."
    │       }
    │
    ├─→ Inertia Client receives JSON
    │       ├─→ Swaps Vue component
    │       ├─→ Updates browser history
    │       └─→ Preserves scroll position
    │
    └─→ User sees new page (no full reload!)
```

### 3. Form Submission (Inertia)

```
User submits tool creation form
    │
    ├─→ Inertia form helper intercepts
    │
    ├─→ POST request with data + headers:
    │       X-Inertia: true
    │       X-CSRF-TOKEN: <token>
    │
    ├─→ Laravel validation runs
    │       ├─→ Success: redirect() with flash message
    │       └─→ Failure: back()->withErrors()
    │
    ├─→ Inertia handles response:
    │       ├─→ Redirect: fetch new page data
    │       └─→ Errors: update form state reactively
    │
    └─→ User sees result (success or errors)
```

---

## Data Flow

### Read Path: Viewing a Tool

```
┌──────────┐
│  User    │ Clicks "GitHub Copilot"
└────┬─────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Vue Router (Inertia)                    │
│  Intercepts click, makes XHR request     │
└────┬─────────────────────────────────────┘
     │ GET /tools/github-copilot
     │ X-Inertia: true
     ▼
┌──────────────────────────────────────────┐
│  Laravel Route                           │
│  Route::get('/tools/{tool:slug}')        │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Middleware Stack                        │
│  ├─→ HandleInertiaRequests               │
│  │   ├─→ Shares $page.props.auth.user   │
│  │   ├─→ Shares $page.props.quote       │
│  │   └─→ Shares $page.props.sidebarOpen │
│  └─→ IncrementToolViews (increments)    │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  ToolController@show                     │
│  $tool = Tool::with('category')->find()  │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Database Query                          │
│  SELECT * FROM tools                     │
│  JOIN categories ON ...                  │
│  WHERE slug = 'github-copilot'           │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Eloquent Model                          │
│  Tool instance with category relationship│
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Controller Returns                      │
│  Inertia::render('Tools/Show', [         │
│    'tool' => $tool                       │
│  ])                                      │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Inertia Server Adapter                  │
│  Serializes to JSON:                     │
│  {                                       │
│    "component": "Tools/Show",            │
│    "props": {                            │
│      "tool": {...},                      │
│      "auth": {...},                      │
│      "quote": {...}                      │
│    }                                     │
│  }                                       │
└────┬─────────────────────────────────────┘
     │ JSON Response
     ▼
┌──────────────────────────────────────────┐
│  Inertia Client (Browser)                │
│  ├─→ Swaps component to Tools/Show.vue   │
│  └─→ Passes props to component           │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Vue Component (Tools/Show.vue)          │
│  ├─→ Receives props                      │
│  ├─→ Renders template                    │
│  └─→ User sees tool details              │
└──────────────────────────────────────────┘
```

### Write Path: Creating a Tool

```
┌──────────┐
│ Admin    │ Submits tool creation form
└────┬─────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Vue Form Component                      │
│  useForm({ name: 'Cursor', ... })        │
└────┬─────────────────────────────────────┘
     │ form.post(route('admin.tools.store'))
     │
     ▼
┌──────────────────────────────────────────┐
│  Inertia Client                          │
│  POST request with form data + CSRF      │
└────┬─────────────────────────────────────┘
     │ POST /admin/tools
     │ X-Inertia: true
     │ X-CSRF-TOKEN: xxx
     ▼
┌──────────────────────────────────────────┐
│  Laravel Route                           │
│  Route::post('/admin/tools')             │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  Middleware Stack                        │
│  ├─→ CSRF Protection                     │
│  ├─→ Auth (must be authenticated)        │
│  └─→ Admin (must have admin role)        │
└────┬─────────────────────────────────────┘
     │
     ▼
┌──────────────────────────────────────────┐
│  ToolController@store                    │
│  Validates with StoreToolRequest         │
└────┬─────────────────────────────────────┘
     │
     ├──✗ Validation fails
     │   │
     │   └─→ back()->withErrors()
     │       └─→ Inertia returns errors to form
     │
     └──✓ Validation passes
         │
         ▼
    ┌──────────────────────────────────────┐
    │  Business Logic                      │
    │  $tool = Tool::create($validated)    │
    └────┬─────────────────────────────────┘
         │
         ▼
    ┌──────────────────────────────────────┐
    │  Database Transaction                │
    │  INSERT INTO tools (...) VALUES (...) │
    └────┬─────────────────────────────────┘
         │
         ▼
    ┌──────────────────────────────────────┐
    │  Redirect with Success Message       │
    │  redirect()                          │
    │    ->route('admin.tools.index')      │
    │    ->with('success', 'Tool created') │
    └────┬─────────────────────────────────┘
         │ 303 See Other (redirect)
         ▼
    ┌──────────────────────────────────────┐
    │  Inertia Client                      │
    │  ├─→ Follows redirect                │
    │  ├─→ Fetches new page data           │
    │  └─→ Shows success toast             │
    └──────────────────────────────────────┘
```

---

## Frontend Architecture

### Component Hierarchy

```
App Root (app.ts)
│
├─→ Layouts/
│   │
│   ├─→ AppLayout.vue (Main application layout)
│   │   ├─→ AppSidebarLayout.vue
│   │   │   └─→ Sidebar components
│   │   ├─→ AppHeaderLayout.vue
│   │   │   └─→ Header/navigation
│   │   └─→ <slot> (Page content)
│   │
│   ├─→ AuthLayout.vue (Authentication pages)
│   │   └─→ AuthCardLayout.vue
│   │       └─→ Centered card wrapper
│   │
│   └─→ settings/Layout.vue (Settings pages)
│       ├─→ Settings navigation
│       └─→ <slot> (Settings content)
│
└─→ Pages/
    │
    ├─→ Home.vue
    ├─→ Dashboard.vue
    │
    ├─→ Tools/
    │   ├─→ Index.vue (Tool directory)
    │   ├─→ Show.vue (Single tool)
    │   ├─→ Create.vue (Admin: create tool)
    │   └─→ Edit.vue (Admin: edit tool)
    │
    ├─→ Categories/
    │   ├─→ Index.vue
    │   └─→ Show.vue
    │
    ├─→ auth/
    │   ├─→ Login.vue
    │   ├─→ Register.vue
    │   ├─→ ForgotPassword.vue
    │   └─→ TwoFactorChallenge.vue
    │
    └─→ settings/
        ├─→ Profile.vue
        ├─→ Password.vue
        ├─→ TwoFactor.vue
        └─→ Appearance.vue
```

### Component Communication

**1. Props Down**
```vue
<!-- Parent passes data to child -->
<ToolCard :tool="tool" :featured="true" />
```

**2. Events Up**
```vue
<!-- Child emits events to parent -->
<SearchInput @search="handleSearch" />
```

**3. Composables (Shared Logic)**
```typescript
// useAppearance.ts - Theme management
const { appearance, setAppearance } = useAppearance()

// useTwoFactorAuth.ts - 2FA logic
const { qrCode, recoveryCodes, enable, disable } = useTwoFactorAuth()
```

**4. Inertia Shared Data (Global State)**
```vue
<!-- Available in all components via $page.props -->
{{ $page.props.auth.user.name }}
{{ $page.props.quote.message }}
```

### Reactive State Patterns

**Local Component State (ref/reactive)**
```typescript
// For component-local data
const isOpen = ref(false)
const formData = reactive({
  name: '',
  email: ''
})
```

**Form State (Inertia useForm)**
```typescript
// For forms that submit to Laravel
const form = useForm({
  name: '',
  description: ''
})

form.post(route('admin.tools.store'))
```

**Computed State**
```typescript
// Derived state
const fullName = computed(() =>
  `${user.firstName} ${user.lastName}`
)
```

**Composables (Reusable Logic)**
```typescript
// Shared between multiple components
export function useToolFilters() {
  const category = ref(null)
  const pricingModel = ref(null)

  const filteredTools = computed(() => {
    // Filter logic
  })

  return { category, pricingModel, filteredTools }
}
```

---

## Backend Architecture

### Directory Structure

```
app/
├── Console/
│   └── Commands/          # Artisan commands
│       ├── ContentExport.php
│       └── ContentImport.php
│
├── Http/
│   ├── Controllers/       # Request handlers
│   │   ├── ToolController.php
│   │   ├── CategoryController.php
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── Settings/
│   │   │   ├── ProfileController.php
│   │   │   └── PasswordController.php
│   │   └── Admin/
│   │       ├── ToolController.php
│   │       └── CategoryController.php
│   │
│   ├── Middleware/        # Request/response filters
│   │   ├── HandleInertiaRequests.php
│   │   ├── HandleAppearance.php
│   │   └── AdminMiddleware.php
│   │
│   └── Requests/          # Form validation
│       ├── StoreToolRequest.php
│       └── UpdateToolRequest.php
│
├── Models/                # Eloquent ORM models
│   ├── User.php
│   ├── Tool.php
│   └── Category.php
│
├── Providers/             # Service container bindings
│   └── AppServiceProvider.php
│
└── Services/              # Business logic (future)
    └── ToolSyncService.php
```

### Model Relationships

```php
// Category → Tools (One-to-Many)
class Category extends Model {
    public function tools() {
        return $this->hasMany(Tool::class);
    }
}

// Tool → Category (Belongs To)
class Tool extends Model {
    public function category() {
        return $this->belongsTo(Category::class);
    }
}

// User → Sessions (One-to-Many)
// User → TwoFactorAuthentication (Fortify trait)
```

### Route Organization

**Separated by Purpose:**

```php
// routes/web.php - Public routes
Route::get('/', HomeController::class);
Route::get('/tools', [ToolController::class, 'index']);
Route::get('/tools/{tool:slug}', [ToolController::class, 'show']);

// routes/auth.php - Authentication routes
Route::get('/login', [LoginController::class, 'create']);
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy']);

// routes/settings.php - User settings
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'edit']);
    Route::patch('/settings/profile', [ProfileController::class, 'update']);
});

// routes/admin.php - Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('tools', Admin\ToolController::class);
    Route::resource('categories', Admin\CategoryController::class);
});
```

### Middleware Stack

```
Request
  │
  ├─→ TrustProxies
  ├─→ HandleCors
  ├─→ ValidatePostSize
  ├─→ TrimStrings
  ├─→ ConvertEmptyStringsToNull
  │
  ├─→ Web Middleware Group:
  │   ├─→ EncryptCookies
  │   ├─→ AddQueuedCookiesToResponse
  │   ├─→ StartSession
  │   ├─→ ShareErrorsFromSession
  │   ├─→ VerifyCsrfToken
  │   ├─→ SubstituteBindings
  │   ├─→ HandleInertiaRequests      ← Shares global data
  │   └─→ HandleAppearance            ← Theme management
  │
  ├─→ Route-Specific Middleware:
  │   ├─→ auth (if required)
  │   ├─→ admin (if admin route)
  │   └─→ verified (if email verification required)
  │
  └─→ Controller
```

---

## Authentication & Authorization

### Authentication Flow (Laravel Fortify)

```
┌──────────────────────────────────────────────────────────────┐
│                    Login Process                             │
└──────────────────────────────────────────────────────────────┘

User submits credentials
    │
    ▼
POST /login (LoginController)
    │
    ├─→ Validate email + password
    │
    ├─→ Fortify::authenticate()
    │   ├─→ Find user by email
    │   ├─→ Verify password (bcrypt)
    │   └─→ Check if account active
    │
    ├─→ Two-Factor Authentication?
    │   │
    │   ├─→ YES: Store 2FA session
    │   │        Redirect to /two-factor-challenge
    │   │        User enters code
    │   │        Verify TOTP code
    │   │        Create session
    │   │
    │   └─→ NO:  Create session immediately
    │
    └─→ Redirect to intended page or dashboard
```

### Authorization (Gates & Policies)

**Not currently implemented, but structure ready:**

```php
// Future: app/Policies/ToolPolicy.php
class ToolPolicy {
    public function update(User $user, Tool $tool) {
        return $user->is_admin;
    }

    public function delete(User $user, Tool $tool) {
        return $user->is_admin;
    }
}

// Usage in controller:
$this->authorize('update', $tool);
```

### Session Management

- **Driver:** Database (stored in `sessions` table)
- **Lifetime:** 120 minutes (config/session.php)
- **Cookie Name:** `aimanifesto_session`
- **Encryption:** All cookies encrypted via EncryptCookies middleware
- **CSRF Protection:** Token validated on all POST/PUT/DELETE requests

---

## State Management

### Server-Side State (Laravel)

**Database (Source of Truth)**
- User sessions
- Tool/Category data
- User profiles

**Cache (Future)**
- Not currently implemented
- Planned: Redis for session storage
- Planned: Query result caching

**Cookies**
- `appearance` - Theme preference (light/dark/system)
- `sidebarOpen` - Sidebar state (true/false)
- Session cookies (Laravel Fortify)

### Client-Side State (Vue)

**Inertia Shared Props (Global, Read-Only)**
```javascript
$page.props.auth.user       // Current user
$page.props.name            // App name
$page.props.quote           // Inspirational quote
$page.props.sidebarOpen     // Sidebar state from cookie
```

**Component Local State**
```javascript
// Each component manages its own state
const isOpen = ref(false)
const searchQuery = ref('')
```

**Form State (Inertia)**
```javascript
// Managed by Inertia's useForm helper
const form = useForm({
  name: '',
  email: ''
})
```

**Theme State (useAppearance Composable)**
```javascript
// Synced with cookie + system preference
const appearance = ref('system') // 'light' | 'dark' | 'system'
```

### State Synchronization

**Cookie → Server → Client Flow:**
```
1. User changes theme preference
2. Vue updates cookie: document.cookie = 'appearance=dark'
3. Next Inertia request includes cookie
4. HandleAppearance middleware reads cookie
5. Middleware shares to Blade: @vite(['resources/js/app.ts'], $appearance)
6. Blade template applies class to <html>
7. Vue reads initial state from cookie on mount
```

### Theme System Architecture

**CSS Custom Properties (Theme Tokens):**
```css
/* resources/css/app.css */
:root {
    /* Light Mode */
    --background: oklch(1 0 0);           /* Pure white #FFFFFF */
    --foreground: oklch(0.15 0 0);        /* Near black #1A1A1A */
    --primary: oklch(0.15 0 0);           /* Near black #1A1A1A */
    --secondary: oklch(0.95 0 0);         /* Light gray #F0F0F0 */
    --muted: oklch(0.95 0 0);             /* Light gray #F0F0F0 */
    --muted-foreground: oklch(0.5 0 0);   /* Medium gray #737373 */
    --border: oklch(0.88 0 0);            /* Border gray #E0E0E0 */

    /* Semantic colors (badges only) */
    --success: oklch(0.7 0.15 180);       /* Teal */
    --info: oklch(0.7 0.16 210);          /* Cyan */
    --warning: oklch(0.75 0.15 70);       /* Amber */
    --danger: oklch(0.63 0.24 25);        /* Red */
}

.dark {
    /* Dark Mode */
    --background: oklch(0.12 0 0);        /* Near black #1A1A1A */
    --foreground: oklch(0.98 0 0);        /* Off-white #F5F5F5 */
    --card: oklch(0.18 0 0);              /* Dark gray #262626 */
    --secondary: oklch(0.25 0 0);         /* Dark gray #333333 */
    --border: oklch(0.3 0 0);             /* Border gray #404040 */
}
```

**Theme Management Components:**
1. **HandleAppearance Middleware** (app/Http/Middleware/HandleAppearance.php)
   - Reads `appearance` cookie
   - Shares theme to Blade templates
   - Applies `.dark` class to `<html>` element server-side

2. **useAppearance Composable** (resources/js/composables/useAppearance.ts)
   - Manages theme state in Vue
   - Syncs with cookie on change
   - Detects system preference when set to 'system'

3. **ThemeToggle Component** (resources/js/components/ThemeToggle.vue)
   - User-facing toggle in footer
   - Switches between light/dark (bypasses 'system')
   - Shows Sun icon in dark mode, Moon in light mode

4. **Theme Initialization** (resources/js/app.ts)
   - `initializeTheme()` runs on page load
   - Reads cookie and applies `.dark` class client-side
   - Ensures consistent theme before Vue hydration

**Design System Benefits:**
- **Consistency:** All components use same theme tokens
- **Maintainability:** Change color once, applies everywhere
- **Performance:** No JavaScript needed for theming (CSS-only)
- **Accessibility:** WCAG AA compliant contrast ratios enforced
- **User Choice:** Respects system preference or allows override

---

## Build & Deployment

### Development Build

```bash
# Start all services concurrently
composer dev

# Runs:
# - php artisan serve (Laravel dev server on :8000)
# - php artisan queue:work (background jobs)
# - npm run dev (Vite dev server with HMR)
```

**Development Features:**
- Hot Module Replacement (HMR) - instant updates
- Source maps - debug original TypeScript
- Vite dev server - pre-bundled dependencies
- Laravel debug bar (optional)

### Production Build

```bash
# Build frontend assets
npm run build

# Generates:
# - public/build/manifest.json (asset map)
# - public/build/assets/*.js (minified JS bundles)
# - public/build/assets/*.css (purged, minified CSS)
```

**Production Optimizations:**
- Tree-shaking (removes unused code)
- Minification (UglifyJS, cssnano)
- Code splitting (vendor chunk, page chunks)
- Asset versioning (cache busting)
- Purged CSS (only used Tailwind classes)

### Server-Side Rendering (SSR)

```bash
# Build with SSR support
npm run build:ssr

# Start SSR server
node bootstrap/ssr/ssr.mjs
```

**How SSR Works:**
1. User requests `/tools`
2. Laravel controller returns Inertia response
3. Instead of sending JSON, Laravel calls SSR server
4. SSR server renders Vue component to HTML string
5. Laravel injects HTML + hydration data into Blade template
6. User receives fully rendered HTML (SEO-friendly)
7. Vue hydrates client-side for interactivity

### Deployment Checklist

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm ci

# 3. Build assets
npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear/cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
php artisan queue:restart
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

---

## Scalability Considerations

### Current Architecture (Single Server)

```
┌─────────────────────────────────────────┐
│           Single Server                 │
│  ┌────────────────────────────────┐    │
│  │  Nginx (Web Server)            │    │
│  │  PHP-FPM (Laravel)             │    │
│  │  MySQL (Database)              │    │
│  │  Node (SSR Server)             │    │
│  │  Redis (Sessions, Cache)       │    │
│  └────────────────────────────────┘    │
└─────────────────────────────────────────┘
```

**Good for:**
- 1-10K daily users
- 100-500 concurrent users
- Development and early production

### Horizontal Scaling Path (Future)

```
┌──────────────┐
│ Load Balancer│
└──────┬───────┘
       │
   ┌───┴────────────────┬──────────────┐
   │                    │              │
┌──▼───────┐   ┌───────▼──┐   ┌──────▼───┐
│ Web      │   │ Web      │   │ Web      │
│ Server 1 │   │ Server 2 │   │ Server N │
└──┬───────┘   └───────┬──┘   └──────┬───┘
   │                   │              │
   └───────────┬───────┴──────────────┘
               │
       ┌───────▼────────┐
       │  Shared Redis  │  (Sessions, Cache)
       └───────┬────────┘
               │
       ┌───────▼────────┐
       │ MySQL Primary  │
       │ (+ Read Replicas)
       └────────────────┘
```

**Database Optimization:**
- Add indexes on `slug` columns (already have unique constraints)
- Read replicas for tool/category queries
- Connection pooling (Pgbouncer or RDS Proxy)

**Cache Strategy:**
- Cache tool directory listings (5-15 min TTL)
- Cache category counts
- Edge caching with CDN (Cloudflare, CloudFront)

**Queue Workers:**
- Separate servers for queue processing
- Horizon for queue monitoring
- Background jobs for exports, email sending

**Asset Delivery:**
- CDN for static assets (`public/build/`)
- S3/Spaces for uploaded images
- HTTP/2 for multiplexing

---

## Security Model

### Authentication Security

**Password Security:**
- Bcrypt hashing (Laravel default, cost factor 10)
- Minimum 8 characters (configurable in Fortify)
- Password reset tokens expire after 60 minutes

**Two-Factor Authentication (2FA):**
- TOTP-based (Google Authenticator, Authy compatible)
- Recovery codes (encrypted in database)
- Forced 2FA option for admin users (future)

**Session Security:**
- HTTP-only cookies (JavaScript cannot access)
- Secure flag (HTTPS only in production)
- SameSite=Lax (CSRF protection)
- Session rotation on login (prevents fixation attacks)

### CSRF Protection

- Token generated per session
- Validated on all mutation requests (POST/PUT/PATCH/DELETE)
- Automatic via `VerifyCsrfToken` middleware
- Inertia includes token automatically in requests

### XSS Protection

**Backend (Laravel):**
- Blade `{{ $var }}` auto-escapes output
- `{!! $html !!}` for trusted HTML only
- Request validation sanitizes input

**Frontend (Vue):**
- `{{ text }}` auto-escapes in templates
- `v-html` directive avoided (or used with trusted content only)
- Content Security Policy headers (future enhancement)

### SQL Injection Prevention

- **Parameterized queries** - Eloquent uses PDO prepared statements
- **Query builder** - Automatically escapes values
- **Never** use raw queries with user input

```php
// ✅ Safe (parameterized)
Tool::where('name', $userInput)->get();

// ❌ Dangerous (raw, avoid)
DB::raw("SELECT * FROM tools WHERE name = '$userInput'");
```

### Authorization

**Middleware-Based:**
- `auth` middleware - Requires authentication
- `admin` middleware - Requires admin role (custom)
- `verified` middleware - Requires email verification (future)

**Route Protection:**
```php
// Admin routes protected
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('admin/tools', ToolController::class);
});
```

### Environment Security

**Secrets Management:**
- `.env` file (never committed to git)
- `.env.example` template (safe to commit)
- Encryption key stored in `APP_KEY`

**Production Best Practices:**
- `APP_DEBUG=false` (hides stack traces)
- HTTPS enforced
- Database credentials in environment variables
- API keys in `.env` (not hardcoded)

### Future Security Enhancements

- [ ] Content Security Policy (CSP) headers
- [ ] Rate limiting on auth endpoints (already has throttle middleware)
- [ ] Security headers (X-Frame-Options, X-Content-Type-Options)
- [ ] Automated security scanning (Snyk, Dependabot)
- [ ] Penetration testing before major launch
- [ ] Bug bounty program (future)

---

## Performance Optimizations

### Frontend Performance

**Code Splitting:**
```javascript
// Pages loaded on-demand, not in initial bundle
const Dashboard = () => import('./pages/Dashboard.vue')
```

**Lazy Loading Components:**
```vue
<script setup>
// Heavy component loaded only when needed
const ToolChart = defineAsyncComponent(() =>
  import('./ToolChart.vue')
)
</script>
```

**Image Optimization (Future):**
- Resize images on upload
- WebP format with fallback
- Lazy loading with Intersection Observer
- Blur placeholder for above-fold images

**Bundle Optimization:**
- Vendor chunk (Vue, Inertia) cached separately
- Tree-shaking removes unused code
- Minification with Terser
- Gzip/Brotli compression

### Backend Performance

**Database Optimization:**
```php
// ✅ Eager loading (prevents N+1 queries)
$tools = Tool::with('category')->get();

// ❌ N+1 problem (avoid)
$tools = Tool::all();
foreach ($tools as $tool) {
    echo $tool->category->name; // Query per tool!
}
```

**Query Optimization:**
- Indexes on `slug` columns (unique constraint includes index)
- Index on `category_id` foreign key
- Index on `is_active` for filtering
- `views_count` increment uses atomic operation

**Caching Strategy (Future):**
```php
// Cache tool directory for 5 minutes
$tools = Cache::remember('tools.index', 300, function () {
    return Tool::with('category')
        ->active()
        ->orderBy('views_count', 'desc')
        ->get();
});
```

**Pagination:**
```php
// Paginate large result sets
$tools = Tool::with('category')
    ->active()
    ->paginate(24); // 24 per page
```

### Monitoring & Observability (Future)

**Application Performance Monitoring:**
- Laravel Telescope (development debugging)
- New Relic or Datadog (production APM)
- Slow query logging
- Error tracking (Sentry, Bugsnag)

**Metrics to Track:**
- Page load time (Core Web Vitals)
- Time to First Byte (TTFB)
- API response times
- Database query duration
- Cache hit ratio
- Queue processing time

---

## Conclusion

AI Manifesto demonstrates a **modern, scalable architecture** that balances:
- **Developer productivity** (Laravel + Vue + Inertia)
- **User experience** (SPA-like navigation, SSR for SEO)
- **Maintainability** (clear separation of concerns, typed code)
- **Security** (authentication, CSRF, XSS prevention)
- **Performance** (code splitting, eager loading, caching strategy)

This architecture provides a **solid foundation** for:
1. ✅ Current phase: Tool directory with admin features
2. ✅ Phase 2: API endpoints for programmatic access
3. ✅ Phase 3: MCP server for conversational tool management
4. ✅ Future: Community submissions, advanced search, analytics

---

## Related Documentation

- [Authentication Flow](./authentication-flow.md) - Detailed auth diagrams
- [Data Model](./data-model.md) - Database schema and relationships
- [../TOOL_LIST_MAINTENANCE_SYSTEM.md](../TOOL_LIST_MAINTENANCE_SYSTEM.md) - Export/Import architecture
- [../processes/development-workflow.md](../processes/development-workflow.md) - Development lifecycle
- [../../CLAUDE.md](../../CLAUDE.md) - Quick reference for AI assistants
- [../../README.md](../../README.md) - Project overview

---

**Document Maintenance:**
- Review quarterly or after major architecture changes
- Update diagrams when components added/removed
- Link to this doc from onboarding materials

**Contributors:**
- Initial draft: Claude Code (2025-11-10)
- Review/approval: [Project maintainer]
