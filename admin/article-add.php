<?php
/**
 * Tambah Artikel Baru - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Tambah Artikel Baru';
$pdo = getDBConnection();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $slugInput = sanitize($_POST['slug'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $excerpt = sanitize($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
    $userId = current_user()['id'] ?? 1;

    $slug = !empty($slugInput) ? slugify($slugInput) : slugify($title);

    // Cek duplikasi slug
    $checkSlug = $pdo->prepare("SELECT id FROM articles WHERE slug = ?");
    $checkSlug->execute([$slug]);
    if ($checkSlug->fetch()) {
        $slug .= '-' . time();
    }

    $errors = [];
    if (empty($title)) $errors[] = 'Judul artikel wajib diisi.';
    if ($categoryId <= 0) $errors[] = 'Kategori artikel wajib dipilih.';
    if (empty($excerpt)) $errors[] = 'Ringkasan artikel wajib diisi.';
    if (empty($content)) $errors[] = 'Isi konten artikel wajib diisi.';

    $imageFilename = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadRes = handle_image_upload($_FILES['image'], __DIR__ . '/../assets/uploads/articles');
        if ($uploadRes['success']) {
            $imageFilename = $uploadRes['filename'];
        } else {
            $errors[] = $uploadRes['message'];
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO articles (category_id, user_id, title, slug, excerpt, content, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $categoryId,
                $userId,
                $title,
                $slug,
                $excerpt,
                $content,
                $imageFilename,
                $status
            ]);

            set_flash('success', 'Artikel "' . htmlspecialchars($title) . '" berhasil dibuat dan disimpan!');
            header('Location: ' . base_url('admin/articles.php'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Gagal menyimpan artikel ke database: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        set_flash('danger', implode('<br>', $errors));
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Tambah Artikel Baru</h3>
    <p class="text-muted small mb-0">Tulis berita, pengumuman, atau artikel edukasi sekolah.</p>
  </div>
  <a href="<?= base_url('admin/articles.php') ?>" class="btn btn-outline-secondary rounded-pill fw-semibold">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
  </a>
</div>

<form action="<?= base_url('admin/article-add.php') ?>" method="POST" enctype="multipart/form-data">
  <div class="row g-4">
    <!-- Kolom Kiri: Form Utama Konten -->
    <div class="col-lg-8">
      <div class="admin-card p-4">
        <!-- Judul -->
        <div class="mb-3">
          <label for="title" class="form-label fw-bold text-dark">Judul Artikel <span class="text-danger">*</span></label>
          <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Masukkan judul artikel yang menarik..." required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <!-- Slug URL -->
        <div class="mb-3">
          <label for="slug" class="form-label small fw-bold text-dark">Slug URL (Permalink) <span class="text-danger">*</span></label>
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted">/artikel-detail.php?slug=</span>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="otomatis-dibuat-dari-judul" value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>" required>
          </div>
          <div class="form-text small">Slug dibuat otomatis dari judul, atau dapat disesuaikan manual.</div>
        </div>

        <!-- Ringkasan (Excerpt) -->
        <div class="mb-3">
          <label for="excerpt" class="form-label small fw-bold text-dark">Ringkasan / Cuplikan (Excerpt) <span class="text-danger">*</span></label>
          <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Ringkasan singkat untuk tampilan kartu preview dan deskripsi pencarian..." required><?= htmlspecialchars($_POST['excerpt'] ?? '') ?></textarea>
        </div>

        <!-- Isi Konten Lengkap -->
        <div class="mb-3">
          <label for="content" class="form-label fw-bold text-dark">Isi Konten Artikel <span class="text-danger">*</span></label>
          <p class="text-muted small mb-2">Gunakan format paragraf HTML (<code>&lt;p&gt;</code>, <code>&lt;h3&gt;</code>, <code>&lt;ul&gt;</code>, dll.) untuk styling yang rapi.</p>
          <textarea class="form-control font-monospace" id="content" name="content" rows="12" placeholder="<p>Tulis paragraf pembuka di sini...</p>" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Pengaturan & Gambar -->
    <div class="col-lg-4">
      <!-- Status & Kategori Card -->
      <div class="admin-card p-4 mb-4">
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Publikasi</h6>

        <div class="mb-3">
          <label for="status" class="form-label small fw-bold text-dark">Status Artikel</label>
          <select class="form-select" id="status" name="status">
            <option value="published" <?= ($_POST['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published (Tayang)</option>
            <option value="draft" <?= ($_POST['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (Konsep)</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label small fw-bold text-dark">Kategori Artikel <span class="text-danger">*</span></label>
          <select class="form-select" id="category_id" name="category_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <hr class="my-3">

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
          <i class="bi bi-save me-1"></i> Simpan & Publikasikan
        </button>
      </div>

      <!-- Gambar Unggulan Card -->
      <div class="admin-card p-4">
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Gambar Sampul (Thumbnail)</h6>

        <div class="mb-3">
          <label for="imageInput" class="form-label small text-muted">Format: JPG, PNG, WEBP (Maksimal 3MB)</label>
          <input class="form-control" type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="text-center">
          <img id="imagePreview" src="#" alt="Pratinjau Gambar" class="img-fluid rounded-3 border" style="display: none; max-height: 180px; width: 100%; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
