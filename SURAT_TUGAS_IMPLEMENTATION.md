# Surat Tugas Form Implementation

**Date**: January 21, 2025  
**Feature**: Create Surat Tugas with Dynamic Participants  
**Status**: ✅ Complete

---

## 🎯 Overview

A Livewire-powered form for creating **Surat Tugas** (Official Duty Letters) with:
- Dynamic participant management (add/remove multiple employees)
- Auto-fill employee data from database
- Seamless redirect to SPD creation after save
- Form validation with Indonesian messages
- Responsive Tailwind CSS design

---

## ✅ What Was Created

### 1. Livewire Components

**`app/Livewire/CreateSuratTugas.php`**
- Dynamic participant array management
- User dropdown with auto-fill
- Form validation
- Database transaction handling
- Auto-redirect to SPD creation

**`app/Livewire/CreateSuratPerjalananDinas.php`**
- SPD form creation
- Auto-fills data from Surat Tugas
- Links SPD to parent Surat Tugas

### 2. Blade Views

**`resources/views/livewire/create-surat-tugas.blade.php`**
- Matches the design from Form Surat Tugas.png
- Dynamic participant cards
- Add/remove employee buttons
- Dropdown selection with auto-fill

**`resources/views/livewire/create-surat-perjalanan-dinas.blade.php`**
- SPD creation form
- Shows parent Surat Tugas info
- Employee selection from participants

**`resources/views/layouts/app.blade.php`**
- Main application layout
- Navigation with auth
- Livewire integration

### 3. Routes

Updated `routes/web.php`:
```php
// Surat Tugas
Route::get('/surat-tugas/create', CreateSuratTugas::class)
    ->name('surat-tugas.create');

// SPD
Route::get('/spd/create', CreateSuratPerjalananDinas::class)
    ->name('spd.create');
```

### 4. Dashboard Integration

Updated dashboard with "Buat Surat Tugas" button.

---

## 🎨 UI Features

### Form Sections

#### 1. Informasi Surat
- **Nomor Surat** - Auto-generated (800.1.11.X/ST/YYYY)
- **Kode Rekening** - Budget code input
- **Dasar** - Legal basis (textarea)
- **Maksud Perjalanan Dinas** - Purpose (textarea)
- **Tempat Tujuan** - Destination
- **Tanggal Mulai & Selesai** - Date range

#### 2. Data Pegawai (Dynamic)
- **+ Tambah Pegawai** button
- Multiple participant cards
- Each card includes:
  - **Nama Pegawai** - Dropdown with search
  - **Pangkat/Golongan** - Auto-filled (read-only)
  - **NIP** - Auto-filled (read-only)
  - **Jabatan** - Auto-filled (read-only)
  - **Peran** - Role selector (Ketua/Anggota/Pendamping)
  - **Delete button** (if more than 1 participant)

#### 3. Action Buttons
- **Batal** - Cancel and return to dashboard
- **Preview surat** - Save and redirect to SPD form

---

## 🔄 Workflow

### Step 1: Create Surat Tugas

```
User → Dashboard → "Buat Surat Tugas"
   ↓
Fill Form:
├─ Informasi Surat (nomor, dasar, maksud, tempat, tanggal)
└─ Data Pegawai (add participants, auto-fill details)
   ↓
Click "Preview surat"
   ↓
Validation
   ↓
Save to Database:
├─ Insert into surat_tugas
└─ Insert into surat_tugas_peserta (pivot)
   ↓
Redirect to SPD Form with surat_tugas_id
```

### Step 2: Create SPD

```
Auto-filled from Surat Tugas:
├─ Maksud perjalanan
├─ Tempat tujuan
├─ Tanggal berangkat/kembali
└─ Kode rekening
   ↓
Fill SPD-specific fields:
├─ Pegawai (from participants)
├─ PPK & PPTK
├─ Tingkat biaya
├─ Alat transportasi
└─ Keterangan
   ↓
Save to Database:
└─ Insert into surat_perjalanan_dinas
   ↓
Redirect to Dashboard
```

---

## 💻 Key Features

### 1. Dynamic Participant Management

```php
// Add participant
public function addPeserta()
{
    $this->peserta[] = [
        'user_id' => '',
        'nama' => '',
        'nip' => '',
        // ... other fields
        'urutan' => count($this->peserta) + 1,
    ];
}

// Remove participant
public function removePeserta($index)
{
    unset($this->peserta[$index]);
    $this->peserta = array_values($this->peserta);
    
    // Update urutan
    foreach ($this->peserta as $key => $value) {
        $this->peserta[$key]['urutan'] = $key + 1;
    }
}
```

### 2. Auto-fill Employee Data

```php
public function selectUser($index, $userId)
{
    $user = User::find($userId);
    
    if ($user) {
        $this->peserta[$index]['user_id'] = $user->id;
        $this->peserta[$index]['nama'] = $user->name;
        $this->peserta[$index]['nip'] = $user->nip ?? '';
        $this->peserta[$index]['pangkat'] = $user->pangkat ?? '';
        $this->peserta[$index]['golongan'] = $user->golongan ?? '';
        $this->peserta[$index]['jabatan'] = $user->jabatan ?? '';
        $this->peserta[$index]['skpa'] = $user->skpa ?? '';
    }
    
    $this->showDropdown[$index] = false;
}
```

