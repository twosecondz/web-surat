# Panduan Meletakkan Gambar

## 📁 Lokasi Folder

Semua gambar harus diletakkan di folder:
```
public/images/
```

## 🖼️ Gambar yang Diperlukan

### 1. Logo untuk Halaman Login
**File**: `public/images/logo-pancacita.png`

**Digunakan di**:
- `resources/views/auth/login.blade.php` (halaman login)

**Cara akses di Blade**:
```blade
<img src="{{ asset('images/logo-pancacita.png') }}" alt="Logo">
```

### 2. Logo untuk Kop Surat (PDF)
**File**: `public/images/logo-pancacita.png` (sama dengan logo login)

**Digunakan di**:
- `resources/views/pdf/surat-tugas.blade.php` (PDF Surat Tugas)
- `resources/views/pdf/surat-spd.blade.php` (PDF SPD)

**Cara akses di PDF Blade**:
```blade
<img src="{{ public_path('images/logo-pancacita.png') }}" style="width: 70px;">
```

## 📂 Struktur Folder

```
bpka-new/
├── public/
│   ├── images/
│   │   ├── logo-pancacita.png    ← Taruh gambar di sini
│   │   └── .gitkeep
│   └── ...
└── ...
```

## ✅ Langkah-langkah

1. **Siapkan gambar logo**:
   - Format: PNG (disarankan) atau JPG
   - Nama file: `logo-pancacita.png`
   - Ukuran: Disarankan 200x200px atau lebih besar (akan di-resize otomatis)

2. **Copy gambar ke folder**:
   ```
   public/images/logo-pancacita.png
   ```

3. **Verifikasi**:
   - Halaman login: Logo akan muncul di sisi kanan
   - PDF Surat Tugas: Logo akan muncul di kop surat (kiri atas)
   - PDF SPD: Logo akan muncul di kop surat (kiri atas)

## 🔍 Cara Mengakses Gambar

### Di Blade View (HTML)
```blade
{{ asset('images/logo-pancacita.png') }}
```
**Hasil**: `http://localhost:8000/images/logo-pancacita.png`

### Di PDF (DomPDF)
```blade
{{ public_path('images/logo-pancacita.png') }}
```
**Hasil**: `C:\Users\ASUS\Music\bpka-new\public\images\logo-pancacita.png`

## ⚠️ Catatan Penting

1. **Nama file harus sama persis**: `logo-pancacita.png` (case-sensitive)
2. **Folder harus ada**: Pastikan folder `public/images/` ada
3. **Permission**: Pastikan file gambar bisa dibaca oleh web server
4. **Format gambar**: PNG dengan transparansi lebih baik untuk logo

## 🧪 Testing

Setelah meletakkan gambar:

1. **Test di browser**:
   ```
   http://localhost:8000/images/logo-pancacita.png
   ```
   Jika gambar muncul, berarti sudah benar.

2. **Test di halaman login**:
   ```
   http://localhost:8000/login
   ```
   Logo harus muncul di sisi kanan.

3. **Test di PDF**:
   - Buat Surat Tugas
   - Download PDF
   - Logo harus muncul di kop surat (kiri atas)

## 📝 File yang Menggunakan Gambar

| File | Path Gambar | Fungsi |
|------|-------------|--------|
| `resources/views/auth/login.blade.php` | `asset('images/logo-pancacita.png')` | Logo di halaman login |
| `resources/views/pdf/surat-tugas.blade.php` | `public_path('images/logo-pancacita.png')` | Logo di kop surat PDF |
| `resources/views/pdf/surat-spd.blade.php` | `public_path('images/logo-pancacita.png')` | Logo di kop surat PDF |

## 🎨 Rekomendasi Spesifikasi Gambar

- **Format**: PNG (dengan transparansi) atau JPG
- **Ukuran**: Minimal 200x200px, maksimal 500x500px
- **Background**: Transparan (untuk PNG) atau putih
- **Resolusi**: 72-150 DPI (cukup untuk web dan PDF)

---

**Lokasi folder**: `public/images/`  
**Nama file**: `logo-pancacita.png`  
**Status**: ✅ Folder sudah ada, tinggal taruh gambar
