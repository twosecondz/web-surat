<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin/kepala
        User::create([
            'name' => 'Reza Saputra, SSTP, M.Si',
            'email' => 'reza.saputra@bpka.acehprov.go.id',
            'username' => 'reza.saputra',
            'password' => Hash::make('password'),
            'nip' => '19800103199810102',
            'pangkat' => 'Pembina Utama Muda',
            'golongan' => 'IV/c',
            'jabatan' => 'Kepala Badan Pengelolaan Keuangan Aceh',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
            'eselon' => 'II.a',
            'no_telepon' => '0651-7551045',
        ]);

        // Create staff members based on the document
        User::create([
            'name' => 'Ramzi, M.Si',
            'email' => 'ramzi@bpka.acehprov.go.id',
            'username' => 'ramzi',
            'password' => Hash::make('password'),
            'nip' => '19740904200803101',
            'pangkat' => 'Pembina Tk. I',
            'golongan' => 'IV/b',
            'jabatan' => 'Sekretaris Badan Pengelolaan Keuangan Aceh',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
            'eselon' => 'III.a',
        ]);

        User::create([
            'name' => 'Sudirman, SE',
            'email' => 'sudirman@bpka.acehprov.go.id',
            'username' => 'sudirman',
            'password' => Hash::make('password'),
            'nip' => '19691126199003101',
            'pangkat' => 'Pembina Tk. I',
            'golongan' => 'IV/b',
            'jabatan' => 'Kepala Bidang Anggaran Aceh',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
            'eselon' => 'III.b',
        ]);

        User::create([
            'name' => 'Mohammad Iqbal, SE',
            'email' => 'mohammad.iqbal@bpka.acehprov.go.id',
            'username' => 'iqbal',
            'password' => Hash::make('password'),
            'nip' => '19831013200504101',
            'pangkat' => 'Penata Tk. I',
            'golongan' => 'III/d',
            'jabatan' => 'Kasubbid Keistimewaan dan SDM',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
            'eselon' => 'IV.a',
        ]);

        // Create PPK
        User::create([
            'name' => 'Mudatsir Syahputra, S.I.Kom',
            'email' => 'mudatsir@bpka.acehprov.go.id',
            'username' => 'mudatsir',
            'password' => Hash::make('password'),
            'nip' => '47220130546200000',
            'jabatan' => 'Tenaga Kontrak Bidang Anggaran Aceh',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
        ]);

        // Create additional test users
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => Hash::make('password'),
            'nip' => '19900101202001101',
            'nik' => '1234567890123456',
            'pangkat' => 'Penata',
            'golongan' => 'III/c',
            'jabatan' => 'Staf Umum',
            'skpa' => 'Badan Pengelolaan Keuangan Aceh',
        ]);
    }
}
