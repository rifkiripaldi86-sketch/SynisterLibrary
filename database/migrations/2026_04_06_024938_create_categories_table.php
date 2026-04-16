<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FILE: database/migrations/2024_01_01_000005_create_categories_table.php
     *
     * PERUBAHAN:
     * - Migration ini SATU-SATUNYA yang menambahkan category_id ke tabel books
     * - Migration 000007 (update_books_add_category_id) HARUS DIHAPUS karena
     *   melakukan hal yang sama persis → menyebabkan error duplicate column
     * - Kolom 'kategori' (string lama) sudah dihapus dari migration 000002,
     *   sehingga tidak perlu dropColumn di sini
     */
    public function up(): void
    {
        // Buat tabel kategori mandiri
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('warna', 7)->default('#c9a84c')
                  ->comment('Hex color untuk badge, contoh: #c9a84c');
            $table->timestamps();
        });

        // Tambah foreign key category_id ke tabel books
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('isbn')
                  ->constrained('categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
