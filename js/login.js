document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".contenido-tab > div");

    // Cambiar entre pestañas
    tabs.forEach((tab, index) => {
        tab.addEventListener("click", (e) => {
            e.preventDefault();

            // Activar la pestaña seleccionada
            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            // Mostrar el contenido correspondiente
            contents.forEach(content => content.style.display = "none");
            contents[index].style.display = "block";
        });
    });

    // Mostrar inicialmente solo el primer contenido
    contents.forEach((content, index) => {
        content.style.display = index === 0 ? "block" : "none";
    });
});
