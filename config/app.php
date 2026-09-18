<?php
/**
 * Konfigurasi Aplikasi & Helper Functions
 * SMK Bangun Nusa Bangsa
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

/**
 * Dapatkan base URL dinamis
 */
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Deteksi subfolder jika berada di htdocs
    $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    
    // Normalisasi folder project
    $baseFolder = '';
    if (strpos($scriptName, '/admin') !== false) {
        $baseFolder = substr($scriptName, 0, strpos($scriptName, '/admin'));
    } else {
        $baseFolder = rtrim($scriptName, '/');
    }

    $url = $protocol . $host . $baseFolder;
    return rtrim($url, '/') . '/' . ltrim($path, '/');
}

/**
 * URL untuk aset publik
 */
function asset($path = '') {
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Sanitasi string input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Flash message helper
 */
function set_flash($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function get_flash() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Format tanggal Indonesia
 */
function format_date_id($datetime, $includeTime = false) {
    if (empty($datetime)) return '-';
    $timestamp = strtotime($datetime);
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $d = date('j', $timestamp);
    $m = $bulan[(int)date('n', $timestamp)];
    $y = date('Y', $timestamp);
    
    if ($includeTime) {
        $t = date('H:i', $timestamp);
        return "$d $m $y, $t WIB";
    }
    return "$d $m $y";
}

/**
 * Membuat slug URL ramah SEO
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a-' . time();
    }
    return $text;
}

/**
 * Helper autentikasi admin
 */
function is_logged_in() {
    return !empty($_SESSION['admin_user']);
}

function current_user() {
    return $_SESSION['admin_user'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        set_flash('danger', 'Silakan login terlebih dahulu untuk mengakses dashboard admin.');
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}

/**
 * Ambil setting profil sekolah dari database
 */
function get_setting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM school_settings");
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // fallback
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Helper Upload Gambar
 */
function handle_image_upload($file, $targetDir, $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg']) {
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Tidak ada file yang diunggah atau terjadi error.'];
    }

    // Cek ukuran max 3MB
    if ($file['size'] > 3 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Ukuran file maksimal 3 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $allowedTypes)) {
        return ['success' => false, 'message' => 'Format file tidak didukung. Harap unggah JPG, PNG, atau WEBP.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'art_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $destination = rtrim($targetDir, '/') . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename];
    }

    return ['success' => false, 'message' => 'Gagal menyimpan file di server.'];
}
