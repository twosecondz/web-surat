<?php

namespace Database\Seeders;

use App\Models\SpdPengikut;
use App\Models\SpdPerjalanan;
use App\Models\SuratPerjalananDinas;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $kepala = User::where('email', 'reza.saputra@bpka.acehprov.go.id')->first();
        $ramzi = User::where('email', 'ramzi@bpka.acehprov.go.id')->first();
        $sudirman = User::where('email', 'sudirman@bpka.acehprov.go.id')->first();
        $iqbal = User::where('email', 'mohammad.iqbal@bpka.acehprov.go.id')->first();
        $ppk = User::where('email', 'mudatsir@bpka.acehprov.go.id')->first();

        if (!$kepala || !$ramzi || !$sudirman || !$iqbal || !$ppk) {
            $this->command->error('Please run UserSeeder first!');
            return;
        }

        // Create Surat Tugas
        $suratTugas = SuratTugas::create([
            'nomor_surat' => '800.1.11.1/ST/',
            'dasar_hukum' => 'Kepala Badan Pengelolaan Keuangan Aceh berdasarkan Peraturan Gubernur Aceh Nomor 3 Tahun 2025 tanggal 10 Februari 2025 tentang Perjalanan Dinas',
            'maksud' => 'Permohonan Audiensi Perihal Usulan Menu Anggaran Belanja Zakat dan Infaq pada SIPD di Kementerian Dalam Negeri Republik Indonesia.',
            'tempat_tujuan' => 'Jakarta',
            'tempat_kegiatan' => 'Kementerian Dalam Negeri',
            'tanggal_mulai' => Carbon::parse('2025-11-15'),
            'tanggal_selesai' => Carbon::parse('2025-11-17'),
            'kode_rekening' => '5.1.02.04.01.0001/Sub Kegiatan 5.02.02.1.01.0007',
            'sub_kegiatan' => '5.02.02.1.01.0007',
            'penandatangan_id' => $kepala->id,
            'penandatangan_nama' => $kepala->name,
            'penandatangan_nip' => $kepala->nip,
            'penandatangan_jabatan' => $kepala->jabatan,
            'tanggal_surat' => Carbon::parse('2025-05-14'),
            'tempat_surat' => 'Banda Aceh',
            'status' => 'approved',
            'created_by' => $iqbal->id,
        ]);

        // Attach participants
        $suratTugas->peserta()->attach($ramzi->id, [
            'nama' => $ramzi->name,
            'nip' => $ramzi->nip,
            'pangkat' => $ramzi->pangkat,
            'golongan' => $ramzi->golongan,
            'jabatan' => $ramzi->jabatan,
            'skpa' => $ramzi->skpa,
            'urutan' => 1,
            'peran' => 'ketua',
        ]);

        $suratTugas->peserta()->attach($sudirman->id, [
            'nama' => $sudirman->name,
            'nip' => $sudirman->nip,
            'pangkat' => $sudirman->pangkat,
            'golongan' => $sudirman->golongan,
            'jabatan' => $sudirman->jabatan,
            'skpa' => $sudirman->skpa,
            'urutan' => 2,
            'peran' => 'anggota',
        ]);

        $suratTugas->peserta()->attach($iqbal->id, [
            'nama' => $iqbal->name,
            'nip' => $iqbal->nip,
            'pangkat' => $iqbal->pangkat,
            'golongan' => $iqbal->golongan,
            'jabatan' => $iqbal->jabatan,
            'skpa' => $iqbal->skpa,
            'urutan' => 3,
            'peran' => 'anggota',
        ]);

        // Create Surat Perjalanan Dinas for Iqbal
        $spd = SuratPerjalananDinas::create([
            'surat_tugas_id' => $suratTugas->id,
            'nomor_spd' => '/SPD/VI/2025',
            'lembar_ke' => '1',
            'kode_no' => '',
            'ppk_id' => $ppk->id,
            'ppk_nama' => $ppk->name,
            'ppk_nip' => $ppk->nip,
            'ppk_jabatan' => $ppk->jabatan,
            'pegawai_id' => $iqbal->id,
            'pegawai_nama' => $iqbal->name,
            'pegawai_nip' => $iqbal->nip,
            'pegawai_pangkat' => $iqbal->pangkat,
            'pegawai_golongan' => $iqbal->golongan,
            'pegawai_jabatan' => $iqbal->jabatan,
            'pegawai_instansi' => $iqbal->skpa,
            'tingkat_biaya' => 'C',
            'maksud_perjalanan' => 'Workshop Pelaksanaan Penyusunan Laporan Syarat Salur Dana Otonomi Khusus TA. 2025.',
            'alat_transportasi' => 'Transportasi Darat',
            'tempat_berangkat' => 'Banda Aceh',
            'tempat_tujuan' => 'Kota Langsa',
            'lama_perjalanan_hari' => 4,
            'tanggal_berangkat' => Carbon::parse('2025-06-23'),
            'tanggal_kembali' => Carbon::parse('2025-06-26'),
            'kegiatan_instansi' => 'Tenaga Kontrak Bidang Anggaran Aceh',
            'akun_kode_rekening' => '5.1.02.04.01.0001',
            'mata_anggaran' => '5.02.02.1.01.0009',
            'keterangan' => null,
            'pptk_id' => $iqbal->id,
            'pptk_nama' => $iqbal->name,
            'pptk_nip' => $iqbal->nip,
            'pptk_jabatan' => $iqbal->jabatan,
            'tanggal_spd' => Carbon::parse('2025-06-23'),
            'tempat_spd' => 'Banda Aceh',
            'status' => 'approved',
        ]);

        // Add journey legs
        SpdPerjalanan::create([
            'surat_perjalanan_dinas_id' => $spd->id,
            'urutan' => 1,
            'berangkat_dari' => 'Banda Aceh',
            'ke' => 'Kota Langsa',
            'pada_tanggal' => Carbon::parse('2025-06-23'),
            'kepala_nama' => $iqbal->name,
            'kepala_nip' => $iqbal->nip,
            'kepala_jabatan' => 'Pejabat Pelaksana Teknis Kegiatan',
            'tiba_di' => null,
            'tiba_tanggal' => null,
            'keterangan' => 'Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat',
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Email: mohammad.iqbal@bpka.acehprov.go.id');
        $this->command->info('Password: password');
    }
}