### 3. Database Transaction

```php
try {
    DB::beginTransaction();

    // Create Surat Tugas
    $suratTugas = SuratTugas::create([...]);

    // Attach participants with pivot data
    foreach ($this->peserta as $peserta) {
        $suratTugas->peserta()->attach($peserta['user_id'], [
            'nama' => $peserta['nama'],
            'nip' => $peserta['nip'],
            // ... snapshots
        ]);
    }

    DB::commit();
    
    // Redirect to SPD
    return redirect()->route('spd.create', [
        'surat_tugas_id' => $suratTugas->id
    ]);

} catch (\Exception $e) {
    DB::rollBack();
    session()->flash('error', $e->getMessage());
}
```

### 4. Auto-redirect to SPD

After saving Surat Tugas, the system automatically redirects to SPD creation:

```php
return redirect()->route('spd.create', [
    'surat_tugas_id' => $suratTugas->id
]);
```

The SPD form auto-fills data from the parent Surat Tugas.

---

## 📝 Validation Rules

### Surat Tugas

```php
[
    'nomor_surat' => 'required|string|max:50|unique:surat_tugas',
    'kode_rekening' => 'required|string|max:50',
    'dasar_hukum' => 'required|string',
    'maksud' => 'required|string',
    'tempat_tujuan' => 'required|string|max:255',
    'tanggal_mulai' => 'required|date',
    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    'peserta' => 'required|array|min:1',
    'peserta.*.user_id' => 'required|exists:users,id',
    'peserta.*.nama' => 'required|string',
    'peserta.*.peran' => 'required|in:ketua,anggota,pendamping',
]
```

### Indonesian Error Messages

```php
[
    'nomor_surat.required' => 'Nomor surat harus diisi.',
    'nomor_surat.unique' => 'Nomor surat sudah digunakan.',
    'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
    'peserta.required' => 'Minimal harus ada 1 pegawai.',
    // ...
]
```

---

## 🎨 UI Components

### Color Scheme

```css
Primary Button: bg-yellow-400 hover:bg-yellow-500
Secondary Button: bg-white border border-gray-300
Input Focus: focus:ring-yellow-400
Error Border: border-red-500
Success Message: bg-green-50 border-green-200
Error Message: bg-red-50 border-red-200
```

### Responsive Design

```html
<!-- Desktop: 2 columns -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Mobile: 1 column (automatic) -->
```

### Dropdown with Alpine.js

```html
<div x-data="{ open: @entangle('showDropdown.' . $index) }">
    <input @click="open = !open" />
    
    <div x-show="open" @click.away="open = false">
        <!-- Dropdown items -->
    </div>
</div>
```

---

## 🧪 Testing

### Manual Testing Steps

#### 1. Create Surat Tugas

```
1. Login to system
2. Click "Buat Surat Tugas" from dashboard
3. Fill form fields:
   - Nomor Surat: 800.1.11.1/ST/2025
   - Kode Rekening: 5.1.02.04.01.0001
   - Dasar: Enter legal basis
   - Maksud: Enter purpose
   - Tempat Tujuan: Jakarta
   - Tanggal Mulai: Tomorrow
   - Tanggal Selesai: +2 days
4. Click "+ Tambah Pegawai"
5. Select employee from dropdown
6. Verify auto-filled fields (NIP, Pangkat, Jabatan)
7. Add another participant
8. Select role for each (Ketua/Anggota)
9. Click "Preview surat"
10. Verify redirect to SPD form
```

#### 2. Verify SPD Auto-fill

```
1. After redirect, check SPD form
2. Verify fields auto-filled:
   ✓ Maksud perjalanan
   ✓ Tempat tujuan
   ✓ Tanggal berangkat/kembali
   ✓ Kode rekening
   ✓ Lama perjalanan (calculated)
3. Select pegawai from participants
4. Fill SPD-specific fields
5. Save
6. Verify redirect to dashboard
```

#### 3. Database Verification

```sql
-- Check Surat Tugas
SELECT * FROM surat_tugas ORDER BY id DESC LIMIT 1;

-- Check Participants (pivot)
SELECT * FROM surat_tugas_peserta 
WHERE surat_tugas_id = [last_id];

-- Check SPD
SELECT * FROM surat_perjalanan_dinas 
WHERE surat_tugas_id = [last_id];
```

---

## 📊 Database Schema Usage

### Tables Used

1. **surat_tugas** - Main table for duty letters
2. **surat_tugas_peserta** - Pivot for participants
3. **surat_perjalanan_dinas** - SPD linked to Surat Tugas
4. **users** - Employee data source

### Data Flow

