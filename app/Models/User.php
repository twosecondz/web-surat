<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'nip',
        'nik',
        'pangkat',
        'golongan',
        'jabatan',
        'skpa',
        'eselon',
        'alamat',
        'no_telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all surat tugas created by this user.
     */
    public function suratTugasCreated(): HasMany
    {
        return $this->hasMany(SuratTugas::class, 'created_by');
    }

    /**
     * Get all surat tugas signed by this user.
     */
    public function suratTugasSigned(): HasMany
    {
        return $this->hasMany(SuratTugas::class, 'penandatangan_id');
    }

    /**
     * Get all surat tugas where this user is a participant.
     */
    public function suratTugasPeserta(): BelongsToMany
    {
        return $this->belongsToMany(SuratTugas::class, 'surat_tugas_peserta')
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
            ->withTimestamps();
    }

    /**
     * Get all SPD where this user is the traveler.
     */
    public function suratPerjalananDinas(): HasMany
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'pegawai_id');
    }

    /**
     * Get all SPD where this user is the PPK.
     */
    public function spdAsPpk(): HasMany
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'ppk_id');
    }

    /**
     * Get all SPD where this user is the PPTK.
     */
    public function spdAsPptk(): HasMany
    {
        return $this->hasMany(SuratPerjalananDinas::class, 'pptk_id');
    }

    /**
     * Get user's full name with title.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ($this->pangkat ? ", {$this->pangkat}" : '');
    }

    /**
     * Get user's display NIP or NIK.
     */
    public function getIdentifierAttribute(): string
    {
        return $this->nip ?? $this->nik ?? $this->email;
    }
}
