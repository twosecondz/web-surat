# Quick Reference - SIPD Database

## 🚀 Quick Start

```bash
# Setup
composer install
cp .env.example .env
php artisan key:generate

# Configure .env database settings
DB_DATABASE=sipd_bpka

# Migrate & Seed
php artisan migrate --seed

# Start server
php artisan serve
```

**Login:** `mohammad.iqbal@bpka.acehprov.go.id` / `password`

---

## 📊 Database Tables

| Table | Purpose | Key Relationships |
|-------|---------|-------------------|
| `users` | User accounts + employee data | Creates/signs/participates in documents |
| `surat_tugas` | Official duty letters | Parent of SPD, has many participants |
| `surat_tugas_peserta` | Participants pivot table | Links users to surat_tugas |
| `surat_perjalanan_dinas` | Official travel documents | Child of surat_tugas |
| `spd_pengikut` | Travel followers/dependents | Child of SPD |
| `spd_perjalanan` | Journey legs/stops | Child of SPD |

---

## 🔑 Key Fields

### Users Table Extensions
```
nip, nik, pangkat, golongan, jabatan, skpa, eselon
```

### Surat Tugas Core Fields
```
nomor_surat, dasar_hukum, maksud, tempat_tujuan
tanggal_mulai, tanggal_selesai, kode_rekening
penandatangan_id, created_by, status
```

### SPD Core Fields
```
surat_tugas_id (FK), nomor_spd
pegawai_id, ppk_id, pptk_id
tingkat_biaya (A/B/C/D)
tempat_berangkat, tempat_tujuan
tanggal_berangkat, tanggal_kembali
status
```

---

## 🔗 Model Relationships

### User Model
```php
$user->suratTugasCreated      // Surat Tugas created
$user->suratTugasSigned       // Surat Tugas signed
$user->suratTugasPeserta      // Participant in (M:N)
$user->suratPerjalananDinas   // SPD as traveler
$user->spdAsPpk               // SPD as PPK
$user->spdAsPptk              // SPD as PPTK
```

### SuratTugas Model
```php
$st->creator                  // User who created
$st->penandatangan            // Signatory user
$st->peserta                  // Participants (M:N)
$st->suratPerjalananDinas     // Related SPDs
```

### SuratPerjalananDinas Model
```php
$spd->suratTugas             // Parent Surat Tugas
$spd->pegawai                // Traveler
$spd->ppk                    // PPK user
$spd->pptk                   // PPTK user
$spd->pengikut               // Followers
$spd->perjalanan             // Journey legs
```

---

## 📝 Status Workflow

```
draft → submitted → approved/rejected → completed
                       ↓
                    revision
                       ↓
                     draft
```

**Status Values:**
- `draft` - Being edited
- `submitted` - Waiting approval
- `approved` - Approved
- `rejected` - Needs revision
- `completed` - Travel completed

---

## 🎯 Common Queries

### Get Surat Tugas with Participants
```php
SuratTugas::with(['peserta' => fn($q) => $q->orderBy('urutan')])
    ->find($id);
```

### Get SPD with Complete Data
```php
SuratPerjalananDinas::with([
    'suratTugas',
    'pegawai',
    'ppk',
    'pptk',
    'pengikut',
    'perjalanan'
])->find($id);
```

### Pending Approvals
```php
SuratTugas::where('status', 'submitted')
    ->orderBy('tanggal_surat', 'desc')
    ->get();
```

### User's Travels
```php
$user->suratTugasPeserta()
    ->wherePivot('peran', 'ketua')
    ->get();
```

---

## 🔧 Eloquent Patterns

### Snapshot Pattern
```php
// Store user data at creation time
'pegawai_id' => $user->id,
'pegawai_nama' => $user->name,      // Snapshot
'pegawai_nip' => $user->nip,        // Snapshot
'pegawai_pangkat' => $user->pangkat,// Snapshot
```

### Pivot with Extra Data
```php
$suratTugas->peserta()->attach($userId, [
    'nama' => $user->name,
    'nip' => $user->nip,
    'urutan' => 1,
    'peran' => 'ketua',
]);
```

### Accessing Pivot Data
```php
foreach ($suratTugas->peserta as $peserta) {
    echo $peserta->pivot->urutan;
    echo $peserta->pivot->peran;
}
```

---

## 🎨 Form Validation

