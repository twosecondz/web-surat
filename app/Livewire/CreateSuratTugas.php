<?php

namespace App\Livewire;

use App\Models\SuratTugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSuratTugas extends Component
{
    // Informasi Surat
    public $nomor_surat = '';
    public $kode_rekening = '';
    public $dasar_hukum = '';
    public $maksud = '';
    public $tempat_tujuan = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $tempat_kegiatan = '';
    public $sub_kegiatan = '';
    
    // Penandatangan
    public $penandatangan_id = '';
    
    // Data Pegawai (Dynamic)
    public $peserta = [];
    
    // Search results
    public $searchResults = [];
    public $searchQuery = '';
    public $showDropdown = [];
    
    // Available users for dropdown
    public $availableUsers = [];

    public function mount()
    {
        // Initialize with one empty participant
        $this->addPeserta();
        
        // Load all users for dropdown
        $this->loadAvailableUsers();
        
        // Auto-generate nomor surat (you can customize this)
        $this->nomor_surat = $this->generateNomorSurat();
        
        // Set default dates
        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->tanggal_selesai = now()->addDays(1)->format('Y-m-d');
    }

    public function loadAvailableUsers()
    {
        $this->availableUsers = User::select('id', 'name', 'nip', 'pangkat', 'golongan', 'jabatan', 'skpa')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function generateNomorSurat()
    {
        // Generate format: 800.1.11.X/ST/2025
        $lastSurat = SuratTugas::whereYear('created_at', date('Y'))
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastSurat ? (int) substr($lastSurat->nomor_surat, 8, 3) + 1 : 1;
        
        return sprintf('800.1.11.%d/ST/%d', $number, date('Y'));
    }

    public function addPeserta()
    {
        $this->peserta[] = [
            'user_id' => '',
            'nama' => '',
            'nip' => '',
            'pangkat' => '',
            'golongan' => '',
            'jabatan' => '',
            'skpa' => '',
            'peran' => 'anggota',
            'urutan' => count($this->peserta) + 1,
        ];
        
        $this->showDropdown[] = false;
    }

    public function removePeserta($index)
    {
        unset($this->peserta[$index]);
        unset($this->showDropdown[$index]);
        
        // Reindex arrays
        $this->peserta = array_values($this->peserta);
        $this->showDropdown = array_values($this->showDropdown);
        
        // Update urutan
        foreach ($this->peserta as $key => $value) {
            $this->peserta[$key]['urutan'] = $key + 1;
        }
    }

    public function selectUser($index, $userId)
    {
        $user = User::find($userId);
        
        if ($user) {
            $this->peserta[$index]['user_id'] = $user->id;
            $this->peserta[$index]['nama'] = $user->name;
            $this->peserta[$index]['nip'] = $user->nip ?? '';
            $this->peserta[$index]['pangkat'] = $user->pangkat ?? '';
            $this->peserta[$index]['golongan'] = $user->golongan ?? '';
            $this->peserta[$index]['jabatan'] = $user->jabatan ?? '';
            $this->peserta[$index]['skpa'] = $user->skpa ?? '';
        }
        
        $this->showDropdown[$index] = false;
    }

    public function toggleDropdown($index)
    {
        $this->showDropdown[$index] = !$this->showDropdown[$index];
    }

    public function closeDropdown($index)
    {
        $this->showDropdown[$index] = false;
    }

    public function simpan()
    {
        // Validation
        $this->validate([
            'nomor_surat' => 'required|string|max:50|unique:surat_tugas,nomor_surat',
            'kode_rekening' => 'required|string|max:50',
            'dasar_hukum' => 'required|string',
            'maksud' => 'required|string',
            'tempat_tujuan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'peserta' => 'required|array|min:1',
            'peserta.*.user_id' => 'required|exists:users,id',
            'peserta.*.nama' => 'required|string',
            'peserta.*.peran' => 'required|in:ketua,anggota,pendamping',
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi.',
            'nomor_surat.unique' => 'Nomor surat sudah digunakan.',
            'kode_rekening.required' => 'Kode rekening harus diisi.',
            'dasar_hukum.required' => 'Dasar hukum harus diisi.',
            'maksud.required' => 'Maksud perjalanan dinas harus diisi.',
            'tempat_tujuan.required' => 'Tempat tujuan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'peserta.required' => 'Minimal harus ada 1 pegawai.',
            'peserta.*.user_id.required' => 'Pegawai harus dipilih.',
            'peserta.*.user_id.exists' => 'Pegawai tidak ditemukan.',
        ]);

        try {
            DB::beginTransaction();

            // Get penandatangan (default to current user or first admin)
            $penandatangan = User::where('jabatan', 'like', '%Kepala Badan%')->first() 
                ?? User::first();

            // Create Surat Tugas
            $suratTugas = SuratTugas::create([
                'nomor_surat' => $this->nomor_surat,
                'dasar_hukum' => $this->dasar_hukum,
                'maksud' => $this->maksud,
                'tempat_tujuan' => $this->tempat_tujuan,
                'tempat_kegiatan' => $this->tempat_kegiatan,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'kode_rekening' => $this->kode_rekening,
                'sub_kegiatan' => $this->sub_kegiatan,
                'penandatangan_id' => $penandatangan->id ?? null,
                'penandatangan_nama' => $penandatangan->name ?? '',
                'penandatangan_nip' => $penandatangan->nip ?? '',
                'penandatangan_jabatan' => $penandatangan->jabatan ?? '',
                'tanggal_surat' => now(),
                'tempat_surat' => 'Banda Aceh',
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // Attach participants with pivot data
            foreach ($this->peserta as $peserta) {
                $suratTugas->peserta()->attach($peserta['user_id'], [
                    'nama' => $peserta['nama'],
                    'nip' => $peserta['nip'],
                    'pangkat' => $peserta['pangkat'],
                    'golongan' => $peserta['golongan'],
                    'jabatan' => $peserta['jabatan'],
                    'skpa' => $peserta['skpa'],
                    'urutan' => $peserta['urutan'],
                    'peran' => $peserta['peran'],
                ]);
            }

            DB::commit();

            // Flash success message
            session()->flash('success', 'Surat Tugas berhasil dibuat!');

            // Redirect to Surat Tugas preview page
            return redirect()->route('surat-tugas.preview', ['id' => $suratTugas->id]);

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
        return view('livewire.create-surat-tugas')->layout('layouts.app');
    }
}
