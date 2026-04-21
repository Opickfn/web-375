document.addEventListener('DOMContentLoaded', function() {
  const container = document.querySelector('.slider-container');
  if (!container) return;

  const cards = container.querySelectorAll('.slider-card');
  const prevBtn = container.querySelector('[data-action=\"prev\"]');
  const nextBtn = container.querySelector('[data-action=\"next\"]');
  const totalEl = container.querySelector('.slider-total');
  const indexEl = container.querySelector('.slider-index');

  if (cards.length <= 1) {
    prevBtn.style.display = 'none';
    nextBtn.style.display = 'none';
    return;
  }

  let currentIndex = 0;

  function showSlide(index) {
    cards.forEach((card, i) => {
      card.classList.toggle('hidden', i !== index);
    });
    indexEl.textContent = index + 1;
  }

  prevBtn.addEventListener('click', () => {
    currentIndex = currentIndex > 0 ? currentIndex - 1 : cards.length - 1;
    showSlide(currentIndex);
  });

  nextBtn.addEventListener('click', () => {
    currentIndex = currentIndex < cards.length - 1 ? currentIndex + 1 : 0;
    showSlide(currentIndex);
  });

  // Auto slide every 5s
  setInterval(() => {
    currentIndex = (currentIndex + 1) % cards.length;
    showSlide(currentIndex);
  }, 5000);

  // Update total
  if (totalEl) totalEl.textContent = cards.length;
});

