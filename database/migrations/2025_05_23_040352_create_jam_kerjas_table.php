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
        Schema::create('jam_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->integer('toleransi_check_out')->default(0);

            $table->integer('toleransi_terlambat')->default(0);
            $table->integer('toleransi_pulang_cepat')->default(0);
            $table->time('jam_mulai_scan_masuk');
            $table->time('jam_mulai_scan_keluar');
            // $table->enum('status_check_in', ['on-time', 'late', 'absent', 'excused'])->default('absent');
            // $table->enum('status_check_out', ['on-time', 'early', 'absent', 'excused'])->default('absent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerjas');
    }
};
