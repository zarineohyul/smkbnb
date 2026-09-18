<?php
/**
 * Konfigurasi Database MySQL (PDO)
 * SMK Bangun Nusa Bangsa
 */

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smk_bnbrz');

function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Jika database belum ada, coba koneksi tanpa dbname agar bisa migrasi otomatis
            try {
                $rawDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
                $rawPdo = new PDO($rawDsn, DB_USER, DB_PASS, $options);
                return $rawPdo;
            } catch (PDOException $rawE) {
                die("Gagal terhubung ke MySQL Server: " . $rawE->getMessage());
            }
        }
    }
    return $pdo;
}
