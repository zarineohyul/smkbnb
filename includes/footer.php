<?php
/**
 * Public Footer Component
 * SMK Bangun Nusa Bangsa
 */
$schoolName = get_setting('school_name', 'SMK Bangun Nusa Bangsa');
$schoolTagline = get_setting('school_tagline', 'Mencetak Generasi Cerdas, Terampil, Berkarakter, dan Siap Kerja Global');
$schoolPhone = get_setting('school_phone', '(021) 8765-4321');
$schoolEmail = get_setting('school_email', 'info@smkbangunnusabangsa.sch.id');
$schoolAddress = get_setting('school_address', 'Jl. Pahlawan Pendidikan No. 45, Kompleks Nusantara Mandiri');
?>
  </main>

  <footer class="footer-custom">
    <div class="container">
      <div class="row g-4">
        <!-- Kolom 1: Profil Singkat -->
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="brand-logo text-white bg-primary p-2 rounded-3">
              <i class="bi bi-mortarboard-fill fs-4"></i>
            </div>
            <h5 class="text-white fw-bold mb-0"><?= htmlspecialchars($schoolName) ?></h5>
          </div>
          <p class="text-secondary small mb-3 leading-relaxed">
            <?= htmlspecialchars($schoolTagline) ?>
          </p>
          <div class="d-flex gap-2">
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="bi bi-facebook"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="bi bi-instagram"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="bi bi-youtube"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <!-- Kolom 2: Tautan Cepat -->
        <div class="col-lg-2 col-md-6">
          <h6 class="footer-title">Tautan Cepat</h6>
          <ul class="footer-links">
            <li><a href="<?= base_url('index.php') ?>"><i class="bi bi-chevron-right me-1 text-primary"></i> Beranda</a></li>
            <li><a href="<?= base_url('profil.php') ?>"><i class="bi bi-chevron-right me-1 text-primary"></i> Profil Sekolah</a></li>
            <li><a href="<?= base_url('jurusan.php') ?>"><i class="bi bi-chevron-right me-1 text-primary"></i> Program Keahlian</a></li>
            <li><a href="<?= base_url('artikel.php') ?>"><i class="bi bi-chevron-right me-1 text-primary"></i> Berita & Artikel</a></li>
            <li><a href="<?= base_url('kontak.php') ?>"><i class="bi bi-chevron-right me-1 text-primary"></i> Kontak & PPDB</a></li>
          </ul>
        </div>

        <!-- Kolom 3: Program Keahlian -->
        <div class="col-lg-3 col-md-6">
          <h6 class="footer-title">Program Keahlian</h6>
          <ul class="footer-links">
            <li><a href="<?= base_url('jurusan.php#tkj') ?>"><i class="bi bi-hdd-network me-1 text-primary"></i> Teknik Komputer & Jaringan</a></li>
            <li><a href="<?= base_url('jurusan.php#tkr') ?>"><i class="bi bi-car-front-fill me-1 text-warning"></i> Teknik Kendaraan Ringan</a></li>
            <li><a href="<?= base_url('jurusan.php#akl') ?>"><i class="bi bi-calculator me-1 text-success"></i> Akuntansi Keuangan</a></li>
          </ul>
        </div>

        <!-- Kolom 4: Hubungi Kami -->
        <div class="col-lg-3 col-md-6">
          <h6 class="footer-title">Kontak Sekolah</h6>
          <div class="small text-secondary mb-2 d-flex align-items-start gap-2">
            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
            <span><?= htmlspecialchars($schoolAddress) ?></span>
          </div>
          <div class="small text-secondary mb-2 d-flex align-items-center gap-2">
            <i class="bi bi-telephone-fill text-success"></i>
            <span><?= htmlspecialchars($schoolPhone) ?></span>
          </div>
          <div class="small text-secondary mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-envelope-fill text-warning"></i>
            <span><?= htmlspecialchars($schoolEmail) ?></span>
          </div>
          <a href="<?= base_url('kontak.php') ?>" class="btn btn-sm btn-outline-warning w-100 rounded-pill fw-bold">
            <i class="bi bi-chat-dots me-1"></i> Kirim Pesan / Tanya PPDB
          </a>
        </div>
      </div>

      <div class="footer-bottom text-center text-secondary">
        <p class="mb-0">
          &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($schoolName) ?></strong>. Seluruh Hak Cipta Dilindungi Undang-Undang.
        </p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- Custom JS -->
  <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
