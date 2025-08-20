// public/js/personajes.js
document.addEventListener("DOMContentLoaded", () => {
  const modalEl = document.getElementById("modalPersonaje");
  if (!modalEl) return;

  modalEl.addEventListener("show.bs.modal", (event) => {
    const button = event.relatedTarget; // el botón que abrió el modal
    if (!button) return;

    // Leer data-* del botón
    const id     = button.getAttribute("data-id");
    const nombre = button.getAttribute("data-nombre");
    const poder  = button.getAttribute("data-poder");
    const desc   = button.getAttribute("data-desc");
    const img    = button.getAttribute("data-img");

    // Poner datos en el modal
    document.getElementById("modalNombre").textContent = nombre;
    document.getElementById("modalPoder").textContent  = poder;
    document.getElementById("modalDesc").textContent   = desc;

    const imgEl = document.getElementById("modalImg");
    imgEl.src = img;
    imgEl.alt = `Imagen de ${nombre}`;

    // Link a la ficha completa con ID
    const link = document.getElementById("linkFichaCompleta");
    link.href = `personaje.php?id=${encodeURIComponent(id)}`;
  });
});
