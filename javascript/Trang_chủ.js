document.getElementById('cart').addEventListener('click', function() {
    window.location.href = 'Giỏ_hàng.php';
});


//Slider
let currentIndex = 0;
  const slides = document.querySelectorAll('.slide');

  function showSlide(index) {
      slides.forEach((slide, i) => {
          slide.classList.remove('active', 'next', 'prev');
          if (i === index) {
              slide.classList.add('active');
          } else if (i === (index + 1) % slides.length) {
              slide.classList.add('next');
          } else if (i === (index - 1 + slides.length) % slides.length) {
              slide.classList.add('prev');
          }
      });
  }

  function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
  }

  setInterval(nextSlide, 5000);
  showSlide(currentIndex);
