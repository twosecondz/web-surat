<?php

namespace App\Livewire;

use App\Models\SuratPerjalananDinas;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSuratPerjalananDinas extends Component
{
    public $surat_tugas_id;
    public $suratTugas;
    
    // SPD specific fields
    public $nomor_spd = '';
    public $pegawai_id = '';
    public $ppk_id = '';
    public $pptk_id = '';
    public $tingkat_biaya = 'C';
    public $maksud_perjalanan = '';
    public $alat_transportasi = 'Transportasi Darat';
    public $tempat_berangkat = 'Banda Aceh';
    public $tempat_tujuan = '';
    public $lama_perjalanan_hari = 1;
    public $tanggal_berangkat = '';
    public $tanggal_kembali = '';
    public $akun_kode_rekening = '';
    public $keterangan = '';
    
    // Available options
    public $availablePeserta = [];
    public $availableUsers = [];

    public function mount($surat_tugas_id = null)
    {
        if (!$surat_tugas_id) {
            session()->flash('error', 'Surat Tugas ID tidak ditemukan.');
            return redirect()->route('surat-tugas.create');
        }

        $this->surat_tugas_id = $surat_tugas_id;
        $this->suratTugas = SuratTugas::with('peserta')->find($surat_tugas_id);

        if (!$this->suratTugas) {
            session()->flash('error', 'Surat Tugas tidak ditemukan.');
            return redirect()->route('surat-tugas.create');
        }

        // Auto-fill from Surat Tugas
        $this->maksud_perjalanan = $this->suratTugas->maksud;
        $this->tempat_tujuan = $this->suratTugas->tempat_tujuan;
        $this->tanggal_berangkat = $this->suratTugas->tanggal_mulai->format('Y-m-d');
        $this->tanggal_kembali = $this->suratTugas->tanggal_selesai->format('Y-m-d');
        $this->akun_kode_rekening = $this->suratTugas->kode_rekening;
        
        // Calculate duration
        $this->lama_perjalanan_hari = $this->suratTugas->tanggal_mulai->diffInDays($this->suratTugas->tanggal_selesai) + 1;

        // Get peserta for selection
        $this->availablePeserta = $this->suratTugas->peserta->toArray();
        
        // Set first peserta as default pegawai
        if (count($this->availablePeserta) > 0) {
            $this->pegawai_id = $this->availablePeserta[0]['id'];
        }

        // Load users for PPK and PPTK
        $this->availableUsers = User::select('id', 'name', 'nip', 'jabatan')
            ->orderBy('name')
            ->get()
            ->toArray();

        // Auto-generate nomor SPD
        $this->nomor_spd = $this->generateNomorSpd();
    }

    public function generateNomorSpd()
    {
        $lastSpd = SuratPerjalananDinas::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastSpd ? (int) substr($lastSpd->nomor_spd, 1, 3) + 1 : 1;
        
        return sprintf('/%03d/SPD/VI/%d', $number, date('Y'));
    }

    public function simpan()
    {
        $this->validate([
            'nomor_spd' => 'required|string|max:50|unique:surat_perjalanan_dinas,nomor_spd',
            'pegawai_id' => 'required|exists:users,id',
            'maksud_perjalanan' => 'required|string',
            'alat_transportasi' => 'required|string',
            'tempat_berangkat' => 'required|string',
            'tempat_tujuan' => 'required|string',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'lama_perjalanan_hari' => 'required|integer|min:1',
        ], [
            'nomor_spd.required' => 'Nomor SPD harus diisi.',
            'nomor_spd.unique' => 'Nomor SPD sudah digunakan.',
            'pegawai_id.required' => 'Pegawai harus dipilih.',
            'maksud_perjalanan.required' => 'Maksud perjalanan harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            $pegawai = User::find($this->pegawai_id);
            $ppk = User::find($this->ppk_id);
            $pptk = User::find($this->pptk_id);

            $spd = SuratPerjalananDinas::create([
                'surat_tugas_id' => $this->surat_tugas_id,
                'nomor_spd' => $this->nomor_spd,
                'pegawai_id' => $this->pegawai_id,
                'pegawai_nama' => $pegawai->name,
                'pegawai_nip' => $pegawai->nip,
                'pegawai_pangkat' => $pegawai->pangkat,
                'pegawai_golongan' => $pegawai->golongan,
                'pegawai_jabatan' => $pegawai->jabatan,
                'pegawai_instansi' => $pegawai->skpa,
                'ppk_id' => $this->ppk_id,
                'ppk_nama' => $ppk->name ?? null,
                'ppk_nip' => $ppk->nip ?? null,
                'ppk_jabatan' => $ppk->jabatan ?? null,
                'pptk_id' => $this->pptk_id,
                'pptk_nama' => $pptk->name ?? null,
                'pptk_nip' => $pptk->nip ?? null,
                'pptk_jabatan' => $pptk->jabatan ?? null,
                'tingkat_biaya' => $this->tingkat_biaya,
                'maksud_perjalanan' => $this->maksud_perjalanan,
                'alat_transportasi' => $this->alat_transportasi,
                'tempat_berangkat' => $this->tempat_berangkat,
                'tempat_tujuan' => $this->tempat_tujuan,
                'lama_perjalanan_hari' => $this->lama_perjalanan_hari,
                'tanggal_berangkat' => $this->tanggal_berangkat,
                'tanggal_kembali' => $this->tanggal_kembali,
                'akun_kode_rekening' => $this->akun_kode_rekening,
                'keterangan' => $this->keterangan,
                'tanggal_spd' => now(),
                'tempat_spd' => 'Banda Aceh',
                'status' => 'draft',
            ]);

            DB::commit();

            session()->flash('success', 'SPD berhasil dibuat!');

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batal()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-surat-perjalanan-dinas')->layout('layouts.app');
    }
}
