<?php
/**
 * Admin Dashboard Overview - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Dashboard Utama';
$pdo = getDBConnection();

// Hitung Statistik
$totalArticles = 0;
$publishedArticles = 0;
$totalViews = 0;
$totalComments = 0;
$pendingComments = 0;

try {
    $totalArticles = (int)$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    $publishedArticles = (int)$pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'published'")->fetchColumn();
    $totalViews = (int)$pdo->query("SELECT COALESCE(SUM(views_count), 0) FROM articles")->fetchColumn();
    $totalComments = (int)$pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    $pendingComments = (int)$pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
} catch (Exception $e) {}

// Ambil 5 Artikel Terbaru
$recentArticles = [];
try {
    $artStmt = $pdo->query("
        SELECT a.*, c.name as category_name,
               (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id) as comments_count
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        ORDER BY a.created_at DESC
        LIMIT 5
    ");
    $recentArticles = $artStmt->fetchAll();
} catch (Exception $e) {}

// Ambil 5 Komentar Terbaru
$recentComments = [];
try {
    $comStmt = $pdo->query("
        SELECT cm.*, a.title as article_title, a.slug as article_slug
        FROM comments cm
        JOIN articles a ON cm.article_id = a.id
        ORDER BY cm.created_at DESC
        LIMIT 5
    ");
    $recentComments = $comStmt->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Dashboard Ikhtisar</h3>
    <p class="text-muted small mb-0">Ringkasan performa konten artikel dan aktivitas interaksi pengunjung.</p>
  </div>
  <div class="d-flex gap-2 mt-3 mt-sm-0">
    <a href="<?= base_url('admin/article-add.php') ?>" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
      <i class="bi bi-plus-lg me-1"></i> Buat Artikel Baru
    </a>
  </div>
</div>

<!-- Stats Metric Cards -->
<div class="row g-4 mb-4">
  <!-- Total Artikel -->
  <div class="col-sm-6 col-xl-3">
    <div class="admin-stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-label">Total Artikel</div>
          <div class="stat-val"><?= number_format($totalArticles) ?></div>
          <small class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i> <?= $publishedArticles ?> Published</small>
        </div>
        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
          <i class="bi bi-newspaper"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Views -->
  <div class="col-sm-6 col-xl-3">
    <div class="admin-stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-label">Total Pembaca</div>
          <div class="stat-val"><?= number_format($totalViews) ?></div>
          <small class="text-muted"><i class="bi bi-graph-up-arrow text-primary me-1"></i> Akumulasi Tayangan</small>
        </div>
        <div class="stat-icon-wrapper bg-info-subtle text-info">
          <i class="bi bi-eye"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Komentar -->
  <div class="col-sm-6 col-xl-3">
    <div class="admin-stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-label">Total Komentar</div>
          <div class="stat-val"><?= number_format($totalComments) ?></div>
          <small class="text-muted"><i class="bi bi-chat-left-text text-secondary me-1"></i> Anonim & Identitas</small>
        </div>
        <div class="stat-icon-wrapper bg-success-subtle text-success">
          <i class="bi bi-chat-dots-fill"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Komentar Perlu Tinjau -->
  <div class="col-sm-6 col-xl-3">
    <div class="admin-stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-label">Komentar Pending</div>
          <div class="stat-val"><?= number_format($pendingComments) ?></div>
          <small class="<?= $pendingComments > 0 ? 'text-warning' : 'text-muted' ?> fw-semibold">
            <i class="bi bi-shield-exclamation me-1"></i> Perlu Moderasi
          </small>
        </div>
        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
          <i class="bi bi-shield-check"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Section: Recent Articles & Comments -->
<div class="row g-4">
  <!-- Tabel Artikel Terbaru -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="admin-card-title">Artikel Terbaru</h5>
        <a href="<?= base_url('admin/articles.php') ?>" class="btn btn-sm btn-outline-primary rounded-pill">
          Lihat Semua (<?= $totalArticles ?>)
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
          <thead>
            <tr>
              <th>Judul Artikel</th>
              <th>Kategori</th>
              <th>Views</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentArticles)): ?>
              <?php foreach ($recentArticles as $art): ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <?php if (!empty($art['image'])): ?>
                        <img src="<?= asset('uploads/articles/' . $art['image']) ?>" alt="" class="rounded-2" style="width: 48px; height: 38px; object-fit: cover;">
                      <?php else: ?>
                        <div class="bg-secondary-subtle rounded-2 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 38px;">
                          <i class="bi bi-image"></i>
                        </div>
                      <?php endif; ?>
                      <div>
                        <a href="<?= base_url('admin/article-edit.php?id=' . $art['id']) ?>" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 280px;">
                          <?= htmlspecialchars($art['title']) ?>
                        </a>
                        <small class="text-muted"><?= format_date_id($art['created_at']) ?> &bull; <?= $art['comments_count'] ?> Komentar</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($art['category_name']) ?></span>
                  </td>
                  <td>
                    <span class="fw-bold text-muted"><?= number_format($art['views_count']) ?></span>
                  </td>
                  <td>
                    <?php if ($art['status'] === 'published'): ?>
                      <span class="badge-published"><i class="bi bi-check2 me-1"></i> Published</span>
                    <?php else: ?>
                      <span class="badge-draft"><i class="bi bi-file-earmark me-1"></i> Draft</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm">
                      <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" target="_blank" class="btn btn-outline-secondary" title="Buka di Website">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="<?= base_url('admin/article-edit.php?id=' . $art['id']) ?>" class="btn btn-outline-primary" title="Edit Artikel">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Belum ada artikel.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Kolom Komentar Terkini -->
  <div class="col-lg-4">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5 class="admin-card-title">Komentar Terbaru</h5>
        <a href="<?= base_url('admin/comments.php') ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
          Kelola
        </a>
      </div>
      <div class="p-3">
        <?php if (!empty($recentComments)): ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($recentComments as $rc): ?>
              <div class="p-3 bg-light rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <strong class="text-dark small">
                    <?= htmlspecialchars($rc['author_name']) ?>
                  </strong>
                  <?php if ($rc['is_anonymous']): ?>
                    <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.65rem;">
                      <i class="bi bi-incognito me-1"></i> Anonim
                    </span>
                  <?php else: ?>
                    <span class="badge bg-success-subtle text-success small" style="font-size: 0.65rem;">
                      <i class="bi bi-person-check me-1"></i> Terverifikasi
                    </span>
                  <?php endif; ?>
                </div>
                <div class="text-muted small mb-2 text-truncate" style="max-height: 40px;">
                  "<?= htmlspecialchars(mb_strimwidth($rc['content'], 0, 80, '...')) ?>"
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($rc['article_slug'])) ?>" target="_blank" class="small text-primary text-decoration-none text-truncate" style="max-width: 180px;">
                    <i class="bi bi-link-45deg"></i> <?= htmlspecialchars($rc['article_title']) ?>
                  </a>
                  <span class="text-muted" style="font-size: 0.72rem;"><?= format_date_id($rc['created_at']) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4 text-muted small">
            Belum ada komentar masuk.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
