<?php
/**
 * Pengaturan Profil Admin & Identitas Sekolah - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Pengaturan Sekolah & Akun';
$pdo = getDBConnection();
$adminUser = current_user();

// Handle Form Profil & Password Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];
    if (empty($name)) $errors[] = 'Nama administrator tidak boleh kosong.';
    if (empty($email)) $errors[] = 'Email administrator tidak boleh kosong.';

    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = 'Password baru minimal 6 karakter.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }
    }

    if (empty($errors)) {
        try {
            if (!empty($newPassword)) {
                $hash = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $email, $hash, $adminUser['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $adminUser['id']]);
            }

            // Update session
            $_SESSION['admin_user']['name'] = $name;
            $_SESSION['admin_user']['email'] = $email;

            set_flash('success', 'Profil administrator berhasil diperbarui.');
            header('Location: ' . base_url('admin/settings.php'));
            exit;
        } catch (Exception $e) {
            set_flash('danger', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    } else {
        set_flash('danger', implode('<br>', $errors));
    }
}

// Handle Form Pengaturan Sekolah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_school') {
    $settingsKeys = [
        'school_name', 'school_tagline', 'school_npsn', 'school_accreditation',
        'school_phone', 'school_email', 'school_address', 'headmaster_name',
        'headmaster_welcome', 'maps_embed'
    ];

    try {
        foreach ($settingsKeys as $key) {
            if (isset($_POST[$key])) {
                $val = trim($_POST[$key]);
                $stmt = $pdo->prepare("INSERT INTO school_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$key, $val, $val]);
            }
        }
        set_flash('success', 'Informasi profil sekolah berhasil diperbarui.');
        header('Location: ' . base_url('admin/settings.php'));
        exit;
    } catch (Exception $e) {
        set_flash('danger', 'Gagal memperbarui informasi sekolah: ' . $e->getMessage());
    }
}

// Ambil nilai setting saat ini
$schoolSettings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM school_settings");
    while ($row = $stmt->fetch()) {
        $schoolSettings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {}

// Ambil info admin terbaru
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$adminUser['id']]);
$userProfile = $stmtUser->fetch();

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Pengaturan Sistem & Profil</h3>
    <p class="text-muted small mb-0">Kelola identitas sekolah dan kredensial akun administrator.</p>
  </div>
</div>

<div class="row g-4">
  <!-- Kolom Profil Admin -->
  <div class="col-lg-5">
    <div class="admin-card p-4 mb-4">
      <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
        <i class="bi bi-person-gear text-primary me-2"></i> Profil & Password Admin
      </h5>
      <form action="<?= base_url('admin/settings.php') ?>" method="POST">
        <input type="hidden" name="action" value="update_profile">

        <div class="mb-3">
          <label class="form-label small fw-bold">Nama Lengkap</label>
          <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($userProfile['name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Username</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($userProfile['username'] ?? '') ?>" disabled readonly>
          <div class="form-text small">Username login tidak dapat diubah.</div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Alamat Email</label>
          <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>" required>
        </div>

        <hr class="my-3">
        <h6 class="fw-bold text-dark small mb-2">Ganti Password (Kosongkan jika tidak diubah)</h6>

        <div class="mb-3">
          <label class="form-label small">Password Baru</label>
          <input type="password" class="form-control" name="new_password" placeholder="Minimal 6 karakter">
        </div>

        <div class="mb-3">
          <label class="form-label small">Konfirmasi Password Baru</label>
          <input type="password" class="form-control" name="confirm_password" placeholder="Ulangi password baru">
        </div>

        <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">
          <i class="bi bi-check2 me-1"></i> Simpan Perubahan Akun
        </button>
      </form>
    </div>
  </div>

  <!-- Kolom Pengaturan Profil Sekolah -->
  <div class="col-lg-7">
    <div class="admin-card p-4">
      <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
        <i class="bi bi-building text-primary me-2"></i> Identitas & Kontak Sekolah
      </h5>
      <form action="<?= base_url('admin/settings.php') ?>" method="POST">
        <input type="hidden" name="action" value="update_school">

        <div class="row g-3 mb-3">
          <div class="col-md-8">
            <label class="form-label small fw-bold">Nama Sekolah</label>
            <input type="text" class="form-control" name="school_name" value="<?= htmlspecialchars($schoolSettings['school_name'] ?? '') ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-bold">Status Akreditasi</label>
            <input type="text" class="form-control" name="school_accreditation" value="<?= htmlspecialchars($schoolSettings['school_accreditation'] ?? '') ?>" placeholder="A (Unggul)">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Tagline / Motto Sekolah</label>
          <input type="text" class="form-control" name="school_tagline" value="<?= htmlspecialchars($schoolSettings['school_tagline'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
            <input type="text" class="form-control" name="school_phone" value="<?= htmlspecialchars($schoolSettings['school_phone'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-bold">Email Sekolah</label>
            <input type="email" class="form-control" name="school_email" value="<?= htmlspecialchars($schoolSettings['school_email'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Alamat Lengkap</label>
          <textarea class="form-control" name="school_address" rows="2"><?= htmlspecialchars($schoolSettings['school_address'] ?? '') ?></textarea>
        </div>

        <hr class="my-3">
        <h6 class="fw-bold text-dark mb-3">Pimpinan Sekolah</h6>

        <div class="mb-3">
          <label class="form-label small fw-bold">Nama Kepala Sekolah</label>
          <input type="text" class="form-control" name="headmaster_name" value="<?= htmlspecialchars($schoolSettings['headmaster_name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Pesan Sambutan Kepala Sekolah</label>
          <textarea class="form-control" name="headmaster_welcome" rows="4"><?= htmlspecialchars($schoolSettings['headmaster_welcome'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold">Kode Embed Peta Google Maps (Iframe)</label>
          <textarea class="form-control font-monospace small" name="maps_embed" rows="3"><?= htmlspecialchars($schoolSettings['maps_embed'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
          <i class="bi bi-save me-1"></i> Simpan Identitas Sekolah
        </button>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
