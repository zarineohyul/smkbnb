<?php
/**
 * Profil Sekolah - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Profil Sekolah';
$pageDescription = 'Visi, Misi, Sejarah, dan Fasilitas Pendidikan di SMK Bangun Nusa Bangsa.';

include __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-light border-bottom">
  <div class="container text-center">
    <span class="section-tag">Tentang Kami</span>
    <h1 class="fw-bold text-dark mb-2">Profil SMK Bangun Nusa Bangsa</h1>
    <p class="text-muted max-w-600 mx-auto">
      Mengenal lebih dekat visi keunggulan, sejarah perjalanan, dan komitmen kami dalam menghadirkan pendidikan vokasi bertaraf industri.
    </p>
  </div>
</section>

<!-- Sejarah & Visi Misi -->
<section class="py-5">
  <div class="container">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-lg-6">
        <h3 class="fw-bold text-dark mb-3">Sejarah Singkat</h3>
        <p class="text-muted leading-relaxed">
          Didirikan dengan cita-cita luhur mencetak generasi muda Indonesia yang mandiri dan berdaya saing di era percepatan digital, <strong>SMK Bangun Nusa Bangsa</strong> telah berkembang menjadi salah satu sekolah menengah kejuruan terdepan.
        </p>
        <p class="text-muted leading-relaxed">
          Dengan mengusung moto <em>"Membangun Kompetensi, Mengabdi untuk Negeri"</em>, sekolah ini terus menyempurnakan kurikulum berbasis Teaching Factory dan bekerja sama dengan puluhan perusahaan multinasional demi memastikan setiap lulusan memiliki portofolio keahlian yang nyata dan diakui industri internasional.
        </p>
      </div>
      <div class="col-lg-6">
        <img src="<?= asset('uploads/articles/sample_mou.jpg') ?>" alt="Kemitraan Industri" class="img-fluid rounded-4 shadow">
      </div>
    </div>

    <!-- Visi & Misi Box -->
    <div class="row g-4 mt-2">
      <div class="col-md-6">
        <div class="p-4 rounded-4 bg-primary text-white h-100 shadow-sm" style="background: linear-gradient(135deg, #1e40af, #2563eb) !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-2 bg-white bg-opacity-20 rounded-3">
              <i class="bi bi-eye-fill fs-3 text-warning"></i>
            </div>
            <h3 class="fw-bold mb-0">Visi Sekolah</h3>
          </div>
          <p class="lead fs-6 text-white-50">
            "Menjadi Sekolah Menengah Kejuruan unggul bertaraf internasional yang menghasilkan sumber daya manusia berakhlak mulia, kompeten dalam teknologi, berjiwa wirausaha, dan siap bersaing di pasar kerja global pada tahun 2030."
          </p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-4 rounded-4 bg-light border h-100 shadow-sm">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-2 bg-primary-subtle rounded-3 text-primary">
              <i class="bi bi-bullseye fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-0">Misi Sekolah</h3>
          </div>
          <ul class="list-unstyled text-muted small mb-0 d-flex flex-column gap-2">
            <li class="d-flex align-items-start gap-2">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span>Menyelenggarakan pendidikan vokasi yang adaptif dan selaras dengan standar kebutuhan dunia kerja dan industri (DUDI).</span>
            </li>
            <li class="d-flex align-items-start gap-2">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span>Mengembangkan potensi bakat dan minat siswa melalui kegiatan akademik, sertifikasi kompetensi keahlian, dan ekstrakurikuler.</span>
            </li>
            <li class="d-flex align-items-start gap-2">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span>Membangun ekosistem belajar yang berlandaskan integritas moral, kedisiplinan, dan nilai-nilai kebangsaan.</span>
            </li>
            <li class="d-flex align-items-start gap-2">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span>Memperluas jejaring kemitraan strategis dengan institusi industri dan perguruan tinggi vokasi unggulan.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Fasilitas Sekolah -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-tag">Fasilitas Kampus</span>
      <h2 class="section-title">Sarana Penunjang Belajar Modern</h2>
      <p class="text-muted max-w-600 mx-auto">
        SMK Bangun Nusa Bangsa menyediakan sarana laboratorium dan lingkungan belajar berstandar industri demi menunjang kenyamanan praktik siswa.
      </p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
          <div class="text-primary mb-3"><i class="bi bi-pc-display-horizontal fs-1"></i></div>
          <h5 class="fw-bold">Laboratorium Komputer & Jaringan</h5>
          <p class="text-muted small">
            Dilengkapi PC berspesifikasi tinggi, perangkat rack server Mikrotik/Cisco, tester fiber optic, dan koneksi internet Gigabit berkecepatan tinggi.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
          <div class="text-warning mb-3"><i class="bi bi-tools fs-1"></i></div>
          <h5 class="fw-bold">Bengkel Otomotif & Workshop TKR</h5>
          <p class="text-muted small">
            Fasilitas standar bengkel resmi ATPM dengan lift hidrolik, engine stand injeksi EFI/Common Rail, scanner komputer OBD-II, dan alat wheel alignment.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
          <div class="text-success mb-3"><i class="bi bi-bank fs-1"></i></div>
          <h5 class="fw-bold">Bank Mini & Lab Akuntansi</h5>
          <p class="text-muted small">
            Simulasi lingkungan kerja perbankan riil dengan mesin hitung uang, software kasir POS, dan aplikasi sistem akuntansi tersertifikasi.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
