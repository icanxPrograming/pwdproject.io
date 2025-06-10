<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Cards.php';
$cardModel = new Cards();

$cards = $cardModel->getCards();
?>

<!-- Main Content -->
<section class="main-content">
  <h2>Cari Yang Terbaik</h2>
  <div class="card-slider-container">
    <button class="card-slide-btn prev" id="cardPrevBtn">&lt;</button>
    <div class="card-slider">
      <div class="card-group" id="cardGroup">
        <?php foreach ($cards as $card): ?>
          <a href="<?= htmlspecialchars($card['redirect']) ?>" class="card-link">
            <div class="card">
              <img src="/PWD-Project-Mandiri/asset/cards/<?= htmlspecialchars($card['gambar']) ?>" alt="<?= htmlspecialchars($card['judul']) ?>" />
              <h3><?= htmlspecialchars($card['judul']) ?></h3>
              <p><?= htmlspecialchars($card['sub_judul']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <button class="card-slide-btn next" id="cardNextBtn">&gt;</button>
  </div>
</section>