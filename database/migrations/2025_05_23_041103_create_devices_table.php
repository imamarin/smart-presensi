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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('device_id', 50)->unique();
            $table->string('lokasi', 150)->nullable();
            $table->string('api_key')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->dateTime('status_koneksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
