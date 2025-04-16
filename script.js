// Script untuk slider (tambahkan di akhir body)
let currentIndex = 0;
const slides = document.querySelectorAll(".slides img");
const totalSlides = slides.length;

document.querySelector(".next").addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % totalSlides;
  updateSlider();
});

document.querySelector(".prev").addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
  updateSlider();
});

function updateSlider() {
  const offset = -currentIndex * 100;
  document.querySelector(".slides").style.transform = `translateX(${offset}%)`;
}

// Slider Main Content
const cardGroup = document.getElementById("cardGroup");
const cardPrevBtn = document.getElementById("cardPrevBtn");
const cardNextBtn = document.getElementById("cardNextBtn");

// Konfigurasi
const cardWidth = 200; // Lebar card + gap (200px card + 20px gap)
const cardsPerPage = 3; // Jumlah card per halaman
let currentPosition = 0;

// Fungsi geser slide
function slideCards(direction) {
  const totalCards = cardGroup.children.length;
  const maxPosition = Math.ceil(totalCards / cardsPerPage) - 1;

  currentPosition += direction;

  // Looping logic
  if (currentPosition > maxPosition) currentPosition = 0;
  if (currentPosition < 0) currentPosition = maxPosition;

  cardGroup.style.transform = `translateX(-${
    currentPosition * cardsPerPage * cardWidth
  }px)`;
}

// Event listeners
cardPrevBtn.addEventListener("click", () => slideCards(-1));
cardNextBtn.addEventListener("click", () => slideCards(1));
