<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // =============================
        // SEED: KATEGORI
        // =============================

        $kategoriData = [
            ['nama' => 'Nonfiksi', 'warna' => '#4caf82', 'deskripsi' => 'Buku pengetahuan dan pengembangan diri'],
            ['nama' => 'Novel', 'warna' => '#9b7fe8', 'deskripsi' => 'Cerita panjang dengan berbagai tema'],
            ['nama' => 'Filosofi', 'warna' => '#6ab0f5', 'deskripsi' => 'Buku tentang pemikiran dan filsafat'],
            ['nama' => 'Romance', 'warna' => '#e87b5a', 'deskripsi' => 'Cerita tentang percintaan'],
            ['nama' => 'Fantasi', 'warna' => '#c9a84c', 'deskripsi' => 'Cerita dunia imajinasi dan petualangan'],
        ];

        foreach ($kategoriData as $kat) {
            Category::create([
                'nama' => $kat['nama'],
                'slug' => Str::slug($kat['nama']),
                'warna' => $kat['warna'],
                'deskripsi' => $kat['deskripsi'],
            ]);
        }

        // Ambil ID kategori
        $katNonfiksi = Category::where('slug', 'nonfiksi')->first()->id;
        $katNovel = Category::where('slug', 'novel')->first()->id;
        $katFilosofi = Category::where('slug', 'filosofi')->first()->id;
        $katRomance = Category::where('slug', 'romance')->first()->id;
        $katFantasi = Category::where('slug', 'fantasi')->first()->id;


        // =============================
        // SEED: USER ADMIN
        // =============================

        User::create([
            'name' => 'Synister Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // =============================
        // SEED: USER SISWA
        // =============================

        User::create([
            'name' => 'Ripki Novaldi',
            'username' => 'Synister',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'no_induk' => '232410210',
            'kelas' => 'XII RPL 3',
        ]);


        // =============================
        // SEED: BUKU
        // =============================

        $books = [

            // NONFIKSI
            [
                'judul_buku' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Avery',
                'tahun_terbit' => 2018,
                'isbn' => '978-0735211292',
                'category_id' => $katNonfiksi,
                'stok' => 5,
                'deskripsi' => 'Buku pengembangan diri tentang membangun kebiasaan baik.',
            ],
            [
                'judul_buku' => 'Rich Dad Poor Dad',
                'penulis' => 'Robert T. Kiyosaki',
                'penerbit' => 'Warner Books',
                'tahun_terbit' => 1997,
                'isbn' => '978-1612680194',
                'category_id' => $katNonfiksi,
                'stok' => 4,
                'deskripsi' => 'Buku tentang edukasi finansial dan cara mengelola uang.',
            ],

            // NOVEL
            [
                'judul_buku' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'isbn' => '978-9793062792',
                'category_id' => $katNovel,
                'stok' => 6,
                'deskripsi' => 'Kisah inspiratif anak-anak Belitung dalam meraih mimpi.',
            ],
            [
                'judul_buku' => 'Bumi',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2014,
                'isbn' => '978-6020324786',
                'category_id' => $katNovel,
                'stok' => 5,
                'deskripsi' => 'Petualangan Raib di dunia paralel yang penuh misteri.',
            ],

            // FILOSOFI
            [
                'judul_buku' => 'The Subtle Art of Not Giving a F*ck',
                'penulis' => 'Mark Manson',
                'penerbit' => 'Harper',
                'tahun_terbit' => 2016,
                'isbn' => '978-0062457714',
                'category_id' => $katFilosofi,
                'stok' => 3,
                'deskripsi' => 'Pendekatan hidup realistis tentang menerima keterbatasan.',
            ],
            [
                'judul_buku' => 'Meditations',
                'penulis' => 'Marcus Aurelius',
                'penerbit' => 'Penguin Classics',
                'tahun_terbit' => 2006,
                'isbn' => '978-0140449334',
                'category_id' => $katFilosofi,
                'stok' => 2,
                'deskripsi' => 'Catatan filosofi stoik dari Kaisar Romawi Marcus Aurelius.',
            ],

            // ROMANCE
            [
                'judul_buku' => 'Dilan 1990',
                'penulis' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2014,
                'isbn' => '978-6027870415',
                'category_id' => $katRomance,
                'stok' => 5,
                'deskripsi' => 'Kisah cinta remaja Dilan dan Milea di Bandung tahun 1990.',
            ],
            [
                'judul_buku' => 'Dear Nathan',
                'penulis' => 'Erisca Febriani',
                'penerbit' => 'Best Media',
                'tahun_terbit' => 2016,
                'isbn' => '978-6026940140',
                'category_id' => $katRomance,
                'stok' => 4,
                'deskripsi' => 'Cerita cinta remaja antara Salma dan Nathan.',
            ],

            // FANTASI
            [
                'judul_buku' => 'Harry Potter dan Batu Bertuah',
                'penulis' => 'J.K. Rowling',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2000,
                'isbn' => '978-6020324781',
                'category_id' => $katFantasi,
                'stok' => 4,
                'deskripsi' => 'Petualangan Harry Potter di sekolah sihir Hogwarts.',
            ],
            [
                'judul_buku' => 'The Hobbit',
                'penulis' => 'J.R.R. Tolkien',
                'penerbit' => 'George Allen & Unwin',
                'tahun_terbit' => 1937,
                'isbn' => '978-0547928227',
                'category_id' => $katFantasi,
                'stok' => 3,
                'deskripsi' => 'Petualangan Bilbo Baggins dalam perjalanan epik.',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}