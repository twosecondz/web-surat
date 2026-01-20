# Testing Authentication - Quick Guide

## 🚀 Quick Start

### 1. Run Migrations

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Add username field to users
- Seed 6 test users with usernames

### 2. Start Server

```bash
php artisan serve
```

### 3. Visit Login Page

Open browser: `http://localhost:8000`

You'll be automatically redirected to `/login`

---

## 🧪 Test Cases

### Test 1: Login with NIP (18 digits)

**Input:**
- Identity: `19831013200504101`
- Password: `password`

**Expected:** ✅ Login successful, redirect to dashboard

---

### Test 2: Login with NIK (16 digits)

**Input:**
- Identity: `1234567890123456`
- Password: `password`

**Expected:** ✅ Login successful (Test User)

---

### Test 3: Login with Email

**Input:**
- Identity: `mohammad.iqbal@bpka.acehprov.go.id`
- Password: `password`

**Expected:** ✅ Login successful, shows "Mohammad Iqbal, SE"

---

### Test 4: Login with Username

**Input:**
- Identity: `iqbal`
- Password: `password`

**Expected:** ✅ Login successful

---

### Test 5: Invalid Credentials

**Input:**
- Identity: `iqbal`
- Password: `wrongpassword`

**Expected:** ❌ Error message: "NIP/NIK/Email/Username atau kata sandi salah."

---

### Test 6: Empty Fields

**Input:**
- Identity: (empty)
- Password: (empty)

**Expected:** ❌ Validation errors showing required fields

---

### Test 7: Logout

1. Login successfully
2. Click "Logout" button on dashboard

**Expected:** ✅ Logged out, redirected to login page with success message

---

### Test 8: Protected Route Access

1. Logout (if logged in)
2. Try to access: `http://localhost:8000/dashboard`

**Expected:** ✅ Redirected to `/login` (auth middleware working)

---

### Test 9: Login Page Access When Authenticated

1. Login successfully
2. Try to access: `http://localhost:8000/login`

**Expected:** ✅ Redirected to `/dashboard` (guest middleware working)

---

### Test 10: Remember Me

**Input:**
- Identity: `iqbal`
- Password: `password`
- Check: ☑️ "Ingat saya"

**Expected:** ✅ Session persists longer (test by closing browser and reopening)

---

## 👥 All Test Users

You can test with any of these accounts:

### User 1: Kepala Badan
- **NIP**: `19800103199810102`
- **Username**: `reza.saputra`
- **Email**: `reza.saputra@bpka.acehprov.go.id`
- **Password**: `password`

### User 2: Sekretaris
- **NIP**: `19740904200803101`
- **Username**: `ramzi`
- **Email**: `ramzi@bpka.acehprov.go.id`
- **Password**: `password`

### User 3: Kepala Bidang
- **NIP**: `19691126199003101`
- **Username**: `sudirman`
- **Email**: `sudirman@bpka.acehprov.go.id`
- **Password**: `password`

### User 4: Kasubbid ⭐ (Recommended for testing)
- **NIP**: `19831013200504101`
- **Username**: `iqbal`
- **Email**: `mohammad.iqbal@bpka.acehprov.go.id`
- **Password**: `password`

### User 5: PPK
- **NIP**: `47220130546200000`
- **Username**: `mudatsir`
- **Email**: `mudatsir@bpka.acehprov.go.id`
- **Password**: `password`

### User 6: Test User (Has NIK)
- **NIP**: `19900101202001101`
- **NIK**: `1234567890123456` ⭐ (Only user with NIK for testing)
- **Username**: `testuser`
- **Email**: `test@example.com`
- **Password**: `password`

---

## 🎨 UI Verification

### Desktop View
- [ ] Logo visible on right side
- [ ] Form on left side
- [ ] Two-column layout
- [ ] Login button is yellow
- [ ] Input fields have proper styling
- [ ] Focus states work (yellow ring)
- [ ] Hover effects work

### Mobile View
- [ ] Logo hidden
- [ ] Single column layout
- [ ] Form is centered
- [ ] Buttons are full width
- [ ] Touch-friendly input sizes

---

## 🐛 Common Issues & Solutions

### Issue: "Route [login] not defined"

