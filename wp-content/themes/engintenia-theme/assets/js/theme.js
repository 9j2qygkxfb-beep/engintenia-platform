(function () {
  const header = document.querySelector('.site-header');
  const slider = document.querySelector('[data-slider]');

  const setHeaderState = () => {
    if (!header) {
      return;
    }

    header.classList.toggle('is-scrolled', window.scrollY > 18);
  };

  setHeaderState();
  window.addEventListener('scroll', setHeaderState, { passive: true });

  if (!slider) {
    return;
  }

  const slides = Array.from(slider.querySelectorAll('.testimonial-card'));

  if (slides.length < 2) {
    return;
  }

  let activeIndex = 0;

  setInterval(() => {
    slides[activeIndex].classList.remove('is-active');
    activeIndex = (activeIndex + 1) % slides.length;
    slides[activeIndex].classList.add('is-active');
  }, 4500);
})();
