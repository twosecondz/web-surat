<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 18)->unique()->nullable()->after('email');
            $table->string('nik', 16)->nullable()->after('nip');
            $table->string('pangkat', 100)->nullable()->after('nik');
            $table->string('golongan', 20)->nullable()->after('pangkat');
            $table->string('jabatan')->nullable()->after('golongan');
            $table->string('skpa')->nullable()->after('jabatan'); // Satuan Kerja Perangkat Aceh
            $table->string('eselon', 10)->nullable()->after('skpa');
            $table->text('alamat')->nullable()->after('eselon');
            $table->string('no_telepon', 20)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'nik',
                'pangkat',
                'golongan',
                'jabatan',
                'skpa',
                'eselon',
                'alamat',
                'no_telepon'
            ]);
        });
    }
};
