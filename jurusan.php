<?php
/**
 * Program Keahlian / Jurusan - SMK Bangun Nusa Bangsa
 */
require_once __DIR__ . '/config/app.php';

$pageTitle = 'Program Keahlian';
$pageDescription = 'Daftar kompetensi keahlian unggulan di SMK Bangun Nusa Bangsa: Teknik Komputer Jaringan, Teknik Kendaraan Ringan, dan Akuntansi Keuangan.';

include __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-light border-bottom">
  <div class="container text-center">
    <span class="section-tag">Kompetensi Kejuruan</span>
    <h1 class="fw-bold text-dark mb-2">Program Keahlian Unggulan</h1>
    <p class="text-muted max-w-600 mx-auto">
      Kurikulum berbasis industri terkini dirancang untuk menghasilkan lulusan yang terampil, kompetitif, dan langsung siap pakai di dunia kerja.
    </p>
  </div>
</section>

<!-- Jurusan Detail Section -->
<section class="py-5">
  <div class="container">

    <!-- 1. TKJ -->
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden" id="tkj">
      <div class="row g-0">
        <div class="col-lg-5 bg-primary text-white p-4 p-md-5 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #1e40af, #3b82f6) !important;">
          <div class="badge bg-white text-primary fw-bold mb-3 align-self-start px-3 py-2">Infrastruktur IT</div>
          <h2 class="fw-bold mb-3">Teknik Komputer & Jaringan (TKJ)</h2>
          <p class="text-white-50 leading-relaxed mb-4">
            Membekali keahlian konfigurasi perangkat jaringan skala enterprise, instalasi kabel serat optik (fiber optic), serta pemeliharaan server cloud dan keamanan siber.
          </p>
          <div class="small">
            <i class="bi bi-patch-check-fill text-warning me-1"></i> Sertifikasi: <strong>MikroTik Certified Network Associate (MTCNA) & Cisco CCNA</strong>
          </div>
        </div>
        <div class="col-lg-7 p-4 p-md-5 bg-white">
          <h5 class="fw-bold text-dark mb-3">Materi & Keterampilan Utama:</h5>
          <div class="row g-3 mb-4">
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Routing & Switching (MikroTik, Cisco)</li>
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Penyambungan & Pengukuran Fiber Optic (OTDR)</li>
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Administrasi Server Linux & Windows</li>
              </ul>
            </div>
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Dasar Cybersecurity & Network Monitoring</li>
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Virtualisasi (Proxmox, VMware) & Cloud</li>
                <li><i class="bi bi-check2 text-primary me-2 fw-bold"></i>Hardware Troubleshooting & Perawatan PC</li>
              </ul>
            </div>
          </div>
          <div class="p-3 bg-light rounded-3">
            <strong class="d-block text-dark small mb-1"><i class="bi bi-person-workspace text-primary me-1"></i> Prospek Karir Lulusan:</strong>
            <span class="text-muted small">Network Engineer, System Administrator, IT Support Specialist, Fiber Optic Technician, ISP Operations Staff.</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. TKR -->
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden" id="tkr">
      <div class="row g-0">
        <div class="col-lg-5 bg-warning text-dark p-4 p-md-5 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;">
          <div class="badge bg-dark text-white fw-bold mb-3 align-self-start px-3 py-2">Teknik Otomotif</div>
          <h2 class="fw-bold mb-3">Teknik Kendaraan Ringan (TKR)</h2>
          <p class="text-dark leading-relaxed mb-4 opacity-75">
            Mencetak teknisi otomotif handal yang terampil dalam pemeliharaan berkala, perbaikan mesin bensin & diesel, sistem injeksi elektronik (EFI), serta kelistrikan kendaraan bermotor.
          </p>
          <div class="small fw-semibold text-dark">
            <i class="bi bi-patch-check-fill text-dark me-1"></i> Sertifikasi: <strong>LSP Otomotif Indonesia (BNSP) & Standar Bengkel Resmi ATPM</strong>
          </div>
        </div>
        <div class="col-lg-7 p-4 p-md-5 bg-white">
          <h5 class="fw-bold text-dark mb-3">Materi & Keterampilan Utama:</h5>
          <div class="row g-3 mb-4">
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Perawatan Mesin Bensin & Diesel Modern</li>
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Sistem Bahan Bakar Injeksi (EFI / Common Rail)</li>
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Sistem Kelistrikan Bodi & AC Kendaraan</li>
              </ul>
            </div>
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Sistem Pemindah Tenaga (Transmisi, Gardan)</li>
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Chassis, Suspensi, Rem ABS, & Power Steering</li>
                <li><i class="bi bi-check2 text-warning me-2 fw-bold"></i>Diagnostik Scanner Engine (OBD-II)</li>
              </ul>
            </div>
          </div>
          <div class="p-3 bg-light rounded-3">
            <strong class="d-block text-dark small mb-1"><i class="bi bi-person-workspace text-warning me-1"></i> Prospek Karir Lulusan:</strong>
            <span class="text-muted small">Teknisi Otomotif Mobil, Service Advisor Bengkel Resmi, Mekanik Diesel, Teknisi Wheel Alignment, Pengusaha Bengkel Mandiri.</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. AKL -->
    <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden" id="akl">
      <div class="row g-0">
        <div class="col-lg-5 bg-success text-white p-4 p-md-5 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #059669, #10b981) !important;">
          <div class="badge bg-white text-success fw-bold mb-3 align-self-start px-3 py-2">Bisnis & Finansial</div>
          <h2 class="fw-bold mb-3">Akuntansi Keuangan (AKL)</h2>
          <p class="text-white-50 leading-relaxed mb-4">
            Menguasai proses akuntansi manual maupun terkomputerisasi, rekonsiliasi bank, tata kelola perpajakan, audit keuangan, dan sistem administrasi kas modern.
          </p>
          <div class="small">
            <i class="bi bi-patch-check-fill text-warning me-1"></i> Sertifikasi: <strong>Sertifikasi Teknisi Akuntansi Yunior (BNSP) & Accurate/MYOB Certified</strong>
          </div>
        </div>
        <div class="col-lg-7 p-4 p-md-5 bg-white">
          <h5 class="fw-bold text-dark mb-3">Materi & Keterampilan Utama:</h5>
          <div class="row g-3 mb-4">
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Komputer Akuntansi (MYOB & Accurate)</li>
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Akuntansi Perusahaan Dagang & Manufaktur</li>
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Administrasi Perpajakan (e-SPT & PPh)</li>
              </ul>
            </div>
            <div class="col-sm-6">
              <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Spreadsheet Tingkat Lanjut (Advanced Excel)</li>
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Layanan Lembaga Perbankan & Kasir POS</li>
                <li><i class="bi bi-check2 text-success me-2 fw-bold"></i>Audit & Analisa Rasio Keuangan</li>
              </ul>
            </div>
          </div>
          <div class="p-3 bg-light rounded-3">
            <strong class="d-block text-dark small mb-1"><i class="bi bi-person-workspace text-success me-1"></i> Prospek Karir Lulusan:</strong>
            <span class="text-muted small">Staf Akuntansi, Junior Auditor, Staff Perpajakan, Teller/Customer Service Bank, Petugas Payroll.</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
