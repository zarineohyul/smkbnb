<?php
/**
 * Detail Artikel & Sistem Komentar - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pdo = getDBConnection();
$slug = sanitize($_GET['slug'] ?? '');

if (empty($slug)) {
    header('Location: ' . base_url('artikel.php'));
    exit;
}

// Ambil data artikel
$article = null;
try {
    $stmt = $pdo->prepare("
        SELECT a.*, c.name as category_name, c.slug as category_slug, u.name as author_name
        FROM articles a
        JOIN categories c ON a.category_id = c.id
        JOIN users u ON a.user_id = u.id
        WHERE a.slug = ? AND a.status = 'published'
        LIMIT 1
    ");
    $stmt->execute([$slug]);
    $article = $stmt->fetch();
} catch (Exception $e) {
    // fallback
}

if (!$article) {
    set_flash('warning', 'Artikel yang Anda cari tidak ditemukan atau belum dipublikasikan.');
    header('Location: ' . base_url('artikel.php'));
    exit;
}

// Increment view counter
try {
    $viewStmt = $pdo->prepare("UPDATE articles SET views_count = views_count + 1 WHERE id = ?");
    $viewStmt->execute([$article['id']]);
    $article['views_count']++;
} catch (Exception $e) {
    // ignore counter failure
}

// Ambil daftar komentar yang sudah disetujui (approved)
$comments = [];
try {
    $commentStmt = $pdo->prepare("
        SELECT * FROM comments 
        WHERE article_id = ? AND status = 'approved' 
        ORDER BY created_at DESC
    ");
    $commentStmt->execute([$article['id']]);
    $comments = $commentStmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

// Ambil artikel terkait di kategori yang sama
$relatedArticles = [];
try {
    $relStmt = $pdo->prepare("
        SELECT id, title, slug, image, created_at 
        FROM articles 
        WHERE category_id = ? AND id != ? AND status = 'published'
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $relStmt->execute([$article['category_id'], $article['id']]);
    $relatedArticles = $relStmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

$pageTitle = $article['title'];
$pageDescription = $article['excerpt'];

include __DIR__ . '/includes/header.php';
?>

<!-- Header Detail Artikel -->
<section class="article-header">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('artikel.php') ?>" class="text-decoration-none">Artikel</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('artikel.php?kategori=' . urlencode($article['category_slug'])) ?>" class="text-decoration-none"><?= htmlspecialchars($article['category_name']) ?></a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 250px;" aria-current="page"><?= htmlspecialchars($article['title']) ?></li>
          </ol>
        </nav>

        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill mb-2">
          <?= htmlspecialchars($article['category_name']) ?>
        </span>

        <h1 class="display-6 fw-bold text-dark mt-2 mb-3">
          <?= htmlspecialchars($article['title']) ?>
        </h1>

        <div class="d-flex flex-wrap align-items-center gap-3 text-muted small py-2 border-top border-bottom">
          <span><i class="bi bi-person-circle text-primary me-1"></i> Penulis: <strong><?= htmlspecialchars($article['author_name']) ?></strong></span>
          <span><i class="bi bi-calendar3 text-primary me-1"></i> <?= format_date_id($article['created_at'], true) ?></span>
          <span><i class="bi bi-eye text-primary me-1"></i> <?= $article['views_count'] ?> Pembaca</span>
          <span><i class="bi bi-chat-dots text-primary me-1"></i> <?= count($comments) ?> Komentar</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Konten Artikel -->
<section class="py-5">
  <div class="container">
    <div class="row justify-content-center g-5">
      <!-- Kolom Konten Utama -->
      <div class="col-lg-8">
        <?php if (!empty($article['image'])): ?>
          <div class="mb-4">
            <img src="<?= asset('uploads/articles/' . $article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-detail-img">
          </div>
        <?php endif; ?>

        <!-- Text Konten -->
        <article class="article-content mb-5">
          <?= $article['content'] ?>
        </article>

        <!-- Share Buttons -->
        <div class="p-3 bg-light rounded-4 d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5 border">
          <span class="fw-bold small text-dark"><i class="bi bi-share-fill text-primary me-2"></i> Bagikan Artikel:</span>
          <div class="d-flex gap-2">
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' ' . base_url('artikel-detail.php?slug=' . $article['slug'])) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
              <i class="bi bi-whatsapp me-1"></i> WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(base_url('artikel-detail.php?slug=' . $article['slug'])) ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
              <i class="bi bi-facebook me-1"></i> Facebook
            </a>
            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan artikel berhasil disalin!');" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
              <i class="bi bi-link-45deg me-1"></i> Salin Link
            </button>
          </div>
        </div>

        <!-- Section Komentar -->
        <div class="comment-section-box" id="komentar">
          <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
            <h4 class="fw-bold mb-0">
              <i class="bi bi-chat-left-text-fill text-primary me-2"></i>
              Komentar Pembaca (<?= count($comments) ?>)
            </h4>
            <a href="#form-komentar" class="btn btn-sm btn-primary rounded-pill">
              <i class="bi bi-pencil-square me-1"></i> Tulis Komentar
            </a>
          </div>

          <!-- Daftar Komentar yang Ada -->
          <div class="comment-list mb-5">
            <?php if (!empty($comments)): ?>
              <?php foreach ($comments as $c): ?>
                <div class="comment-item">
                  <div class="d-flex align-items-start gap-3">
                    <div class="comment-avatar <?= $c['is_anonymous'] ? 'avatar-anon' : 'avatar-user' ?>">
                      <?= $c['is_anonymous'] ? '<i class="bi bi-person-fill-lock"></i>' : strtoupper(substr($c['author_name'], 0, 1)) ?>
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                        <div class="d-flex align-items-center gap-2">
                          <h6 class="fw-bold mb-0 text-dark">
                            <?= htmlspecialchars($c['author_name']) ?>
                          </h6>
                          <?php if ($c['is_anonymous']): ?>
                            <span class="comment-badge-anon">
                              <i class="bi bi-incognito me-1"></i> Anonim
                            </span>
                          <?php else: ?>
                            <span class="comment-badge-verified">
                              <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                            </span>
                          <?php endif; ?>
                        </div>
                        <span class="text-muted small">
                          <i class="bi bi-clock me-1"></i> <?= format_date_id($c['created_at'], true) ?>
                        </span>
                      </div>
                      <p class="text-secondary mb-0 small leading-relaxed mt-2">
                        <?= nl2br(htmlspecialchars($c['content'])) ?>
                      </p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center py-4 text-muted">
                <i class="bi bi-chat-square-dots fs-2 text-secondary mb-2 d-block"></i>
                <p class="mb-0">Belum ada komentar untuk artikel ini. Jadilah yang pertama memberikan tanggapan!</p>
              </div>
            <?php endif; ?>
          </div>

          <!-- Formulir Tambah Komentar Interaktif -->
          <div class="comment-form-card" id="form-komentar">
            <h5 class="fw-bold text-dark mb-3">
              <i class="bi bi-chat-dots-fill text-primary me-2"></i> Tinggalkan Komentar
            </h5>
            <p class="text-muted small mb-4">
              Anda dapat memilih untuk berkomentar dengan menyertakan identitas resmi (nama & email) atau berkomentar secara anonim tanpa membuka identitas.
            </p>

            <form action="<?= base_url('post-comment.php') ?>" method="POST" id="commentForm">
              <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
              <input type="hidden" name="article_slug" value="<?= $article['slug'] ?>">

              <!-- Toggle Anonim Switch -->
              <div class="toggle-anon-wrapper mb-4">
                <div class="form-check form-switch d-flex align-items-center gap-2 m-0">
                  <input class="form-check-input fs-5 m-0" type="checkbox" role="switch" id="is_anonymous" name="is_anonymous" value="1">
                  <label class="form-check-label fw-bold text-primary mb-0 ms-1" for="is_anonymous">
                    <i class="bi bi-incognito me-1"></i> Berikan Komentar sebagai Anonim (Tanpa Nama & Email)
                  </label>
                </div>
                <div id="anonNotice" class="small text-muted mt-2 d-none">
                  <i class="bi bi-shield-check text-success me-1"></i> Identitas Anda disembunyikan. Komentar akan tampil atas nama <strong>Anonim</strong>.
                </div>
              </div>

              <!-- Field Nama & Email (Tampil jika BUKAN anonim) -->
              <div class="row g-3 mb-3">
                <div class="col-md-6" id="authorNameGroup">
                  <label for="author_name" class="form-label small fw-bold text-dark">
                    Nama Lengkap <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control" id="author_name" name="author_name" placeholder="Masukkan nama Anda..." required>
                </div>

                <div class="col-md-6" id="authorEmailGroup">
                  <label for="author_email" class="form-label small fw-bold text-dark">
                    Alamat Email <span class="text-danger">*</span>
                  </label>
                  <input type="email" class="form-control" id="author_email" name="author_email" placeholder="contoh@domain.com" required>
                  <div class="form-text small">Email Anda tidak akan dipublikasikan ke publik.</div>
                </div>
              </div>

              <!-- Field Isi Komentar -->
              <div class="mb-3">
                <label for="comment_content" class="form-label small fw-bold text-dark">
                  Isi Tanggapan / Komentar <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="comment_content" name="content" rows="4" placeholder="Tuliskan komentar atau pertanyaan Anda dengan sopan..." required minlength="5"></textarea>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <i class="bi bi-info-circle me-1"></i> Komentar harus mematuhi etika komunikasi yang baik.
                </small>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                  <i class="bi bi-send-fill me-1"></i> Kirim Komentar
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>

      <!-- Sidebar Artikel Terkait & Info Sekolah -->
      <div class="col-lg-4">
        <!-- Card Terkait -->
        <div class="p-4 bg-light rounded-4 border mb-4 shadow-sm">
          <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
            <i class="bi bi-newspaper text-primary me-2"></i> Artikel Terkait
          </h5>
          <?php if (!empty($relatedArticles)): ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($relatedArticles as $rel): ?>
                <div class="d-flex align-items-center gap-3">
                  <?php if (!empty($rel['image'])): ?>
                    <img src="<?= asset('uploads/articles/' . $rel['image']) ?>" alt="" class="rounded-3 flex-shrink-0" style="width: 70px; height: 60px; object-fit: cover;">
                  <?php else: ?>
                    <div class="rounded-3 bg-secondary-subtle flex-shrink-0 d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 60px;">
                      <i class="bi bi-image"></i>
                    </div>
                  <?php endif; ?>
                  <div>
                    <h6 class="small fw-bold mb-1">
                      <a href="<?= base_url('artikel-detail.php?slug=' . urlencode($rel['slug'])) ?>" class="text-dark text-decoration-none">
                        <?= htmlspecialchars($rel['title']) ?>
                      </a>
                    </h6>
                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?= format_date_id($rel['created_at']) ?></small>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-muted small mb-0">Belum ada artikel lain dalam kategori ini.</p>
          <?php endif; ?>
        </div>

        <!-- Banner Pendaftaran -->
        <div class="p-4 rounded-4 text-white shadow-sm text-center" style="background: linear-gradient(135deg, #1e40af, #0f172a);">
          <i class="bi bi-mortarboard-fill fs-1 text-warning mb-2 d-inline-block"></i>
          <h5 class="fw-bold mb-2">Ingin Bergabung?</h5>
          <p class="small text-white-50 mb-3">
            Pendaftaran peserta didik baru SMK Bangun Nusa Bangsa TA 2026/2027 telah dibuka.
          </p>
          <a href="<?= base_url('kontak.php') ?>" class="btn btn-warning btn-sm rounded-pill fw-bold w-100 py-2">
            Konsultasi PPDB Sekarang
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
