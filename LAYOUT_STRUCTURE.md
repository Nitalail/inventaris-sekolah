# 📋 **Dokumentasi Struktur Layout Baru**

## 🎯 **Tujuan Pembaruan**

Struktur templating telah diperbaiki untuk memisahkan komponen-komponen UI menjadi partials yang dapat digunakan kembali, meningkatkan maintainability, dan konsistensi desain.

---

## 🏗️ **Struktur Layout**

### **1. Master Layout**
```
resources/views/layouts/master.blade.php
```
- Template dasar untuk semua halaman
- Mengelola `<head>`, scripts, dan struktur HTML utama
- Support untuk custom styles dan scripts per halaman

### **2. Admin Layout**
```
resources/views/layouts/admin.blade.php
```
- Extends master layout
- Khusus untuk halaman admin
- Sudah termasuk Alpine.js state management
- Auto-include sidebar dan navbar admin

### **3. User Layout** 
```
resources/views/layouts/user.blade.php
```
- Extends master layout
- Khusus untuk halaman user/siswa
- Include header, footer, dan modals user
- Responsive design untuk mobile

---

## 🧩 **Partials yang Tersedia**

### **Admin Partials**
```
resources/views/partials/admin/
├── sidebar.blade.php     # Sidebar navigasi admin
└── navbar.blade.php      # Header dengan search & notifications
```

### **User Partials**
```
resources/views/partials/user/
├── header.blade.php      # Header dengan navigasi user
├── footer.blade.php      # Footer dengan info sekolah
└── modals.blade.php      # Modal untuk notifications & borrowing
```

### **Shared Partials**
```
resources/views/partials/
├── notification-system.blade.php    # Sistem notifikasi global
└── admin-notifications.blade.php    # Notifikasi khusus admin
```

---

## 📝 **Cara Menggunakan**

### **Membuat Halaman Admin Baru**

```blade
@extends('layouts.admin')

@section('title', 'Nama Halaman')

@php
    $header = 'Judul Halaman';
    $description = 'Deskripsi halaman (opsional)';
    $breadcrumb = [
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'Current Page', 'url' => '#']
    ];
@endphp

@push('styles')
<style>
    /* Custom CSS untuk halaman ini */
</style>
@endpush

@section('page-content')
    <!-- Konten halaman di sini -->
    <div class="glass rounded-2xl p-6">
        <h2>Content goes here</h2>
    </div>
@endsection

@push('scripts')
<script>
    // JavaScript khusus halaman
</script>
@endpush
```

### **Membuat Halaman User Baru**

```blade
@extends('layouts.user')

@section('title', 'Nama Halaman - SchoolLend')

@php
    $pageHeader = [
        'title' => 'Judul Halaman',
        'description' => 'Deskripsi halaman',
        'breadcrumb' => [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Current Page', 'url' => '#']
        ]
    ];
@endphp

@section('page-content')
    <!-- Konten halaman -->
    <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg border border-gray-100 p-8">
        <h2>User content here</h2>
    </div>
@endsection

@push('scripts')
<script>
    // User page scripts
</script>
@endpush
```

---

## 🎨 **Design System**

### **CSS Classes yang Tersedia**

#### **Glass Effect**
```css
.glass {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
```

#### **Status Badges**
```css
.badge-dipinjam     /* Blue badge */
.badge-dikembalikan /* Green badge */
.badge-terlambat    /* Amber badge */
.badge-diperbaiki   /* Purple badge */
.badge-warning      /* Red badge */
```

#### **Transitions**
```css
.transition-slow {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
```

### **Color Palette**
```css
:root {
    --primary: #6366f1;        /* Indigo */
    --secondary: #8b5cf6;      /* Purple */
    --accent: #10b981;         /* Emerald */
}
```

---

## 🔧 **Fitur Layout Admin**

### **Sidebar Features**
- ✅ Auto-highlight halaman aktif
- ✅ Mobile responsive dengan toggle
- ✅ User profile dropdown
- ✅ Logout functionality

