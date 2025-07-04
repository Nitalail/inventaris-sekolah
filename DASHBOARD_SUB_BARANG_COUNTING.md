# Dashboard Sub Barang Counting Logic

## Deskripsi
Implementasi logika perhitungan sub barang di dashboard admin yang mengecualikan sub barang dengan status 'nonaktif' dari total count.

## Perubahan yang Dilakukan

### 1. DashboardController (`app/Http/Controllers/DashboardController.php`)

#### Method `calculateBasicStats()`
- **Sebelum**: `$totalSubBarang = SubBarang::count();`
- **Sesudah**: `$totalSubBarang = SubBarang::where('kondisi', '!=', 'nonaktif')->count();`

#### Method `calculateBorrowedChange()`
- **Sebelum**: Query tidak memfilter kondisi sub barang
- **Sesudah**: Query menambahkan filter `WHERE sub_barang.kondisi != 'nonaktif'`

#### Method `getLowStockItems()`
- **Sebelum**: `$query->whereIn('kondisi', ['baik', 'rusak_ringan'])`
- **Sesudah**: Tetap sama (sudah benar, hanya menghitung yang bisa dipinjam)

### 2. BarangController (`app/Http/Controllers/Admin/BarangController.php`)

#### Method `index()`
- **Sebelum**: `->withCount('subBarang as sub_barang_count')`
- **Sesudah**: `->withCount(['subBarang as sub_barang_count' => function ($query) { $query->where('kondisi', '!=', 'nonaktif'); }])`

## Logika Perhitungan

### Total Sub Barang
```php
// Hanya menghitung sub barang yang aktif (tidak nonaktif)
$totalSubBarang = SubBarang::where('kondisi', '!=', 'nonaktif')->count();
```

### Sub Barang yang Dipinjam
```php
// Hanya menghitung sub barang aktif yang sedang dipinjam
$borrowedSubBarang = DB::select("
    SELECT COUNT(DISTINCT sub_barang.id) as count
    FROM sub_barang 
    WHERE sub_barang.kondisi != 'nonaktif'
    AND EXISTS (
        SELECT 1 FROM peminjaman 
        WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
        AND peminjaman.status IN ('pending', 'dipinjam', 'dikonfirmasi')
    )
")[0]->count ?? 0;
```

### Perubahan Bulanan
```php
// Hanya menghitung perubahan untuk sub barang aktif
$totalSubBarangLastMonth = SubBarang::where('kondisi', '!=', 'nonaktif')
    ->where('created_at', '<=', $lastMonth)
    ->count();
```

## Status Sub Barang

### Kondisi yang Diperhitungkan
- ✅ `baik` - Sub barang dalam kondisi baik
- ✅ `rusak_ringan` - Sub barang rusak ringan (masih bisa dipinjam)
- ✅ `rusak_berat` - Sub barang rusak berat (tidak bisa dipinjam tapi masih dihitung)
- ❌ `nonaktif` - Sub barang nonaktif (tidak dihitung dalam total)

### Kondisi yang Bisa Dipinjam
- ✅ `baik` - Bisa dipinjam
- ✅ `rusak_ringan` - Bisa dipinjam
- ❌ `rusak_berat` - Tidak bisa dipinjam
- ❌ `nonaktif` - Tidak bisa dipinjam

## Dampak Perubahan

### 1. Dashboard Admin
- **Total Sub Barang**: Hanya menampilkan sub barang aktif
- **Item Dipinjam**: Hanya menghitung sub barang aktif yang dipinjam
- **Persentase Perubahan**: Berdasarkan sub barang aktif saja

### 2. Halaman Barang
- **Count per Barang**: Hanya menghitung sub barang aktif
- **Tampilan**: Lebih akurat untuk inventory management

### 3. Low Stock Items
- **Perhitungan**: Tetap menggunakan logika yang sama (baik + rusak_ringan)
- **Tidak Terpengaruh**: Karena sudah benar dari awal

## Testing

### Test Case 1: Sub Barang Nonaktif
1. Buat sub barang dengan kondisi 'nonaktif'
2. Buka dashboard admin
3. Verifikasi total sub barang tidak bertambah

### Test Case 2: Sub Barang Aktif
1. Buat sub barang dengan kondisi 'baik'
2. Buka dashboard admin
3. Verifikasi total sub barang bertambah

### Test Case 3: Perubahan Status
1. Ubah status sub barang dari 'baik' ke 'nonaktif'
2. Refresh dashboard admin
3. Verifikasi total sub barang berkurang

### Test Case 4: Peminjaman
1. Pinjam sub barang dengan kondisi 'baik'
2. Buka dashboard admin
3. Verifikasi "Item Dipinjam" bertambah

## Konsistensi

### Model SubBarang
- Sudah memiliki scope `active()` dan `inactive()`
- Sudah memiliki method `deactivate()` dan `activate()`
- Sudah memiliki accessor untuk status text dan class

### Model Barang
- Sudah memiliki method `getAvailableSubBarangCountAttribute()`
- Sudah memiliki scope `availableForLoan()`

### AvailableStockTrait
- Sudah menggunakan constraint yang benar
- Sudah memfilter kondisi yang tepat

## Kesimpulan

Perubahan ini memastikan bahwa:
1. **Dashboard menampilkan data yang akurat** - hanya sub barang aktif
2. **Inventory management lebih presisi** - tidak menghitung barang nonaktif
3. **Konsistensi data** - semua perhitungan menggunakan logika yang sama
4. **User experience lebih baik** - admin melihat data yang relevan

Implementasi ini sudah siap digunakan dan akan memberikan gambaran yang lebih akurat tentang inventaris sekolah yang aktif. 