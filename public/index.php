<?php
// Configuración para esta página
$pageTitle = 'Wiki X‑Men | Inicio';
$active    = 'inicio';
$pageCss   = ['css/imagenes.css'];

require __DIR__ . '/partials/header.php';
?>

<h1>Bienvenido a la wiqui del mundo de X‑Men</h1>
<p>Descubre a los mutantes y sus habilidades.</p>

<?php
$slides = [
  ['src' => 'images/jeangrey.jpg',     'title' => 'Jean Grey',    'text' => 'Telepatía'],
  ['src' => 'images/nightcrawler.jpg', 'title' => 'Nightcrawler', 'text' => 'Teletransportación'],
  ['src' => 'images/storm.jpg',        'title' => 'Storm',        'text' => 'Control del clima'],
  ['src' => 'images/colossus.webp',    'title' => 'Colossus',     'text' => 'Superfuerza'],
];
// Fallback por si falta alguna imagen
$default = 'images/default.jpg';
foreach ($slides as &$s) {
  $rutaReal = __DIR__ . '/' . $s['src'];
  if (!file_exists($rutaReal)) $s['src'] = $default;
}
unset($s);
?>

<div id="carruselMutantes" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($slides as $i => $s): ?>
            <button type="button" data-bs-target="#carruselMutantes" data-bs-slide-to="<?= $i ?>"
                    class="<?= $i===0 ? 'active' : '' ?>" aria-current="<?= $i===0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $i+1 ?>"></button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner">
        <?php foreach ($slides as $i => $s): ?>
            <div class="carousel-item <?= $i===0 ? 'active' : '' ?>" data-bs-interval="4000">
                <img src="<?= htmlspecialchars($s['src']) ?>" class="d-block w-100 carrusel-img"
                    alt="<?= htmlspecialchars($s['title']) ?>">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?= htmlspecialchars($s['title']) ?></h5>
                    <p><?= htmlspecialchars($s['text']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carruselMutantes" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carruselMutantes" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>
<h2 class="mt-5 mb-3">Destacados</h2>
<div class="row g-4">
    <div class="col-md-4">
        <a class="text-decoration-none" href="personajes.php">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                <h5 class="card-title">Personajes</h5>
                <p class="card-text">Explora fichas con poderes, equipo y biografía.</p>
                <span class="badge bg-primary">Ver fichas</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="text-decoration-none" href="#">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                <h5 class="card-title">Poderes</h5>
                <p class="card-text">Lista de poderes y personajes que los poseen.</p>
                <span class="badge bg-info text-dark">Explorar</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a class="text-decoration-none" href="#">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                <h5 class="card-title">Equipos</h5>
                <p class="card-text">X‑Men, Hermandad, X‑Force… organiza el universo.</p>
                <span class="badge bg-success">Ver equipos</span>
                </div>
            </div>
        </a>
    </div>
</div>

<section class="cta-section mt-5 p-4 rounded-3 bg-light border">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
        <h3 class="h4 mb-1">¿Quieres contribuir a la wiki?</h3>
        <p class="mb-0">Súmate al repo y propone mejoras con Pull Requests.</p>
        </div>
        <a class="btn btn-dark" href="https://github.com/Rumomo/asturnario-xmen" target="_blank" rel="noopener">
        Ver repositorio en GitHub
        </a>
    </div>
</section>

<!-- BOTÓN VOLVER ARRIBA -->
<button id="btnScrollTop" class="btn btn-primary">
    <i class="bi bi-arrow-up-circle"></i>
</button>
<script src="js/scrollTop.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