**Solution:**
```bash
php artisan route:clear
php artisan route:cache
php artisan route:list
```

### Issue: "Class 'LoginController' not found"

**Solution:**
```bash
composer dump-autoload
```

### Issue: "Column 'username' not found"

**Solution:**
```bash
php artisan migrate:fresh --seed
```

### Issue: Login page shows but styling is broken

**Solution:**
```bash
npm install
npm run build
```

Or for development:
```bash
npm run dev
```

### Issue: "419 Page Expired" when submitting form

**Solution:**
- Clear browser cache
- Check that `@csrf` token is in the form
```bash
php artisan cache:clear
```

### Issue: Logo not showing

**Solution:**
- Place logo image at: `public/images/logo-pancacita.png`
- Or the logo will be hidden automatically (onerror handler)

---

## 🔍 Debugging

### Check Routes

```bash
php artisan route:list
```

Should show:
```
GET|HEAD  /                      → redirect to /login
GET|HEAD  /login                 → LoginController@showLoginForm
POST      /login                 → LoginController@login
GET|HEAD  /dashboard             → Closure (protected)
POST      /logout                → LoginController@logout
```

### Check Database

```bash
php artisan tinker
```

```php
// Check users
User::count(); // Should return 6

// Check username field exists
User::first()->username; // Should return username

// Test authentication manually
$user = User::where('username', 'iqbal')->first();
Auth::login($user);
Auth::check(); // Should return true
```

### Check Migrations

```bash
php artisan migrate:status
```

All migrations should show "Ran"

---

## 📊 Expected Results Summary

| Test | Input Type | Expected Result |
|------|------------|-----------------|
| 1 | NIP (18 digits) | ✅ Login success |
| 2 | NIK (16 digits) | ✅ Login success |
| 3 | Email | ✅ Login success |
| 4 | Username | ✅ Login success |
| 5 | Wrong password | ❌ Error message |
| 6 | Empty fields | ❌ Validation errors |
| 7 | Logout | ✅ Redirect to login |
| 8 | Access dashboard without auth | ✅ Redirect to login |
| 9 | Access login when authenticated | ✅ Redirect to dashboard |
| 10 | Remember me | ✅ Persistent session |

---

## ✅ Checklist

Before marking as complete:

- [ ] All 10 test cases pass
- [ ] Desktop UI matches design
- [ ] Mobile UI works correctly
- [ ] All 6 users can login with all formats
- [ ] Validation messages in Indonesian
- [ ] Error handling works
- [ ] Success messages display
- [ ] Logout functionality works
- [ ] Middleware redirects work
- [ ] Dashboard displays user info correctly
- [ ] No console errors
- [ ] No PHP errors in log

---

## 📝 Testing Log Template

Use this to track your testing:

```
DATE: _____________
TESTER: ___________

[ ] Test 1 - NIP Login
[ ] Test 2 - NIK Login
[ ] Test 3 - Email Login
[ ] Test 4 - Username Login
[ ] Test 5 - Invalid Credentials
[ ] Test 6 - Empty Fields
[ ] Test 7 - Logout
[ ] Test 8 - Protected Routes
[ ] Test 9 - Guest Middleware
[ ] Test 10 - Remember Me

[ ] Desktop UI ✓
[ ] Mobile UI ✓
[ ] No Errors ✓

NOTES:
_________________________________
_________________________________
_________________________________

STATUS: [ ] PASS  [ ] FAIL
```

---

## 🎯 Performance Testing

### Load Time Expectations

- Login page: < 500ms
- Dashboard: < 800ms
- Logout: < 200ms

### Browser Compatibility

Test in:
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers

---

## 📱 Mobile Testing

### Test on:
- [ ] iOS Safari
- [ ] Android Chrome
- [ ] Different screen sizes
- [ ] Portrait orientation
- [ ] Landscape orientation

---

## 🎉 Success Criteria

Authentication is complete when:

✅ All 10 test cases pass  
✅ UI matches design perfectly  
✅ No console errors  
✅ No PHP errors  
✅ All users can login  
✅ Security features work  
✅ Documentation is complete

---

**Happy Testing! 🚀**

If you find any issues, check `AUTHENTICATION_GUIDE.md` for troubleshooting or refer to `SETUP_GUIDE.md` for general setup help.
