<?php
require_once __DIR__ . '/../src/Xmen/Domain/Mutantes.php';
require_once __DIR__ . '/../src/Xmen/Domain/Poder.php';

/**
 * Dataset temporal (quítalo cuando cargues desde BD/JSON)
 * Debe coincidir con el listado de personajes.php
 */
$mutantes = [
    new Mutante(1, "jeangrey",     Poder::Telepatia,     "Mutante con habilidades psíquicas avanzadas."),
    new Mutante(2, "Nightcrawler", Poder::Invisibilidad, "Puede teletransportarse a voluntad."),
    new Mutante(3, "Storm",        Poder::Volar,         "Controla el clima y puede volar."),
    new Mutante(4, "Colossus",     Poder::Fuerza,        "Fuerza sobrehumana gracias a su cuerpo metálico."),
];

/**Leer id de la URL */
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

/**Buscar por id */
$mutante = null;
if ($id !== null) {
    foreach ($mutantes as $m) {
        if ($m->getId() === $id) {
            $mutante = $m;
            break;
        }
    }
}

/**Si no existe, 404 básico */
if (!$mutante) {
    http_response_code(404);
    $pageTitle = 'Personaje no encontrado | Wiki X‑Men';
    $active    = 'personajes';
    require __DIR__ . '/partials/header.php';
    echo '<div class="alert alert-danger">Personaje no encontrado.</div>';
    echo '<a href="personajes.php" class="btn btn-secondary mt-2">← Volver al listado</a>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

/**Resolver imagen por nombre (con fallback) */
$nombreSlug  = strtolower(str_replace(' ', '', $mutante->getNombre()));
$extensiones = ['jpg','jpeg','png','webp'];
$imagen      = 'images/default.jpg';
foreach ($extensiones as $ext) {
    if (file_exists(__DIR__ . "/images/{$nombreSlug}.{$ext}")) {
        $imagen = "images/{$nombreSlug}.{$ext}";
        break;
    }
}

/**Config de página y header */
$pageTitle = htmlspecialchars(ucfirst($mutante->getNombre())) . ' | Wiki X-Men';
$active    = 'personajes';
$pageCss   = ['css/imagenes.css']; // opcional
require __DIR__ . '/partials/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item"><a href="personajes.php">Ficha de personajes</a></li>
    <li class="breadcrumb-item active" aria-current="page">
      <?= htmlspecialchars(ucfirst($mutante->getNombre())) ?>
    </li>
  </ol>
</nav>

<!-- Ficha del personaje -->
<div class="row g-4">
  <div class="col-md-5">
    <img src="<?= htmlspecialchars($imagen) ?>" class="img-fluid rounded shadow-sm"
         alt="Imagen de <?= htmlspecialchars($mutante->getNombre()) ?>">
    </div>
    <div class="col-md-7">
      <h1 class="h3"><?= htmlspecialchars(ucfirst($mutante->getNombre())) ?></h1>
      <p class="mb-2">
        <span class="badge bg-primary"><?= htmlspecialchars($mutante->getPoder()->value) ?></span>
      </p>
      <p><?= htmlspecialchars($mutante->getDescripcion()) ?></p>

      <!-- Campos ampliables -->
      <dl class="row">
        <dt class="col-sm-4">Primera aparición</dt>
        <dd class="col-sm-8">—</dd>
        <dt class="col-sm-4">Afiliaciones</dt>
        <dd class="col-sm-8">—</dd>
      </dl>

      <a href="personajes.php" class="btn btn-secondary mt-2">← Volver al listado</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
