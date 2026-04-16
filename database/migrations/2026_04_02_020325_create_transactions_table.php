<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FILE: database/migrations/2024_01_01_000003_create_transactions_table.php
     *
     * PERUBAHAN:
     * - denda: integer → unsignedInteger (denda tidak mungkin negatif)
     * - Tambah index pada kolom 'status' untuk performa query filter
     * - Tambah composite index (user_id, status) yang sering dipakai bersama
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('book_id')
                  ->constrained('books')
                  ->onDelete('cascade');

            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana')->comment('Tanggal rencana pengembalian');
            $table->date('tanggal_kembali_aktual')->nullable()->comment('Tanggal aktual saat buku dikembalikan');

            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');

            // FIX: unsignedInteger agar denda tidak bisa negatif
            $table->unsignedInteger('denda')->default(0)->comment('Denda dalam rupiah jika terlambat');

            $table->text('catatan')->nullable();

            $table->timestamps();

            // FIX: Tambah index untuk performa query filter
            $table->index('status');
            $table->index(['user_id', 'status']); // composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
