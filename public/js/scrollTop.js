// Mostrar u ocultar el botón según el scroll
window.addEventListener("scroll", () => {
  const btn = document.getElementById("btnScrollTop");
  if (document.documentElement.scrollTop > 200) {
    btn.style.display = "block";
  } else {
    btn.style.display = "none";
  }
});

// Volver arriba con scroll suave
document.getElementById("btnScrollTop").addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});