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
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 50)->unique();
            $table->text('dasar_hukum'); // Legal basis (e.g., Peraturan Gubernur)
            $table->text('maksud'); // Purpose of the official travel
            $table->string('tempat_tujuan'); // Destination
            $table->string('tempat_kegiatan')->nullable(); // Location/venue for the activity
            $table->date('tanggal_mulai'); // Start date
            $table->date('tanggal_selesai'); // End date
            $table->string('kode_rekening', 50); // Budget account code
            $table->string('sub_kegiatan', 100)->nullable(); // Sub-activity code
            
            // Penandatangan (Signatory)
            $table->foreignId('penandatangan_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('penandatangan_nama')->nullable(); // Backup if not in users
            $table->string('penandatangan_nip', 18)->nullable();
            $table->string('penandatangan_jabatan')->nullable();
            
            $table->date('tanggal_surat'); // Letter date
            $table->string('tempat_surat', 100)->default('Banda Aceh'); // Letter location
            
            // Status workflow
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'completed'])->default('draft');
            $table->text('catatan')->nullable(); // Notes/remarks
            
            // Created by
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
