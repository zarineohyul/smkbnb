<?php
/**
 * Edit Artikel - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Edit Artikel';
$pdo = getDBConnection();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . base_url('admin/articles.php'));
    exit;
}

// Ambil data artikel
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    set_flash('danger', 'Artikel tidak ditemukan.');
    header('Location: ' . base_url('admin/articles.php'));
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $slugInput = sanitize($_POST['slug'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $excerpt = sanitize($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';

    $slug = !empty($slugInput) ? slugify($slugInput) : slugify($title);

    // Cek duplikasi slug selain ID ini
    $checkSlug = $pdo->prepare("SELECT id FROM articles WHERE slug = ? AND id != ?");
    $checkSlug->execute([$slug, $id]);
    if ($checkSlug->fetch()) {
        $slug .= '-' . time();
    }

    $errors = [];
    if (empty($title)) $errors[] = 'Judul artikel wajib diisi.';
    if ($categoryId <= 0) $errors[] = 'Kategori artikel wajib dipilih.';
    if (empty($excerpt)) $errors[] = 'Ringkasan artikel wajib diisi.';
    if (empty($content)) $errors[] = 'Isi konten artikel wajib diisi.';

    $imageFilename = $article['image'];
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
            $updateStmt = $pdo->prepare("
                UPDATE articles 
                SET category_id = ?, title = ?, slug = ?, excerpt = ?, content = ?, image = ?, status = ?
                WHERE id = ?
            ");
            $updateStmt->execute([
                $categoryId,
                $title,
                $slug,
                $excerpt,
                $content,
                $imageFilename,
                $status,
                $id
            ]);

            set_flash('success', 'Artikel "' . htmlspecialchars($title) . '" berhasil diperbarui!');
            header('Location: ' . base_url('admin/articles.php'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Gagal memperbarui artikel: ' . $e->getMessage();
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
    <h3 class="fw-bold text-dark mb-1">Edit Artikel</h3>
    <p class="text-muted small mb-0">Ubah informasi, teks konten, atau sampul artikel.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($article['slug'])) ?>" target="_blank" class="btn btn-outline-info rounded-pill fw-semibold">
      <i class="bi bi-eye me-1"></i> Pratinjau
    </a>
    <a href="<?= base_url('admin/articles.php') ?>" class="btn btn-outline-secondary rounded-pill fw-semibold">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>
</div>

<form action="<?= base_url('admin/article-edit.php?id=' . $article['id']) ?>" method="POST" enctype="multipart/form-data">
  <div class="row g-4">
    <!-- Kolom Kiri: Form Konten -->
    <div class="col-lg-8">
      <div class="admin-card p-4">
        <div class="mb-3">
          <label for="title" class="form-label fw-bold text-dark">Judul Artikel <span class="text-danger">*</span></label>
          <input type="text" class="form-control form-control-lg" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? $article['title']) ?>">
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label small fw-bold text-dark">Slug URL (Permalink) <span class="text-danger">*</span></label>
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted">/artikel-detail.php?slug=</span>
            <input type="text" class="form-control" id="slug" name="slug" data-manual="true" value="<?= htmlspecialchars($_POST['slug'] ?? $article['slug']) ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="excerpt" class="form-label small fw-bold text-dark">Ringkasan / Cuplikan (Excerpt) <span class="text-danger">*</span></label>
          <textarea class="form-control" id="excerpt" name="excerpt" rows="3" required><?= htmlspecialchars($_POST['excerpt'] ?? $article['excerpt']) ?></textarea>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label fw-bold text-dark">Isi Konten Artikel <span class="text-danger">*</span></label>
          <textarea class="form-control font-monospace" id="content" name="content" rows="12" required><?= htmlspecialchars($_POST['content'] ?? $article['content']) ?></textarea>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Pengaturan & Gambar -->
    <div class="col-lg-4">
      <div class="admin-card p-4 mb-4">
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Status & Kategori</h6>

        <div class="mb-3">
          <label for="status" class="form-label small fw-bold text-dark">Status Artikel</label>
          <select class="form-select" id="status" name="status">
            <?php $curStatus = $_POST['status'] ?? $article['status']; ?>
            <option value="published" <?= $curStatus === 'published' ? 'selected' : '' ?>>Published (Tayang)</option>
            <option value="draft" <?= $curStatus === 'draft' ? 'selected' : '' ?>>Draft (Konsep)</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label small fw-bold text-dark">Kategori Artikel <span class="text-danger">*</span></label>
          <select class="form-select" id="category_id" name="category_id" required>
            <?php $curCat = $_POST['category_id'] ?? $article['category_id']; ?>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $curCat == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="small text-muted mb-3">
          <div><i class="bi bi-eye me-1"></i> Dibaca: <strong><?= number_format($article['views_count']) ?> kali</strong></div>
          <div><i class="bi bi-calendar3 me-1"></i> Dibuat: <strong><?= format_date_id($article['created_at']) ?></strong></div>
        </div>

        <hr class="my-3">

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
          <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
        </button>
      </div>

      <!-- Gambar Unggulan -->
      <div class="admin-card p-4">
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Gambar Sampul</h6>

        <?php if (!empty($article['image'])): ?>
          <div class="mb-3 text-center">
            <span class="small text-muted d-block mb-1">Gambar Saat Ini:</span>
            <img src="<?= asset('uploads/articles/' . $article['image']) ?>" alt="Gambar Sekarang" class="img-fluid rounded-3 border mb-2" style="max-height: 160px; width: 100%; object-fit: cover;">
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="imageInput" class="form-label small text-muted">Ganti Gambar (Opsional):</label>
          <input class="form-control form-control-sm" type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="text-center">
          <img id="imagePreview" src="#" alt="Pratinjau Gambar Baru" class="img-fluid rounded-3 border" style="display: none; max-height: 160px; width: 100%; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
