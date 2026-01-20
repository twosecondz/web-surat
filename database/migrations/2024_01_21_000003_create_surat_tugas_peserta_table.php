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
        Schema::create('surat_tugas_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Fields that might differ from user profile at the time of assignment
            $table->string('nama')->nullable(); // Snapshot of name
            $table->string('nip', 18)->nullable(); // Snapshot of NIP
            $table->string('pangkat', 100)->nullable(); // Snapshot of rank
            $table->string('golongan', 20)->nullable(); // Snapshot of grade
            $table->string('jabatan')->nullable(); // Snapshot of position
            $table->string('skpa')->nullable(); // Snapshot of SKPA
            
            $table->integer('urutan')->default(1); // Order of participants
            $table->enum('peran', ['ketua', 'anggota', 'pendamping'])->default('anggota');
            
            $table->timestamps();
            
            // Prevent duplicate participants
            $table->unique(['surat_tugas_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_peserta');
    }
};
