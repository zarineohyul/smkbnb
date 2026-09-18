<?php
/**
 * Login Administrator - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

// Jika sudah login, langsung ke dashboard
if (is_logged_in()) {
    header('Location: ' . base_url('admin/index.php'));
    exit;
}

$schoolName = get_setting('school_name', 'SMK Bangun Nusa Bangsa');

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        set_flash('danger', 'Harap isi username dan password dengan lengkap.');
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login Berhasil
                session_regenerate_id(true);
                $_SESSION['admin_user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                set_flash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['name']) . '!');
                header('Location: ' . base_url('admin/index.php'));
                exit;
            } else {
                set_flash('danger', 'Kombinasi username atau password salah.');
            }
        } catch (Exception $e) {
            set_flash('danger', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Administrator - <?= htmlspecialchars($schoolName) ?></title>
  
  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Admin Custom CSS -->
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="login-body">

<div class="login-card">
  <div class="text-center mb-4">
    <div class="brand-logo text-white bg-primary p-3 rounded-4 d-inline-flex mb-3 shadow" style="width: 56px; height: 56px; align-items: center; justify-content: center;">
      <i class="bi bi-shield-lock-fill fs-2"></i>
    </div>
    <h4 class="fw-bold text-dark mb-1">Admin Panel Login</h4>
    <p class="text-muted small mb-0"><?= htmlspecialchars($schoolName) ?></p>
  </div>

  <?php include __DIR__ . '/../includes/alerts.php'; ?>

  <form action="<?= base_url('admin/login.php') ?>" method="POST">
    <div class="mb-3">
      <label for="username" class="form-label small fw-bold text-dark">Username</label>
      <div class="input-group">
        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
        <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="Masukkan username" required autofocus>
      </div>
    </div>

    <div class="mb-4">
      <label for="password" class="form-label small fw-bold text-dark">Password</label>
      <div class="input-group">
        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-key"></i></span>
        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Masukkan password" required>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold mb-3 shadow-sm">
      <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
    </button>
  </form>

  <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
    <i class="bi bi-info-circle-fill me-1"></i> <strong>Akun Default:</strong><br>
    Username: <code class="fw-bold">admin</code> | Password: <code class="fw-bold">admin123</code>
  </div>

  <div class="text-center">
    <a href="<?= base_url('index.php') ?>" class="small text-muted text-decoration-none">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Utama
    </a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
