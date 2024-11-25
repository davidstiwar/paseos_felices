document.addEventListener("DOMContentLoaded", () => {
    const mision = document.querySelector('.mision');
    const vision = document.querySelector('.vision');
    const porque_elegirnos = document.querySelector('.porque_elegirnos');
  
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show'); // Aplica la clase 'show' cuando la sección entra en vista
        }
      });
    }, {
      threshold: 0.5 // El 50% de la sección debe ser visible para activarse
    });
  
    observer.observe(mision); // Empieza a observar la sección de misión
    observer.observe(vision); // Empieza a observar la sección de visión
    observer.observe(porque_elegirnos);
  });
  