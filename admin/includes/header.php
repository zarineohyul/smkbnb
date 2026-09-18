<?php
/**
 * Admin Header & Sidebar Component
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../../config/app.php';
require_login();

$adminUser = current_user();
$currentAdminPage = basename($_SERVER['PHP_SELF']);
$schoolName = get_setting('school_name', 'SMK Bangun Nusa Bangsa');

// Ambil jumlah komentar pending jika ada
$pendingCommentsCount = 0;
try {
    $pdo = getDBConnection();
    $cntStmt = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'");
    $pendingCommentsCount = (int)$cntStmt->fetchColumn();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Admin Panel' : 'Dashboard Admin - ' . htmlspecialchars($schoolName) ?></title>
  
  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Admin Custom CSS -->
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin-body">

<div class="admin-wrapper">
  <!-- Sidebar Backdrop on Mobile -->
  <div id="sidebarBackdrop" class="sidebar-backdrop d-none"></div>

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <a href="<?= base_url('admin/index.php') ?>" class="sidebar-brand">
      <div class="sidebar-brand-icon">
        <i class="bi bi-mortarboard-fill"></i>
      </div>
      <div>
        <div class="sidebar-brand-title">ADMIN PANEL</div>
        <div class="sidebar-brand-sub">SMK Bangun Nusa Bangsa</div>
      </div>
    </a>

    <div class="sidebar-nav">
      <div class="sidebar-heading">Menu Utama</div>
      
      <a href="<?= base_url('admin/index.php') ?>" class="sidebar-link <?= $currentAdminPage == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>

      <div class="sidebar-heading">Kelola Konten</div>

      <a href="<?= base_url('admin/articles.php') ?>" class="sidebar-link <?= in_array($currentAdminPage, ['articles.php', 'article-edit.php']) ? 'active' : '' ?>">
        <i class="bi bi-newspaper"></i>
        <span>Kelola Artikel</span>
      </a>

      <a href="<?= base_url('admin/article-add.php') ?>" class="sidebar-link <?= $currentAdminPage == 'article-add.php' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Tambah Artikel</span>
      </a>

      <a href="<?= base_url('admin/categories.php') ?>" class="sidebar-link <?= $currentAdminPage == 'categories.php' ? 'active' : '' ?>">
        <i class="bi bi-tags-fill"></i>
        <span>Kategori Artikel</span>
      </a>

      <a href="<?= base_url('admin/comments.php') ?>" class="sidebar-link <?= $currentAdminPage == 'comments.php' ? 'active' : '' ?> d-flex justify-content-between align-items-center">
        <div>
          <i class="bi bi-chat-left-dots-fill me-2"></i>
          <span>Komentar</span>
        </div>
        <?php if ($pendingCommentsCount > 0): ?>
          <span class="badge bg-warning text-dark rounded-pill"><?= $pendingCommentsCount ?></span>
        <?php endif; ?>
      </a>

      <div class="sidebar-heading">Sistem & Profil</div>

      <a href="<?= base_url('admin/settings.php') ?>" class="sidebar-link <?= $currentAdminPage == 'settings.php' ? 'active' : '' ?>">
        <i class="bi bi-gear-fill"></i>
        <span>Pengaturan Sekolah</span>
      </a>

      <a href="<?= base_url('index.php') ?>" target="_blank" class="sidebar-link">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Lihat Website</span>
      </a>
    </div>

    <div class="sidebar-footer">
      <a href="<?= base_url('admin/logout.php') ?>" class="btn btn-outline-danger btn-sm w-100 rounded-3" onclick="return confirm('Apakah Anda yakin ingin logout?');">
        <i class="bi bi-box-arrow-right me-1"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Main Content Wrapper -->
  <div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
      <button class="btn btn-light d-lg-none" id="sidebarToggle">
        <i class="bi bi-list fs-5"></i>
      </button>

      <div class="d-flex align-items-center gap-2">
        <span class="text-muted small d-none d-sm-inline">Selamat Datang,</span>
        <strong class="text-dark"><?= htmlspecialchars($adminUser['name'] ?? 'Admin') ?></strong>
      </div>

      <div class="d-flex align-items-center gap-3">
        <a href="<?= base_url('index.php') ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
          <i class="bi bi-globe me-1"></i> Kunjungi Portal
        </a>
        <div class="dropdown">
          <button class="btn btn-light rounded-circle p-2 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-5 text-primary"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
            <li><h6 class="dropdown-header">Akun Administrator</h6></li>
            <li><a class="dropdown-item" href="<?= base_url('admin/settings.php') ?>"><i class="bi bi-key me-2"></i> Ganti Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= base_url('admin/logout.php') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Alerts Container -->
    <div class="admin-content-alerts">
      <?php include __DIR__ . '/../../includes/alerts.php'; ?>
    </div>

    <!-- Page Content Container -->
    <div class="admin-content">
