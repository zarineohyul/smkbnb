<?php
/**
 * Logout Administrator
 */
require_once __DIR__ . '/../config/app.php';

if (isset($_SESSION['admin_user'])) {
    unset($_SESSION['admin_user']);
}

session_destroy();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_flash('success', 'Anda telah berhasil keluar (logout) dari panel admin.');
header('Location: ' . base_url('admin/login.php'));
exit;
