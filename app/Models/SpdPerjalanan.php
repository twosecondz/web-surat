<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpdPerjalanan extends Model
{
    use HasFactory;

    protected $table = 'spd_perjalanan';

    protected $fillable = [
        'surat_perjalanan_dinas_id',
        'urutan',
        'berangkat_dari',
        'ke',
        'pada_tanggal',
        'kepala_nama',
        'kepala_nip',
        'kepala_jabatan',
        'tiba_di',
        'tiba_tanggal',
        'tiba_kepala_nama',
        'tiba_kepala_nip',
        'tiba_kepala_jabatan',
        'keterangan',
    ];

    protected $casts = [
        'pada_tanggal' => 'date',
        'tiba_tanggal' => 'date',
        'urutan' => 'integer',
    ];

    /**
     * Get the parent SPD.
     */
    public function suratPerjalananDinas(): BelongsTo
    {
        return $this->belongsTo(SuratPerjalananDinas::class);
    }
}
