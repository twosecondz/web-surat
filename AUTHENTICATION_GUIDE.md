# Authentication Guide - SIPD

## Overview

The Sistem Informasi Perjalanan Dinas (SIPD) implements a custom authentication system that allows users to login with multiple identifier types in a single input field.

---

## Features

### Multi-Format Login ✨

Users can login using any of the following formats in a single "NIP" field:

1. **NIP (18 digits)** - Nomor Induk Pegawai
2. **NIK (16 digits)** - Nomor Induk Kependudukan  
3. **Email** - Standard email format
4. **Username** - Alphanumeric username

### Smart Detection Logic

The system automatically detects the identifier type:

```php
// Email format
if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
    return 'email';
}

// Numeric (NIP/NIK)
if (is_numeric($identity)) {
    if (strlen($identity) === 18) return 'nip';
    if (strlen($identity) === 16) return 'nik';
    return 'nip'; // default
}

// Otherwise username
return 'username';
```

---

## Database Schema

### Users Table Extensions

The `users` table has been extended with:

```php
'username'    // string(50), unique, nullable
'nip'         // string(18), unique, nullable
'nik'         // string(16), nullable
'pangkat'     // string(100), nullable
'golongan'    // string(20), nullable
'jabatan'     // string, nullable
'skpa'        // string, nullable
'eselon'      // string(10), nullable
```

### Migration

```bash
php artisan migrate
```

This runs the new migration:
- `2024_01_21_000007_add_username_to_users_table.php`

---

## Files Created

### Controllers

**`app/Http/Controllers/Auth/LoginController.php`**
- `showLoginForm()` - Display login page
- `login()` - Process login request with smart detection
- `logout()` - Handle user logout
- `getLoginField()` - Determine identifier type

### Views

**`resources/views/auth/login.blade.php`**
- Tailwind CSS styled login page
- Matches the design from Login.png
- Responsive layout with logo
- Error handling and validation messages

**`resources/views/dashboard.blade.php`**
- Post-login dashboard
- User information display
- Quick action cards
- Statistics overview
- Logout functionality

### Routes

**`routes/web.php`**
```php
// Guest routes
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);

// Authenticated routes
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/dashboard', function () {...});
```

---

## Usage

### Starting the Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

### Test Login Credentials

You can login with any of these formats for the test user:

| Format | Value | Password |
|--------|-------|----------|
| **NIP** | `19831013200504101` | `password` |
| **Email** | `mohammad.iqbal@bpka.acehprov.go.id` | `password` |
| **Username** | `iqbal` | `password` |

### All Available Test Users

| Name | NIP | NIK | Username | Email |
|------|-----|-----|----------|-------|
| Reza Saputra | 19800103199810102 | - | reza.saputra | reza.saputra@bpka.acehprov.go.id |
| Ramzi | 19740904200803101 | - | ramzi | ramzi@bpka.acehprov.go.id |
| Sudirman | 19691126199003101 | - | sudirman | sudirman@bpka.acehprov.go.id |
| Mohammad Iqbal | 19831013200504101 | - | iqbal | mohammad.iqbal@bpka.acehprov.go.id |
| Mudatsir | 47220130546200000 | - | mudatsir | mudatsir@bpka.acehprov.go.id |
| Test User | 19900101202001101 | 1234567890123456 | testuser | test@example.com |

**All passwords:** `password`

---

## Login Flow

### 1. User Visits Login Page

```
GET /login
```

- Displays login form
- If already authenticated, redirects to `/dashboard`

### 2. User Submits Credentials

```
POST /login
{
    "identity": "19831013200504101",
    "password": "password",
    "remember": true // optional
}
```

### 3. System Validates & Authenticates

```php
// Validate input
$request->validate([
    'identity' => 'required|string',
    'password' => 'required|string',
]);

// Detect login field
$loginField = $this->getLoginField($identity);

// Attempt authentication
Auth::attempt([
    $loginField => $identity, 
    'password' => $password
], $remember);
```

### 4. Success Response

```
Redirect to /dashboard
Session: success message
```

### 5. Error Response

```
Redirect back to /login
Errors: validation messages
```

---

## UI Components

### Login Page Design

The login page matches the provided design:

#### Left Side - Login Form
- **Title**: "Login" (text-5xl, font-bold)
- **NIP Field**: 
  - Label: "NIP"
  - Placeholder: "Masukkan NIP"
  - Accepts: NIP/NIK/Email/Username
- **Password Field**:
  - Label: "Kata Sandi"
  - Placeholder: "Masukkan kata sandi"
  - Type: password
- **Remember Me**: Optional checkbox
- **Submit Button**: Yellow (bg-yellow-400), full width
- **Help Text**: Explains multi-format login

#### Right Side - Logo
- BPKA Pancacita logo
- Hidden on mobile (lg:block)
- Full width on large screens

### Color Scheme

```css
Primary: #FACC15 (yellow-400)
Hover: #EAB308 (yellow-500)
Focus: Yellow ring with offset
Text: Gray-900 (near black)
Background: White
Border: Gray-300
```

---

## Security Features

### Implemented

✅ **CSRF Protection** - Laravel's built-in CSRF tokens
✅ **Password Hashing** - Bcrypt hashing
✅ **Session Regeneration** - On successful login
✅ **Guest Middleware** - Prevents authenticated access to login
✅ **Auth Middleware** - Protects dashboard routes
✅ **Validation** - Input validation with custom messages
✅ **Remember Me** - Optional persistent sessions

### Recommended Additions

