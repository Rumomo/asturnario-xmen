// theme.js – Bootstrap 5.3 color modes con persistencia e iconos
(function () {
  const STORAGE_KEY = 'preferred-theme'; // 'light' | 'dark' | 'auto'
  const html = document.documentElement;
  const btn  = () => document.getElementById('themeToggle');
  const icon = () => document.getElementById('themeIcon');

  // Aplica tema (light/dark) en el atributo data-bs-theme
  function applyTheme(theme) {
    html.setAttribute('data-bs-theme', theme);
    // Icono del botón
    if (icon()) {
      icon().className = (theme === 'dark') ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
  }

  // Detecta preferencia del usuario (si no hay guardado, usa sistema)
  function getInitialTheme() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved === 'light' || saved === 'dark') return saved;
    // preferencia del sistema
    const systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    return systemDark ? 'dark' : 'light';
  }

  // Init
  const initial = getInitialTheme();
  applyTheme(initial);

  // Click del botón: alterna light/dark
  window.addEventListener('DOMContentLoaded', () => {
    if (!btn()) return;
    btn().addEventListener('click', () => {
      const current = html.getAttribute('data-bs-theme') || 'light';
      const next = (current === 'dark') ? 'light' : 'dark';
      localStorage.setItem(STORAGE_KEY, next);
      applyTheme(next);
    });
  });

 
})();
