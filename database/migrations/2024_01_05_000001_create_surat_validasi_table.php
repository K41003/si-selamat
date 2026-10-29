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
            $table->foreignId('validated_by')->constrained('users');
            $table->string('validator_name');
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->string('qr_code_token')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('validated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_validasi');
    }
};
