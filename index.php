<?php
/**
 * Beranda - Website Profil SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Beranda';
$pageDescription = 'Website resmi SMK Bangun Nusa Bangsa, mencetak generasi unggul, kompeten di era digital, dan siap berkarir di industri global.';

$pdo = getDBConnection();

// Ambil 3 artikel terbaru yang published
$latestArticles = [];
try {
    $stmt = $pdo->query("
        SELECT a.*, c.name as category_name, c.slug as category_slug,
               (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id AND cm.status = 'approved') as comment_count
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        WHERE a.status = 'published'
        ORDER BY a.created_at DESC
        LIMIT 3
    ");
    $latestArticles = $stmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

$headmasterName = get_setting('headmaster_name', 'Muhammad Yunus, S.E., M.Pd.');
$headmasterWelcome = get_setting('headmaster_welcome', 'Selamat datang di website resmi SMK Bangun Nusa Bangsa.');

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container position-relative">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <div class="hero-badge mb-3">
          <i class="bi bi-patch-check-fill"></i> Terakreditasi "A" BAN-SM & SMK Pusat Keunggulan
        </div>
        <h1 class="hero-title text-white">
          Wujudkan Masa Depan Gemilang Bersama <span class="text-warning">SMK Bangun Nusa Bangsa</span>
        </h1>
        <p class="hero-lead mt-3">
          Pendidikan vokasi modern berorientasi industri digital. Mengintegrasikan sertifikasi keahlian, teknologi terkini, dan karakter profesional untuk mencetak lulusan yang siap bersaing secara global.
        </p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="<?= base_url('jurusan.php') ?>" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow">
            <i class="bi bi-compass me-1"></i> Jelajahi Jurusan
          </a>
          <a href="<?= base_url('kontak.php') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold">
            <i class="bi bi-file-earmark-text me-1"></i> Informasi PPDB
          </a>
        </div>
      </div>
      <div class="col-lg-5 text-center">
        <div class="position-relative d-inline-block">
          <img src="<?= asset('uploads/articles/sample_ppdb.jpg') ?>" alt="Kampus SMK BNB" class="img-fluid rounded-4 shadow-lg border border-3 border-white-50" style="max-height: 380px; object-fit: cover;">
          <div class="position-absolute bottom-0 start-0 m-3 p-3 bg-white text-dark rounded-3 shadow text-start border-start border-4 border-primary" style="max-width: 260px;">
            <div class="fw-bold fs-6 text-primary"><i class="bi bi-award-fill text-warning"></i> Sekolah Siap Kerja</div>
            <small class="text-muted">Kurikulum Teaching Factory terhubung dengan 50+ mitra industri.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Bar Overlapping -->
<div class="container stats-bar">
  <div class="row g-4">
    <div class="col-6 col-md-3">
      <div class="stats-card text-center">
        <div class="stats-icon bg-primary-subtle text-primary mx-auto">
          <i class="bi bi-grid-fill"></i>
        </div>
        <div class="stat-val">3</div>
        <div class="stat-label">Program Keahlian</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-card text-center">
        <div class="stats-icon bg-success-subtle text-success mx-auto">
          <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-val">1.250+</div>
        <div class="stat-label">Siswa Aktif</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-card text-center">
        <div class="stats-icon bg-warning-subtle text-warning mx-auto">
          <i class="bi bi-briefcase-fill"></i>
        </div>
        <div class="stat-val">96.8%</div>
        <div class="stat-label">Terserap Kerja & Kuliah</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-card text-center">
        <div class="stats-icon bg-info-subtle text-info mx-auto">
          <i class="bi bi-building-check"></i>
        </div>
        <div class="stat-val">50+</div>
        <div class="stat-label">Mitra Industri (DUDI)</div>
      </div>
    </div>
  </div>
</div>

<!-- Sambutan Kepala Sekolah -->
<section class="py-5 my-4">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-4 text-center">
        <div class="position-relative d-inline-block">
          <img src="<?= asset('images/headmaster.jpg') ?>" alt="Kepala Sekolah" class="img-fluid rounded-4 shadow" style="max-height: 380px; object-fit: cover;">
          <div class="mt-3">
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($headmasterName) ?></h5>
            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1">Kepala SMK Bangun Nusa Bangsa</span>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <span class="section-tag">Sambutan Pimpinan</span>
        <h2 class="section-title mb-3">Mendidik dengan Hati, Membekali dengan Keahlian Berkelanjutan</h2>
        <div class="lead fs-6 text-muted mb-4">
          <?= nl2br(htmlspecialchars($headmasterWelcome)) ?>
        </div>
        <div class="row g-3">
          <div class="col-sm-6">
            <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
              <i class="bi bi-check2-circle text-success fs-3"></i>
              <div>
                <strong class="d-block text-dark">Sertifikasi BNSP & Vendor Global</strong>
                <small class="text-muted">Lulusan dibekali sertifikasi Mikrotik, Cisco, LSP Otomotif & Akuntansi.</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
              <i class="bi bi-check2-circle text-success fs-3"></i>
              <div>
                <strong class="d-block text-dark">Penyaluran Kerja BKK Terpadu</strong>
                <small class="text-muted">Bursa Kerja Khusus aktif menyalurkan alumni ke perusahaan mitra.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Program Keahlian Unggulan -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-tag">Kompetensi Keahlian</span>
      <h2 class="section-title">Program Studi Siap Karir</h2>
      <p class="text-muted max-w-600 mx-auto">
        Setiap program dirancang secara adaptif sesuai kebutuhan industri 4.0 dan sertifikasi keahlian terstandar.
      </p>
    </div>

    <div class="row g-4">
      <!-- 1. TKJ -->
      <div class="col-md-4">
        <div class="jurusan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="jurusan-badge">Infrastruktur IT</span>
            <div class="p-2 bg-primary-subtle text-primary rounded-3">
              <i class="bi bi-hdd-network fs-4"></i>
            </div>
          </div>
          <h4 class="fw-bold mb-2">Teknik Komputer & Jaringan</h4>
          <p class="text-muted small flex-grow-1">
            Mempelajari perakitan komputer, instalasi jaringan fiber optic, konfigurasi Mikrotik & Cisco routing, administrasi server Linux/Windows, serta cybersecurity.
          </p>
          <hr class="my-3 text-muted">
          <div class="small fw-semibold text-primary">
            <i class="bi bi-briefcase me-1"></i> Network Admin, IT Support, Cloud Tech
          </div>
        </div>
      </div>

      <!-- 2. TKR -->
      <div class="col-md-4">
        <div class="jurusan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="jurusan-badge">Teknik Otomotif</span>
            <div class="p-2 bg-warning-subtle text-warning rounded-3">
              <i class="bi bi-car-front-fill fs-4"></i>
            </div>
          </div>
          <h4 class="fw-bold mb-2">Teknik Kendaraan Ringan</h4>
          <p class="text-muted small flex-grow-1">
            Menguasai perawatan dan overhaul mesin bensin & diesel, sistem injeksi elektronik (EFI), kelistrikan bodi, chassis, pemindah tenaga, dan tune-up modern.
          </p>
          <hr class="my-3 text-muted">
          <div class="small fw-semibold text-warning">
            <i class="bi bi-briefcase me-1"></i> Teknisi Otomotif, Service Advisor, Mekanik
          </div>
        </div>
      </div>

      <!-- 3. AKL -->
      <div class="col-md-4">
        <div class="jurusan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="jurusan-badge">Bisnis & Finansial</span>
            <div class="p-2 bg-success-subtle text-success rounded-3">
              <i class="bi bi-calculator fs-4"></i>
            </div>
          </div>
          <h4 class="fw-bold mb-2">Akuntansi Keuangan</h4>
          <p class="text-muted small flex-grow-1">
            Pembukuan komputer akuntansi (MYOB & Accurate), tata kelola perpajakan (PPh/PPN), manajemen kas, audit laporan keuangan, dan layanan perbankan digital.
          </p>
          <hr class="my-3 text-muted">
          <div class="small fw-semibold text-success">
            <i class="bi bi-briefcase me-1"></i> Staff Akuntansi, Tax Officer, Teller Bank
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="<?= base_url('jurusan.php') ?>" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
        Pelajari Detail Kurikulum Seluruh Jurusan <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>

<!-- Berita & Artikel Terbaru -->
<section class="py-5">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
      <div>
        <span class="section-tag">Kabar Terkini</span>
        <h2 class="section-title mb-0">Berita & Artikel Sekolah</h2>
      </div>
      <a href="<?= base_url('artikel.php') ?>" class="btn btn-link text-primary fw-bold text-decoration-none">
        Lihat Semua Berita <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="row g-4">
      <?php if (!empty($latestArticles)): ?>
        <?php foreach ($latestArticles as $art): ?>
          <div class="col-md-4">
            <div class="article-card">
              <div class="article-img-wrapper">
                <?php if (!empty($art['image'])): ?>
                  <img src="<?= asset('uploads/articles/' . $art['image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="article-img">
                <?php else: ?>
                  <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center text-muted">
                    <i class="bi bi-image fs-1"></i>
                  </div>
                <?php endif; ?>
                <span class="article-cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
              </div>
              <div class="p-4 d-flex flex-column flex-grow-1">
                <div class="d-flex align-items-center gap-3 text-muted small mb-2">
                  <span><i class="bi bi-calendar3 me-1"></i> <?= format_date_id($art['created_at']) ?></span>
                  <span><i class="bi bi-chat-text me-1"></i> <?= $art['comment_count'] ?> Komentar</span>
                </div>
                <h5 class="fw-bold mb-3">
                  <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" class="article-title text-decoration-none">
                    <?= htmlspecialchars($art['title']) ?>
                  </a>
                </h5>
                <p class="text-muted small mb-4 flex-grow-1">
                  <?= htmlspecialchars(mb_strimwidth($art['excerpt'], 0, 120, '...')) ?>
                </p>
                <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" class="btn btn-sm btn-outline-primary rounded-pill fw-bold align-self-start">
                  Baca Selengkapnya & Komentar <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <p class="text-muted">Belum ada artikel yang dipublikasikan.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Call to Action PPDB -->
<section class="py-5 bg-primary text-white position-relative" style="background: linear-gradient(135deg, #1e40af 0%, #0f172a 100%) !important;">
  <div class="container text-center py-4">
    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-3">Penerimaan Siswa Baru 2026/2027</span>
    <h2 class="display-6 fw-bold mb-3">Siap Menjadi Bagian dari Generasi Berprestasi?</h2>
    <p class="lead max-w-600 mx-auto text-light opacity-75 mb-4">
      Pendaftaran telah dibuka untuk seluruh program keahlian. Kuota terbatas untuk kelas reguler dan kelas industri mitra.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="<?= base_url('kontak.php') ?>" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">
        <i class="bi bi-person-check-fill me-1"></i> Hubungi Panitia PPDB
      </a>
      <a href="<?= base_url('profil.php') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold">
        <i class="bi bi-building me-1"></i> Pelajari Profil Sekolah
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
