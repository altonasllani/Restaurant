window.addEventListener('scroll', function() {
  document.querySelector('.header').style.opacity = Math.max(1 - window.scrollY / 300, 0.8);
});


  const toggleMenu = document.getElementById('toggle-menu');
    const closeMenu = document.getElementById('close-menu');
    const Nav = document.getElementById('nav');

  toggleMenu.addEventListener('click', () => {
      Nav.classList.add('active');
  });

  closeMenu.addEventListener('click', () => {
      Nav.classList.remove('active');
}); 