### Surat Tugas Rules
```php
'nomor_surat' => 'required|unique:surat_tugas',
'tanggal_mulai' => 'required|date',
'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
'peserta' => 'required|array|min:1',
'peserta.*.user_id' => 'required|exists:users,id',
```

### SPD Rules
```php
'surat_tugas_id' => 'required|exists:surat_tugas,id',
'nomor_spd' => 'required|unique:surat_perjalanan_dinas',
'tingkat_biaya' => 'nullable|in:A,B,C,D',
'tanggal_berangkat' => 'required|date',
'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
```

---

## 🔍 Seeder Data

### Users Created
- **Kepala Badan**: Reza Saputra (19800103199810102)
- **Sekretaris**: Ramzi (19740904200803101)
- **Kepala Bidang**: Sudirman (19691126199003101)
- **Kasubbid**: Mohammad Iqbal (19831013200504101)
- **PPK**: Mudatsir Syahputra (47220130546200000)
- **Test User**: test@example.com (19900101202001101)

### Sample Documents
- 1 Surat Tugas (3 participants)
- 1 SPD (with journey legs)

---

## 📋 Artisan Commands

### Migrations
```bash
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback last batch
php artisan migrate:fresh        # Drop all & re-run
php artisan migrate:fresh --seed # Fresh + seed
```

### Seeders
```bash
php artisan db:seed                           # All seeders
php artisan db:seed --class=UserSeeder        # Specific seeder
php artisan db:seed --class=SampleDataSeeder  # Sample data
```

### Testing
```bash
php artisan tinker              # Interactive shell
```

---

## 🎯 Development Checklist

### Phase 1: Auth ✅
- [x] Database schema
- [x] Models with relationships
- [ ] Authentication system
- [ ] Authorization policies

### Phase 2: Forms
- [ ] Livewire: Surat Tugas form
- [ ] Livewire: SPD form
- [ ] Participant management UI
- [ ] Journey legs UI

### Phase 3: Workflow
- [ ] Approval system
- [ ] Status transitions
- [ ] Notifications
- [ ] Activity log

### Phase 4: PDF
- [ ] Surat Tugas template
- [ ] SPD template (2 pages)
- [ ] DomPDF integration
- [ ] Digital signatures

### Phase 5: Dashboard
- [ ] Admin dashboard
- [ ] User dashboard
- [ ] Reports & analytics
- [ ] Export functions

---

## 📚 Documentation Files

- `DATABASE_SCHEMA.md` - Complete schema documentation
- `ERD_DIAGRAM.md` - Visual ERD with Mermaid diagrams
- `SETUP_GUIDE.md` - Installation & setup instructions
- `QUICK_REFERENCE.md` - This file (quick reference)

---

## 🐛 Troubleshooting

### Foreign Key Errors
```bash
php artisan migrate:fresh  # Drop all and re-run
```

### Seeder Issues
```bash
# Run in order
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SampleDataSeeder
```

### String Length Errors
Add to `AppServiceProvider::boot()`:
```php
Schema::defaultStringLength(191);
```

---

## 💡 Tips & Best Practices

### 1. Always Load Relationships
```php
// Good - Eager loading
$spd = SuratPerjalananDinas::with(['suratTugas', 'pegawai'])->find($id);

// Bad - N+1 problem
$spd = SuratPerjalananDinas::find($id);
$spd->suratTugas; // Additional query
$spd->pegawai;    // Another additional query
```

### 2. Use Scopes for Status
```php
// In model
public function scopeApproved($query) {
    return $query->where('status', 'approved');
}

// Usage
SuratTugas::approved()->get();
```

### 3. Validate Date Ranges
```php
'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
```

### 4. Snapshot Important Data
Always store user data that might change:
```php
'pegawai_nama' => $user->name,  // Not just the ID
```

### 5. Order Pivot Results
```php
$suratTugas->peserta()->orderBy('urutan')->get();
```

---

## 🔐 Security Considerations

- [ ] Validate user permissions before CRUD operations
- [ ] Implement policies for sensitive actions
- [ ] Sanitize inputs
- [ ] Use mass assignment protection
- [ ] Audit trail for status changes
- [ ] Soft deletes for data recovery

---

## 📞 Contact & Support

**Project**: Sistem Informasi Perjalanan Dinas  
**Organization**: Badan Pengelolaan Keuangan Aceh  
**Tech Stack**: Laravel 11, Livewire 3, Tailwind CSS, DomPDF

---

*Quick Reference v1.0 - January 21, 2025*
