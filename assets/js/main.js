/**
 * SMK Bangun Nusa Bangsa - Public Interactions
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Comment Form - Anonymous Toggle Logic
  const anonCheckbox = document.getElementById('is_anonymous');
  const authorNameGroup = document.getElementById('authorNameGroup');
  const authorEmailGroup = document.getElementById('authorEmailGroup');
  const authorNameInput = document.getElementById('author_name');
  const authorEmailInput = document.getElementById('author_email');
  const anonNotice = document.getElementById('anonNotice');

  if (anonCheckbox) {
    function updateCommentFormState() {
      if (anonCheckbox.checked) {
        // Mode Anonim Aktif
        if (authorNameGroup) authorNameGroup.style.display = 'none';
        if (authorEmailGroup) authorEmailGroup.style.display = 'none';
        if (authorNameInput) {
          authorNameInput.removeAttribute('required');
          authorNameInput.value = 'Anonim';
        }
        if (authorEmailInput) {
          authorEmailInput.removeAttribute('required');
          authorEmailInput.value = '';
        }
        if (anonNotice) anonNotice.classList.remove('d-none');
      } else {
        // Mode Beridentitas (Nama & Email Wajib)
        if (authorNameGroup) authorNameGroup.style.display = 'block';
        if (authorEmailGroup) authorEmailGroup.style.display = 'block';
        if (authorNameInput) {
          authorNameInput.setAttribute('required', 'required');
          if (authorNameInput.value === 'Anonim') {
            authorNameInput.value = '';
          }
        }
        if (authorEmailInput) {
          authorEmailInput.setAttribute('required', 'required');
        }
        if (anonNotice) anonNotice.classList.add('d-none');
      }
    }

    anonCheckbox.addEventListener('change', updateCommentFormState);
    // Jalankan saat pertama kali dimuat
    updateCommentFormState();
  }

  // 2. Auto-dismiss alerts after 5 seconds
  const autoAlerts = document.querySelectorAll('.alert-dismissible');
  autoAlerts.forEach(function (alert) {
    setTimeout(function () {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
});
