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
        Schema::create('spd_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_perjalanan_dinas_id')->constrained('surat_perjalanan_dinas')->cascadeOnDelete();
            
            // Journey leg details (from page 2 of SPD)
            $table->integer('urutan')->default(1); // Row number (I, II, III, etc.)
            
            $table->string('berangkat_dari', 100)->nullable(); // Departure from
            $table->string('ke', 100)->nullable(); // To
            $table->date('pada_tanggal')->nullable(); // On date
            
            // Kepala (Head/Authority at this leg)
            $table->string('kepala_nama')->nullable();
            $table->string('kepala_nip', 18)->nullable();
            $table->string('kepala_jabatan')->nullable();
            
            // Tiba di (Arrival)
            $table->string('tiba_di', 100)->nullable();
            $table->date('tiba_tanggal')->nullable();
            
            // Kepala at arrival location
            $table->string('tiba_kepala_nama')->nullable();
            $table->string('tiba_kepala_nip', 18)->nullable();
            $table->string('tiba_kepala_jabatan')->nullable();
            
            $table->text('keterangan')->nullable(); // Remarks
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spd_perjalanan');
    }
};
