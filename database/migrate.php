<?php
/**
 * Script Migrasi & Seeder Database
 * SMK Bangun Nusa Bangsa
 */

require_once __DIR__ . '/../config/database.php';

echo "=== MEMULAI MIGRASI DATABASE SMK BANGUN NUSA BANGSA ===\n";

try {
    // 1. Koneksi awal ke MySQL Server
    $rawPdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 2. Buat database jika belum ada
    $rawPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "[OK] Database '" . DB_NAME . "' berhasil dipersiapkan.\n";

    // 3. Sambungkan ke database yang telah dibuat
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 4. Eksekusi tabel dari schema.sql
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schemaSql);
    echo "[OK] Tabel-tabel sistem berhasil dibuat/diverifikasi.\n";

    // 5. Seed Admin User
    $adminCheck = $pdo->query("SELECT id FROM users WHERE username = 'admin'")->fetch();
    $adminPasswordHash = password_hash('admin123', PASSWORD_BCRYPT);
    if (!$adminCheck) {
        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Administrator SMK BNB', 'admin', 'admin@smkbangunnusabangsa.sch.id', $adminPasswordHash, 'admin']);
        $adminId = $pdo->lastInsertId();
        echo "[OK] Akun Admin dibuat (Username: admin | Password: admin123)\n";
    } else {
        $adminId = $adminCheck['id'];
        echo "[INFO] Akun admin sudah ada.\n";
    }

    // 6. Seed Kategori
    $categories = [
        ['Berita Sekolah', 'berita-sekolah', 'Informasi resmi, agenda kegiatan, dan kabar terkini seputar SMK Bangun Nusa Bangsa.'],
        ['Prestasi Siswa', 'prestasi-siswa', 'Pencapaian membanggakan siswa-siswi dalam kompetisi akademik maupun non-akademik.'],
        ['Kegiatan & Ekskul', 'kegiatan-ekskul', 'Aktivitas organisasi siswa, ekstrakurikuler, dan proyek kreasi siswa.'],
        ['Info PPDB & Beasiswa', 'info-ppdb-beasiswa', 'Informasi penerimaan peserta didik baru, syarat masuk, dan program beasiswa unggulan.'],
        ['Teknologi & Edukasi', 'teknologi-edukasi', 'Artikel edukatif, tips belajar produktif, dan wawasan perkembangan industri teknologi.'],
    ];

    $categoryIds = [];
    foreach ($categories as $cat) {
        $check = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
        $check->execute([$cat[1]]);
        $existing = $check->fetch();
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$cat[0], $cat[1], $cat[2]]);
            $categoryIds[$cat[1]] = $pdo->lastInsertId();
        } else {
            $categoryIds[$cat[1]] = $existing['id'];
        }
    }
    echo "[OK] Kategori artikel berhasil diinisialisasi.\n";

    // 7. Seed Data Pengaturan Sekolah
    $settings = [
        'school_name' => 'SMK Bangun Nusa Bangsa',
        'school_tagline' => 'Mencetak Generasi Cerdas, Terampil, Berkarakter, dan Siap Kerja Global',
        'school_npsn' => '20109988',
        'school_accreditation' => 'A (Unggul)',
        'school_phone' => '(021) 8765-4321 / 0812-3456-7890',
        'school_email' => 'info@smkbangunnusabangsa.sch.id',
        'school_address' => 'Jl. Pahlawan Pendidikan No. 45, Kompleks Nusantara Mandiri, Indonesia',
        'headmaster_name' => 'Muhammad Yunus, S.E., M.Pd.',
        'headmaster_welcome' => 'Selamat datang di website resmi SMK Bangun Nusa Bangsa. Kami berkomitmen menyelenggarakan pendidikan vokasi berkualitas dengan kurikulum berbasis industri terkini, didukung fasilitas modern, pendidik tersertifikasi, serta jaringan kemitraan dengan puluhan industri nasional dan multinasional. Mari bersama-sama membangun masa depan gemilang!',
        'maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.28581896898!2d106.756285!3d-6.2297465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e800000001%3A0x1!2sSMK!5e0!3m2!1sid!2sid!4v1600000000000" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
    ];

    foreach ($settings as $key => $val) {
        $stmt = $pdo->prepare("INSERT INTO school_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $val, $val]);
    }
    echo "[OK] Pengaturan profil sekolah berhasil disimpan.\n";

    // 8. Seed Artikel Berkualitas
    $articles = [
        [
            'category_slug' => 'prestasi-siswa',
            'title' => 'Siswa Jurusan Rekayasa Perangkat Lunak SMK BNB Raih Medali Emas LKS Tingkat Provinsi',
            'slug' => 'siswa-rpl-smk-bnb-raih-medali-emas-lks-tingkat-provinsi',
            'excerpt' => 'Prestasi membanggakan kembali ditorehkan oleh tim RPL SMK Bangun Nusa Bangsa dalam ajang Lomba Kompetensi Siswa (LKS) bidang Web Technologies.',
            'content' => "<p>Prestasi gemilang kembali diukir oleh perwakilan siswa dari Kompetensi Keahlian Rekayasa Perangkat Lunak (RPL) SMK Bangun Nusa Bangsa. Dalam perhelatan <strong>Lomba Kompetensi Siswa (LKS) Tingkat Provinsi</strong> yang diselenggarakan pekan lalu, ananda Muhammad Farhan berhasil meraih <em>Medali Emas</em> pada mata lomba <strong>Web Technologies</strong>.</p>
<p>Kompetisi yang berlangsung ketat selama tiga hari ini menguji keterampilan peserta dalam pengembangan aplikasi web modern berbasis Full-Stack, integrasi API, pengujian performa, dan arsitektur basis data relasional. Farhan berhasil menyelesaikan seluruh modul penugasan dengan skor tertinggi dan kecepatan implementasi yang memukau para dewan juri praktisi industri.</p>
<p>Kepala SMK Bangun Nusa Bangsa, Bapak Drs. H. Hendra Wijaya, M.Pd., menyampaikan apresiasi yang setinggi-tingginya kepada siswa dan guru pembimbing. 'Keberhasilan ini membuktikan bahwa kualitas kurikulum teaching factory dan fasilitas laboratorium komputer di SMK Bangun Nusa Bangsa mampu mencetak talenta digital berdaya saing tinggi,' ungkap beliau.</p>
<p>Dengan kemenangan ini, Farhan akan mewakili provinsi melaju ke tingkat Nasional pada bulan Oktober mendatang. Semoga prestasi ini menjadi pemicu semangat bagi seluruh civitas akademika SMK BNB untuk terus berinovasi dan berkarya nyata.</p>",
            'image' => 'sample_prestasi.jpg',
            'status' => 'published',
            'views' => 142
        ],
        [
            'category_slug' => 'info-ppdb-beasiswa',
            'title' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Resmi Dibuka!',
            'slug' => 'penerimaan-peserta-didik-baru-ppdb-tahun-ajaran-2026-2027',
            'excerpt' => 'SMK Bangun Nusa Bangsa membuka pendaftaran siswa baru untuk 4 program keahlian unggulan. Dapatkan beasiswa prestasi dan potongan biaya formulir bagi pendaftar gelombang pertama.',
            'content' => "<p>Kabar gembira bagi para lulusan SMP/MTs sederajat di seluruh tanah air! <strong>Penerimaan Peserta Didik Baru (PPDB) SMK Bangun Nusa Bangsa Tahun Ajaran 2026/2027</strong> kini telah resmi dibuka untuk Gelombang 1 (Jalur Prestasi & Reguler).</p>
<p>Sebagai sekolah vokasi unggulan yang telah terakreditasi 'A', SMK Bangun Nusa Bangsa menawarkan 4 Program Keahlian siap kerja:</p>
<ul>
<li><strong>Rekayasa Perangkat Lunak (RPL):</strong> Mempelajari web development, mobile apps, database modern, dan cloud computing.</li>
<li><strong>Teknik Komputer & Jaringan (TKJ):</strong> Sertifikasi Mikrotik & Cisco, fiber optic, cybersecurity, dan administrasi server.</li>
<li><strong>Desain Komunikasi Visual (DKV):</strong> Graphic design, 2D/3D animation, videografi sinematik, dan branding digital.</li>
<li><strong>Akuntansi & Keuangan Lembaga (AKL):</strong> Komputer akuntansi, perpajakan, fintech, dan audit keuangan perusahaan.</li>
</ul>
<p>Bagi calon peserta didik yang mendaftar pada Gelombang 1, tersedia program <em>Beasiswa Bebas Uang Gedung</em> untuk 20 pendaftar pertama dengan nilai rapor atau prestasi kejuaraan minimal tingkat kota/kabupaten. Segera daftarkan diri Anda melalui sekretariat PPDB di kampus SMK BNB atau secara online.</p>",
            'image' => 'sample_ppdb.jpg',
            'status' => 'published',
            'views' => 389
        ],
        [
            'category_slug' => 'berita-sekolah',
            'title' => 'SMK Bangun Nusa Bangsa Teken MoU Kerja Sama dengan 15 Perusahaan Teknologi Nasional',
            'slug' => 'smk-bnb-teken-mou-kerja-sama-dengan-15-perusahaan-teknologi',
            'excerpt' => 'Langkah nyata penguatan link and match pendidikan vokasi dengan dunia industri, membuka peluang magang dan rekrutmen kerja langsung bagi lulusan.',
            'content' => "<p>Dalam rangka memperkuat sinergi antara dunia pendidikan dan dunia usaha/industri (DUDI), SMK Bangun Nusa Bangsa menyelenggarakan seremoni penandatanganan <em>Memorandum of Understanding (MoU)</em> bersama <strong>15 perusahaan teknologi terkemuka</strong>.</p>
<p>Kemitraan strategis ini mencakup program:</p>
<ol>
<li>Penyelarasan kurikulum sekolah agar senantiasa relevan dengan kebutuhan industri mutakhir.</li>
<li>Program Guru Tamu dan Praktisi Mengajar dari tenaga ahli perusahaan.</li>
<li>Penyelenggaraan Praktek Kerja Lapangan (PKL) berkualitas bagi siswa kelas XI.</li>
<li>Peluang penyaluran kerja langsung sebelum kelulusan melalui bursa kerja khusus (BKK).</li>
</ol>
<p>Direktur Kemitraan Industri menyampaikan bahwa lulusan SMK Bangun Nusa Bangsa memiliki etos kerja disiplin dan portofolio teknis yang sangat memuaskan, sehingga perusahaan antusias menyerap talenta-talenta muda dari sekolah ini.</p>",
            'image' => 'sample_mou.jpg',
            'status' => 'published',
            'views' => 215
        ],
        [
            'category_slug' => 'kegiatan-ekskul',
            'title' => 'Gelar Karya Ekstrakurikuler & Pameran Inovasi Teknologi SMK BNB 2026',
            'slug' => 'gelar-karya-ekstrakurikuler-pameran-inovasi-teknologi-2026',
            'excerpt' => 'Ratusan karya kreatif mulai dari robotika, game interaktif, film pendek, hingga produk olahan bisnis karya siswa dipamerkan secara meriah di aula utama.',
            'content' => "<p>Suasana semarak dan penuh inspirasi menyelimuti aula kampus SMK Bangun Nusa Bangsa saat digelarnya acara tahunan <strong>Expo Inovasi Siswa & Pameran Ekstrakurikuler</strong>.</p>
<p>Acara ini menjadi wadah unjuk kebolehan lebih dari 18 klub ekstrakurikuler, antara lain Robotika, Coding Club, Fotografi, Palang Merah Remaja (PMR), Paskibra, Seni Tari, dan Futsal. Di panggung pameran teknologi, siswa jurusan TKJ dan RPL memamerkan alat Smart Home berbasis Internet of Things (IoT) yang dapat mengontrol kelistrikan rumah secara otomatis melalui smartphone.</p>
<p>Para orang tua siswa dan masyarakat umum yang hadir memberikan apresiasi tinggi terhadap kemandirian dan rasa percaya diri siswa saat mempresentasikan karya inovasi mereka.</p>",
            'image' => 'sample_expo.jpg',
            'status' => 'published',
            'views' => 98
        ]
    ];

    $articleIds = [];
    foreach ($articles as $art) {
        $catId = $categoryIds[$art['category_slug']] ?? 1;
        $check = $pdo->prepare("SELECT id FROM articles WHERE slug = ?");
        $check->execute([$art['slug']]);
        $existing = $check->fetch();
        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO articles (category_id, user_id, title, slug, excerpt, content, image, status, views_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $catId,
                $adminId,
                $art['title'],
                $art['slug'],
                $art['excerpt'],
                $art['content'],
                $art['image'],
                $art['status'],
                $art['views']
            ]);
            $articleIds[$art['slug']] = $pdo->lastInsertId();
        } else {
            $articleIds[$art['slug']] = $existing['id'];
        }
    }
    echo "[OK] Contoh artikel berkualitas berhasil dibuat.\n";

    // 9. Seed Komentar (Menguji Komentar Beridentitas vs Komentar Anonim)
    $comments = [
        [
            'article_slug' => 'siswa-rpl-smk-bnb-raih-medali-emas-lks-tingkat-provinsi',
            'is_anonymous' => 0,
            'author_name' => 'Budi Santoso',
            'author_email' => 'budi.santoso@gmail.com',
            'content' => 'Selamat dan sukses untuk ananda Farhan dan SMK Bangun Nusa Bangsa! Sangat bangga melihat adik-adik kelas terus berprestasi membanggakan almamater.',
            'status' => 'approved'
        ],
        [
            'article_slug' => 'siswa-rpl-smk-bnb-raih-medali-emas-lks-tingkat-provinsi',
            'is_anonymous' => 1,
            'author_name' => 'Anonim',
            'author_email' => null,
            'content' => 'Keren sekali! Jurusan RPL SMK BNB memang tidak diragukan lagi kualitas pembelajaran coding dan web-nya. Mantap!',
            'status' => 'approved'
        ],
        [
            'article_slug' => 'penerimaan-peserta-didik-baru-ppdb-tahun-ajaran-2026-2027',
            'is_anonymous' => 0,
            'author_name' => 'Siti Rahmawati',
            'author_email' => 'siti.rahma@yahoo.com',
            'content' => 'Mohon info min, apakah untuk pendaftaran jurusan DKV ada tes bakat menggambar terlebih dahulu? Terima kasih.',
            'status' => 'approved'
        ],
        [
            'article_slug' => 'penerimaan-peserta-didik-baru-ppdb-tahun-ajaran-2026-2027',
            'is_anonymous' => 1,
            'author_name' => 'Anonim',
            'author_email' => null,
            'content' => 'Info yang sangat jelas dan bermanfaat. Siap mendaftarkan adik saya untuk jurusan TKJ gelombang ini!',
            'status' => 'approved'
        ]
    ];

    foreach ($comments as $com) {
        $artId = $articleIds[$com['article_slug']] ?? null;
        if ($artId) {
            $check = $pdo->prepare("SELECT id FROM comments WHERE article_id = ? AND content = ?");
            $check->execute([$artId, $com['content']]);
            if (!$check->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO comments (article_id, is_anonymous, author_name, author_email, content, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $artId,
                    $com['is_anonymous'],
                    $com['author_name'],
                    $com['author_email'],
                    $com['content'],
                    $com['status']
                ]);
            }
        }
    }
    echo "[OK] Komentar contoh (Anonim & Beridentitas) berhasil dimasukkan.\n";

    echo "=== MIGRASI SELESAI DENGAN SUKSES! ===\n";

} catch (Exception $e) {
    echo "\n[ERROR] Migrasi gagal: " . $e->getMessage() . "\n";
    exit(1);
}
