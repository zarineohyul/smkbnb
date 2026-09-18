<?php
/**
 * Kontak & Lokasi - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Hubungi Kami';
$pageDescription = 'Informasi kontak resmi, alamat kampus, dan formulir pesan serta layanan informasi PPDB SMK Bangun Nusa Bangsa.';

$schoolName = get_setting('school_name', 'SMK Bangun Nusa Bangsa');
$schoolPhone = get_setting('school_phone', '(021) 8765-4321');
$schoolEmail = get_setting('school_email', 'info@smkbangunnusabangsa.sch.id');
$schoolAddress = get_setting('school_address', 'Jl. SKB Raya No. 18 Cibinong');
$mapsEmbed = get_setting('maps_embed', '');

// Proses jika ada submit form kontak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderName = sanitize($_POST['name'] ?? '');
    $senderEmail = sanitize($_POST['email'] ?? '');
    $senderPhone = sanitize($_POST['phone'] ?? '');
    $senderMessage = sanitize($_POST['message'] ?? '');

    if (!empty($senderName) && !empty($senderEmail) && !empty($senderMessage)) {
        set_flash('success', 'Pesan Anda telah berhasil terkirim ke tim administrasi SMK Bangun Nusa Bangsa. Kami akan segera menghubungi Anda.');
        header('Location: ' . base_url('kontak.php'));
        exit;
    } else {
        set_flash('danger', 'Mohon lengkapi seluruh kolom formulir yang wajib diisi.');
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-light border-bottom">
  <div class="container text-center">
    <span class="section-tag">Informasi & Bantuan</span>
    <h1 class="fw-bold text-dark mb-2">Hubungi Kami</h1>
    <p class="text-muted max-w-600 mx-auto">
      Punya pertanyaan seputar kurikulum, pendaftaran siswa baru (PPDB), atau kemitraan industri? Tim kami siap membantu Anda.
    </p>
  </div>
</section>

<!-- Konten Kontak -->
<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Kolom Info Kontak -->
      <div class="col-lg-5">
        <h3 class="fw-bold text-dark mb-4">Informasi Sekolah</h3>
        
        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4 mb-3 border">
          <div class="p-3 bg-primary-subtle text-primary rounded-3">
            <i class="bi bi-geo-alt-fill fs-4"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Alamat Kampus</h6>
            <p class="text-muted small mb-0"><?= htmlspecialchars($schoolAddress) ?></p>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4 mb-3 border">
          <div class="p-3 bg-success-subtle text-success rounded-3">
            <i class="bi bi-telephone-fill fs-4"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Telepon & WhatsApp</h6>
            <p class="text-muted small mb-0"><?= htmlspecialchars($schoolPhone) ?></p>
            <small class="text-success fw-semibold">Senin - Jumat (07:30 - 16:00 WIB)</small>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4 mb-4 border">
          <div class="p-3 bg-warning-subtle text-warning rounded-3">
            <i class="bi bi-envelope-fill fs-4"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Email Resmi</h6>
            <p class="text-muted small mb-0"><?= htmlspecialchars($schoolEmail) ?></p>
            <small class="text-muted">Respon maksimal 1x24 jam kerja</small>
          </div>
        </div>

        <!-- Jam Layanan -->
        <div class="p-4 bg-primary text-white rounded-4 shadow-sm" style="background: linear-gradient(135deg, #1e40af, #2563eb) !important;">
          <h5 class="fw-bold mb-2"><i class="bi bi-clock-history me-2"></i> Jam Pelayanan Sekretariat</h5>
          <ul class="list-unstyled small text-white-50 mb-0 d-flex flex-column gap-1">
            <li class="d-flex justify-content-between">
              <span>Senin - Kamis:</span>
              <strong class="text-white">07:30 - 15:30 WIB</strong>
            </li>
            <li class="d-flex justify-content-between">
              <span>Jumat:</span>
              <strong class="text-white">07:30 - 11:30 WIB</strong>
            </li>
            <li class="d-flex justify-content-between">
              <span>Sabtu (Layanan PPDB):</span>
              <strong class="text-white">08:00 - 13:00 WIB</strong>
            </li>
          </ul>
        </div>
      </div>

      <!-- Kolom Formulir Pesan -->
      <div class="col-lg-7">
        <div class="p-4 p-md-5 bg-white border rounded-4 shadow-sm">
          <h3 class="fw-bold text-dark mb-2">Kirim Pesan / Tanya PPDB</h3>
          <p class="text-muted small mb-4">
            Silakan isi formulir di bawah ini untuk konsultasi pendaftaran siswa baru atau informasi lainnya.
          </p>

          <form action="<?= base_url('kontak.php') ?>" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Anda..." required>
              </div>
              <div class="col-md-6">
                <label for="phone" class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx">
              </div>
              <div class="col-12">
                <label for="email" class="form-label small fw-bold">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@domain.com" required>
              </div>
              <div class="col-12">
                <label for="message" class="form-label small fw-bold">Isi Pesan / Pertanyaan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tuliskan pertanyaan atau pesan Anda di sini..." required></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                  <i class="bi bi-send-fill me-1"></i> Kirim Pesan Sekarang
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Peta Lokasi Google Maps -->
    <div class="mt-5">
      <h4 class="fw-bold text-dark mb-3"><i class="bi bi-map-fill text-primary me-2"></i> Lokasi Kampus Sekolah</h4>
      <div class="rounded-4 overflow-hidden shadow-sm border" style="min-height: 350px;">
        <?php if (!empty($mapsEmbed)): ?>
          <?= $mapsEmbed ?>
        <?php else: ?>
          <iframe src="https://maps.google.com/maps?q=Jakarta&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
