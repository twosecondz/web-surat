<?php

namespace App\Livewire;

use App\Models\SuratPerjalananDinas;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSuratDinas extends Component
{
    public $tugas_id;
    public $suratTugas;
    
    // Informasi Dasar
    public $nomor_spd = '';
    public $ppk_id = '';
    public $ppk_nama = '';
    public $ppk_nip = '';
    public $ppk_pangkat = '';
    
    // Data Pegawai (from participants)
    public $pegawai_id = '';
    public $nama_pegawai = '';
    public $pangkat_golongan = '';
    public $nip_pegawai = '';
    public $jabatan_pegawai = '';
    public $tingkat_biaya = 'C';
    
    // Detail Perjalanan
    public $maksud_perjalanan = '';
    public $alat_transportasi = 'Transportasi Darat';
    public $tempat_tujuan = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $lamanya_hari = 1;
    
    // Pembebanan Anggaran
    public $kegiatan_sub_kegiatan = '';
    public $kode_rekening = '';
    
    // Penandatanganan PPTK
    public $pptk_id = '';
    public $nama_pptk = '';
    public $pangkat_pptk = '';
    public $nip_pptk = '';
    
    // Available options
    public $availablePeserta = [];
    public $availableUsers = [];

    public function mount($tugas_id)
    {
        if (!$tugas_id) {
            session()->flash('error', 'Surat Tugas ID tidak ditemukan.');
            return redirect()->route('surat-tugas.create');
        }

        $this->tugas_id = $tugas_id;
        
        // Fetch Surat Tugas with participants
        $this->suratTugas = SuratTugas::with('peserta')->find($tugas_id);

        if (!$this->suratTugas) {
            session()->flash('error', 'Surat Tugas tidak ditemukan.');
            return redirect()->route('surat-tugas.create');
        }

        // Auto-fill from Surat Tugas
        $this->autoFillFromSuratTugas();
        
        // Load available users for PPK and PPTK
        $this->loadAvailableUsers();
        
        // Auto-generate nomor SPD
        $this->nomor_spd = $this->generateNomorSpd();
    }

    protected function autoFillFromSuratTugas()
    {
        // Pre-fill shared fields
        $this->maksud_perjalanan = $this->suratTugas->maksud;
        $this->tempat_tujuan = $this->suratTugas->tempat_tujuan;
        $this->tanggal_mulai = $this->suratTugas->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $this->suratTugas->tanggal_selesai->format('Y-m-d');
        $this->kode_rekening = $this->suratTugas->kode_rekening;
        $this->kegiatan_sub_kegiatan = $this->suratTugas->sub_kegiatan ?? '';
        
        // Calculate duration
        $this->lamanya_hari = $this->suratTugas->tanggal_mulai
            ->diffInDays($this->suratTugas->tanggal_selesai) + 1;

        // Get participants for selection
        $this->availablePeserta = $this->suratTugas->peserta->map(function($peserta) {
            return [
                'id' => $peserta->id,
                'nama' => $peserta->name,
                'nip' => $peserta->nip,
                'pangkat' => $peserta->pangkat,
                'golongan' => $peserta->golongan,
                'jabatan' => $peserta->jabatan,
                'pivot_nama' => $peserta->pivot->nama,
                'pivot_nip' => $peserta->pivot->nip,
                'pivot_pangkat' => $peserta->pivot->pangkat,
                'pivot_golongan' => $peserta->pivot->golongan,
                'pivot_jabatan' => $peserta->pivot->jabatan,
            ];
        })->toArray();
        
        // Set first participant as default pegawai
        if (count($this->availablePeserta) > 0) {
            $this->selectPegawai($this->availablePeserta[0]['id']);
        }
    }

    protected function loadAvailableUsers()
    {
        $this->availableUsers = User::select('id', 'name', 'nip', 'pangkat', 'golongan', 'jabatan')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function generateNomorSpd()
    {
        $year = date('Y');
        $lastSpd = SuratPerjalananDinas::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastSpd ? ((int) substr($lastSpd->nomor_spd, 1, strpos($lastSpd->nomor_spd, '/', 1) - 1)) + 1 : 1;
        
        return sprintf('/%03d/SPD/%s/%d', $number, date('m'), $year);
    }

    public function selectPegawai($userId)
    {
        $peserta = collect($this->availablePeserta)->firstWhere('id', $userId);
        
        if ($peserta) {
            $this->pegawai_id = $peserta['id'];
            $this->nama_pegawai = $peserta['pivot_nama'];
            $this->nip_pegawai = $peserta['pivot_nip'];
            $this->pangkat_golongan = ($peserta['pivot_pangkat'] ?? '') . 
                                      ($peserta['pivot_golongan'] ? ' / ' . $peserta['pivot_golongan'] : '');
            $this->jabatan_pegawai = $peserta['pivot_jabatan'];
        }
    }

    public function selectPpk($userId)
    {
        $user = User::find($userId);
        
        if ($user) {
            $this->ppk_id = $user->id;
            $this->ppk_nama = $user->name;
            $this->ppk_nip = $user->nip ?? '';
            $this->ppk_pangkat = $user->pangkat ?? '';
        }
    }

    public function selectPptk($userId)
    {
        $user = User::find($userId);
        
        if ($user) {
            $this->pptk_id = $user->id;
            $this->nama_pptk = $user->name;
            $this->nip_pptk = $user->nip ?? '';
            $this->pangkat_pptk = $user->pangkat ?? '';
        }
    }

    public function updatedPegawaiId($value)
    {
        $this->selectPegawai($value);
    }

    public function updatedPpkId($value)
    {
        if ($value) {
            $this->selectPpk($value);
        }
    }

    public function updatedPptkId($value)
    {
        if ($value) {
            $this->selectPptk($value);
        }
    }

    public function simpan()
    {
        // Validation
        $this->validate([
            'nomor_spd' => 'required|string|max:50|unique:surat_perjalanan_dinas,nomor_spd',
            'pegawai_id' => 'required|exists:users,id',
            'maksud_perjalanan' => 'required|string',
            'tempat_tujuan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alat_transportasi' => 'required|string',
            'lamanya_hari' => 'required|integer|min:1',
        ], [
            'nomor_spd.required' => 'Nomor SPD harus diisi.',
            'nomor_spd.unique' => 'Nomor SPD sudah digunakan.',
            'pegawai_id.required' => 'Pegawai harus dipilih.',
            'pegawai_id.exists' => 'Pegawai tidak valid.',
            'maksud_perjalanan.required' => 'Maksud perjalanan dinas harus diisi.',
            'tempat_tujuan.required' => 'Tempat tujuan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'alat_transportasi.required' => 'Alat transportasi harus diisi.',
            'lamanya_hari.required' => 'Lamanya perjalanan harus diisi.',
            'lamanya_hari.min' => 'Lamanya perjalanan minimal 1 hari.',
        ]);

        try {
            DB::beginTransaction();

            $pegawai = User::find($this->pegawai_id);
            $ppk = $this->ppk_id ? User::find($this->ppk_id) : null;
            $pptk = $this->pptk_id ? User::find($this->pptk_id) : null;

            // Create SPD
            $spd = SuratPerjalananDinas::create([
                'surat_tugas_id' => $this->tugas_id,
                'nomor_spd' => $this->nomor_spd,
                'lembar_ke' => '1',
                'kode_no' => '',
                
                // PPK
                'ppk_id' => $this->ppk_id,
                'ppk_nama' => $this->ppk_nama ?: ($ppk->name ?? null),
                'ppk_nip' => $this->ppk_nip ?: ($ppk->nip ?? null),
                'ppk_jabatan' => $ppk->jabatan ?? null,
                
                // Pegawai (snapshot from pivot)
                'pegawai_id' => $this->pegawai_id,
                'pegawai_nama' => $this->nama_pegawai,
                'pegawai_nip' => $this->nip_pegawai,
                'pegawai_pangkat' => $pegawai->pangkat ?? '',
                'pegawai_golongan' => $pegawai->golongan ?? '',
                'pegawai_jabatan' => $this->jabatan_pegawai,
                'pegawai_instansi' => $pegawai->skpa ?? 'Badan Pengelolaan Keuangan Aceh',
                
                // Travel details
                'tingkat_biaya' => $this->tingkat_biaya,
                'maksud_perjalanan' => $this->maksud_perjalanan,
                'alat_transportasi' => $this->alat_transportasi,
                'tempat_berangkat' => 'Banda Aceh',
                'tempat_tujuan' => $this->tempat_tujuan,
                'lama_perjalanan_hari' => $this->lamanya_hari,
                'tanggal_berangkat' => $this->tanggal_mulai,
                'tanggal_kembali' => $this->tanggal_selesai,
                
                // Budget
                'kegiatan_instansi' => $this->kegiatan_sub_kegiatan,
                'akun_kode_rekening' => $this->kode_rekening,
                'mata_anggaran' => '',
                
                // PPTK
                'pptk_id' => $this->pptk_id,
                'pptk_nama' => $this->nama_pptk ?: ($pptk->name ?? null),
                'pptk_nip' => $this->nip_pptk ?: ($pptk->nip ?? null),
                'pptk_jabatan' => $pptk->jabatan ?? null,
                
                // Others
                'keterangan' => '',
                'tanggal_spd' => now(),
                'tempat_spd' => 'Banda Aceh',
                'status' => 'draft',
            ]);

            DB::commit();

            session()->flash('success', 'Surat Perjalanan Dinas berhasil dibuat!');

            return redirect()->route('spd.preview', ['id' => $spd->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            
            return;
        }
    }

    public function batal()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-surat-dinas')->layout('layouts.app');
    }
}
