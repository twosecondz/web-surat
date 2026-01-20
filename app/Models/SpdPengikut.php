<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpdPengikut extends Model
{
    use HasFactory;

    protected $table = 'spd_pengikut';

    protected $fillable = [
        'surat_perjalanan_dinas_id',
        'nama',
        'tanggal_lahir',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
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
