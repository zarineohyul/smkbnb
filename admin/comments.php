<?php
/**
 * Moderasi Komentar Artikel - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Manajemen Komentar';
$pdo = getDBConnection();

// Handle Ubah Status Komentar
if (isset($_GET['status_change']) && isset($_GET['id'])) {
    $cId = (int)$_GET['id'];
    $newStatus = sanitize($_GET['status_change']);

    if (in_array($newStatus, ['approved', 'pending', 'spam'])) {
        try {
            $stmt = $pdo->prepare("UPDATE comments SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $cId]);
            set_flash('success', 'Status komentar berhasil diubah menjadi: ' . ucfirst($newStatus));
        } catch (Exception $e) {
            set_flash('danger', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
    header('Location: ' . base_url('admin/comments.php'));
    exit;
}

// Handle Hapus Komentar
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    try {
        $del = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $del->execute([$delId]);
        set_flash('success', 'Komentar berhasil dihapus permanen.');
    } catch (Exception $e) {
        set_flash('danger', 'Gagal menghapus komentar: ' . $e->getMessage());
    }
    header('Location: ' . base_url('admin/comments.php'));
    exit;
}

// Filter Parameter
$filterArticleId = (int)($_GET['article_id'] ?? 0);
$filterStatus = sanitize($_GET['status'] ?? '');
$filterType = sanitize($_GET['type'] ?? ''); // 'anon' or 'named'

$where = [];
$params = [];

if ($filterArticleId > 0) {
    $where[] = "cm.article_id = ?";
    $params[] = $filterArticleId;
}

if (!empty($filterStatus) && in_array($filterStatus, ['approved', 'pending', 'spam'])) {
    $where[] = "cm.status = ?";
    $params[] = $filterStatus;
}

if ($filterType === 'anon') {
    $where[] = "cm.is_anonymous = 1";
} elseif ($filterType === 'named') {
    $where[] = "cm.is_anonymous = 0";
}

$whereSql = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

// Ambil Komentar
$comments = [];
try {
    $stmt = $pdo->prepare("
        SELECT cm.*, a.title as article_title, a.slug as article_slug
        FROM comments cm
        JOIN articles a ON cm.article_id = a.id
        $whereSql
        ORDER BY cm.created_at DESC
    ");
    $stmt->execute($params);
    $comments = $stmt->fetchAll();
} catch (Exception $e) {}

// Ambil List Artikel untuk dropdown filter
$articleList = $pdo->query("SELECT id, title FROM articles ORDER BY title ASC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- Header Page -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Manajemen & Moderasi Komentar</h3>
    <p class="text-muted small mb-0">Kelola komentar pembaca (baik mode Anonim maupun Beridentitas nama & email).</p>
  </div>
</div>

<!-- Filter Komentar -->
<div class="admin-card p-3 mb-4">
  <form action="<?= base_url('admin/comments.php') ?>" method="GET" class="row g-2 align-items-center">
    <div class="col-md-4">
      <select name="article_id" class="form-select form-select-sm">
        <option value="">Semua Artikel</option>
        <?php foreach ($articleList as $al): ?>
          <option value="<?= $al['id'] ?>" <?= $filterArticleId == $al['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars(mb_strimwidth($al['title'], 0, 50, '...')) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="type" class="form-select form-select-sm">
        <option value="">Semua Tipe Komentar</option>
        <option value="anon" <?= $filterType === 'anon' ? 'selected' : '' ?>>Hanya Anonim</option>
        <option value="named" <?= $filterType === 'named' ? 'selected' : '' ?>>Hanya Beridentitas (Nama & Email)</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select form-select-sm">
        <option value="">Semua Status</option>
        <option value="approved" <?= $filterStatus === 'approved' ? 'selected' : '' ?>>Disetujui (Approved)</option>
        <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>Menunggu Review (Pending)</option>
        <option value="spam" <?= $filterStatus === 'spam' ? 'selected' : '' ?>>Spam</option>
      </select>
    </div>
    <div class="col-md-2 d-flex gap-1">
      <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">Filter</button>
      <?php if ($filterArticleId > 0 || !empty($filterStatus) || !empty($filterType)): ?>
        <a href="<?= base_url('admin/comments.php') ?>" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Tabel Komentar -->
<div class="admin-card">
  <div class="admin-card-header">
    <h5 class="admin-card-title">Daftar Komentar (<?= count($comments) ?>)</h5>
  </div>
  <div class="table-responsive">
    <table class="table table-custom align-middle mb-0">
      <thead>
        <tr>
          <th>Penulis</th>
          <th>Tipe</th>
          <th>Isi Komentar</th>
          <th>Artikel Terkait</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th class="text-end">Moderasi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($comments)): ?>
          <?php foreach ($comments as $c): ?>
            <tr>
              <td>
                <div class="fw-bold text-dark">
                  <?= htmlspecialchars($c['author_name']) ?>
                </div>
                <?php if (!empty($c['author_email'])): ?>
                  <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($c['author_email']) ?></small>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($c['is_anonymous']): ?>
                  <span class="badge bg-secondary-subtle text-secondary border fw-semibold">
                    <i class="bi bi-incognito me-1"></i> Anonim
                  </span>
                <?php else: ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">
                    <i class="bi bi-person-check-fill me-1"></i> Identitas Lengkap
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <div class="text-secondary small leading-relaxed" style="max-width: 300px;">
                  <?= nl2br(htmlspecialchars($c['content'])) ?>
                </div>
              </td>
              <td>
                <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($c['article_slug'])) ?>" target="_blank" class="small text-primary text-decoration-none fw-semibold d-block text-truncate" style="max-width: 200px;">
                  <i class="bi bi-link-45deg"></i> <?= htmlspecialchars($c['article_title']) ?>
                </a>
              </td>
              <td>
                <?php if ($c['status'] === 'approved'): ?>
                  <span class="badge bg-success-subtle text-success fw-bold">Disetujui</span>
                <?php elseif ($c['status'] === 'pending'): ?>
                  <span class="badge bg-warning-subtle text-dark fw-bold">Pending</span>
                <?php else: ?>
                  <span class="badge bg-danger-subtle text-danger fw-bold">Spam</span>
                <?php endif; ?>
              </td>
              <td class="small text-muted">
                <?= format_date_id($c['created_at'], true) ?>
              </td>
              <td class="text-end">
                <div class="dropdown d-inline-block">
                  <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Aksi
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <?php if ($c['status'] !== 'approved'): ?>
                      <li>
                        <a class="dropdown-item text-success" href="<?= base_url('admin/comments.php?id=' . $c['id'] . '&status_change=approved') ?>">
                          <i class="bi bi-check-circle me-2"></i> Setujui (Publish)
                        </a>
                      </li>
                    <?php endif; ?>
                    <?php if ($c['status'] !== 'pending'): ?>
                      <li>
                        <a class="dropdown-item text-warning" href="<?= base_url('admin/comments.php?id=' . $c['id'] . '&status_change=pending') ?>">
                          <i class="bi bi-clock-history me-2"></i> Set Jadi Pending
                        </a>
                      </li>
                    <?php endif; ?>
                    <?php if ($c['status'] !== 'spam'): ?>
                      <li>
                        <a class="dropdown-item text-secondary" href="<?= base_url('admin/comments.php?id=' . $c['id'] . '&status_change=spam') ?>">
                          <i class="bi bi-shield-slash me-2"></i> Tandai Spam
                        </a>
                      </li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <button class="dropdown-item text-danger" onclick="confirmDelete('<?= base_url('admin/comments.php?delete=' . $c['id']) ?>', 'Yakin ingin menghapus komentar ini secara permanen?');">
                        <i class="bi bi-trash me-2"></i> Hapus Permanen
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bi bi-chat-square-dots fs-1 d-block mb-2 text-secondary"></i>
              Tidak ada komentar yang ditemukan.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
