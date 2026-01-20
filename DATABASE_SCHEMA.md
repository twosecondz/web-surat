# Database Schema Documentation
## Sistem Informasi Perjalanan Dinas (SIPD)

This document outlines the database structure for the Official Travel Management System.

---

## Table of Contents
1. [Users Table](#users-table)
2. [Surat Tugas Table](#surat-tugas-table)
3. [Surat Tugas Peserta Table](#surat-tugas-peserta-table)
4. [Surat Perjalanan Dinas Table](#surat-perjalanan-dinas-table)
5. [SPD Pengikut Table](#spd-pengikut-table)
6. [SPD Perjalanan Table](#spd-perjalanan-table)
7. [Relationships](#relationships)
8. [Workflow](#workflow)

---

## Users Table

Extended Laravel's default users table with employee-specific fields.

### Additional Columns:
| Column | Type | Description |
|--------|------|-------------|
| `nip` | string(18), unique, nullable | Nomor Induk Pegawai (Employee ID) |
| `nik` | string(16), nullable | Nomor Induk Kependudukan (National ID) |
| `pangkat` | string(100), nullable | Rank/Grade (e.g., Penata Tk. I) |
| `golongan` | string(20), nullable | Class/Group (e.g., III/d) |
| `jabatan` | string, nullable | Position/Role |
| `skpa` | string, nullable | Satuan Kerja Perangkat Aceh (Work Unit) |
| `eselon` | string(10), nullable | Echelon level |
| `alamat` | text, nullable | Address |
| `no_telepon` | string(20), nullable | Phone number |

### Relationships:
- `suratTugasCreated()` - Surat Tugas created by this user
- `suratTugasSigned()` - Surat Tugas signed by this user
- `suratTugasPeserta()` - Surat Tugas where user is a participant (many-to-many)
- `suratPerjalananDinas()` - SPD where user is the traveler
- `spdAsPpk()` - SPD where user is the PPK
- `spdAsPptk()` - SPD where user is the PPTK

---

## Surat Tugas Table

Main table for official duty letters (assignment letters).

### Columns:
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint, primary | Primary key |
| `nomor_surat` | string(50), unique | Letter number (e.g., 800.1.11.1/ST/) |
| `dasar_hukum` | text | Legal basis (reference regulations) |
| `maksud` | text | Purpose of the official duty |
| `tempat_tujuan` | string | Destination location |
| `tempat_kegiatan` | string, nullable | Activity venue |
| `tanggal_mulai` | date | Start date |
| `tanggal_selesai` | date | End date |
| `kode_rekening` | string(50) | Budget account code |
| `sub_kegiatan` | string(100), nullable | Sub-activity code |
| `penandatangan_id` | foreign key, nullable | Signatory user ID |
| `penandatangan_nama` | string, nullable | Signatory name (backup) |
| `penandatangan_nip` | string(18), nullable | Signatory NIP |
| `penandatangan_jabatan` | string, nullable | Signatory position |
| `tanggal_surat` | date | Letter date |
| `tempat_surat` | string(100) | Letter location (default: Banda Aceh) |
| `status` | enum | Status: draft, submitted, approved, rejected, completed |
| `catatan` | text, nullable | Notes/remarks |
| `created_by` | foreign key | Creator user ID |

### Relationships:
- `creator()` - User who created the letter
- `penandatangan()` - User who signs the letter
- `peserta()` - Participants (many-to-many through pivot)
- `suratPerjalananDinas()` - Related SPD documents

---

## Surat Tugas Peserta Table

Pivot table linking Surat Tugas with participant Users.

### Columns:
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint, primary | Primary key |
| `surat_tugas_id` | foreign key | Reference to surat_tugas |
| `user_id` | foreign key | Reference to users |
| `nama` | string, nullable | Name snapshot |
| `nip` | string(18), nullable | NIP snapshot |
| `pangkat` | string(100), nullable | Rank snapshot |
| `golongan` | string(20), nullable | Grade snapshot |
| `jabatan` | string, nullable | Position snapshot |
| `skpa` | string, nullable | SKPA snapshot |
| `urutan` | integer | Order/sequence number |
| `peran` | enum | Role: ketua, anggota, pendamping |

**Note:** Snapshot fields preserve participant data at the time of assignment, even if user profile changes later.

### Unique Constraint:
- `(surat_tugas_id, user_id)` - Prevents duplicate participants

---

## Surat Perjalanan Dinas Table

Official Travel Document (SPD) - derived from Surat Tugas.

### Columns:
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint, primary | Primary key |
| `surat_tugas_id` | foreign key | Parent Surat Tugas |
| `nomor_spd` | string(50), unique | SPD number |
| `lembar_ke` | string(10) | Sheet number (default: 1) |
| `kode_no` | string(50), nullable | Code number |
| `ppk_id` | foreign key, nullable | PPK user ID |
| `ppk_nama` | string, nullable | PPK name |
| `ppk_nip` | string(18), nullable | PPK NIP |
| `ppk_jabatan` | string, nullable | PPK position |
| `pegawai_id` | foreign key | Traveling employee user ID |
| `pegawai_nama` | string, nullable | Employee name snapshot |
| `pegawai_nip` | string(18), nullable | Employee NIP snapshot |
| `pegawai_pangkat` | string(100), nullable | Employee rank snapshot |
| `pegawai_golongan` | string(20), nullable | Employee grade snapshot |
| `pegawai_jabatan` | string, nullable | Employee position snapshot |
| `pegawai_instansi` | string, nullable | Employee institution |
| `tingkat_biaya` | enum(A,B,C,D), nullable | Travel cost level |
| `maksud_perjalanan` | text | Travel purpose |
| `alat_transportasi` | string(100) | Transportation mode |
| `tempat_berangkat` | string(100) | Departure location |
| `tempat_tujuan` | string(100) | Destination location |
| `lama_perjalanan_hari` | integer | Duration in days |
| `tanggal_berangkat` | date | Departure date |
| `tanggal_kembali` | date | Return date |
| `kegiatan_instansi` | string, nullable | Activity/Institution |
| `akun_kode_rekening` | string(50) | Budget account code |
| `mata_anggaran` | string, nullable | Budget item |
| `keterangan` | text, nullable | Additional notes |
| `pptk_id` | foreign key, nullable | PPTK user ID |
| `pptk_nama` | string, nullable | PPTK name |
| `pptk_nip` | string(18), nullable | PPTK NIP |
| `pptk_jabatan` | string, nullable | PPTK position |
| `tanggal_spd` | date | SPD issue date |
| `tempat_spd` | string(100) | SPD issue location |
| `status` | enum | Status: draft, submitted, approved, rejected, completed |

### Relationships:
- `suratTugas()` - Parent Surat Tugas
- `ppk()` - PPK (Pejabat Pembuat Komitmen)
- `pegawai()` - Traveling employee
- `pptk()` - PPTK (Pejabat Pelaksana Teknis Kegiatan)
- `pengikut()` - Followers/dependents
- `perjalanan()` - Journey legs

---

## SPD Pengikut Table

Stores followers/dependents accompanying the traveler.

### Columns:
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint, primary | Primary key |
| `surat_perjalanan_dinas_id` | foreign key | Parent SPD |
| `nama` | string(100) | Name of follower |
| `tanggal_lahir` | date, nullable | Birth date |
| `keterangan` | text, nullable | Remarks (relationship, etc.) |
| `urutan` | integer | Order/sequence |

### Relationships:
- `suratPerjalananDinas()` - Parent SPD

---

## SPD Perjalanan Table

Stores multiple journey legs/stops during the official travel (from SPD page 2).

### Columns:
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint, primary | Primary key |
| `surat_perjalanan_dinas_id` | foreign key | Parent SPD |
| `urutan` | integer | Row number (I, II, III, etc.) |
| `berangkat_dari` | string(100), nullable | Departure from |
| `ke` | string(100), nullable | To (destination) |
| `pada_tanggal` | date, nullable | On date |
| `kepala_nama` | string, nullable | Authority name at departure |
| `kepala_nip` | string(18), nullable | Authority NIP |
| `kepala_jabatan` | string, nullable | Authority position |
| `tiba_di` | string(100), nullable | Arrival at |
| `tiba_tanggal` | date, nullable | Arrival date |
| `tiba_kepala_nama` | string, nullable | Authority name at arrival |
| `tiba_kepala_nip` | string(18), nullable | Authority NIP at arrival |
| `tiba_kepala_jabatan` | string, nullable | Authority position at arrival |
| `keterangan` | text, nullable | Remarks |

### Relationships:
- `suratPerjalananDinas()` - Parent SPD

---

## Relationships

### Entity Relationship Diagram (ERD) Overview:

```
users
  ├─ 1:N → surat_tugas (as creator)
  ├─ 1:N → surat_tugas (as penandatangan)
  ├─ M:N → surat_tugas (as peserta, through pivot)
  ├─ 1:N → surat_perjalanan_dinas (as pegawai)
  ├─ 1:N → surat_perjalanan_dinas (as ppk)
  └─ 1:N → surat_perjalanan_dinas (as pptk)

surat_tugas
  ├─ N:1 → users (creator)
  ├─ N:1 → users (penandatangan)
  ├─ M:N → users (peserta, through pivot)
  └─ 1:N → surat_perjalanan_dinas

surat_perjalanan_dinas
  ├─ N:1 → surat_tugas
  ├─ N:1 → users (pegawai)
  ├─ N:1 → users (ppk)
  ├─ N:1 → users (pptk)
  ├─ 1:N → spd_pengikut
  └─ 1:N → spd_perjalanan
```

---

## Workflow

### 1. User Authentication
- User logs in with NIP/NIK/Email
- System validates credentials

### 2. Create Surat Tugas
- User fills in duty letter details:
  - Legal basis (dasar_hukum)
  - Purpose (maksud)
  - Destination (tempat_tujuan)
  - Date range (tanggal_mulai - tanggal_selesai)
  - Budget code (kode_rekening)
- Add participants (multiple users)
- Set signatory (penandatangan)
- Save as draft or submit

### 3. Create Surat Perjalanan Dinas (SPD)
- System carries over data from Surat Tugas
- User completes SPD-specific fields:
  - Travel cost level (tingkat_biaya)
  - Transportation mode (alat_transportasi)
  - Specific departure/arrival locations
  - Budget details (mata_anggaran)
  - PPK and PPTK information
- Optionally add:
  - Followers/dependents (pengikut)
  - Journey legs (perjalanan) for multi-stop trips

### 4. Approval Process
- Status flow: draft → submitted → approved/rejected → completed
- Authorized users can approve/reject
- System tracks status changes

### 5. PDF Generation
- Generate printable PDFs using dompdf
- Two documents:
  1. Surat Tugas
  2. Surat Perjalanan Dinas (SPD) with 2 pages

---

## Key Design Decisions

### 1. **Snapshot Pattern**
Fields like `pegawai_nama`, `pegawai_nip`, etc., are snapshots of user data at the time of document creation. This ensures historical accuracy even if user profiles are updated later.

### 2. **Soft Deletes**
Main tables (`surat_tugas`, `surat_perjalanan_dinas`) use soft deletes to preserve data integrity and audit trails.

### 3. **Status Enum**
Both main documents use status workflow: `draft → submitted → approved → rejected → completed`

### 4. **Flexible References**
Officer fields (PPK, PPTK, Penandatangan) have both foreign key references and text fields, allowing flexibility when officials are not system users.

### 5. **Multiple Participants**
Surat Tugas supports multiple participants through a proper many-to-many relationship with additional pivot data.

### 6. **Journey Legs**
SPD supports complex multi-stop journeys through the `spd_perjalanan` table, matching the official document format.

---

## Migration Commands

To run all migrations:
```bash
php artisan migrate
```

To rollback:
```bash
php artisan migrate:rollback
```

To refresh (drop all and re-run):
```bash
php artisan migrate:fresh
```

---

## Next Steps

1. **Seeders**: Create database seeders for testing
2. **Factories**: Build model factories for dummy data
3. **Validation**: Implement form request validation
4. **Authorization**: Set up policies for access control
5. **PDF Templates**: Design Blade views for PDF generation
6. **Livewire Components**: Build reactive forms for data entry
7. **Workflows**: Implement approval workflow logic

---

## Database Indexing Recommendations

For optimal performance, consider adding indexes on:
- `surat_tugas.nomor_surat`
- `surat_tugas.status`
- `surat_tugas.created_by`
- `surat_perjalanan_dinas.nomor_spd`
- `surat_perjalanan_dinas.status`
- `surat_perjalanan_dinas.pegawai_id`
- `users.nip`
- `users.nik`

---

*Last Updated: January 21, 2025*
