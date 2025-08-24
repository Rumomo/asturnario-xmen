<?php
$pageTitle = 'X-Men | Wiki X-Men';
$active    = 'xmen';
$pageCss   = ['css/imagenes.css'];
$pageJs    = ['js/personajes.js']; // mantiene tu JS de modal
require __DIR__ . '/partials/header.php';

// Cargar mutantes desde JSON
$posibles = [
  __DIR__ . '/data/mutantes.json',
  __DIR__ . '/../data/mutantes.json',
];
$mutantesFile = null;
foreach ($posibles as $p) { if (is_readable($p)) { $mutantesFile = $p; break; } }
if (!$mutantesFile) {
  echo '<div class="alert alert-danger">No se encontró <code>mutantes.json</code>.</div>';
  require __DIR__ . '/partials/footer.php';
  exit;
}
$mutantes = json_decode(file_get_contents($mutantesFile), true) ?? [];

// Helpers
function pretty_poder(string $p): string {
  $p = str_replace('_', ' ', $p);
  $map = [
    'control del clima'      => 'Control del clima',
    'forma de diamante'      => 'Forma de diamante',
    'poder cosmico'          => 'Poder cósmico',
    'absorcion de energia'   => 'Absorción de energía',
    'absorcion de poderes'   => 'Absorción de poderes',
    'armadura psionica'      => 'Armadura psiónica',
    'grito sonico'           => 'Grito sónico',
  ];
  $base = mb_strtolower($p);
  return $map[$base] ?? ucfirst($base);
}
function resolver_imagen(array $m): string {
  $default = 'images/default.jpg';
  if (!empty($m['imagen']) && file_exists(__DIR__ . '/' . $m['imagen'])) {
    return $m['imagen'];
  }
  $slug = strtolower($m['slug'] ?? '');
  foreach (['jpg','jpeg','png','webp'] as $ext) {
    $path = "images/{$slug}.{$ext}";
    if (file_exists(__DIR__ . '/' . $path)) { return $path; }
  }
  return $default;
}

// Filtrar SOLO miembros de X‑Men
$xmen = array_values(array_filter($mutantes, function ($m) {
  if (empty($m['afiliaciones']) || !is_array($m['afiliaciones'])) return false;
  foreach ($m['afiliaciones'] as $af) {
    $af = mb_strtolower($af);
    if ($af === 'x-men' || $af === 'xmen') return true;
  }
  return false;
}));

// Ordenar alfabéticamente por nombre (servidor)
usort($xmen, fn($a,$b) => strcasecmp($a['nombre'] ?? '', $b['nombre'] ?? ''));

// Poderes disponibles (para el filtro)
$poderesDisponibles = [];
foreach ($xmen as $m) {
  foreach (($m['poderes'] ?? []) as $p) {
    $key = mb_strtolower($p);
    $poderesDisponibles[$key] = pretty_poder($p);
  }
}
asort($poderesDisponibles);
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">X-Men</li>
  </ol>
</nav>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
  <h1 class="h3 mb-0">Miembros de los X‑Men</h1>
  <span class="badge bg-dark"><?= count($xmen) ?> miembros</span>
</div>

<!-- Controles: buscar, filtrar por poder y ordenar -->
<div class="row g-2 mb-4">
  <div class="col-12 col-md-6">
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input id="filtroNombre" type="text" class="form-control" placeholder="Buscar por nombre…">
    </div>
  </div>
  <div class="col-6 col-md-3">
    <select id="filtroPoder" class="form-select">
      <option value="">Todos los poderes</option>
      <?php foreach ($poderesDisponibles as $key => $label): ?>
        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-6 col-md-3">
    <select id="ordenSelect" class="form-select">
      <option value="az" selected>Orden: A → Z</option>
      <option value="za">Orden: Z → A</option>
    </select>
  </div>
</div>

