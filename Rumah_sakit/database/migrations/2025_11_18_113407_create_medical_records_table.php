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
        Schema::table('medical_records', function (Blueprint $table) {
            // Jika tabel medical_records belum ada, buat dulu
            if (!Schema::hasTable('medical_records')) {
                Schema::create('medical_records', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('pasien_id')->constrained('users')->onDelete('cascade');
                    $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
                    $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
                    $table->text('diagnosis');
                    $table->text('tindakan_medis');
                    $table->text('catatan')->nullable();
                    $table->timestamps();
                });
            } else {
                // Jika tabel sudah ada, tambahkan kolom dokter_id jika belum ada
                if (!Schema::hasColumn('medical_records', 'dokter_id')) {
                    $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
