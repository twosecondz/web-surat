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
        Schema::create('spd_pengikut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_perjalanan_dinas_id')->constrained('surat_perjalanan_dinas')->cascadeOnDelete();
            
            $table->string('nama', 100); // Name of follower/dependent
            $table->date('tanggal_lahir')->nullable(); // Birth date
            $table->text('keterangan')->nullable(); // Remarks (e.g., relationship, purpose)
            
            $table->integer('urutan')->default(1); // Order
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spd_pengikut');
    }
};
