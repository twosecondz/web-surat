# Setup Guide - Sistem Informasi Perjalanan Dinas

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for Tailwind CSS)

## Installation Steps

### 1. Clone & Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Configuration

Edit `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipd_bpka
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Create Database

Create a new MySQL database:

```sql
CREATE DATABASE sipd_bpka CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations

```bash
# Run all migrations
php artisan migrate

# Or run with seeding
php artisan migrate --seed
```

### 6. Seed Sample Data (Optional)

```bash
# Seed database with sample users and documents
php artisan db:seed
```

This will create:
- **6 sample users** including officials from BPKA
- **1 sample Surat Tugas** with 3 participants
- **1 sample Surat Perjalanan Dinas** with journey details

**Test Login Credentials:**
```
Email: mohammad.iqbal@bpka.acehprov.go.id
Password: password
```

### 7. Install Livewire & DomPDF

```bash
# Install Livewire 3
composer require livewire/livewire

# Install DomPDF for PDF generation
composer require barryvdh/laravel-dompdf
```

### 8. Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## Database Structure

The system uses 6 main tables:

1. **users** - User accounts with employee details (NIP, pangkat, golongan, etc.)
2. **surat_tugas** - Official duty letters
3. **surat_tugas_peserta** - Pivot table for duty letter participants
4. **surat_perjalanan_dinas** - Official travel documents (SPD)
5. **spd_pengikut** - Travel followers/dependents
6. **spd_perjalanan** - Journey legs/stops

See `DATABASE_SCHEMA.md` for detailed documentation.

---

## User Roles & Accounts

### Sample Users Created by Seeder:

| Name | Email | Role | NIP |
|------|-------|------|-----|
| Reza Saputra, SSTP, M.Si | reza.saputra@bpka.acehprov.go.id | Kepala Badan | 19800103199810102 |
| Ramzi, M.Si | ramzi@bpka.acehprov.go.id | Sekretaris | 19740904200803101 |
| Sudirman, SE | sudirman@bpka.acehprov.go.id | Kepala Bidang | 19691126199003101 |
| Mohammad Iqbal, SE | mohammad.iqbal@bpka.acehprov.go.id | Kasubbid | 19831013200504101 |
| Mudatsir Syahputra, S.I.Kom | mudatsir@bpka.acehprov.go.id | PPK | 47220130546200000 |
| Test User | test@example.com | Staff | 19900101202001101 |

**All passwords:** `password`

---

## Workflow Overview

### Creating Documents:

1. **Login** with NIP/Email
2. **Create Surat Tugas**:
   - Fill in duty details
   - Add participants
   - Set signatory
   - Save/Submit
3. **Create SPD** (linked to Surat Tugas):
   - Data auto-filled from Surat Tugas
   - Complete SPD-specific fields
   - Add followers (optional)
   - Add journey legs (optional)
   - Save/Submit
4. **Approval Process**:
   - Documents flow through status: draft → submitted → approved/rejected
5. **Generate PDF**:
   - Print official documents

---

## Key Features

### 1. Data Snapshot Pattern
When creating documents, employee data (name, NIP, rank, etc.) is saved as a snapshot. This ensures documents show historical data even if employee profiles change later.

### 2. Multiple Participants
Surat Tugas can have multiple participants with defined roles (ketua, anggota, pendamping).

### 3. Multi-Leg Journeys
SPD supports complex travel itineraries with multiple stops and intermediate destinations.

### 4. Flexible Authorization
Officials (PPK, PPTK, Penandatangan) can be system users or entered manually if they're not in the system.

### 5. Status Workflow
Both document types use status tracking:
- **draft**: Being edited
- **submitted**: Submitted for approval
- **approved**: Approved by authority
- **rejected**: Rejected (needs revision)
- **completed**: Process completed

---

## Development Commands

### Migration Commands
```bash
# Run migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Rollback all and re-run
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Seeder Commands
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SampleDataSeeder
```

### Model Commands
```bash
# Create new model with migration
php artisan make:model ModelName -m

# Create factory
php artisan make:factory ModelNameFactory
```

