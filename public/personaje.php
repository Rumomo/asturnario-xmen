<?php
// -------------------------------------
// Leer ID desde la URL
// -------------------------------------
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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
  http_response_code(500);
  $pageTitle = 'Error | Wiki X‑Men';
  $active    = 'personajes';
  require __DIR__ . '/partials/header.php';
  echo '<div class="alert alert-danger">No se encontró <code>mutantes.json</code>.</div>';
  require __DIR__ . '/partials/footer.php';
  exit;
}

$mutantes = json_decode(file_get_contents($mutantesFile), true) ?? [];

// -------------------------------------
// Helpers
// -------------------------------------
function pretty_poder(string $p): string {
  $p = str_replace('_', ' ', $p);
  $map = [
    'control del clima'      => 'Control del clima',
    'forma de diamante'      => 'Forma de diamante',
    'poder cosmico'          => 'Poder cósmico',
    'absorcion de energia'   => 'Absorción de energía',
    'absorcion de poderes'   => 'Absorción de poderes',
    'armadura psionica'      => 'Armadura psiónica',
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

// -------------------------------------
// Buscar mutante por ID
// -------------------------------------
$mutante = null;
foreach ($mutantes as $m) {
  if ((int)($m['id'] ?? 0) === $id) { $mutante = $m; break; }
}

// -------------------------------------
// Si no existe, 404
// -------------------------------------
if (!$mutante) {
  http_response_code(404);
  $pageTitle = 'Personaje no encontrado | Wiki X‑Men';
  $active    = 'personajes';
  require __DIR__ . '/partials/header.php';
  echo '<div class="alert alert-danger">Personaje no encontrado.</div>';
  echo '<a class="btn btn-secondary mt-2" href="personajes.php">← Volver al listado</a>';
  require __DIR__ . '/partials/footer.php';
  exit;
}

// -------------------------------------
// Preparar datos para la vista
// -------------------------------------
$nombre   = $mutante['nombre'] ?? ucfirst($mutante['slug'] ?? 'Mutante');
$desc     = $mutante['descripcion'] ?? '';
$poderes  = $mutante['poderes'] ?? [];
$imagen   = resolver_imagen($mutante);
$primera  = $mutante['primera_aparicion'] ?? '—';
$afils    = $mutante['afiliaciones'] ?? [];

// -------------------------------------
// Header de la página (con título dinámico)
// -------------------------------------
$pageTitle = htmlspecialchars($nombre) . ' | Wiki X‑Men';
$active    = 'personajes';
$pageCss   = ['css/imagenes.css']; // opcional
require __DIR__ . '/partials/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item"><a href="personajes.php">Ficha de personajes</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($nombre) ?></li>
  </ol>
</nav>

<!-- Ficha del personaje -->
<div class="row g-4">
  <div class="col-md-5">
    <img src="<?= htmlspecialchars($imagen) ?>" class="img-fluid rounded shadow-sm"
         alt="Imagen de <?= htmlspecialchars($nombre) ?>">
  </div>

  <div class="col-md-7">
    <h1 class="h3"><?= htmlspecialchars($nombre) ?></h1>

    <div class="mb-2">
      <?php foreach ($poderes as $p): ?>
        <span class="badge bg-primary me-1 mb-1"><?= htmlspecialchars(pretty_poder($p)) ?></span>
      <?php endforeach; ?>
    </div>

    <p><?= htmlspecialchars($desc) ?></p>

    <dl class="row">
      <dt class="col-sm-4">Primera aparición</dt>
      <dd class="col-sm-8"><?= htmlspecialchars($primera) ?></dd>

      <dt class="col-sm-4">Afiliaciones</dt>
      <dd class="col-sm-8">
        <?= $afils ? htmlspecialchars(implode(', ', $afils)) : '—' ?>
      </dd>
    </dl>

    <a href="personajes.php" class="btn btn-secondary mt-2">← Volver al listado</a>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
