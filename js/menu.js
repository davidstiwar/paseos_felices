document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger");
    const navLinks = document.querySelector(".nav-links");
  
    // Al hacer clic en el botón de hamburguesa, mostramos/ocultamos el menú
    hamburger.addEventListener("click", (event) => {
      navLinks.classList.toggle("active");
      hamburger.classList.toggle("open");
      // Prevenir que el clic en el botón cierre el menú inmediatamente
      event.stopPropagation();
    });
  
    // Al hacer clic en cualquier parte de la página (excepto el menú y el botón), cerramos el menú
    document.addEventListener("click", (event) => {
      if (!navLinks.contains(event.target) && !hamburger.contains(event.target)) {
        navLinks.classList.remove("active");
        hamburger.classList.remove("open");
      }
    });
  });
  