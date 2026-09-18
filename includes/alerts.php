<?php
/**
 * Alert / Flash Message Component
 */
$flash = get_flash();
if ($flash):
    $alertType = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';
    $iconMap = [
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill'
    ];
    $icon = $iconMap[$alertType] ?? 'bi-bell-fill';
?>
<div class="container mt-3">
  <div class="alert alert-<?= htmlspecialchars($alertType) ?> alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm rounded-3" role="alert">
    <i class="bi <?= $icon ?> fs-5"></i>
    <div><?= $message ?></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<?php endif; ?>