<div id="gridXmen" class="row">
  <?php foreach ($xmen as $m): ?>
    <?php
      $img      = resolver_imagen($m);
      $nombre   = $m['nombre'] ?? ucfirst($m['slug']);
      $desc     = $m['descripcion'] ?? '';
      $poderes  = $m['poderes'] ?? [];
      $poderesTxt = implode(', ', array_map('pretty_poder', $poderes));
      $poderesKey = implode(',', array_map('mb_strtolower', $poderes));
      $id       = (int)($m['id'] ?? 0);
    ?>
    <div class="col-md-4 mb-4 item-personaje"
         data-nombre="<?= htmlspecialchars(mb_strtolower($nombre)) ?>"
         data-poderes="<?= htmlspecialchars($poderesKey) ?>">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="Imagen de <?= htmlspecialchars($nombre) ?>">
        <div class="card-body">
          <h5 class="card-title mb-1"><?= htmlspecialchars($nombre) ?></h5>
          <div class="mb-2">
            <?php foreach ($poderes as $p): ?>
              <span class="badge bg-primary me-1 mb-1"><?= htmlspecialchars(pretty_poder($p)) ?></span>
            <?php endforeach; ?>
          </div>
          <p class="card-text"><?= htmlspecialchars($desc) ?></p>
          <button
            class="btn btn-outline-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalPersonaje"
            data-id="<?= $id ?>"
            data-nombre="<?= htmlspecialchars($nombre) ?>"
            data-poder="<?= htmlspecialchars($poderesTxt) ?>"
            data-desc="<?= htmlspecialchars($desc) ?>"
            data-img="<?= htmlspecialchars($img) ?>">
            Ver detalle
          </button>
          <a class="btn btn-link btn-sm" href="personaje.php?id=<?= $id ?>">Ficha completa »</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal (sin cambios) -->
<div class="modal fade" id="modalPersonaje" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNombre">Nombre del personaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <img id="modalImg" src="" class="img-fluid rounded mb-3" alt="">
        <p class="mb-2"><span id="modalPoder" class="badge bg-primary"></span></p>
        <p id="modalDesc" class="mb-0"></p>
      </div>
      <div class="modal-footer">
        <a id="linkFichaCompleta" href="#" class="btn btn-primary">Ver ficha completa</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- JS de filtrado/orden client-side (puedes moverlo a js/personajes.js si prefieres) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const $grid   = document.getElementById('gridXmen');
  const $cards  = Array.from($grid.querySelectorAll('.item-personaje'));
  const $q      = document.getElementById('filtroNombre');
  const $poder  = document.getElementById('filtroPoder');
  const $orden  = document.getElementById('ordenSelect');

  function aplicarFiltros() {
    const q = ($q.value || '').trim().toLowerCase();
    const p = ($poder.value || '').toLowerCase();

    $cards.forEach(card => {
      const nom = card.getAttribute('data-nombre') || '';
      const pods = (card.getAttribute('data-poderes') || '');
      const coincideNombre = !q || nom.includes(q);
      const coincidePoder  = !p || pods.split(',').includes(p);
      card.style.display = (coincideNombre && coincidePoder) ? '' : 'none';
    });

    aplicarOrden();
  }

  function aplicarOrden() {
    const dir = $orden.value; // 'az' | 'za'
    const visibles = $cards.filter(c => c.style.display !== 'none');

    visibles.sort((a, b) => {
      const an = a.getAttribute('data-nombre') || '';
      const bn = b.getAttribute('data-nombre') || '';
      const cmp = an.localeCompare(bn, 'es', { sensitivity: 'base' });
      return dir === 'az' ? cmp : -cmp;
    });

    // reordenar en el DOM
    visibles.forEach(el => $grid.appendChild(el));
  }

  $q.addEventListener('input', aplicarFiltros);
  $poder.addEventListener('change', aplicarFiltros);
  $orden.addEventListener('change', aplicarOrden);

  // inicial
  aplicarFiltros();
});
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
