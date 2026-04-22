<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================
        // SEED: KATEGORI (5)
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

        $katNonfiksi = Category::where('slug', 'nonfiksi')->first()->id;
        $katNovel    = Category::where('slug', 'novel')->first()->id;
        $katFilosofi = Category::where('slug', 'filosofi')->first()->id;
        $katRomance  = Category::where('slug', 'romance')->first()->id;
        $katFantasi  = Category::where('slug', 'fantasi')->first()->id;

        // =============================
        // SEED: USER ADMIN
        // =============================
        User::create([
            'name'     => 'Synister Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // =============================
        // SEED: USER SISWA (tambah 3 siswa)
        // =============================
        $siswa1 = User::create([
            'name'     => 'Ripki Novaldi',
            'username' => 'Synister',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
            'no_induk' => '232410210',
            'kelas'    => 'XII RPL 3',
        ]);

        $siswa2 = User::create([
            'name'     => 'Aisyah Putri',
            'username' => 'aisyah',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
            'no_induk' => '232410211',
            'kelas'    => 'XII RPL 3',
        ]);

        $siswa3 = User::create([
            'name'     => 'Budi Santoso',
            'username' => 'budi',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
            'no_induk' => '232410212',
            'kelas'    => 'XII RPL 2',
        ]);

        // =============================
        // SEED: BUKU (20 judul)
        // =============================
        $books = [
            // NONFIKSI (4)
            ['judul_buku' => 'Atomic Habits', 'penulis' => 'James Clear', 'penerbit' => 'Avery', 'tahun_terbit' => 2018, 'isbn' => '978-0735211292', 'category_id' => $katNonfiksi, 'stok' => 5, 'deskripsi' => 'Buku pengembangan diri tentang membangun kebiasaan baik.'],
            ['judul_buku' => 'Rich Dad Poor Dad', 'penulis' => 'Robert T. Kiyosaki', 'penerbit' => 'Warner Books', 'tahun_terbit' => 1997, 'isbn' => '978-1612680194', 'category_id' => $katNonfiksi, 'stok' => 4, 'deskripsi' => 'Buku tentang edukasi finansial.'],
            ['judul_buku' => 'Deep Work', 'penulis' => 'Cal Newport', 'penerbit' => 'Grand Central', 'tahun_terbit' => 2016, 'isbn' => '978-1455586691', 'category_id' => $katNonfiksi, 'stok' => 3, 'deskripsi' => 'Fokus mendalam untuk produktivitas maksimal.'],
            ['judul_buku' => 'The 7 Habits of Highly Effective People', 'penulis' => 'Stephen R. Covey', 'penerbit' => 'Free Press', 'tahun_terbit' => 1989, 'isbn' => '978-0743269513', 'category_id' => $katNonfiksi, 'stok' => 2, 'deskripsi' => '7 kebiasaan untuk efektivitas pribadi.'],

            // NOVEL (4)
            ['judul_buku' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun_terbit' => 2005, 'isbn' => '978-9793062792', 'category_id' => $katNovel, 'stok' => 6, 'deskripsi' => 'Kisah inspiratif anak-anak Belitung.'],
            ['judul_buku' => 'Bumi', 'penulis' => 'Tere Liye', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2014, 'isbn' => '978-6020324786', 'category_id' => $katNovel, 'stok' => 5, 'deskripsi' => 'Petualangan Raib di dunia paralel.'],
            ['judul_buku' => 'Pulang', 'penulis' => 'Tere Liye', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2015, 'isbn' => '978-6020329460', 'category_id' => $katNovel, 'stok' => 4, 'deskripsi' => 'Perjalanan Burlian mencari makna pulang.'],
            ['judul_buku' => 'Negeri 5 Menara', 'penulis' => 'A. Fuadi', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2009, 'isbn' => '978-9792266267', 'category_id' => $katNovel, 'stok' => 5, 'deskripsi' => 'Kisah santri di pesantren Gontor.'],

            // FILOSOFI (4)
            ['judul_buku' => 'The Subtle Art of Not Giving a F*ck', 'penulis' => 'Mark Manson', 'penerbit' => 'Harper', 'tahun_terbit' => 2016, 'isbn' => '978-0062457714', 'category_id' => $katFilosofi, 'stok' => 3, 'deskripsi' => 'Pendekatan realistis menerima keterbatasan.'],
            ['judul_buku' => 'Meditations', 'penulis' => 'Marcus Aurelius', 'penerbit' => 'Penguin Classics', 'tahun_terbit' => 2006, 'isbn' => '978-0140449334', 'category_id' => $katFilosofi, 'stok' => 2, 'deskripsi' => 'Catatan filosofi stoik dari Kaisar Romawi.'],
            ['judul_buku' => 'Thus Spoke Zarathustra', 'penulis' => 'Friedrich Nietzsche', 'penerbit' => 'Penguin', 'tahun_terbit' => 1983, 'isbn' => '978-0140441185', 'category_id' => $katFilosofi, 'stok' => 2, 'deskripsi' => 'Filsafat tentang ubermensch dan kehendak kuasa.'],
            ['judul_buku' => 'The Republic', 'penulis' => 'Plato', 'penerbit' => 'Penguin', 'tahun_terbit' => 1980, 'isbn' => '978-0140455113', 'category_id' => $katFilosofi, 'stok' => 3, 'deskripsi' => 'Dialog Socrates tentang keadilan dan negara ideal.'],

            // ROMANCE (4)
            ['judul_buku' => 'Dilan 1990', 'penulis' => 'Pidi Baiq', 'penerbit' => 'Pastel Books', 'tahun_terbit' => 2014, 'isbn' => '978-6027870415', 'category_id' => $katRomance, 'stok' => 5, 'deskripsi' => 'Kisah cinta Dilan dan Milea di Bandung.'],
            ['judul_buku' => 'Dear Nathan', 'penulis' => 'Erisca Febriani', 'penerbit' => 'Best Media', 'tahun_terbit' => 2016, 'isbn' => '978-6026940140', 'category_id' => $katRomance, 'stok' => 4, 'deskripsi' => 'Cerita cinta remaja antara Salma dan Nathan.'],
            ['judul_buku' => 'P.S. I Love You', 'penulis' => 'Cecelia Ahern', 'penerbit' => 'Hyperion', 'tahun_terbit' => 2004, 'isbn' => '978-0786890752', 'category_id' => $katRomance, 'stok' => 3, 'deskripsi' => 'Surat-surat cinta dari suami yang telah tiada.'],
            ['judul_buku' => 'Me Before You', 'penulis' => 'Jojo Moyes', 'penerbit' => 'Penguin', 'tahun_terbit' => 2012, 'isbn' => '978-0143124542', 'category_id' => $katRomance, 'stok' => 4, 'deskripsi' => 'Kisah cinta antara Lou dan Will yang lumpuh.'],

            // FANTASI (4)
            ['judul_buku' => 'Harry Potter dan Batu Bertuah', 'penulis' => 'J.K. Rowling', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2000, 'isbn' => '978-6020324781', 'category_id' => $katFantasi, 'stok' => 4, 'deskripsi' => 'Petualangan Harry Potter di Hogwarts.'],
            ['judul_buku' => 'The Hobbit', 'penulis' => 'J.R.R. Tolkien', 'penerbit' => 'George Allen & Unwin', 'tahun_terbit' => 1937, 'isbn' => '978-0547928227', 'category_id' => $katFantasi, 'stok' => 3, 'deskripsi' => 'Petualangan Bilbo Baggins.'],
            ['judul_buku' => 'The Lion, the Witch and the Wardrobe', 'penulis' => 'C.S. Lewis', 'penerbit' => 'HarperCollins', 'tahun_terbit' => 1950, 'isbn' => '978-0064471046', 'category_id' => $katFantasi, 'stok' => 4, 'deskripsi' => 'Petualangan di negeri ajaib Narnia.'],
            ['judul_buku' => 'Percy Jackson & the Olympians: The Lightning Thief', 'penulis' => 'Rick Riordan', 'penerbit' => 'Disney', 'tahun_terbit' => 2005, 'isbn' => '978-0786838656', 'category_id' => $katFantasi, 'stok' => 5, 'deskripsi' => 'Petualangan Percy Jackson, anak dewa Yunani.'],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // =============================
        // SEED: TRANSAKSI PEMINJAMAN (denda & keterlambatan)
        // =============================

        // Helper untuk mengurangi stok buku saat dipinjam
        $kurangiStok = function($book, $jumlah = 1) {
            $book->stok -= $jumlah;
            $book->save();
        };

        // Ambil referensi buku yang sudah dibuat
        $book1 = Book::where('judul_buku', 'Atomic Habits')->first();
        $book2 = Book::where('judul_buku', 'Laskar Pelangi')->first();
        $book3 = Book::where('judul_buku', 'Bumi')->first();
        $book4 = Book::where('judul_buku', 'The Subtle Art of Not Giving a F*ck')->first();
        $book5 = Book::where('judul_buku', 'Dilan 1990')->first();
        $book6 = Book::where('judul_buku', 'Harry Potter dan Batu Bertuah')->first();
        $book7 = Book::where('judul_buku', 'The Hobbit')->first();
        $book8 = Book::where('judul_buku', 'Dear Nathan')->first();
        $book9 = Book::where('judul_buku', 'Pulang')->first();
        $book10 = Book::where('judul_buku', 'Me Before You')->first();

        // 1. Sedang dipinjam (belum terlambat)
        $trans1 = Transaction::create([
            'user_id'                  => $siswa1->id,
            'book_id'                  => $book1->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(3),
            'tanggal_kembali_rencana'  => Carbon::now()->addDays(4),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Masih dalam masa pinjam',
        ]);
        $kurangiStok($book1);

        // 2. Terlambat (masih dipinjam, melebihi tanggal kembali)
        $trans2 = Transaction::create([
            'user_id'                  => $siswa2->id,
            'book_id'                  => $book2->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(10),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(3),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Terlambat, belum dikembalikan',
        ]);
        $kurangiStok($book2);

        // 3. Sudah dikembalikan tepat waktu (denda 0)
        $trans3 = Transaction::create([
            'user_id'                  => $siswa3->id,
            'book_id'                  => $book3->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(7),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(2),
            'tanggal_kembali_aktual'   => Carbon::now()->subDays(2),
            'status'                   => 'dikembalikan',
            'denda'                    => 0,
            'catatan'                  => 'Dikembalikan tepat waktu',
        ]);
        $kurangiStok($book3);
        $book3->stok += 1;
        $book3->save();

        // 4. Sudah dikembalikan terlambat (denda dihitung otomatis via method)
        $trans4 = Transaction::create([
            'user_id'                  => $siswa1->id,
            'book_id'                  => $book4->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(14),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(7),
            'tanggal_kembali_aktual'   => Carbon::now()->subDays(2),
            'status'                   => 'dikembalikan',
            'denda'                    => 0, // akan diisi ulang pakai hitungDenda()
            'catatan'                  => 'Terlambat, denda akan dihitung',
        ]);
        $kurangiStok($book4);
        // Hitung denda menggunakan method dari model
        $denda4 = $trans4->hitungDenda();
        $trans4->update(['denda' => $denda4]);
        $book4->stok += 1;
        $book4->save();

        // 5. Baru dipinjam (hari ini)
        $trans5 = Transaction::create([
            'user_id'                  => $siswa2->id,
            'book_id'                  => $book5->id,
            'tanggal_pinjam'           => Carbon::now(),
            'tanggal_kembali_rencana'  => Carbon::now()->addDays(7),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Baru dipinjam hari ini',
        ]);
        $kurangiStok($book5);

        // 6. Terlambat parah (telat 10 hari)
        $trans6 = Transaction::create([
            'user_id'                  => $siswa3->id,
            'book_id'                  => $book6->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(20),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(10),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Sudah telat 10 hari',
        ]);
        $kurangiStok($book6);

        // 7. Sudah dikembalikan dengan denda besar (telat 12 hari)
        $trans7 = Transaction::create([
            'user_id'                  => $siswa1->id,
            'book_id'                  => $book7->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(25),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(15),
            'tanggal_kembali_aktual'   => Carbon::now()->subDays(3),
            'status'                   => 'dikembalikan',
            'denda'                    => 0,
            'catatan'                  => 'Telat 12 hari',
        ]);
        $kurangiStok($book7);
        $denda7 = $trans7->hitungDenda();
        $trans7->update(['denda' => $denda7]);
        $book7->stok += 1;
        $book7->save();

        // 8. Sudah dikembalikan telat 2 hari
        $trans8 = Transaction::create([
            'user_id'                  => $siswa2->id,
            'book_id'                  => $book8->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(10),
            'tanggal_kembali_rencana'  => Carbon::now()->subDays(5),
            'tanggal_kembali_aktual'   => Carbon::now()->subDays(3),
            'status'                   => 'dikembalikan',
            'denda'                    => 0,
            'catatan'                  => 'Telat 2 hari',
        ]);
        $kurangiStok($book8);
        $denda8 = $trans8->hitungDenda();
        $trans8->update(['denda' => $denda8]);
        $book8->stok += 1;
        $book8->save();

        // 9. Sedang dipinjam (batas besok)
        $trans9 = Transaction::create([
            'user_id'                  => $siswa3->id,
            'book_id'                  => $book9->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(6),
            'tanggal_kembali_rencana'  => Carbon::now()->addDay(),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Harus dikembalikan besok',
        ]);
        $kurangiStok($book9);

        // 10. Terlambat 1 hari (masih dipinjam)
        $trans10 = Transaction::create([
            'user_id'                  => $siswa1->id,
            'book_id'                  => $book10->id,
            'tanggal_pinjam'           => Carbon::now()->subDays(8),
            'tanggal_kembali_rencana'  => Carbon::now()->subDay(),
            'tanggal_kembali_aktual'   => null,
            'status'                   => 'dipinjam',
            'denda'                    => 0,
            'catatan'                  => 'Terlambat 1 hari',
        ]);
        $kurangiStok($book10);
    }
}
