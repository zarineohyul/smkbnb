<?php
/**
 * Public Header Component
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$schoolName = get_setting('school_name', 'SMK Bangun Nusa Bangsa');
$schoolPhone = get_setting('school_phone', '(021) 8765-4321');
$schoolEmail = get_setting('school_email', 'info@smkbangunnusabangsa.sch.id');
$schoolAccreditation = get_setting('school_accreditation', 'A (Unggul)');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . $schoolName : $schoolName . ' - Portal Resmi Sekolah Vokasi Unggulan' ?></title>
  <meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Website resmi ' . $schoolName . ', sekolah kejuruan berprestasi dengan program keahlian teknologi dan bisnis terdepan.' ?>">
  
  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

  <!-- Top bar -->
  <div class="top-bar py-2 d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-4">
        <span><i class="bi bi-telephone-fill text-warning me-1"></i> <?= htmlspecialchars($schoolPhone) ?></span>
        <span><i class="bi bi-envelope-fill text-warning me-1"></i> <?= htmlspecialchars($schoolEmail) ?></span>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
          <i class="bi bi-award-fill me-1"></i> Akreditasi <?= htmlspecialchars($schoolAccreditation) ?>
        </span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <?php if (is_logged_in()): ?>
          <a href="<?= base_url('admin/index.php') ?>" class="badge bg-warning text-dark text-decoration-none py-1 px-2 fw-semibold">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
          </a>
        <?php else: ?>
          <a href="<?= base_url('admin/login.php') ?>" class="text-white-50 text-decoration-none small">
            <i class="bi bi-lock-fill me-1"></i> Login Staf/Admin
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Main Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('index.php') ?>">
        <div class="brand-logo">
          <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="brand-text">
          <div class="brand-title">SMK BANGUN NUSA BANGSA</div>
          <div class="brand-sub">Vocational High School of Technology</div>
        </div>
      </a>
      
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
          <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="<?= base_url('index.php') ?>">
              <i class="bi bi-house-door me-1"></i> Beranda
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'profil.php' ? 'active' : '' ?>" href="<?= base_url('profil.php') ?>">
              <i class="bi bi-info-circle me-1"></i> Profil
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'jurusan.php' ? 'active' : '' ?>" href="<?= base_url('jurusan.php') ?>">
              <i class="bi bi-grid-3x3-gap me-1"></i> Jurusan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['artikel.php', 'artikel-detail.php']) ? 'active' : '' ?>" href="<?= base_url('artikel.php') ?>">
              <i class="bi bi-newspaper me-1"></i> Berita & Artikel
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'kontak.php' ? 'active' : '' ?>" href="<?= base_url('kontak.php') ?>">
              <i class="bi bi-envelope me-1"></i> Kontak
            </a>
          </li>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a href="<?= base_url('kontak.php') ?>" class="btn btn-primary rounded-pill px-3 py-2 fw-bold text-white shadow-sm">
              <i class="bi bi-person-plus-fill me-1"></i> PPDB Online
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <?php include __DIR__ . '/alerts.php'; ?>
