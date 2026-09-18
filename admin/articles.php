<?php
/**
 * Kelola Artikel - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Kelola Artikel';
$pdo = getDBConnection();

$search = sanitize($_GET['q'] ?? '');
$filterCategory = (int)($_GET['kategori'] ?? 0);
$filterStatus = sanitize($_GET['status'] ?? '');

$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(a.title LIKE ? OR a.excerpt LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filterCategory > 0) {
    $where[] = "a.category_id = ?";
    $params[] = $filterCategory;
}

if (!empty($filterStatus) && in_array($filterStatus, ['published', 'draft'])) {
    $where[] = "a.status = ?";
    $params[] = $filterStatus;
}

$whereSql = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

// Ambil Kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Query Artikel
$articles = [];
try {
    $stmt = $pdo->prepare("
        SELECT a.*, c.name as category_name, u.name as author_name,
               (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id) as comment_count
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        JOIN users u ON a.user_id = u.id
        $whereSql
        ORDER BY a.created_at DESC
    ");
    $stmt->execute($params);
    $articles = $stmt->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Kelola Artikel & Berita</h3>
    <p class="text-muted small mb-0">Total <?= count($articles) ?> artikel ditemukan.</p>
  </div>
  <a href="<?= base_url('admin/article-add.php') ?>" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
  </a>
</div>

<!-- Filter Box -->
<div class="admin-card p-3 mb-4">
  <form action="<?= base_url('admin/articles.php') ?>" method="GET" class="row g-2 align-items-center">
    <div class="col-md-5">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari judul artikel..." value="<?= htmlspecialchars($search) ?>">
      </div>
    </div>
    <div class="col-md-3">
      <select name="kategori" class="form-select form-select-sm">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status" class="form-select form-select-sm">
        <option value="">Semua Status</option>
        <option value="published" <?= $filterStatus === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>
    <div class="col-md-2 d-flex gap-1">
      <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">Filter</button>
      <?php if (!empty($search) || $filterCategory > 0 || !empty($filterStatus)): ?>
        <a href="<?= base_url('admin/articles.php') ?>" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Table Artikel -->
<div class="admin-card">
  <div class="table-responsive">
    <table class="table table-custom align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 70px;">Media</th>
          <th>Judul & Ringkasan</th>
          <th>Kategori</th>
          <th>Komentar</th>
          <th>Views</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($articles)): ?>
          <?php foreach ($articles as $art): ?>
            <tr>
              <td>
                <?php if (!empty($art['image'])): ?>
                  <img src="<?= asset('uploads/articles/' . $art['image']) ?>" alt="" class="rounded-3" style="width: 60px; height: 45px; object-fit: cover;">
                <?php else: ?>
                  <div class="bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 45px;">
                    <i class="bi bi-image"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url('admin/article-edit.php?id=' . $art['id']) ?>" class="fw-bold text-dark text-decoration-none d-block">
                  <?= htmlspecialchars($art['title']) ?>
                </a>
                <small class="text-muted text-truncate d-block" style="max-width: 320px;">
                  <?= htmlspecialchars($art['excerpt']) ?>
                </small>
              </td>
              <td>
                <span class="badge bg-light text-dark border"><?= htmlspecialchars($art['category_name']) ?></span>
              </td>
              <td>
                <a href="<?= base_url('admin/comments.php?article_id=' . $art['id']) ?>" class="badge bg-primary-subtle text-primary text-decoration-none">
                  <i class="bi bi-chat-text-fill me-1"></i> <?= $art['comment_count'] ?>
                </a>
              </td>
              <td>
                <span class="fw-semibold text-muted"><?= number_format($art['views_count']) ?></span>
              </td>
              <td>
                <?php if ($art['status'] === 'published'): ?>
                  <span class="badge-published"><i class="bi bi-check-circle-fill me-1"></i> Published</span>
                <?php else: ?>
                  <span class="badge-draft"><i class="bi bi-pencil-fill me-1"></i> Draft</span>
                <?php endif; ?>
              </td>
              <td class="small text-muted">
                <?= date('d/m/Y', strtotime($art['created_at'])) ?>
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" target="_blank" class="btn btn-outline-secondary" title="Lihat di Portal">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= base_url('admin/article-edit.php?id=' . $art['id']) ?>" class="btn btn-outline-primary" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="confirmDelete('<?= base_url('admin/article-delete.php?id=' . $art['id']) ?>', 'Yakin ingin menghapus artikel ini? Semua komentar di artikel ini juga akan terhapus.');">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
              Tidak ada artikel yang sesuai kriteria filter.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