```
users (source)
  ↓ (SELECT for dropdown)
CreateSuratTugas Component
  ↓ (INSERT with snapshots)
surat_tugas + surat_tugas_peserta
  ↓ (REDIRECT with ID)
CreateSuratPerjalananDinas Component
  ↓ (SELECT + auto-fill)
surat_perjalanan_dinas (INSERT)
```

---

## 🔧 Customization

### Change Auto-generated Nomor Format

Edit in `CreateSuratTugas.php`:

```php
public function generateNomorSurat()
{
    // Current: 800.1.11.X/ST/2025
    // Custom: XXX/ST/BPKA/2025
    
    $lastSurat = SuratTugas::whereYear('created_at', date('Y'))
        ->orderBy('id', 'desc')
        ->first();
    
    $number = $lastSurat ? $lastSurat->id + 1 : 1;
    
    return sprintf('%03d/ST/BPKA/%d', $number, date('Y'));
}
```

### Add More Fields

1. **Add to Component**:
```php
public $new_field = '';
```

2. **Add to View**:
```html
<div>
    <label>New Field</label>
    <input wire:model="new_field" />
</div>
```

3. **Add to Validation**:
```php
'new_field' => 'required|string',
```

4. **Add to Save**:
```php
SuratTugas::create([
    // ... existing fields
    'new_field' => $this->new_field,
]);
```

---

## 🐛 Troubleshooting

### Issue: Dropdown not showing

**Solution**: Ensure Alpine.js is loaded (included with Livewire 3)

### Issue: Auto-fill not working

**Solution**: Check `selectUser()` method and verify user data exists

### Issue: Redirect fails

**Solution**: Verify SPD route exists:
```bash
php artisan route:list | grep spd
```

### Issue: Validation errors not showing

**Solution**: Check `@error` directives in blade and ensure field names match

### Issue: Database transaction fails

**Solution**: Check foreign key constraints and ensure all required fields are filled

---

## 🚀 Next Steps

### Phase 1: Current ✅
- [x] Create Surat Tugas form
- [x] Dynamic participant management
- [x] Auto-fill employee data
- [x] Redirect to SPD creation
- [x] SPD form with auto-fill

### Phase 2: Enhancements 🔄
- [ ] List/view existing Surat Tugas
- [ ] Edit Surat Tugas
- [ ] Delete Surat Tugas
- [ ] Search/filter Surat Tugas
- [ ] Bulk actions

### Phase 3: Advanced Features 🔮
- [ ] PDF generation for Surat Tugas
- [ ] PDF generation for SPD
- [ ] Approval workflow
- [ ] Email notifications
- [ ] Document versioning
- [ ] Digital signatures

---

## 📚 Related Documentation

- `DATABASE_SCHEMA.md` - Database structure
- `AUTHENTICATION_GUIDE.md` - Login system
- `QUICK_REFERENCE.md` - Developer reference
- `ERD_DIAGRAM.md` - Visual relationships

---

## ✅ Testing Checklist

### Form Functionality
- [x] Form loads without errors
- [x] All input fields render
- [x] Nomor surat auto-generated
- [x] Add participant button works
- [x] Remove participant button works
- [x] Dropdown shows all users
- [x] Auto-fill populates all fields
- [x] Validation shows errors
- [x] Indonesian error messages display
- [x] Save creates database records
- [x] Redirect to SPD works
- [x] SPD auto-fills from Surat Tugas

### Database Operations
- [x] Surat Tugas created correctly
- [x] Participants attached with pivot data
- [x] Snapshots saved correctly
- [x] SPD links to Surat Tugas
- [x] Foreign keys work
- [x] Timestamps set
- [x] Status defaults to 'draft'

### UI/UX
- [x] Responsive on mobile
- [x] Responsive on desktop
- [x] Colors match design
- [x] Buttons styled correctly
- [x] Error messages visible
- [x] Success messages show
- [x] Loading states work
- [x] Dropdown closes on select
- [x] Form submits without refresh

---

## 📈 Performance

### Optimizations

✅ **Eager Loading** - Load users once on mount  
✅ **Efficient Queries** - Single query for participants  
✅ **Transaction Safety** - DB rollback on error  
✅ **Livewire** - No page refreshes  
✅ **Alpine.js** - Client-side dropdown logic

### Load Times (Expected)

- Form load: < 500ms
- Add participant: Instant (client-side)
- Dropdown open: Instant
- Form submit: < 2s (includes DB + redirect)

---

## 🎉 Summary

**Status**: ✅ **Fully Functional**

The Surat Tugas form is complete with:
- ✅ Dynamic participant management
- ✅ Auto-fill from user database
- ✅ Proper validation
- ✅ Database transactions
- ✅ Auto-redirect to SPD
- ✅ Responsive design
- ✅ Indonesian messages

**The system now supports the complete workflow from Surat Tugas creation through SPD generation.**

---

**Version**: 1.0  
**Last Updated**: January 21, 2025  
**Next Phase**: Document Listing & Management

---

*Built with ❤️ for BPKA using Laravel 11 + Livewire 3*
