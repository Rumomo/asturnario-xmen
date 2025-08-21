<?php
// -------------------------------------
// Config de página + header
// -------------------------------------
$pageTitle = 'Ficha de personajes | Wiki X‑Men';
$active    = 'personajes';
$pageCss   = ['css/imagenes.css'];     // si tienes estilos de imágenes
$pageJs    = ['js/personajes.js'];     // tu JS del modal (opcional)
require __DIR__ . '/partials/header.php';

// -------------------------------------
// Cargar mutantes desde JSON
// (ajusta la ruta si lo tienes fuera de /public)
// -------------------------------------
$posibles = [
  __DIR__ . '/data/mutantes.json',    // dentro de /public
  __DIR__ . '/data/mutantes.json', // fuera de /public
];
$mutantesFile = null;
foreach ($posibles as $p) { if (is_readable($p)) { $mutantesFile = $p; break; } }
if (!$mutantesFile) {
  echo '<div class="alert alert-danger">No se encontró <code>mutantes.json</code>.</div>';
  require __DIR__ . '/partials/footer.php';
  exit;
}
$mutantes = json_decode(file_get_contents($mutantesFile), true) ?? [];

// helper para “prettificar” los poderes (snake_case → texto)
function pretty_poder(string $p): string {
  $p = str_replace('_', ' ', $p);
  // normaliza algunos comunes
  $map = [
    'control del clima' => 'Control del clima',
    'forma de diamante' => 'Forma de diamante',
    'poder cosmico'     => 'Poder cósmico',
    'absorcion de energia' => 'Absorción de energía',
    'absorcion de poderes' => 'Absorción de poderes',
    'armadura psionica' => 'Armadura psiónica',
  ];
  $base = mb_strtolower($p);
  return $map[$base] ?? ucfirst($base);
}

// resuelve imagen existente (usa la del JSON o intenta por slug)
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
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ficha de personajes</li>
  </ol>
</nav>

<div class="row">
  <?php foreach ($mutantes as $m): ?>
    <?php
      $img      = resolver_imagen($m);
      $nombre   = $m['nombre'] ?? ucfirst($m['slug']);
      $desc     = $m['descripcion'] ?? '';
      $poderes  = $m['poderes'] ?? [];
      $poderesTxt = implode(', ', array_map('pretty_poder', $poderes));
      $id       = (int)($m['id'] ?? 0);
    ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="Imagen de <?= htmlspecialchars($nombre) ?>">
        <div class="card-body">
          <h5 class="card-title mb-1"><?= htmlspecialchars($nombre) ?></h5>

          <!-- badges de poderes -->
          <div class="mb-2">
            <?php foreach ($poderes as $p): ?>
              <span class="badge bg-primary me-1 mb-1"><?= htmlspecialchars(pretty_poder($p)) ?></span>
            <?php endforeach; ?>
          </div>

          <p class="card-text"><?= htmlspecialchars($desc) ?></p>

          <!-- Botón que abre el modal con data-* -->
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

          <!-- Link a ficha completa -->
          <a class="btn btn-link btn-sm" href="personaje.php?id=<?= $id ?>">Ficha completa »</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal reutilizable -->
<div class="modal fade" id="modalPersonaje" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNombre">Nombre del personaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <img id="modalImg" src="" class="img-fluid rounded mb-3" alt="">
        <!-- si prefieres badges en vez de texto, cambia a un contenedor y píntalos en JS -->
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

<?php require __DIR__ . '/partials/footer.php'; ?>
