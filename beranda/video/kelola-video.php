<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Video.php';
$videoModel = new Video();

// Ambil semua video yang statusnya 'Posting'
$data = $videoModel->getAllActive();
?>

<div class="video-list">
  <?php if (empty($data)): ?>
    <p class="text-muted">Tidak ada video tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $item): ?>
      <div class="video-card">
        <div class="video-image">
          <!-- Embed Video -->
          <?php
          $url = htmlspecialchars($item['url']);
          $judul_video = htmlspecialchars($item['judul_video']);

          // Cek apakah URL dari YouTube
          if (strpos($url, 'youtube') !== false && preg_match('/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
            $iframeUrl = "https://www.youtube.com/embed/$videoId?autoplay=0";
            echo '<iframe src="' . $iframeUrl . '" allowfullscreen style="width:100%; height:340px; border-radius:12px; border:none;"></iframe>';
          } else {
            // Jika bukan YouTube, coba embed biasa atau tampilkan placeholder
            echo '<div class="video-placeholder">';
            echo   '<p>URL video tidak didukung. Silakan klik judul untuk membuka di tab baru.</p>';
            echo   '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">Buka Video</a>';
            echo '</div>';
          }
          ?>
        </div>

        <div class="video-content">
          <h3 class="video-title">
            <a href="<?= $url ?>" target="_blank" class="video-title-link">
              <?= $judul_video ?>
            </a>
          </h3>

          <div class="video-meta">
            <span class="category"><?= ucfirst(htmlspecialchars($item['kategori'])) ?></span>
            <span class="date"><?= date('d M Y', strtotime($item['tanggal'])) ?></span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>