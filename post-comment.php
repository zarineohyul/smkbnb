<?php
/**
 * Handler Pengiriman Komentar Artikel
 * SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . base_url('artikel.php'));
    exit;
}

$pdo = getDBConnection();

$articleId = (int)($_POST['article_id'] ?? 0);
$articleSlug = sanitize($_POST['article_slug'] ?? '');
$isAnonymous = isset($_POST['is_anonymous']) && $_POST['is_anonymous'] == '1' ? 1 : 0;
$authorName = sanitize($_POST['author_name'] ?? '');
$authorEmail = filter_var(trim($_POST['author_email'] ?? ''), FILTER_SANITIZE_EMAIL);
$content = trim($_POST['content'] ?? '');

$redirectUrl = base_url('artikel-detail.php?slug=' . urlencode($articleSlug) . '#komentar');

// Verifikasi artikel ada
$stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ? AND status = 'published'");
$stmt->execute([$articleId]);
if (!$stmt->fetch()) {
    set_flash('danger', 'Artikel tidak valid atau sudah dihapus.');
    header('Location: ' . base_url('artikel.php'));
    exit;
}

// Validasi Isi Komentar
if (empty($content) || mb_strlen($content) < 3) {
    set_flash('danger', 'Isi komentar tidak boleh kosong (minimal 3 karakter).');
    header('Location: ' . $redirectUrl);
    exit;
}

// Validasi Berdasarkan Mode Anonim vs Identitas Wajib
if ($isAnonymous) {
    $finalAuthorName = 'Anonim';
    $finalAuthorEmail = null;
} else {
    // Mode Beridentitas: Wajib ada nama & email valid
    if (empty($authorName) || $authorName === 'Anonim') {
        set_flash('danger', 'Nama lengkap wajib diisi jika Anda tidak memilih opsi anonim.');
        header('Location: ' . $redirectUrl);
        exit;
    }

    if (empty($authorEmail) || !filter_var($authorEmail, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Alamat email wajib diisi dengan format yang valid jika tidak berkomentar secara anonim.');
        header('Location: ' . $redirectUrl);
        exit;
    }

    $finalAuthorName = $authorName;
    $finalAuthorEmail = $authorEmail;
}

try {
    $insertStmt = $pdo->prepare("
        INSERT INTO comments (article_id, is_anonymous, author_name, author_email, content, status) 
        VALUES (?, ?, ?, ?, ?, 'approved')
    ");
    $insertStmt->execute([
        $articleId,
        $isAnonymous,
        $finalAuthorName,
        $finalAuthorEmail,
        $content
    ]);

    set_flash('success', 'Terima kasih! Komentar Anda berhasil dipublikasikan.');
} catch (Exception $e) {
    set_flash('danger', 'Terjadi kesalahan sistem saat menyimpan komentar: ' . $e->getMessage());
}

header('Location: ' . $redirectUrl);
exit;
