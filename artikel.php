<?php
/**
 * Daftar Artikel & Berita - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Berita & Artikel';
$pageDescription = 'Informasi terkini seputar kegiatan siswa, prestasi, pengumuman sekolah, dan tips edukasi dari SMK Bangun Nusa Bangsa.';

$pdo = getDBConnection();

// Parameter Pencarian & Filter
$search = sanitize($_GET['q'] ?? '');
$categorySlug = sanitize($_GET['kategori'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

// Ambil Kategori untuk Filter Tabs
$categories = [];
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
} catch (Exception $e) {
    // fallback
}

// Query Artikel dengan Filter Dinamis
$whereClauses = ["a.status = 'published'"];
$params = [];

if (!empty($search)) {
    $whereClauses[] = "(a.title LIKE ? OR a.excerpt LIKE ? OR a.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($categorySlug)) {
    $whereClauses[] = "c.slug = ?";
    $params[] = $categorySlug;
}

$whereSql = implode(' AND ', $whereClauses);

// Hitung total artikel untuk pagination
$totalArticles = 0;
try {
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        WHERE $whereSql
    ");
    $countStmt->execute($params);
    $totalArticles = $countStmt->fetchColumn();
} catch (Exception $e) {
    // fallback
}

$totalPages = ceil($totalArticles / $limit);

// Ambil data artikel
$articles = [];
try {
    $articleStmt = $pdo->prepare("
        SELECT a.*, c.name as category_name, c.slug as category_slug,
               (SELECT COUNT(*) FROM comments cm WHERE cm.article_id = a.id AND cm.status = 'approved') as comment_count
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        WHERE $whereSql
        ORDER BY a.created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    $articleStmt->execute($params);
    $articles = $articleStmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-light border-bottom">
  <div class="container text-center">
    <span class="section-tag">Warta Sekolah</span>
    <h1 class="fw-bold text-dark mb-2">Berita & Informasi Terkini</h1>
    <p class="text-muted max-w-600 mx-auto">
      Kabar seputar prestasi, agenda sekolah, kegiatan kejuruan, dan artikel inspiratif SMK Bangun Nusa Bangsa.
    </p>

    <!-- Form Pencarian -->
    <div class="row justify-content-center mt-4">
      <div class="col-md-6 col-lg-5">
        <form action="<?= base_url('artikel.php') ?>" method="GET" class="input-group shadow-sm rounded-pill overflow-hidden bg-white p-1 border">
          <input type="text" name="q" class="form-control border-0 ps-3 shadow-none" placeholder="Cari judul artikel atau topik..." value="<?= htmlspecialchars($search) ?>">
          <?php if (!empty($categorySlug)): ?>
            <input type="hidden" name="kategori" value="<?= htmlspecialchars($categorySlug) ?>">
          <?php endif; ?>
          <button class="btn btn-primary rounded-pill px-4" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
      <a href="<?= base_url('artikel.php' . (!empty($search) ? '?q=' . urlencode($search) : '')) ?>" 
         class="btn btn-sm rounded-pill px-3 py-2 <?= empty($categorySlug) ? 'btn-primary' : 'btn-outline-secondary' ?>">
        Semua Kategori
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= base_url('artikel.php?kategori=' . urlencode($cat['slug']) . (!empty($search) ? '&q=' . urlencode($search) : '')) ?>" 
           class="btn btn-sm rounded-pill px-3 py-2 <?= $categorySlug == $cat['slug'] ? 'btn-primary' : 'btn-outline-secondary' ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- List Artikel -->
<section class="py-5">
  <div class="container">
    <?php if (!empty($search) || !empty($categorySlug)): ?>
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <span class="text-muted">
          Menampilkan hasil untuk: 
          <?php if (!empty($search)): ?><strong>"<?= htmlspecialchars($search) ?>"</strong> <?php endif; ?>
          <?php if (!empty($categorySlug)): ?><span class="badge bg-primary-subtle text-primary">Kategori: <?= htmlspecialchars($categorySlug) ?></span><?php endif; ?>
          (<?= $totalArticles ?> artikel ditemukan)
        </span>
        <a href="<?= base_url('artikel.php') ?>" class="small text-danger text-decoration-none">
          <i class="bi bi-x-circle me-1"></i> Reset Filter
        </a>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $art): ?>
          <div class="col-md-6 col-lg-4">
            <div class="article-card">
              <div class="article-img-wrapper">
                <?php if (!empty($art['image'])): ?>
                  <img src="<?= asset('uploads/articles/' . $art['image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="article-img">
                <?php else: ?>
                  <div class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center text-muted">
                    <i class="bi bi-newspaper fs-1"></i>
                  </div>
                <?php endif; ?>
                <span class="article-cat-badge"><?= htmlspecialchars($art['category_name']) ?></span>
              </div>
              <div class="p-4 d-flex flex-column flex-grow-1">
                <div class="d-flex align-items-center gap-3 text-muted small mb-2">
                  <span><i class="bi bi-calendar3 me-1"></i> <?= format_date_id($art['created_at']) ?></span>
                  <span><i class="bi bi-chat-text me-1"></i> <?= $art['comment_count'] ?></span>
                  <span><i class="bi bi-eye me-1"></i> <?= $art['views_count'] ?></span>
                </div>
                <h5 class="fw-bold mb-3">
                  <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" class="article-title text-decoration-none">
                    <?= htmlspecialchars($art['title']) ?>
                  </a>
                </h5>
                <p class="text-muted small mb-4 flex-grow-1">
                  <?= htmlspecialchars(mb_strimwidth($art['excerpt'], 0, 130, '...')) ?>
                </p>
                <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($art['slug'])) ?>" class="btn btn-sm btn-outline-primary rounded-pill fw-bold align-self-start">
                  Baca Selengkapnya & Komentar <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <div class="mb-3 text-muted">
            <i class="bi bi-search fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark">Tidak ada artikel yang ditemukan</h4>
          <p class="text-muted">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
          <a href="<?= base_url('artikel.php') ?>" class="btn btn-primary rounded-pill px-4 mt-2">
            Lihat Semua Artikel
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav class="mt-5">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link rounded-circle me-2" href="?page=<?= $page - 1 ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?>">
              <i class="bi bi-chevron-left"></i>
            </a>
          </li>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
              <a class="page-link rounded-circle mx-1" href="?page=<?= $i ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?>">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link rounded-circle ms-2" href="?page=<?= $page + 1 ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?><?= !empty($categorySlug) ? '&kategori=' . urlencode($categorySlug) : '' ?>">
              <i class="bi bi-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