### Livewire Commands
```bash
# Create Livewire component
php artisan make:livewire ComponentName

# Create full-page component
php artisan livewire:make ComponentName --full
```

---

## Testing Database Schema

After running migrations and seeders, verify the setup:

```bash
# Open Laravel Tinker
php artisan tinker
```

```php
// Test queries
use App\Models\User;
use App\Models\SuratTugas;
use App\Models\SuratPerjalananDinas;

// Get all users
User::count(); // Should return 6

// Get Surat Tugas with participants
$st = SuratTugas::with('peserta')->first();
$st->peserta; // Should show 3 participants

// Get SPD with relationships
$spd = SuratPerjalananDinas::with(['suratTugas', 'pegawai', 'perjalanan'])->first();
$spd->pegawai->name; // Should show "Mohammad Iqbal, SE"
$spd->suratTugas->nomor_surat; // Should show "800.1.11.1/ST/"
```

---

## Next Development Steps

### Phase 1: Authentication & Authorization
- [ ] Setup Laravel Breeze or custom auth
- [ ] Implement NIP/NIK login
- [ ] Create user roles & permissions
- [ ] Build authorization policies

### Phase 2: CRUD Operations
- [ ] Livewire form for Surat Tugas
- [ ] Livewire form for SPD
- [ ] Participant management UI
- [ ] Journey legs management UI

### Phase 3: Workflow & Approvals
- [ ] Implement approval system
- [ ] Email notifications
- [ ] Status change tracking
- [ ] Approval history log

### Phase 4: PDF Generation
- [ ] Design Blade template for Surat Tugas
- [ ] Design Blade template for SPD (2 pages)
- [ ] Implement DomPDF generation
- [ ] Add digital signatures

### Phase 5: Dashboard & Reports
- [ ] Admin dashboard
- [ ] User dashboard
- [ ] Travel reports
- [ ] Budget tracking
- [ ] Export to Excel

### Phase 6: Additional Features
- [ ] Document archiving
- [ ] Search & filter
- [ ] Notifications system
- [ ] Activity logs
- [ ] API endpoints

---

## Troubleshooting

### Migration Errors

**Error: Syntax error or access violation: 1071 Specified key was too long**

Solution: Add to `config/database.php` in mysql connection:
```php
'charset' => 'utf8mb4',
'collation' => 'utf8mb4_unicode_ci',
```

And in `AppServiceProvider::boot()`:
```php
use Illuminate\Support\Facades\Schema;

Schema::defaultStringLength(191);
```

### Foreign Key Constraint Errors

If you get foreign key errors when migrating:
```bash
# Drop all tables and re-run
php artisan migrate:fresh
```

### Seeder Errors

If seeder fails with "User not found":
```bash
# Run seeders in order
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SampleDataSeeder
```

---

## File Structure

```
database/
├── migrations/
│   ├── 2024_01_21_000001_add_employee_fields_to_users_table.php
│   ├── 2024_01_21_000002_create_surat_tugas_table.php
│   ├── 2024_01_21_000003_create_surat_tugas_peserta_table.php
│   ├── 2024_01_21_000004_create_surat_perjalanan_dinas_table.php
│   ├── 2024_01_21_000005_create_spd_pengikut_table.php
│   └── 2024_01_21_000006_create_spd_perjalanan_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   └── SampleDataSeeder.php
│
app/
└── Models/
    ├── User.php
    ├── SuratTugas.php
    ├── SuratPerjalananDinas.php
    ├── SpdPengikut.php
    └── SpdPerjalanan.php
```

---

## Support & Documentation

- **Database Schema**: See `DATABASE_SCHEMA.md`
- **Laravel Docs**: https://laravel.com/docs/11.x
- **Livewire Docs**: https://livewire.laravel.com/docs
- **DomPDF Docs**: https://github.com/barryvdh/laravel-dompdf

---

## License

This project is proprietary software for BPKA (Badan Pengelolaan Keuangan Aceh).

---

*Last Updated: January 21, 2025*
