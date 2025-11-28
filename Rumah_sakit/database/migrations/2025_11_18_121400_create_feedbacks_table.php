<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            if (!Schema::hasColumn('feedbacks', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete()->after('dokter_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            if (Schema::hasColumn('feedbacks', 'appointment_id')) {
                $table->dropConstrainedForeignId('appointment_id');
            }
        });
    }
};