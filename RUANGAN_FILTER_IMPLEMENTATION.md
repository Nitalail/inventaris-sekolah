# Implementasi Filter Ruangan Aktif

## Deskripsi
Implementasi filter untuk menampilkan hanya ruangan yang aktif (status = 'aktif') di pilihan dropdown pada form barang dan laporan, sehingga ruangan yang sedang diperbaiki atau tidak aktif tidak akan muncul sebagai pilihan.

## Perubahan yang Dilakukan

### 1. Model Ruangan (`app/Models/Ruangan.php`)

#### Menambahkan Scope Methods
```php
// Scope untuk ruangan yang aktif
public function scopeActive($query)
{
    return $query->where('status', 'aktif');
}

// Scope untuk ruangan yang tidak aktif
public function scopeInactive($query)
{
    return $query->where('status', '!=', 'aktif');
}

// Scope untuk ruangan yang sedang diperbaiki
public function scopeUnderMaintenance($query)
{
    return $query->where('status', 'perbaikan');
}
```

### 2. BarangController (`app/Http/Controllers/Admin/BarangController.php`)

#### Method `index()`
- **Sebelum**: `$ruangan = Ruangan::all();`
- **Sesudah**: `$ruangan = Ruangan::active()->get();`

#### Method `create()`
- **Sebelum**: `$ruangan = Ruangan::all();`
- **Sesudah**: `$ruangan = Ruangan::active()->get();`

#### Method `edit()`
- **Sebelum**: `$ruangan = Ruangan::all();`
- **Sesudah**: `$ruangan = Ruangan::active()->get();`

### 3. LaporanController (`app/Http/Controllers/Admin/LaporanController.php`)

#### Method `index()`
- **Sebelum**: `$ruangans = Ruangan::all();`
- **Sesudah**: `$ruangans = Ruangan::active()->get();`

## Status Ruangan

### Status yang Diperhitungkan
- ✅ `aktif` - Ruangan aktif dan bisa digunakan
- ❌ `perbaikan` - Ruangan sedang diperbaiki (tidak muncul di dropdown)
- ❌ `tidak_aktif` - Ruangan tidak aktif (tidak muncul di dropdown)

## Dampak Perubahan

### 1. Halaman Barang (`/admin/barang`)
- **Filter Ruangan**: Hanya menampilkan ruangan aktif
- **Form Tambah Barang**: Hanya ruangan aktif yang bisa dipilih
- **Form Edit Barang**: Hanya ruangan aktif yang bisa dipilih

### 2. Halaman Laporan (`/admin/laporan`)
- **Filter Ruangan**: Hanya menampilkan ruangan aktif di filter laporan
- **Generate Laporan**: Hanya bisa filter berdasarkan ruangan aktif

### 3. Konsistensi Data
- **Barang Baru**: Hanya bisa dibuat di ruangan aktif
- **Edit Barang**: Hanya bisa dipindah ke ruangan aktif
- **Laporan**: Hanya menampilkan data ruangan aktif

## Halaman yang Tidak Terpengaruh

### 1. Halaman Ruangan (`/admin/ruangan`)
- **Tetap menampilkan semua ruangan** (aktif, perbaikan, tidak aktif)
- **Admin masih bisa mengelola status ruangan**
- **Validasi tetap berjalan** (tidak bisa nonaktifkan ruangan yang masih ada barangnya)

### 2. Dashboard Admin
- **Tetap menampilkan statistik semua ruangan**
- **Perhitungan total tidak berubah**

## Testing

### Test Case 1: Ruangan Aktif
1. Buat ruangan dengan status 'aktif'
2. Buka halaman tambah barang
3. Verifikasi ruangan muncul di dropdown

### Test Case 2: Ruangan Perbaikan
1. Ubah status ruangan menjadi 'perbaikan'
2. Buka halaman tambah barang
3. Verifikasi ruangan tidak muncul di dropdown

### Test Case 3: Ruangan Tidak Aktif
1. Ubah status ruangan menjadi 'tidak_aktif'
2. Buka halaman tambah barang
3. Verifikasi ruangan tidak muncul di dropdown

### Test Case 4: Edit Barang
1. Buat barang di ruangan aktif
2. Ubah status ruangan menjadi 'perbaikan'
3. Edit barang tersebut
4. Verifikasi hanya ruangan aktif yang tersedia di dropdown

### Test Case 5: Filter Laporan
1. Buka halaman laporan
2. Verifikasi hanya ruangan aktif yang muncul di filter

## Keuntungan Implementasi

### 1. User Experience
- **Mengurangi kebingungan**: User tidak akan memilih ruangan yang tidak bisa digunakan
- **Data yang konsisten**: Barang hanya dibuat di ruangan yang aktif
- **Validasi otomatis**: Tidak perlu validasi tambahan di frontend

### 2. Data Integrity
- **Konsistensi data**: Barang selalu berada di ruangan aktif
- **Laporan yang akurat**: Hanya menampilkan data ruangan yang relevan
- **Maintenance yang mudah**: Scope methods memudahkan maintenance

### 3. Business Logic
- **Logika bisnis yang jelas**: Ruangan non-aktif tidak bisa digunakan
- **Pencegahan error**: Tidak ada barang yang "terjebak" di ruangan non-aktif
- **Fleksibilitas**: Admin masih bisa mengelola status ruangan di halaman ruangan

## Scope Methods yang Tersedia

### Ruangan::active()
```php
// Mengambil semua ruangan yang aktif
$activeRooms = Ruangan::active()->get();
```

### Ruangan::inactive()
```php
// Mengambil semua ruangan yang tidak aktif (perbaikan + tidak_aktif)
$inactiveRooms = Ruangan::inactive()->get();
```

### Ruangan::underMaintenance()
```php
// Mengambil ruangan yang sedang diperbaiki
$maintenanceRooms = Ruangan::underMaintenance()->get();
```

## Kesimpulan

Implementasi ini memastikan bahwa:
1. **User hanya bisa memilih ruangan yang aktif** di form barang
2. **Data tetap konsisten** dengan hanya menampilkan ruangan yang relevan
3. **Maintenance lebih mudah** dengan scope methods yang jelas
4. **Business logic yang solid** dengan pemisahan yang jelas antara ruangan aktif dan non-aktif

Implementasi sudah siap digunakan dan akan memberikan pengalaman yang lebih baik bagi admin dalam mengelola inventaris sekolah. 