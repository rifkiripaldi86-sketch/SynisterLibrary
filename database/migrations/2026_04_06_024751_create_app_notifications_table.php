<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FILE: database/migrations/2024_01_01_000006_create_app_notifications_table.php
     *
     * Mendukung notifikasi untuk siswa dan admin:
     * - Siswa: berhasil_pinjam, berhasil_kembali, jatuh_tempo, terlambat
     * - Admin: admin_pinjam_baru, admin_kembali, admin_jatuh_tempo, admin_terlambat, stok_menipis
     */
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->enum('type', [
                // Notifikasi untuk siswa
                'jatuh_tempo',
                'terlambat',
                'berhasil_pinjam',
                'berhasil_kembali',

                // Notifikasi untuk admin
                'admin_pinjam_baru',
                'admin_kembali',
                'admin_jatuh_tempo',
                'admin_terlambat',
                'stok_menipis',
            ]);

            $table->string('judul');
            $table->text('pesan');

            $table->foreignId('transaction_id')
                  ->nullable()
                  ->constrained('transactions')
                  ->onDelete('cascade');

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            // Index untuk mempercepat query notifikasi belum dibaca per user
            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
