<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable()->unique(); // diisi saat disetujui/dicetak
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->restrictOnDelete();
            $table->foreignId('warga_id')->constrained('warga')->restrictOnDelete();
            $table->foreignId('dibuat_oleh')->constrained('users')->restrictOnDelete(); // Staff

            // Isi form dinamis sesuai template_field jenis_surat, misal keperluan surat
            $table->json('data_surat')->nullable();

            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamp('tanggal_pengajuan')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
