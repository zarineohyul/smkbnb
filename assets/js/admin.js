/**
 * SMK Bangun Nusa Bangsa - Admin Scripts
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Slug generator otomatis dari judul artikel
  const titleInput = document.getElementById('title');
  const slugInput = document.getElementById('slug');

  if (titleInput && slugInput) {
    titleInput.addEventListener('input', function () {
      // Hanya update slug jika belum diubah secara manual atau masih baru
      if (!slugInput.dataset.manual) {
        let text = titleInput.value.toLowerCase();
        text = text.replace(/[^\w\s-]/g, '');
        text = text.replace(/[\s_-]+/g, '-');
        text = text.replace(/^-+|-+$/g, '');
        slugInput.value = text;
      }
    });

    slugInput.addEventListener('input', function () {
      slugInput.dataset.manual = 'true';
    });
  }

  // 2. Image upload preview
  const imageInput = document.getElementById('imageInput');
  const imagePreview = document.getElementById('imagePreview');

  if (imageInput && imagePreview) {
    imageInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // 3. Mobile Sidebar Toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('show');
      if (backdrop) backdrop.classList.toggle('d-none');
    });

    if (backdrop) {
      backdrop.addEventListener('click', function () {
        sidebar.classList.remove('show');
        backdrop.classList.add('d-none');
      });
    }
  }
});

// Helper konfirmasi hapus
function confirmDelete(url, message = 'Apakah Anda yakin ingin menghapus data ini?') {
  if (confirm(message)) {
    window.location.href = url;
  }
}
