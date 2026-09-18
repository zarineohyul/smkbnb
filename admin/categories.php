<?php
/**
 * Kelola Kategori Artikel - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Kelola Kategori';
$pdo = getDBConnection();

// Handle Tambah Kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (!empty($name)) {
        $slug = slugify($name);
        // Cek duplikasi
        $chk = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
        $chk->execute([$slug]);
        if ($chk->fetch()) {
            $slug .= '-' . time();
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $description]);
            set_flash('success', 'Kategori "' . htmlspecialchars($name) . '" berhasil ditambahkan.');
            header('Location: ' . base_url('admin/categories.php'));
            exit;
        } catch (Exception $e) {
            set_flash('danger', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    } else {
        set_flash('danger', 'Nama kategori tidak boleh kosong.');
    }
}

// Handle Update Kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if ($id > 0 && !empty($name)) {
        $slug = slugify($name);
        $chk = $pdo->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
        $chk->execute([$slug, $id]);
        if ($chk->fetch()) {
            $slug .= '-' . time();
        }

        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $description, $id]);
            set_flash('success', 'Kategori berhasil diperbarui.');
            header('Location: ' . base_url('admin/categories.php'));
            exit;
        } catch (Exception $e) {
            set_flash('danger', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }
}

// Handle Hapus Kategori
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($delId > 0) {
        // Cek apakah ada artikel di kategori ini
        $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE category_id = ?");
        $cntStmt->execute([$delId]);
        $artCount = (int)$cntStmt->fetchColumn();

        if ($artCount > 0) {
            set_flash('warning', "Kategori tidak dapat dihapus karena masih digunakan oleh $artCount artikel.");
        } else {
            try {
                $del = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                $del->execute([$delId]);
                set_flash('success', 'Kategori berhasil dihapus.');
            } catch (Exception $e) {
                set_flash('danger', 'Gagal menghapus kategori: ' . $e->getMessage());
            }
        }
    }
    header('Location: ' . base_url('admin/categories.php'));
    exit;
}

// Ambil Semua Kategori Beserta Jumlah Artikel
$categories = [];
try {
    $categories = $pdo->query("
        SELECT c.*, COUNT(a.id) as article_count 
        FROM categories c
        LEFT JOIN articles a ON c.id = a.category_id
        GROUP BY c.id
        ORDER BY c.name ASC
    ")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Kategori Artikel</h3>
    <p class="text-muted small mb-0">Kelompokkan berita dan artikel sekolah berdasarkan topik.</p>
  </div>
</div>

<div class="row g-4">
  <!-- Form Tambah Kategori -->
  <div class="col-lg-4">
    <div class="admin-card p-4">
      <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Tambah Kategori Baru</h5>
      <form action="<?= base_url('admin/categories.php') ?>" method="POST">
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
          <label for="catName" class="form-label small fw-bold text-dark">Nama Kategori <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="catName" name="name" placeholder="Contoh: Info Beasiswa" required>
        </div>

        <div class="mb-3">
          <label for="catDesc" class="form-label small fw-bold text-dark">Deskripsi Singkat</label>
          <textarea class="form-control" id="catDesc" name="description" rows="3" placeholder="Keterangan singkat tentang kategori ini..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
          <i class="bi bi-plus-circle me-1"></i> Simpan Kategori
        </button>
      </form>
    </div>
  </div>

  <!-- Daftar Kategori -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="admin-card-title">Daftar Kategori Tersedia (<?= count($categories) ?>)</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
          <thead>
            <tr>
              <th>Nama Kategori</th>
              <th>Slug (URL)</th>
              <th>Deskripsi</th>
              <th>Artikel</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($categories)): ?>
              <?php foreach ($categories as $cat): ?>
                <tr>
                  <td class="fw-bold text-dark">
                    <?= htmlspecialchars($cat['name']) ?>
                  </td>
                  <td>
                    <code class="small text-muted"><?= htmlspecialchars($cat['slug']) ?></code>
                  </td>
                  <td class="small text-muted text-truncate" style="max-width: 220px;">
                    <?= htmlspecialchars($cat['description'] ?: '-') ?>
                  </td>
                  <td>
                    <span class="badge bg-primary-subtle text-primary fw-semibold">
                      <?= $cat['article_count'] ?> Artikel
                    </span>
                  </td>
                  <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $cat['id'] ?>">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <?php if ($cat['article_count'] == 0): ?>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= base_url('admin/categories.php?delete=' . $cat['id']) ?>', 'Hapus kategori <?= htmlspecialchars(addslashes($cat['name'])) ?>?');">
                        <i class="bi bi-trash"></i>
                      </button>
                    <?php else: ?>
                      <button type="button" class="btn btn-sm btn-outline-secondary disabled" title="Tidak dapat dihapus karena memiliki artikel">
                        <i class="bi bi-trash"></i>
                      </button>
                    <?php endif; ?>

                    <!-- Modal Edit -->
                    <div class="modal fade text-start" id="editModal<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content rounded-4 border-0 shadow">
                          <form action="<?= base_url('admin/categories.php') ?>" method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                            
                            <div class="modal-header border-bottom">
                              <h5 class="modal-title fw-bold">Edit Kategori</h5>
                              <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                              <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($cat['description'] ?? '') ?></textarea>
                              </div>
                            </div>
                            <div class="modal-footer border-top">
                              <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary rounded-pill fw-bold">Simpan Perubahan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
