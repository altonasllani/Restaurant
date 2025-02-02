// Funksioni për tejdukshmërinë e header-it gjatë scroll-it
window.addEventListener('scroll', function () {

  // Kontrollon nëse menuja është aktive
  const isMenuActive = document.body.classList.contains('no-scroll');
  if (!isMenuActive) {
    const opacity = Math.max(1 - window.scrollY / 300, 0.8);
    document.querySelector('.header').style.opacity = opacity;
  } else {
    document.querySelector('.header').style.opacity = '1'; // Ruaj tejdukshmërinë e plotë kur menuja është aktive
  }
});

// Selektimet për menunë
const toggleMenu = document.getElementById('toggle-menu');
const closeMenu = document.getElementById('close-menu');
const Nav = document.getElementById('nav');

// Hapja e menysë
toggleMenu.addEventListener('click', () => {
  Nav.classList.remove('removed');
  Nav.classList.add('active');
  document.body.classList.add('no-scroll'); // Shton klasën për ndalimin e scroll-it
  document.querySelector('.header').style.opacity = '1'; // Sigurohet që header-i të jetë i dukshëm kur menuja hapet
});

// Mbyllja e menysë
closeMenu.addEventListener('click', () => {
  Nav.classList.add('removed');
  Nav.classList.remove('active');
  document.body.classList.remove('no-scroll'); // Heq klasën për ndalimin e scroll-it
});


// slider //
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let slideInterval;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
        dots[i].classList.toggle('active', i === index);
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

// Auto-play slider function with a 10000ms interval
function startAutoPlay() {
    slideInterval = setInterval(nextSlide, 10000);
}

// Add click event to dots
dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        currentSlide = i;
        showSlide(currentSlide);
        
        // Clear previous interval and restart auto-play after 10000ms
        clearInterval(slideInterval);
        startAutoPlay();
    });
});

// Start the auto-play when the page loads
startAutoPlay();

