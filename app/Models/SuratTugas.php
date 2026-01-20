<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat_tugas';

    protected $fillable = [
        'nomor_surat',
        'dasar_hukum',
        'maksud',
        'tempat_tujuan',
        'tempat_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'kode_rekening',
        'sub_kegiatan',
        'penandatangan_id',
        'penandatangan_nama',
        'penandatangan_nip',
        'penandatangan_jabatan',
        'tanggal_surat',
        'tempat_surat',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_surat' => 'date',
    ];

    /**
     * Get the user who created this surat tugas.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the signatory (penandatangan).
     */
    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penandatangan_id');
    }

    /**
     * Get all participants of this surat tugas.
     */
    public function peserta(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'surat_tugas_peserta')
            ->withPivot([
                'nama',
                'nip',
                'pangkat',
                'golongan',
                'jabatan',
                'skpa',
                'urutan',
                'peran'
            ])
            ->withTimestamps()
            ->orderBy('surat_tugas_peserta.urutan');
    }

    /**
     * Get the related Surat Perjalanan Dinas (SPD).
     */
    public function suratPerjalananDinas(): HasMany
    {
        return $this->hasMany(SuratPerjalananDinas::class);
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if surat tugas is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
