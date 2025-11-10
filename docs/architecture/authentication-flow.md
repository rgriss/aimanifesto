# Authentication & Authorization Architecture

**Document Version:** 1.0
**Last Updated:** November 10, 2025
**Status:** Current
**Related:** [System Overview](./system-overview.md)

---

## Table of Contents

1. [Overview](#overview)
2. [Authentication Methods](#authentication-methods)
3. [User Registration Flow](#user-registration-flow)
4. [Login Flow](#login-flow)
5. [Two-Factor Authentication (2FA)](#two-factor-authentication-2fa)
6. [Password Reset Flow](#password-reset-flow)
7. [Email Verification Flow](#email-verification-flow)
8. [Session Management](#session-management)
9. [Authorization Model](#authorization-model)
10. [Security Considerations](#security-considerations)
11. [Future Enhancements](#future-enhancements)

---

## Overview

AI Manifesto uses **Laravel Fortify** for authentication, which provides:
- Traditional email/password authentication
- Two-factor authentication (TOTP)
- Email verification
- Password reset functionality
- Rate limiting on auth endpoints

**Authentication Method:** Session-based (cookies)
**Future Plans:** Token-based API authentication (Laravel Sanctum)

---

## Authentication Methods

### Current: Session-Based Authentication

```
User logs in
    │
    ├─→ Server creates session in database
    │
    ├─→ Server sends encrypted cookie to browser
    │       Cookie name: aimanifesto_session
    │       HttpOnly: true (JavaScript cannot access)
    │       Secure: true (HTTPS only in production)
    │       SameSite: Lax (CSRF protection)
    │
    └─→ Browser sends cookie with every request
        └─→ Server validates session
            ├─→ Valid: User authenticated
            └─→ Invalid/Expired: User redirected to login
```

**Why Session-Based?**
- ✅ Stateful - Server knows who's logged in
- ✅ Secure - HttpOnly cookies prevent XSS attacks
- ✅ Simple - Works with traditional web apps
- ✅ Fortify default - No additional setup needed

### Future: Token-Based Authentication (API)

```
User requests API token
    │
    ├─→ Server generates token (Laravel Sanctum)
    │
    ├─→ User receives token: "1|abcd1234..."
    │
    └─→ User sends token in Authorization header:
        Authorization: Bearer 1|abcd1234...
```

**When to use tokens?**
- API access from external applications
- Mobile app authentication
- MCP server authentication
- Third-party integrations

---

## User Registration Flow

### Visual Flow

```
┌──────────────────────────────────────────────────────────────┐
│                    Registration Flow                          │
└──────────────────────────────────────────────────────────────┘

User visits /register
    │
    ▼
┌───────────────────────────┐
│  Vue: Register.vue        │
│  Form: name, email, pwd   │
└─────────┬─────────────────┘
          │ Submit form
          ▼
POST /register
    │
    ├─→ Fortify validation:
    │   ├─→ Name required, max 255
    │   ├─→ Email required, email format, unique
    │   ├─→ Password min 8 chars, confirmed
    │   │
    │   ├─✗ Validation fails
    │   │   └─→ Return errors to form
    │   │
    │   └─✓ Validation passes
    │
    ├─→ Create user record:
    │       INSERT INTO users (name, email, password)
    │       VALUES (?, ?, bcrypt($password))
    │
    ├─→ Fire event: Registered
    │   └─→ Send email verification (if enabled)
    │
    ├─→ Auto-login (create session)
    │
    └─→ Redirect to /dashboard
        └─→ User sees success message
```

### Code Implementation

**Route:**
```php
// routes/auth.php (Fortify auto-registers this)
Route::post('/register', [RegisteredUserController::class, 'store']);
```

**Controller:**
```php
use Laravel\Fortify\Contracts\CreatesNewUsers;

class RegisteredUserController {
    public function store(Request $request, CreatesNewUsers $creator) {
        // Fortify handles validation + user creation
        $user = $creator->create($request->all());

        // Auto-login
        Auth::login($user);

        // Redirect
        return redirect('/dashboard');
    }
}
```

**User Creation (Fortify Action):**
```php
// app/Actions/Fortify/CreateNewUser.php
class CreateNewUser implements CreatesNewUsers {
    use PasswordValidationRules;

    public function create(array $input) {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
```

**Vue Component:**
```vue
<!-- resources/js/pages/auth/Register.vue -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

function submit() {
  form.post(route('register'), {
    onSuccess: () => {
      // Redirected to dashboard
    }
  })
}
</script>
```

---

## Login Flow

### Standard Login (Without 2FA)

```
User visits /login
    │
    ▼
┌───────────────────────────┐
│  Vue: Login.vue           │
│  Form: email, password    │
└─────────┬─────────────────┘
          │ Submit form
          ▼
POST /login
    │
    ├─→ Fortify validation:
    │   ├─→ Email required, email format
    │   ├─→ Password required
    │   │
    │   ├─✗ Validation fails
    │   │   └─→ Return errors to form
    │   │
    │   └─✓ Validation passes
    │
    ├─→ Attempt authentication:
    │   ├─→ Find user by email
    │   ├─→ Verify password (bcrypt compare)
    │   │
    │   ├─✗ Credentials invalid
    │   │   └─→ Return "credentials invalid" error
    │   │       (Don't reveal which field is wrong)
    │   │
    │   └─✓ Credentials valid
    │
    ├─→ Check 2FA enabled?
    │   │
    │   ├─→ YES: (See 2FA flow below)
    │   │
    │   └─→ NO:  Create session immediately
    │
    ├─→ Fire event: Login
    │
    ├─→ Regenerate session ID (prevent fixation)
    │
    └─→ Redirect to intended page or /dashboard
        └─→ User logged in
```

### Code Implementation

**Route:**
```php
// routes/auth.php
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest');
```

**Login Attempt:**
```php
// Laravel Fortify handles this internally
if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
    // Success
    $request->session()->regenerate(); // Prevent session fixation
    return redirect()->intended('/dashboard');
}

// Failure
return back()->withErrors([
    'email' => 'The provided credentials do not match our records.',
]);
```

**Rate Limiting:**
```php
// Fortify automatically limits login attempts:
// - 5 attempts per minute per email
// - Throttled for 1 minute after 5 failures
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->email);
});
```

**Vue Component:**
```vue
<!-- resources/js/pages/auth/Login.vue -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false
})

function submit() {
  form.post(route('login'), {
    onFinish: () => form.reset('password')
  })
}
</script>
```

---

## Two-Factor Authentication (2FA)

### Enabling 2FA

```
User visits /settings/two-factor
    │
    ▼
┌───────────────────────────────────┐
│  User clicks "Enable 2FA"         │
└─────────┬─────────────────────────┘
          │ POST /user/two-factor-authentication
          ▼
Laravel generates:
    ├─→ TOTP secret (base32 encoded)
    ├─→ QR code (data URI)
    └─→ Recovery codes (10 random codes, hashed)
    │
    ▼
Store in database:
    ├─→ users.two_factor_secret (encrypted)
    └─→ users.two_factor_recovery_codes (JSON, encrypted)
    │
    ▼
Return to user:
    ├─→ QR code image
    └─→ Recovery codes (display once)
    │
    ▼
User scans QR with authenticator app
    ├─→ Google Authenticator
    ├─→ Authy
    ├─→ 1Password
    └─→ Any TOTP-compatible app
    │
    ▼
User enters 6-digit code to confirm
    │
    ├─✗ Code invalid
    │   └─→ Show error "Invalid code"
    │
    └─✓ Code valid
        └─→ 2FA fully enabled
```

### Login with 2FA

```
User enters email + password
    │
    ▼
POST /login
    │
    ├─→ Credentials valid?
    │   │
    │   ├─✗ Invalid
    │   │   └─→ Return error
    │   │
    │   └─✓ Valid
    │
    ├─→ Check 2FA enabled?
    │   │
    │   └─→ YES: Store attempted auth in session
    │           └─→ Redirect to /two-factor-challenge
    │
    ▼
┌───────────────────────────────────┐
│  Two-Factor Challenge Page        │
│  User enters 6-digit code         │
└─────────┬─────────────────────────┘
          │ POST /two-factor-challenge
          ▼
Verify code:
    ├─→ Option 1: TOTP code (from app)
    │   ├─→ Generate expected code from secret
    │   ├─→ Compare with user input
    │   │   ├─✗ Mismatch
    │   │   │   └─→ Return error "Invalid code"
    │   │   │
    │   │   └─✓ Match
    │   │       └─→ Create session
    │   │
    │   └─→ Allow 30-second time window (prevents clock drift issues)
    │
    ├─→ Option 2: Recovery code
    │   ├─→ Hash user input
    │   ├─→ Check if hash exists in recovery_codes array
    │   │   ├─✗ Not found
    │   │   │   └─→ Return error "Invalid code"
    │   │   │
    │   │   └─✓ Found
    │   │       ├─→ Remove used code from array
    │   │       └─→ Create session
    │   │
    │   └─→ Warn if few recovery codes remaining
    │
    └─→ SUCCESS: User fully authenticated
        ├─→ Fire event: TwoFactorAuthenticated
        ├─→ Regenerate session
        └─→ Redirect to intended page
```

### TOTP Algorithm (Time-Based One-Time Password)

```
How 6-digit codes are generated:

Secret Key (shared between server and user's device)
    └─→ Base32 encoded: "JBSWY3DPEHPK3PXP"
    │
    ▼
Current Unix timestamp
    └─→ 1699632000 (seconds since epoch)
    │
    ▼
Divide by 30 seconds (time step)
    └─→ 56654400 (counter)
    │
    ▼
HMAC-SHA1(secret, counter)
    └─→ Hash output: 0x1f8698...
    │
    ▼
Extract 6 digits from hash
    └─→ 123456 (the code user enters)

Time window:
    ├─→ Valid: Current code
    ├─→ Valid: Previous code (30 sec ago)
    └─→ Valid: Next code (30 sec future)
    └─→ Prevents clock sync issues
```

### Recovery Codes

**Generation:**
```php
// Fortify generates 10 recovery codes
$recoveryCodes = collect(range(1, 10))->map(function () {
    return Str::random(10) . '-' . Str::random(10);
});

// Example codes:
// "HXf9k2j4nP-QZ3mL7wRt1v"
// "TY6pN8vKq2-LM9fJ4hS5xW"
```

**Storage:**
```php
// Codes are hashed before storage
$user->two_factor_recovery_codes = json_encode(
    $recoveryCodes->map(fn($code) => Hash::make($code))->toArray()
);
```

**Usage:**
- Each code can only be used once
- After use, code is removed from database
- User should regenerate codes periodically
- Display warning when < 3 codes remaining

---

## Password Reset Flow

```
User visits /forgot-password
    │
    ▼
┌───────────────────────────────────┐
│  Enter email address              │
└─────────┬─────────────────────────┘
          │ POST /forgot-password
          ▼
Check if email exists:
    │
    ├─✗ Email not found
    │   └─→ Still show success (don't reveal if email exists)
    │       "If that email exists, we sent a link"
    │
    └─✓ Email found
        │
        ├─→ Generate reset token:
        │   └─→ Random 64-char string
        │
        ├─→ Store in password_reset_tokens table:
        │   ├─→ email: user@example.com
        │   ├─→ token: hash(token)
        │   └─→ created_at: now()
        │
        ├─→ Send email with link:
        │   └─→ /reset-password/{token}?email={email}
        │
        └─→ Return success message
            └─→ "Password reset link sent!"

User clicks email link
    │
    ▼
GET /reset-password/{token}?email={email}
    │
    ├─→ Validate token not expired (60 minutes)
    │   │
    │   ├─✗ Expired
    │   │   └─→ Show error "Link expired, request new one"
    │   │
    │   └─✓ Valid
    │       └─→ Show password reset form
    │
    ▼
User enters new password
    │
    ▼
POST /reset-password
    │
    ├─→ Validate:
    │   ├─→ Token valid and not expired
    │   ├─→ Email matches token
    │   └─→ Password meets requirements
    │   │
    │   ├─✗ Validation fails
    │   │   └─→ Return errors
    │   │
    │   └─✓ Valid
    │
    ├─→ Update password:
    │   └─→ UPDATE users SET password = bcrypt($new)
    │
    ├─→ Delete reset token
    │
    ├─→ Invalidate all sessions (force re-login)
    │
    └─→ Auto-login with new password
        └─→ Redirect to /dashboard
```

### Code Implementation

**Request Reset:**
```php
// app/Http/Controllers/Auth/PasswordResetLinkController.php
public function store(Request $request) {
    $request->validate(['email' => 'required|email']);

    // Fortify handles token generation and email
    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
}
```

**Reset Password:**
```php
// app/Http/Controllers/Auth/NewPasswordController.php
public function store(Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}
```

---

## Email Verification Flow

**Note:** Currently optional in AI Manifesto, but ready to enable.

```
User registers
    │
    ├─→ Email verification enabled?
    │   │
    │   └─→ YES:
    │       ├─→ Set email_verified_at = NULL
    │       ├─→ Send verification email
    │       └─→ Show "Check your email" message
    │
    ▼
User receives email with link:
    /email/verify/{id}/{hash}
    │
    ▼
User clicks link
    │
    ├─→ Verify signature (prevents tampering)
    │   │
    │   ├─✗ Invalid signature
    │   │   └─→ 403 Forbidden
    │   │
    │   └─✓ Valid
    │
    ├─→ Check already verified?
    │   │
    │   ├─→ YES: Redirect to dashboard
    │   │
    │   └─→ NO:
    │       ├─→ Set email_verified_at = now()
    │       ├─→ Fire event: Verified
    │       └─→ Redirect to dashboard
    │           └─→ Show success "Email verified!"
```

### Protecting Routes

```php
// Require verified email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/tools', [ToolController::class, 'index']);
});

// If not verified, user redirected to /email/verify
```

---

## Session Management

### Session Storage

**Database Schema:**
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

**Session Data Stored:**
- User ID (if authenticated)
- CSRF token
- Flash messages
- Previous URL (for redirects)
- Intended URL (after login)
- 2FA attempted auth (temporary)

### Session Lifecycle

**Creation:**
```php
// When user visits site
session()->start(); // Creates session in DB, sends cookie
```

**Authentication:**
```php
// After successful login
Auth::login($user, $remember = false);
// Sets 'login_web_XXX' in session with user ID
// Regenerates session ID (prevents fixation)
```

**Validation:**
```php
// On every request
if (Auth::check()) {
    // User authenticated (session valid)
} else {
    // Guest or expired session
}
```

**Expiration:**
- **Idle timeout:** 120 minutes (config/session.php)
- **Absolute timeout:** None (session lives until idle timeout)
- **Remember me:** 2 weeks (if checkbox checked)

**Logout:**
```php
Auth::logout();
$request->session()->invalidate(); // Delete session from DB
$request->session()->regenerateToken(); // New CSRF token
```

### Cookie Security

**Session Cookie:**
```
Name: aimanifesto_session
Value: eyJpdiI6... (encrypted payload)
Domain: aimanifesto.net
Path: /
Secure: true (HTTPS only in production)
HttpOnly: true (JavaScript cannot read)
SameSite: Lax (CSRF protection)
Expires: When browser closes (unless "remember me")
```

**Remember Me Cookie:**
```
Name: remember_web_XXX
Value: [user_id]|[token]|[hmac]
Expires: 2 weeks (configurable)
HttpOnly: true
Secure: true
```

---

## Authorization Model

### Current Implementation

**Role-Based (Simple):**
```php
// User model
class User extends Authenticatable {
    protected $casts = [
        'is_admin' => 'boolean',
    ];
}

// Check admin status
if (Auth::user()->is_admin) {
    // Allow admin actions
}
```

**Middleware:**
```php
// app/Http/Middleware/AdminMiddleware.php
public function handle($request, $next) {
    if (!Auth::check() || !Auth::user()->is_admin) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
```

**Route Protection:**
```php
// routes/admin.php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('tools', ToolController::class);
    Route::resource('categories', CategoryController::class);
});
```

### Future: Laravel Policies

**Tool Policy (Planned):**
```php
// app/Policies/ToolPolicy.php
class ToolPolicy {
    public function viewAny(User $user): bool {
        return true; // Everyone can view tools
    }

    public function view(User $user, Tool $tool): bool {
        return $tool->is_active || $user->is_admin;
    }

    public function create(User $user): bool {
        return $user->is_admin;
    }

    public function update(User $user, Tool $tool): bool {
        return $user->is_admin;
    }

    public function delete(User $user, Tool $tool): bool {
        return $user->is_admin;
    }
}
```

**Usage in Controller:**
```php
public function update(Request $request, Tool $tool) {
    $this->authorize('update', $tool);

    // User authorized, proceed with update
}
```

**Usage in Blade/Vue:**
```php
@can('update', $tool)
    <button>Edit Tool</button>
@endcan
```

### Future: Granular Permissions (Spatie Permission)

**If project grows, consider Spatie Laravel Permission:**

```php
// Define permissions
$user->givePermissionTo('edit tools');
$user->givePermissionTo('delete tools');

// Define roles
$role = Role::create(['name' => 'editor']);
$role->givePermissionTo(['edit tools', 'edit categories']);
$user->assignRole('editor');

// Check permissions
if ($user->can('edit tools')) {
    // Allow
}
```

---

## Security Considerations

### Password Security

**Hashing:**
- Algorithm: Bcrypt (Blowfish cipher)
- Cost factor: 10 (configurable in config/hashing.php)
- Automatically salted (bcrypt includes salt in hash)

**Requirements:**
- Minimum 8 characters (configurable)
- Confirmed (password_confirmation field)
- No maximum length (bcrypt truncates at 72 bytes, but we don't limit)

**Best Practices:**
- ✅ Never store passwords in plaintext
- ✅ Never log passwords
- ✅ Never send passwords in emails
- ✅ Use `Hash::make()`, never md5() or sha1()

### Session Fixation Prevention

```php
// After successful login, regenerate session ID
$request->session()->regenerate();

// Prevents attacker from hijacking known session ID
```

### CSRF Protection

```php
// All POST/PUT/PATCH/DELETE require CSRF token
// Automatically validated by VerifyCsrfToken middleware

// Inertia includes token automatically
// Manual forms need:
@csrf
```

### Brute Force Protection

**Login Rate Limiting:**
- 5 attempts per minute per email
- Throttled for 1 minute after 5 failures
- Fortify handles automatically

**Additional Protection (Future):**
- Captcha after 3 failed attempts
- IP-based rate limiting
- Account lockout after 10 failures

### 2FA Security

**TOTP Best Practices:**
- ✅ Secret encrypted at rest (Laravel's encryption)
- ✅ QR code displayed once, not stored
- ✅ Recovery codes hashed
- ✅ Time window allows clock drift (30 sec)

**Recovery Code Security:**
- ✅ One-time use only
- ✅ Hashed before storage
- ✅ Removed after use
- ⚠️ User should store securely offline

### Timing Attack Prevention

```php
// Don't reveal which field is wrong
❌ "Email not found"
❌ "Password incorrect"
✅ "The provided credentials do not match our records."

// Use hash_equals() for token comparison (constant time)
✅ hash_equals($expected, $actual)
❌ $expected === $actual (timing attack vulnerable)
```

---

## Future Enhancements

### OAuth Social Login

**Planned Providers:**
- GitHub
- Google
- Microsoft

**Architecture:**
```
User clicks "Login with GitHub"
    │
    ├─→ Redirect to GitHub OAuth
    │
    ├─→ User authorizes
    │
    ├─→ GitHub redirects back with code
    │
    ├─→ Exchange code for access token
    │
    ├─→ Fetch user info from GitHub API
    │
    ├─→ Find or create user in database
    │
    └─→ Login user, redirect to dashboard
```

**Implementation:** Laravel Socialite

### Passkeys / WebAuthn

**Future passwordless authentication:**
- Biometric authentication (fingerprint, Face ID)
- Hardware keys (YubiKey)
- Platform authenticators (Windows Hello)

### API Token Authentication (Phase 2)

**For API endpoints:**
```php
// Generate token
$token = $user->createToken('api-access')->plainTextToken;

// Use token
curl -H "Authorization: Bearer $token" \
     https://api.aimanifesto.net/tools
```

### Multi-Session Management

**Allow users to see active sessions:**
- View all logged-in devices
- Logout from specific devices
- Logout from all other devices

### Audit Logging

**Track authentication events:**
- Login attempts (success/failure)
- Password changes
- 2FA enable/disable
- Recovery code usage
- Account lockouts

---

## Related Documentation

- [System Overview](./system-overview.md) - Overall architecture
- [Data Model](./data-model.md) - Database schema
- [../../CLAUDE.md](../../CLAUDE.md) - Quick reference

---

**Document Maintenance:**
- Update when authentication changes occur
- Review after security audits
- Keep code examples in sync with implementation
