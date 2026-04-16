<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FILE: database/migrations/2024_01_01_000002_create_books_table.php
     *
     * PERUBAHAN:
     * - stok: integer → unsignedInteger (stok tidak mungkin negatif)
     * - Hapus kolom 'kategori' string (diganti category_id di migration 000005)
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('isbn')->unique()->nullable();
            $table->unsignedInteger('stok')->default(1); // FIX: unsignedInteger agar tidak bisa negatif
            $table->text('deskripsi')->nullable();
            $table->string('cover_image')->nullable()->comment('Path ke gambar cover buku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
