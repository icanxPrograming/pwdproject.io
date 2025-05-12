let currentIndex = 0;
let isTransitioning = false;
const slides = document.querySelectorAll(".slides img");
const totalSlides = slides.length;
const slideDuration = 500;

function updateSlider() {
  if (isTransitioning) return;

  isTransitioning = true;
  const offset = -currentIndex * 100;
  document.querySelector(".slides").style.transform = `translateX(${offset}%)`;

  setTimeout(() => {
    isTransitioning = false;
  }, slideDuration);
}

document.querySelector(".next").addEventListener("click", () => {
  if (isTransitioning) return;
  currentIndex = (currentIndex + 1) % totalSlides;
  updateSlider();
});

document.querySelector(".prev").addEventListener("click", () => {
  if (isTransitioning) return;
  currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
  updateSlider();
});

let slideInterval = setInterval(() => {
  if (!isTransitioning) {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlider();
  }
}, 5000);

document
  .querySelector(".slider-container")
  .addEventListener("mouseenter", () => {
    clearInterval(slideInterval);
  });

document
  .querySelector(".slider-container")
  .addEventListener("mouseleave", () => {
    slideInterval = setInterval(() => {
      if (!isTransitioning) {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlider();
      }
    }, 5000);
  });

// Slider Main Content
const cardGroup = document.getElementById("cardGroup");
const cardPrevBtn = document.getElementById("cardPrevBtn");
const cardNextBtn = document.getElementById("cardNextBtn");

const cardWidth = 200;
const cardsPerPage = 3;
let currentPosition = 0;

function slideCards(direction) {
  const totalCards = cardGroup.children.length;
  const maxPosition = Math.ceil(totalCards / cardsPerPage) - 1;

  currentPosition += direction;

  if (currentPosition > maxPosition) currentPosition = 0;
  if (currentPosition < 0) currentPosition = maxPosition;

  cardGroup.style.transform = `translateX(-${
    currentPosition * cardsPerPage * cardWidth
  }px)`;
}

cardPrevBtn.addEventListener("click", () => slideCards(-1));
cardNextBtn.addEventListener("click", () => slideCards(1));

document.addEventListener("DOMContentLoaded", function () {
  const locationDropdown = document.getElementById("location-dropdown");
  const searchInput = document.getElementById("search-input");

  updatePlaceholder();

  locationDropdown.addEventListener("change", updatePlaceholder);

  function updatePlaceholder() {
    const selectedLocation =
      locationDropdown.options[locationDropdown.selectedIndex].text;
    searchInput.placeholder = `Temukan Mobil di ${selectedLocation}...`;
  }
});
