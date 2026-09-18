-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2026 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smk_bnbrz`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft') DEFAULT 'published',
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `user_id`, `title`, `slug`, `excerpt`, `content`, `image`, `status`, `views_count`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Siswa Jurusan Akuntansi Keuangan SMK BNB Meraih juara Dua dan Tiga dalam olimpiade Gebyar Akuntansi', 'siswa-rpl-smk-bnb-raih-medali-emas-lks-tingkat-provinsi', 'Prestasi membanggakan kembali ditorehkan oleh tim RPL SMK Bangun Nusa Bangsa dalam ajang Lomba Kompetensi Siswa (LKS) bidang Web Technologies.', '<p>Prestasi gemilang kembali diukir oleh perwakilan siswa dari Kompetensi Gebyar Akuntansi SMK Bangun Nusa Bangsa. Dalam perhelatan <strong>Lomba Kompetensi Siswa (LKS) Tingkat Provinsi</strong> yang diselenggarakan pekan lalu, ananda Queen, Hanif, khean, Dipa, Vincent berhasil meraih <em>Juara</em> pada mata lomba <strong>Gebyar Akuntansi</strong>.</p>\r\n<p>Kompetisi yang berlangsung ketat selama satu hari ini menguji keterampilan peserta dalam pengembangan perhitungan yang memukau para dewan juri praktisi industri.</p>\r\n<p>Kepala SMK Bangun Nusa Bangsa, Bapak Muhammad Yunus, S.E, M.Pd menyampaikan apresiasi yang setinggi-tingginya kepada siswa dan guru pembimbing.', 'art_1789714277_f15df5a9.jpeg', 'published', 146, '2026-09-18 04:19:31', '2026-09-18 06:59:18'),
(2, 4, 1, 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Resmi Dibuka!', 'penerimaan-peserta-didik-baru-ppdb-tahun-ajaran-2026-2027', 'SMK Bangun Nusa Bangsa membuka pendaftaran siswa baru untuk 4 program keahlian unggulan. Dapatkan beasiswa prestasi dan potongan biaya formulir bagi pendaftar gelombang pertama.', '<p>Kabar gembira bagi para lulusan SMP/MTs sederajat di seluruh tanah air! <strong>Penerimaan Peserta Didik Baru (PPDB) SMK Bangun Nusa Bangsa Tahun Ajaran 2026/2027</strong> kini telah resmi dibuka untuk Gelombang 1 (Jalur Prestasi & Reguler).</p>\n<p>Sebagai sekolah vokasi unggulan yang telah terakreditasi \'A\', SMK Bangun Nusa Bangsa menawarkan 3 Program Keahlian siap kerja:</p>\n<ul>\n<li><strong>Rekayasa Perangkat Lunak (RPL):</strong> Mempelajari web development, mobile apps, database modern, dan cloud computing.</li>\n<li><strong>Teknik Komputer & Jaringan (TKJ):</strong> Sertifikasi Mikrotik & Cisco, fiber optic, cybersecurity, dan administrasi server.</li>\n<li><strong>Desain Komunikasi Visual (DKV):</strong> Graphic design, 2D/3D animation, videografi sinematik, dan branding digital.</li>\n<li><strong>Akuntansi & Keuangan Lembaga (AKL):</strong> Komputer akuntansi, perpajakan, fintech, dan audit keuangan perusahaan.</li>\n</ul>\n<p>Bagi calon peserta didik yang mendaftar pada Gelombang 1, tersedia program <em>Beasiswa Bebas Uang Gedung</em> untuk 20 pendaftar pertama dengan nilai rapor atau prestasi kejuaraan minimal tingkat kota/kabupaten. Segera daftarkan diri Anda melalui sekretariat PPDB di kampus SMK BNB atau secara online.</p>', 'sample_ppdb.jpg', 'published', 389, '2026-09-18 04:19:31', '2026-09-18 06:37:44'),
(3, 1, 1, 'SMK Bangun Nusa Bangsa Teken MoU Kerja Sama dengan 15 Perusahaan Teknologi Nasional', 'smk-bnb-teken-mou-kerja-sama-dengan-15-perusahaan-teknologi', 'Langkah nyata penguatan link and match pendidikan vokasi dengan dunia industri, membuka peluang magang dan rekrutmen kerja langsung bagi lulusan.', '<p>Dalam rangka memperkuat sinergi antara dunia pendidikan dan dunia usaha/industri (DUDI), SMK Bangun Nusa Bangsa menyelenggarakan seremoni penandatanganan <em>Memorandum of Understanding (MoU)</em> bersama <strong>15 perusahaan teknologi terkemuka</strong>.</p>\r\n<p>Kemitraan strategis ini mencakup program:</p>\r\n<ol>\r\n<li>Penyelarasan kurikulum sekolah agar senantiasa relevan dengan kebutuhan industri mutakhir.</li>\r\n<li>Program Guru Tamu dan Praktisi Mengajar dari tenaga ahli perusahaan.</li>\r\n<li>Penyelenggaraan Praktek Kerja Lapangan (PKL) berkualitas bagi siswa kelas XI.</li>\r\n<li>Peluang penyaluran kerja langsung sebelum kelulusan melalui bursa kerja khusus (BKK).</li>\r\n</ol>\r\n<p>Direktur Kemitraan Industri menyampaikan bahwa lulusan SMK Bangun Nusa Bangsa memiliki etos kerja disiplin dan portofolio teknis yang sangat memuaskan, sehingga perusahaan antusias menyerap talenta-talenta muda dari sekolah ini.</p>', 'art_1789714867_095208c9.jpeg', 'published', 215, '2026-09-18 04:19:31', '2026-09-18 07:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Berita Sekolah', 'berita-sekolah', 'Informasi resmi, agenda kegiatan, dan kabar terkini seputar SMK Bangun Nusa Bangsa.', '2026-09-18 04:19:30'),
(2, 'Prestasi Siswa', 'prestasi-siswa', 'Pencapaian membanggakan siswa-siswi dalam kompetisi akademik maupun non-akademik.', '2026-09-18 04:19:30'),
(3, 'Kegiatan & Ekskul', 'kegiatan-ekskul', 'Aktivitas organisasi siswa, ekstrakurikuler, dan proyek kreasi siswa.', '2026-09-18 04:19:30'),
(4, 'Info PPDB & Beasiswa', 'info-ppdb-beasiswa', 'Informasi penerimaan peserta didik baru, syarat masuk, dan program beasiswa unggulan.', '2026-09-18 04:19:30'),
(5, 'Teknologi & Edukasi', 'teknologi-edukasi', 'Artikel edukatif, tips belajar produktif, dan wawasan perkembangan industri teknologi.', '2026-09-18 04:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('approved','pending','spam') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `is_anonymous`, `author_name`, `author_email`, `content`, `status`, `created_at`) VALUES
(1, 1, 0, 'Budi Santoso', 'budi.santoso@gmail.com', 'Selamat dan sukses untuk ananda Farhan dan SMK Bangun Nusa Bangsa! Sangat bangga melihat adik-adik kelas terus berprestasi membanggakan almamater.', 'approved', '2026-09-18 04:19:31'),
(2, 1, 1, 'Anonim', NULL, 'Keren sekali! Jurusan RPL SMK BNB memang tidak diragukan lagi kualitas pembelajaran coding dan web-nya. Mantap!', 'approved', '2026-09-18 04:19:31'),
(3, 2, 0, 'Siti Rahmawati', 'siti.rahma@yahoo.com', 'Mohon info min, apakah untuk pendaftaran jurusan DKV ada tes bakat menggambar terlebih dahulu? Terima kasih.', 'approved', '2026-09-18 04:19:31'),
(4, 2, 1, 'Anonim', NULL, 'Info yang sangat jelas dan bermanfaat. Siap mendaftarkan adik saya untuk jurusan TKJ gelombang ini!', 'approved', '2026-09-18 04:19:31'),
(7, 1, 1, 'Anonim', NULL, 'Komentar pengujian anonim via HTTP POST otomatis', 'approved', '2026-09-18 06:16:54'),
(8, 1, 0, 'Dewi Sartika', 'dewi.sartika@gmail.com', 'Komentar pengujian nama dan email via HTTP POST otomatis', 'approved', '2026-09-18 06:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `school_settings`
--

CREATE TABLE `school_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_settings`
--

INSERT INTO `school_settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'school_name', 'SMK Bangun Nusa Bangsa'),
(2, 'school_tagline', 'Mencetak Generasi Cerdas, Terampil, Berkarakter, dan Siap Kerja Global'),
(3, 'school_npsn', '20109988'),
(4, 'school_accreditation', 'A (Unggul)'),
(5, 'school_phone', '(021) 8765-4321 / 0812-3456-7890'),
(6, 'school_email', 'info@smkbangunnusabangsa.sch.id'),
(7, 'school_address', 'Jl. SKB Raya No. 16 Cibinong'),
(8, 'headmaster_name', 'Muhammad Yunus, S.E., M.Pd.'),
(9, 'headmaster_welcome', 'Selamat datang di website resmi SMK Bangun Nusa Bangsa. Kami berkomitmen menyelenggarakan pendidikan vokasi berkualitas dengan kurikulum berbasis industri terkini, didukung fasilitas modern, pendidik tersertifikasi, serta jaringan kemitraan dengan puluhan industri nasional dan multinasional. Mari bersama-sama membangun masa depan gemilang!'),
(10, 'maps_embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.043133990306!2d106.8173992!3d-6.516224800000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c3d871fa7e45%3A0xfa1a6208a1db1bad!2sSMK%20BANGUN%20NUSA%20BANGSA!5e0!3m2!1sid!2sid!4v1789716349157!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"strict-origin-when-cross-origin\"></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator SMK BNB', 'admin', 'admin@smkbangunnusabangsa.sch.id', '$2y$10$.dbVmUGCNmL6rZ4ht3TxGOb.hDSlELPgBYQMEm3/EVmiuQnuQtXi6', 'admin', '2026-09-18 04:19:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `school_settings`
--
ALTER TABLE `school_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
