<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoPassword = Hash::make('UmlLibrary#2026');

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@elibrary.local'],
            [
                'id' => User::where('email', 'admin@elibrary.local')->value('id') ?? Str::uuid(),
                'name' => 'Administrator',
                'username' => 'admin',
                'email_verified_at' => now(),
                'password' => $demoPassword,
                'phone' => '081234567890',
                'city' => 'Bandar Lampung',
                'country' => 'Indonesia',
                'member_type' => 'staff',
                'is_active' => true,
            ]
        );

        // Create test user
        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'id' => User::where('email', 'test@example.com')->value('id') ?? Str::uuid(),
                'name' => 'Test User',
                'username' => 'testuser',
                'email_verified_at' => now(),
                'password' => $demoPassword,
                'phone' => '081234567891',
                'city' => 'Bandar Lampung',
                'country' => 'Indonesia',
                'student_id' => 'STU001',
                'member_type' => 'student',
                'is_active' => true,
            ]
        );

        // Create test librarian
        $librarian = User::updateOrCreate(
            ['email' => 'librarian@elibrary.local'],
            [
                'id' => User::where('email', 'librarian@elibrary.local')->value('id') ?? Str::uuid(),
                'name' => 'Librarian Staff',
                'username' => 'librarian',
                'email_verified_at' => now(),
                'password' => $demoPassword,
                'phone' => '081234567892',
                'city' => 'Bandar Lampung',
                'country' => 'Indonesia',
                'employee_id' => 'EMP001',
                'member_type' => 'staff',
                'is_active' => true,
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'staff@elibrary.local'],
            [
                'id' => User::where('email', 'staff@elibrary.local')->value('id') ?? Str::uuid(),
                'name' => 'Staff Perpustakaan',
                'username' => 'staff',
                'email_verified_at' => now(),
                'password' => $demoPassword,
                'phone' => '081234567893',
                'city' => 'Bandar Lampung',
                'country' => 'Indonesia',
                'employee_id' => 'EMP002',
                'member_type' => 'staff',
                'is_active' => true,
            ]
        );

        $lecturer = User::updateOrCreate(
            ['email' => 'dosen@elibrary.local'],
            [
                'id' => User::where('email', 'dosen@elibrary.local')->value('id') ?? Str::uuid(),
                'name' => 'Dosen UML',
                'username' => 'dosen',
                'email_verified_at' => now(),
                'password' => $demoPassword,
                'phone' => '081234567894',
                'city' => 'Bandar Lampung',
                'country' => 'Indonesia',
                'employee_id' => 'DSN001',
                'member_type' => 'faculty',
                'is_active' => true,
            ]
        );

        foreach (['Super Admin', 'Librarian', 'Staff', 'Mahasiswa', 'Dosen'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin->assignRole('Super Admin');
        $librarian->assignRole('Librarian');
        $staff->assignRole('Staff');
        $testUser->assignRole('Mahasiswa');
        $lecturer->assignRole('Dosen');

        // Create book categories
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Buku-buku fiksi dan cerita'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku-buku non-fiksi dan ilmu pengetahuan'],
            ['name' => 'Teknologi', 'description' => 'Buku-buku tentang teknologi dan pemrograman'],
            ['name' => 'Seni & Budaya', 'description' => 'Buku-buku tentang seni dan budaya'],
            ['name' => 'Sejarah', 'description' => 'Buku-buku sejarah'],
        ];

        foreach ($categories as $cat) {
            \App\Models\BookCategory::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'id' => Str::uuid(),
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'icon' => 'fa-book',
                    'is_active' => true,
                ]
            );
        }

        // Create authors
        $authors = [
            ['name' => 'Ahmad Fuadi', 'country' => 'Indonesia'],
            ['name' => 'Andrea Hirata', 'country' => 'Indonesia'],
            ['name' => 'Pramoedya Ananta Toer', 'country' => 'Indonesia'],
            ['name' => 'Seno Gumira Ajidarma', 'country' => 'Indonesia'],
        ];

        foreach ($authors as $author) {
            \App\Models\Author::firstOrCreate(
                ['name' => $author['name']],
                [
                    'id' => Str::uuid(),
                    'slug' => Str::slug($author['name']),
                    'country' => $author['country'],
                    'is_active' => true,
                ]
            );
        }

        // Create publishers
        $publishers = [
            ['name' => 'Gramedia Pustaka Utama', 'city' => 'Jakarta'],
            ['name' => 'Mizan Publika', 'city' => 'Bandung'],
            ['name' => 'Penerbit Erlangga', 'city' => 'Jakarta'],
            ['name' => 'Tempo Publishing', 'city' => 'Jakarta'],
        ];

        foreach ($publishers as $pub) {
            \App\Models\Publisher::firstOrCreate(
                ['name' => $pub['name']],
                [
                    'id' => Str::uuid(),
                    'slug' => Str::slug($pub['name']),
                    'city' => $pub['city'],
                    'country' => 'Indonesia',
                    'is_active' => true,
                ]
            );
        }

        // Create shelves
        $shelves = [
            ['name' => 'Rak A1', 'code' => 'A1', 'location' => 'Lantai 1 - Sebelah Kiri'],
            ['name' => 'Rak A2', 'code' => 'A2', 'location' => 'Lantai 1 - Sebelah Kanan'],
            ['name' => 'Rak B1', 'code' => 'B1', 'location' => 'Lantai 2 - Sebelah Kiri'],
            ['name' => 'Rak B2', 'code' => 'B2', 'location' => 'Lantai 2 - Sebelah Kanan'],
        ];

        foreach ($shelves as $shelf) {
            \App\Models\Shelf::firstOrCreate(
                ['code' => $shelf['code']],
                [
                    'id' => Str::uuid(),
                    'name' => $shelf['name'],
                    'location' => $shelf['location'],
                    'capacity' => 100,
                    'available_spots' => 100,
                    'is_active' => true,
                ]
            );
        }

        // Create sample books
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'isbn' => '978-9799651915',
                'category' => 'Fiksi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Gramedia Pustaka Utama',
                'shelf' => 'A1',
                'pages' => 412,
                'year' => 2005,
            ],
            [
                'title' => 'Tetralogi Buru',
                'isbn' => '978-9799032175',
                'category' => 'Fiksi',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Gramedia Pustaka Utama',
                'shelf' => 'A1',
                'pages' => 528,
                'year' => 1980,
            ],
            [
                'title' => 'Ranah 3 Warna',
                'isbn' => '978-9799044128',
                'category' => 'Fiksi',
                'author' => 'Ahmad Fuadi',
                'publisher' => 'Gramedia Pustaka Utama',
                'shelf' => 'A2',
                'pages' => 464,
                'year' => 2015,
            ],
            [
                'title' => 'Sejarah Indonesia Modern',
                'isbn' => '978-9790123456',
                'category' => 'Sejarah',
                'author' => 'Seno Gumira Ajidarma',
                'publisher' => 'Penerbit Erlangga',
                'shelf' => 'B1',
                'pages' => 380,
                'year' => 2018,
            ],
        ];

        foreach ($books as $bookData) {
            $category = \App\Models\BookCategory::where('name', $bookData['category'])->first();
            $author = \App\Models\Author::where('name', $bookData['author'])->first();
            $publisher = \App\Models\Publisher::where('name', $bookData['publisher'])->first();
            $shelf = \App\Models\Shelf::where('code', $bookData['shelf'])->first();

            if ($category && $author && $publisher && $shelf) {
                $book = \App\Models\Book::firstOrCreate(
                    ['isbn' => $bookData['isbn']],
                    [
                        'id' => Str::uuid(),
                        'title' => $bookData['title'],
                        'slug' => Str::slug($bookData['title']),
                        'category_id' => $category->id,
                        'publisher_id' => $publisher->id,
                        'shelf_id' => $shelf->id,
                        'isbn' => $bookData['isbn'],
                        'publication_year' => $bookData['year'],
                        'pages' => $bookData['pages'],
                        'language' => 'id',
                        'total_copies' => 5,
                        'available_copies' => 5,
                        'status' => 'available',
                        'is_featured' => true,
                        'is_active' => true,
                    ]
                );

                // Attach author to book
                if ($author) {
                    if (! $book->authors()->whereKey($author->id)->exists()) {
                        $book->authors()->attach($author->id, ['id' => Str::uuid()]);
                    }
                }
            }
        }

        // Create sample sliders
        $sliders = [
            ['title' => 'Selamat Datang di E-Library UML', 'description' => 'Pusat koleksi buku digital dan fisik terlengkap', 'button_text' => 'Jelajahi Koleksi'],
            ['title' => 'Pinjam Buku dengan Mudah', 'description' => 'Sistem peminjaman online yang cepat dan praktis', 'button_text' => 'Pelajari Cara Pinjam'],
        ];

        foreach ($sliders as $slider) {
            \App\Models\Slider::firstOrCreate(
                ['title' => $slider['title']],
                [
                    'id' => Str::uuid(),
                    'title' => $slider['title'],
                    'description' => $slider['description'],
                    'image' => 'library-uml.jpg',
                    'link' => route('collections'),
                    'button_text' => $slider['button_text'],
                    'is_active' => true,
                ]
            );
        }

        // Create FAQs
        $faqs = [
            [
                'question' => 'Bagaimana cara menjadi anggota perpustakaan?',
                'answer' => 'Silakan datang ke perpustakaan dengan membawa kartu identitas resmi (KTP/SIM/Paspor) dan mengisi formulir pendaftaran.',
            ],
            [
                'question' => 'Berapa lama waktu peminjaman buku?',
                'answer' => 'Waktu peminjaman standar adalah 14 hari, dapat diperpanjang maksimal 2 kali dengan durasi 7 hari setiap perpanjangan.',
            ],
            [
                'question' => 'Berapa denda keterlambatan?',
                'answer' => 'Denda keterlambatan adalah Rp 5.000 per buku per hari. Denda maksimal adalah Rp 50.000 per buku.',
            ],
        ];

        foreach ($faqs as $faq) {
            \App\Models\FAQ::firstOrCreate(
                ['question' => $faq['question']],
                [
                    'id' => Str::uuid(),
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'is_active' => true,
                ]
            );
        }

        // Create testimonials
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'position' => 'Mahasiswa',
                'content' => 'E-Library UML sangat membantu saya dalam menyelesaikan skripsi. Koleksinya lengkap dan stafnya sangat profesional!',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'position' => 'Dosen',
                'content' => 'Koleksi buku yang sangat berkualitas dan sistem manajemen yang modern. Perpustakaan terbaik di Lampung!',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            \App\Models\Testimonial::firstOrCreate(
                ['email' => 'testimonial-' . Str::slug($testimonial['name']) . '@example.com'],
                [
                    'id' => Str::uuid(),
                    'name' => $testimonial['name'],
                    'email' => 'testimonial-' . Str::slug($testimonial['name']) . '@example.com',
                    'position' => $testimonial['position'],
                    'content' => $testimonial['content'],
                    'rating' => 5,
                    'is_approved' => true,
                ]
            );
        }

        foreach ([
            'Panduan Layanan Perpustakaan Semester Baru',
            'Workshop Literasi Digital dan Sitasi Ilmiah',
            'Akses Repository Kampus Kini Terintegrasi',
        ] as $title) {
            \App\Models\News::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'id' => Str::uuid(),
                    'title' => $title,
                    'content' => 'Informasi perpustakaan Universitas Muhammadiyah Lampung untuk mendukung aktivitas akademik sivitas kampus.',
                    'excerpt' => 'Informasi terbaru layanan perpustakaan UML.',
                    'created_by' => $admin->id,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        foreach ([
            'Kelas Literasi Informasi' => now()->addDays(7),
            'Bedah Buku Akademik' => now()->addDays(14),
        ] as $title => $date) {
            \App\Models\Event::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'id' => Str::uuid(),
                    'title' => $title,
                    'description' => 'Kegiatan perpustakaan untuk meningkatkan budaya baca dan literasi digital kampus.',
                    'location' => 'Perpustakaan UML',
                    'start_date' => $date,
                    'end_date' => $date->copy()->addHours(2),
                    'created_by' => $admin->id,
                    'is_published' => true,
                ]
            );
        }

        $category = \App\Models\BookCategory::first();

        foreach (['Pedoman Akademik Digital', 'Modul Literasi Informasi'] as $title) {
            \App\Models\Ebook::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'id' => Str::uuid(),
                    'title' => $title,
                    'description' => 'Ebook pendukung layanan akademik UML.',
                    'category_id' => $category?->id,
                    'file_path' => 'ebooks/sample.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024,
                    'uploaded_by' => $admin->id,
                    'is_active' => true,
                ]
            );
        }

        foreach (['Repository Skripsi Manajemen', 'Jurnal Pengabdian Masyarakat'] as $title) {
            \App\Models\Repository::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'id' => Str::uuid(),
                    'title' => $title,
                    'description' => 'Dokumen repository akademik UML.',
                    'category_id' => $category?->id,
                    'file_path' => 'repositories/sample.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 2048,
                    'uploaded_by' => $admin->id,
                    'is_active' => true,
                ]
            );
        }

        foreach ([
            'site_name' => 'E-Library Universitas Muhammadiyah Lampung',
            'seo_description' => 'Portal perpustakaan digital Universitas Muhammadiyah Lampung',
            'smtp_status' => 'configured',
        ] as $key => $value) {
            \App\Models\Setting::firstOrCreate(
                ['key' => $key],
                ['id' => Str::uuid(), 'value' => $value, 'type' => 'string']
            );
        }
    }
}
