<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Services.php';
$servicesModel = new Services();
$services = $servicesModel->getServices();
function slugify($text)
{
  $text = str_replace(' ', '-', $text);
  $text = preg_replace('/[^a-zA-Z0-9\-]/', '', $text);
  $text = strtolower($text);
  return $text;
}
?>
<div class="services-page">
  <?php foreach ($services as $row): ?>
    <?php $sectionId = slugify($row['judul_section']); ?>
    <div class="service-section" id="<?= $sectionId ?>">
      <div class="service-header" onclick="toggleContent(this)">
        <?= htmlspecialchars($row['icon']) ?> <?= htmlspecialchars($row['judul_section']) ?>
        <span class="service-icon">➕</span>
      </div>
      <div class="service-content">
        <?= $row['konten'] ?>
      </div>
    </div>
  <?php endforeach; ?>

  <a href="index.php" class="services-back-link">Kembali</a>
</div>