- [ ] Rate limiting (throttle middleware)
- [ ] Two-factor authentication
- [ ] Password reset functionality
- [ ] Email verification
- [ ] Activity logging
- [ ] Failed login tracking
- [ ] Account lockout after X attempts

---

## Customization

### Adding New Fields

To add more identifier fields:

1. **Add column to users table**:
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('new_field')->unique()->nullable();
});
```

2. **Update detection logic**:
```php
protected function getLoginField(string $identity): string
{
    // Add your custom logic
    if ($this->isCustomFormat($identity)) {
        return 'new_field';
    }
    // ... existing logic
}
```

3. **Update fillable in User model**:
```php
protected $fillable = [
    // ... existing fields
    'new_field',
];
```

### Changing UI Colors

Update Tailwind classes in `login.blade.php`:

```html
<!-- Change button color from yellow to blue -->
<button class="bg-blue-500 hover:bg-blue-600">
    Login
</button>

<!-- Change focus ring -->
<input class="focus:ring-blue-400">
```

### Custom Validation Messages

Edit `LoginController@login`:

```php
$request->validate([
    'identity' => 'required|string',
    'password' => 'required|string|min:8',
], [
    'identity.required' => 'Custom message here',
    'password.min' => 'Password minimum 8 karakter',
]);
```

---

## Middleware Configuration

### Guest Redirect

Users already logged in are redirected to dashboard:

```php
// bootstrap/app.php
$middleware->redirectUsersTo('/dashboard');
```

### Auth Redirect

Guests trying to access protected routes are redirected to login:

```php
// bootstrap/app.php
$middleware->redirectGuestsTo('/login');
```

---

## Testing

### Manual Testing Checklist

- [ ] Login with NIP (18 digits)
- [ ] Login with NIK (16 digits)  
- [ ] Login with Email
- [ ] Login with Username
- [ ] Invalid credentials show error
- [ ] Remember me works
- [ ] Logout works
- [ ] Protected routes require auth
- [ ] Authenticated users can't access login
- [ ] Session persists across requests
- [ ] CSRF protection works

### Using Laravel Tinker

```bash
php artisan tinker
```

```php
// Test authentication
$user = User::where('nip', '19831013200504101')->first();
Auth::login($user);
Auth::check(); // true
Auth::user()->name; // "Mohammad Iqbal, SE"
Auth::logout();
```

---

## Troubleshooting

### Issue: "Route [login] not defined"

**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: "419 Page Expired" on login

**Solution**: CSRF token issue
```bash
php artisan cache:clear
php artisan session:clear
```

Or check that `@csrf` is in the form.

### Issue: Login successful but redirects to login

**Solution**: Check middleware configuration in `bootstrap/app.php`

### Issue: Logo not showing

**Solution**: Place logo image at:
```
public/images/logo-pancacita.png
```

Or update the image path in `login.blade.php`

---

## API Reference

### LoginController Methods

#### `showLoginForm()`
```php
public function showLoginForm()
```
**Returns**: View (auth.login)
**Route**: GET /login

#### `login(Request $request)`
```php
public function login(Request $request)
```
**Parameters**:
- `identity` (string, required) - NIP/NIK/Email/Username
- `password` (string, required) - User password
- `remember` (boolean, optional) - Remember me flag

**Returns**: Redirect to dashboard or back with errors
**Route**: POST /login

#### `logout(Request $request)`
```php
public function logout(Request $request)
```
**Returns**: Redirect to login with success message
**Route**: POST /logout

#### `getLoginField(string $identity)`
```php
protected function getLoginField(string $identity): string
```
**Parameters**:
- `identity` (string) - User input

**Returns**: string ('email', 'nip', 'nik', or 'username')

---

## Best Practices

### Session Management

```php
// Regenerate session on login
$request->session()->regenerate();

// Invalidate session on logout
$request->session()->invalidate();
$request->session()->regenerateToken();
```

### Password Security

```php
// Always hash passwords
'password' => Hash::make($password)

// Never store plain text passwords
// Never log passwords
// Use HTTPS in production
```

### Validation

```php
// Always validate user input
$request->validate([...]);

// Use custom messages in Indonesian
'identity.required' => 'NIP/NIK/Email harus diisi'
```

---

## Deployment Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production` in .env
- [ ] Set strong `APP_KEY`
- [ ] Enable HTTPS
- [ ] Set `SESSION_SECURE_COOKIE=true`
- [ ] Configure proper session driver (redis/database)
- [ ] Add rate limiting to login route
- [ ] Set up email for password resets
- [ ] Configure proper logging
- [ ] Test all login formats
- [ ] Add real logo image
- [ ] Set up monitoring

---

## Future Enhancements

### Planned Features

1. **Password Reset**
   - Email-based password reset
   - Security questions
   - Token expiration

2. **Two-Factor Authentication**
   - SMS OTP
   - Email OTP
   - Authenticator app support

3. **Social Login** (if needed)
   - Google OAuth
   - Microsoft SSO

4. **Activity Logging**
   - Login history
   - Failed attempts
   - IP tracking
   - Device tracking

5. **Account Management**
   - Change password
   - Update profile
   - Session management
   - Device management

---

## Support

For questions or issues:

1. Check `SETUP_GUIDE.md` for general setup
2. Review this authentication guide
3. Check Laravel authentication docs: https://laravel.com/docs/11.x/authentication

---

**Version**: 1.0  
**Last Updated**: January 21, 2025  
**Status**: ✅ Authentication System Complete

---

*Built with ❤️ for BPKA*
