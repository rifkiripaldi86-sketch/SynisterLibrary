<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FILE: database/migrations/2024_01_01_000001_create_users_table.php
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable(); // nullable agar siswa tidak wajib email
            $table->string('password');
            $table->enum('role', ['admin', 'siswa'])->default('siswa');
            $table->string('no_induk')->nullable()->comment('NIS untuk siswa');
            $table->string('kelas')->nullable()->comment('Kelas siswa, misal: XII RPL 1');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