### **Navbar Features** 
- ✅ Search bar dengan context-aware routing
- ✅ Quick actions berdasarkan halaman aktif
- ✅ Notification bell
- ✅ Mobile search toggle

### **Automatic Features**
- ✅ Auto-include notification system
- ✅ Mobile sidebar management
- ✅ Alpine.js state management
- ✅ Breadcrumb generation

---

## 🔧 **Fitur Layout User**

### **Header Features**
- ✅ Responsive navigation menu
- ✅ User profile dropdown
- ✅ Notification badge
- ✅ Mobile menu toggle

### **Footer Features**
- ✅ School information
- ✅ Quick links
- ✅ Developer credits
- ✅ System statistics

### **Modal System**
- ✅ Borrow modal dengan form validation
- ✅ Notification modal dengan mark as read
- ✅ Toast notifications
- ✅ Auto-close dan escape key support

---

## 📱 **Responsive Design**

### **Breakpoints**
- `sm`: 640px
- `md`: 768px  
- `lg`: 1024px
- `xl`: 1280px

### **Mobile Optimizations**
- ✅ Collapsible sidebar untuk admin
- ✅ Mobile-first navigation untuk user
- ✅ Touch-friendly buttons dan modals
- ✅ Optimized typography scaling

---

## 🚀 **Performance Features**

### **Asset Loading**
- ✅ Vite untuk bundling optimal
- ✅ CSS splitting per layout
- ✅ Lazy loading untuk non-critical components

### **JavaScript Optimizations**
- ✅ Alpine.js untuk reactive components
- ✅ Event delegation untuk better performance
- ✅ Debounced search inputs

---

## 🔍 **Contoh Implementasi**

### **File Contoh yang Telah Dibuat**
1. `resources/views/admin/dashboard-new.blade.php` - Contoh admin page
2. `resources/views/user/dashboard-new.blade.php` - Contoh user page

### **Migrasi dari Layout Lama**
1. Ganti `<!DOCTYPE html>` dengan `@extends('layouts.admin')` atau `@extends('layouts.user')`
2. Pindahkan content ke dalam `@section('page-content')`
3. Pindahkan CSS ke `@push('styles')`
4. Pindahkan JavaScript ke `@push('scripts')`
5. Set variabel header menggunakan `@php` block

---

## 🛠️ **Tips Development**

### **Best Practices**
1. **Gunakan partials** untuk komponen yang digunakan berulang
2. **Leverage Alpine.js** untuk interactivity sederhana
3. **Gunakan `@push('styles')` dan `@push('scripts')`** untuk assets per halaman
4. **Konsisten dalam penamaan** class CSS dan JavaScript functions

### **Performance Tips**
1. **Minimize inline styles** - gunakan CSS classes
2. **Debounce search inputs** untuk mengurangi API calls
3. **Lazy load modals** - hanya populate content saat dibuka
4. **Use CSS transitions** daripada JavaScript animations

---

## 📦 **Assets & Dependencies**

### **CSS Framework**
- **Tailwind CSS** - Utility-first CSS framework
- **Custom components** dalam `app.css`

### **JavaScript Libraries**
- **Alpine.js** - Lightweight reactive framework
- **Font Awesome** - Icons

### **Build Tools**
- **Vite** - Fast build tool
- **Laravel Mix** - Asset compilation

---

## 🔄 **Update & Maintenance**

### **Cara Update Layout**
1. Edit file layout di `resources/views/layouts/`
2. Update partials di `resources/views/partials/`
3. Run `npm run build` untuk compile assets
4. Test di multiple breakpoints

### **Monitoring**
- ✅ Check console untuk errors
- ✅ Test responsive design
- ✅ Validate HTML structure
- ✅ Performance audit dengan DevTools

---

## 📞 **Support**

Jika ada pertanyaan atau issues terkait layout system:
1. Check dokumentasi ini terlebih dahulu
2. Review contoh implementasi
3. Test di browser yang berbeda
4. Hubungi developer untuk assistance

**Happy coding! 🚀** 