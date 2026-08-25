<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_validasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat')->cascadeOnDelete();
            $table->foreignId('kades_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->text('catatan')->nullable(); // wajib diisi jika ditolak
            $table->string('qr_code_path')->nullable(); // path file QR sebagai e-signature
            $table->string('qr_code_hash')->nullable(); // hash unik untuk verifikasi keaslian
            $table->timestamp('tanggal_validasi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_validasi');
    }
};
