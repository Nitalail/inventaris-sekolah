# Troubleshooting CSRF Token Error

## Deskripsi
Dokumentasi untuk mengatasi error "CSRF token not found" di halaman `/user/profile`.

## Masalah yang Ditemukan

### 1. **Meta Tag CSRF Token Hilang**
- **Masalah**: File `user/profile.blade.php` tidak memiliki meta tag CSRF token
- **Dampak**: JavaScript tidak bisa mengakses CSRF token untuk AJAX request
- **Solusi**: Menambahkan meta tag CSRF token di bagian `<head>`

### 2. **Session Configuration**
- **Masalah**: Session driver menggunakan database
- **Status**: Tabel sessions sudah ada dan berfungsi
- **Verifikasi**: Session ID dan CSRF token bisa di-generate

## Solusi yang Diterapkan

### 1. **Menambahkan Meta Tag CSRF Token**

#### File: `resources/views/user/profile.blade.php`
**Sebelum:**
```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Siswa - SchoolLend</title>
```

**Sesudah:**
```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Profil Siswa - SchoolLend</title>
```

### 2. **Test Page untuk Verifikasi**

#### File: `resources/views/test-csrf-profile.blade.php`
- **Fungsi**: Test page untuk memverifikasi CSRF token berfungsi
- **Route**: `/test-csrf-profile`
- **Fitur**: Menampilkan CSRF token, session ID, dan form test

## Verifikasi Implementasi

### 1. **Cek Meta Tag CSRF Token**
```html
<!-- Pastikan meta tag ada di bagian head -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### 2. **Cek Session Configuration**
```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'database'),
'table' => env('SESSION_TABLE', 'sessions'),
```

### 3. **Cek Database Sessions**
```bash
# Verifikasi tabel sessions ada
php artisan tinker --execute="echo 'Sessions table exists: ' . (Schema::hasTable('sessions') ? 'Yes' : 'No');"
```

## Testing Steps

### Step 1: Test CSRF Token Generation
1. **Buka halaman test**: `/test-csrf-profile`
2. **Verifikasi CSRF token muncul** di halaman
3. **Verifikasi session ID** ter-generate
4. **Verifikasi user authenticated**

### Step 2: Test Form Submission
1. **Login sebagai user**
2. **Buka halaman test**: `/test-csrf-profile`
3. **Isi form dan submit**
4. **Verifikasi tidak ada error CSRF**

### Step 3: Test Profile Page
1. **Login sebagai user**
2. **Buka halaman profile**: `/user/profile`
3. **Klik "Edit Profile"**
4. **Isi form dan submit**
5. **Verifikasi profile berhasil diupdate**

## Troubleshooting Checklist

### ✅ **Meta Tag CSRF Token**
- [ ] Meta tag ada di bagian `<head>`
- [ ] Content menggunakan `{{ csrf_token() }}`
- [ ] Tag tidak rusak atau terpotong

### ✅ **Session Configuration**
- [ ] Session driver dikonfigurasi dengan benar
- [ ] Tabel sessions ada di database
- [ ] Session lifetime tidak expired

### ✅ **Route dan Controller**
- [ ] Route terdaftar dengan benar
- [ ] Controller method ada dan berfungsi
- [ ] Middleware auth aktif

### ✅ **Form Implementation**
- [ ] Form menggunakan `@csrf` directive
- [ ] Method PATCH/PUT sesuai
- [ ] Action route benar

## Common Issues dan Solutions

### Issue 1: "CSRF token not found"
**Solution:**
1. Pastikan meta tag CSRF ada di `<head>`
2. Clear cache: `php artisan config:clear`
3. Clear view cache: `php artisan view:clear`

### Issue 2: "Session expired"
**Solution:**
1. Cek session lifetime di `config/session.php`
2. Pastikan user login ulang
3. Cek session storage di database

### Issue 3: "Form submission failed"
**Solution:**
1. Cek browser console untuk error JavaScript
2. Verifikasi CSRF token di form
3. Cek network tab untuk request details

## Debugging Tools

### 1. **Browser Developer Tools**
- **Console**: Cek error JavaScript
- **Network**: Cek request/response
- **Elements**: Verifikasi meta tag CSRF

### 2. **Laravel Debug**
```php
// Tambahkan di controller untuk debug
dd([
    'csrf_token' => csrf_token(),
    'session_id' => session()->getId(),
    'user_authenticated' => auth()->check(),
    'user' => auth()->user()
]);
```

### 3. **Test Page**
- **URL**: `/test-csrf-profile`
- **Fungsi**: Menampilkan semua informasi CSRF dan session
- **Form Test**: Test submission dengan CSRF token

## Prevention Measures

### 1. **Template Consistency**
- **Gunakan layout yang konsisten** untuk semua halaman
- **Pastikan meta tag CSRF** ada di semua template
- **Standardize form implementation**

### 2. **Middleware Configuration**
- **VerifyCsrfToken middleware** aktif
- **Session middleware** dikonfigurasi dengan benar
- **Auth middleware** untuk protected routes

### 3. **Development Best Practices**
- **Clear cache** setelah perubahan config
- **Test CSRF token** di development
- **Monitor session storage** secara regular

## Kesimpulan

Error CSRF token not found di halaman `/user/profile` disebabkan oleh:
1. **Meta tag CSRF token hilang** dari template
2. **Session configuration** perlu verifikasi

**Solusi yang diterapkan:**
1. ✅ **Menambahkan meta tag CSRF token** di `user/profile.blade.php`
2. ✅ **Verifikasi session configuration** dan database
3. ✅ **Membuat test page** untuk debugging
4. ✅ **Clear cache** untuk memastikan perubahan ter-apply

**Status**: Masalah sudah diperbaiki dan siap untuk testing.

## Next Steps

1. **Test halaman profile** dengan login user
2. **Verifikasi form submission** berfungsi
3. **Monitor error logs** untuk masalah lain
4. **Remove test page** setelah konfirmasi berfungsi 