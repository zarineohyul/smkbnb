<?php
/**
 * Hapus Artikel - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/../config/app.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT title, image FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $art = $stmt->fetch();

        if ($art) {
            // Hapus file gambar jika bukan contoh bawaan
            if (!empty($art['image']) && !str_starts_with($art['image'], 'sample_')) {
                $imagePath = __DIR__ . '/../assets/uploads/articles/' . $art['image'];
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            // Hapus dari database (komentar otomatis terhapus karena ON DELETE CASCADE)
            $delStmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
            $delStmt->execute([$id]);

            set_flash('success', 'Artikel "' . htmlspecialchars($art['title']) . '" dan komentar terkait berhasil dihapus.');
        } else {
            set_flash('warning', 'Artikel tidak ditemukan.');
        }
    } catch (Exception $e) {
        set_flash('danger', 'Gagal menghapus artikel: ' . $e->getMessage());
    }
}

header('Location: ' . base_url('admin/articles.php'));
exit;
