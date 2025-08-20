<?php
require_once __DIR__ . '/../src/Xmen/Domain/Mutantes.php';
require_once __DIR__ . '/../src/Xmen/Domain/Poder.php';

/* Datos de prueba */
$mutantes = [
    new Mutante(1, "jeangrey",    Poder::Telepatia,     "Mutante con habilidades psíquicas avanzadas."),
    new Mutante(2, "Nightcrawler",Poder::Invisibilidad, "Puede teletransportarse a voluntad."),
    new Mutante(3, "Storm",       Poder::Volar,         "Controla el clima y puede volar."),
    new Mutante(4, "Colossus",    Poder::Fuerza,        "Fuerza sobrehumana gracias a su cuerpo metálico."),
];

/* Config para partials */
$pageTitle = 'Ficha de personajes | Wiki X-Men';
$active    = 'personajes';
$pageCss   = ['css/imagenes.css'];               // si te ayuda para tamaño de imágenes
$pageJs    = ['js/personajes.js'];               // 👈 nuestro JS para el modal

require __DIR__ . '/partials/header.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ficha de personajes</li>
  </ol>
</nav>

<div class="row">
  <?php foreach ($mutantes as $mutante): ?>
    <?php
      // Resolver imagen por nombre
      $nombre      = strtolower(str_replace(' ', '', $mutante->getNombre()));
      $extensiones = ['jpg','jpeg','png','webp'];
      $imagen      = 'images/default.jpg';
      foreach ($extensiones as $ext) {
        if (file_exists(__DIR__ . "/images/{$nombre}.{$ext}")) {
          $imagen = "images/{$nombre}.{$ext}";
          break;
        }
      }
    ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($imagen) ?>" class="card-img-top" alt="Imagen de <?= htmlspecialchars($mutante->getNombre()) ?>">
        <div class="card-body">
          <h5 class="card-title mb-1"><?= htmlspecialchars(ucfirst($mutante->getNombre())) ?></h5>
          <span class="badge bg-primary mb-2"><?= htmlspecialchars($mutante->getPoder()->value) ?></span>
          <p class="card-text"><?= htmlspecialchars($mutante->getDescripcion()) ?></p>

          <!-- Botón que abre el modal y pasa datos -->
          <button
            class="btn btn-outline-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalPersonaje"
            data-id="<?= $mutante->getId() ?>"
            data-nombre="<?= htmlspecialchars(ucfirst($mutante->getNombre())) ?>"
            data-poder="<?= htmlspecialchars($mutante->getPoder()->value) ?>"
            data-desc="<?= htmlspecialchars($mutante->getDescripcion()) ?>"
            data-img="<?= htmlspecialchars($imagen) ?>">
            Ver detalle
          </button>
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
