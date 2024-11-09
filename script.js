window.addEventListener('scroll', function() {
    document.querySelector('.header').style.opacity = Math.max(1 - window.scrollY / 300, 0.8);
  });
  