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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('metode_pembayaran', ['qris', 'transfer'])->default('qris');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('kode_pembayaran')->nullable();
            $table->integer('jumlah')->default(0);
            $table->enum('status', ['berhasil', 'gagal', 'pending', 'menunggu verifikasi'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
