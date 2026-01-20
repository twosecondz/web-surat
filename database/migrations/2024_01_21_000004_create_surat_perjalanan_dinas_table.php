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
        Schema::create('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            
            // Link to parent Surat Tugas
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->cascadeOnDelete();
            
            // SPD specific fields
            $table->string('nomor_spd', 50)->unique();
            $table->string('lembar_ke', 10)->default('1'); // Sheet number
            $table->string('kode_no', 50)->nullable();
            
            // Pejabat Pembuat Komitmen (Commitment Making Officer)
            $table->foreignId('ppk_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ppk_nama')->nullable();
            $table->string('ppk_nip', 18)->nullable();
            $table->string('ppk_jabatan')->nullable();
            
            // Pegawai yang melaksanakan perjalanan dinas (Traveling employee)
            $table->foreignId('pegawai_id')->constrained('users')->cascadeOnDelete();
            $table->string('pegawai_nama')->nullable(); // Snapshot
            $table->string('pegawai_nip', 18)->nullable(); // Snapshot
            $table->string('pegawai_pangkat', 100)->nullable(); // Snapshot
            $table->string('pegawai_golongan', 20)->nullable(); // Snapshot
            $table->string('pegawai_jabatan')->nullable(); // Snapshot
            $table->string('pegawai_instansi')->nullable(); // Snapshot
            
            // Tingkat Biaya Perjalanan Dinas (Travel cost level)
            $table->enum('tingkat_biaya', ['A', 'B', 'C', 'D'])->nullable();
            
            // Maksud Perjalanan Dinas (Purpose)
            $table->text('maksud_perjalanan');
            
            // Alat Transportasi (Transportation)
            $table->string('alat_transportasi', 100); // e.g., Transportasi Darat
            
            // Tempat (Places)
            $table->string('tempat_berangkat', 100); // e.g., Banda Aceh
            $table->string('tempat_tujuan', 100); // e.g., Kota Langsa
            
            // Lamanya Perjalanan (Duration)
            $table->integer('lama_perjalanan_hari')->default(1); // Duration in days
            $table->date('tanggal_berangkat'); // Departure date
            $table->date('tanggal_kembali'); // Return date
            
            // Pembebanan Anggaran (Budget charging)
            $table->string('kegiatan_instansi')->nullable(); // Activity/Institution
            $table->string('akun_kode_rekening', 50); // Account/Budget code
            $table->string('mata_anggaran')->nullable(); // Budget item
            
            // Keterangan lain-lain (Other notes)
            $table->text('keterangan')->nullable();
            
            // Pejabat Pelaksana Teknis Kegiatan (Technical Implementation Officer)
            $table->foreignId('pptk_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pptk_nama')->nullable();
            $table->string('pptk_nip', 18)->nullable();
            $table->string('pptk_jabatan')->nullable();
            
            // Tanggal penerbitan SPD
            $table->date('tanggal_spd');
            $table->string('tempat_spd', 100)->default('Banda Aceh');
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'completed'])->default('draft');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_perjalanan_dinas');
    }
};
