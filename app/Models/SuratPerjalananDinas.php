<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratPerjalananDinas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat_perjalanan_dinas';

    protected $fillable = [
        'surat_tugas_id',
        'nomor_spd',
        'lembar_ke',
        'kode_no',
        'ppk_id',
        'ppk_nama',
        'ppk_nip',
        'ppk_jabatan',
        'pegawai_id',
        'pegawai_nama',
        'pegawai_nip',
        'pegawai_pangkat',
        'pegawai_golongan',
        'pegawai_jabatan',
        'pegawai_instansi',
        'tingkat_biaya',
        'maksud_perjalanan',
        'alat_transportasi',
        'tempat_berangkat',
        'tempat_tujuan',
        'lama_perjalanan_hari',
        'tanggal_berangkat',
        'tanggal_kembali',
        'kegiatan_instansi',
        'akun_kode_rekening',
        'mata_anggaran',
        'keterangan',
        'pptk_id',
        'pptk_nama',
        'pptk_nip',
        'pptk_jabatan',
        'tanggal_spd',
        'tempat_spd',
        'status',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_spd' => 'date',
        'lama_perjalanan_hari' => 'integer',
    ];

    /**
     * Get the parent Surat Tugas.
     */
    public function suratTugas(): BelongsTo
    {
        return $this->belongsTo(SuratTugas::class);
    }

    /**
     * Get the PPK (Pejabat Pembuat Komitmen).
     */
    public function ppk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    /**
     * Get the traveling employee.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    /**
     * Get the PPTK (Pejabat Pelaksana Teknis Kegiatan).
     */
    public function pptk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pptk_id');
    }

    /**
     * Get all followers/dependents.
     */
    public function pengikut(): HasMany
    {
        return $this->hasMany(SpdPengikut::class)->orderBy('urutan');
    }

    /**
     * Get all journey legs.
     */
    public function perjalanan(): HasMany
    {
        return $this->hasMany(SpdPerjalanan::class)->orderBy('urutan');
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
