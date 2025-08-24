<?php
// ===============================
// Configuración de la página
// ===============================
$pageTitle = 'Wiki X-Men | Inicio';
$active    = 'inicio';
$pageCss   = ['css/imagenes.css'];

require __DIR__ . '/partials/header.php';


// ===============================
// Cargar mutantes desde JSON
// ===============================
$mutantesFile = __DIR__ . '/data/mutantes.json';
if (!file_exists($mutantesFile)) {
    die("No se encontró el archivo de mutantes en: $mutantesFile");
}
$mutantesData = json_decode(file_get_contents($mutantesFile), true);

// Indexar por "slug" derivado de la imagen o del nombre
$bySlug = [];
foreach ($mutantesData as $m) {
    // 1) si el JSON trae "imagen", sacamos el nombre de archivo (sin extensión)
    if (!empty($m['imagen'])) {
        $slug = strtolower(pathinfo($m['imagen'], PATHINFO_FILENAME));
    } else {
        // 2) si no hay imagen, usamos el nombre en minúsculas sin espacios (fallback)
        $slug = strtolower(trim($m['nombre'] ?? ''));
        $slug = str_replace(' ', '', $slug);
    }
    if ($slug) $bySlug[$slug] = $m;
}

// Lista de destacados (slugs/archivos que quieres en el carrusel, en orden)
$destacados = array_keys($bySlug);

// Helpers
$defaultImg = 'images/default.jpg';
function pretty_poder(string $p): string {
    // Limpia claves tipo "control_del_clima" -> "control del clima"
    $p = str_replace(['_', 'Psionica', 'Diamante', 'Cosmico'], [' ', 'psiónica', 'diamante', 'cósmico'], $p);
    // minúsculas “bonitas” (puedes ajustar acentos finos en tu enum si prefieres)
    return mb_strtolower($p);
}

// Construir slides
$slides = [];
foreach ($destacados as $slug) {
    if (!isset($bySlug[$slug])) continue;
    $m = $bySlug[$slug];

    // Resolver imagen existente
    $img = $m['imagen'] ?? null;
    if ($img && !file_exists(__DIR__ . '/' . $img)) {
        // si la ruta del JSON no existe, intentamos con extensiones comunes
        $try = ['jpg','jpeg','png','webp'];
        $img = null;
        foreach ($try as $ext) {
            $path = "images/{$slug}.{$ext}";
            if (file_exists(__DIR__ . '/' . $path)) { $img = $path; break; }
        }
    }
    if (!$img) $img = $defaultImg;

    // Texto de poderes
    $poderes = $m['poderes'] ?? [];
    $text = $poderes ? implode(', ', array_map('pretty_poder', $poderes)) : ($m['descripcion'] ?? '');

    $slides[] = [
        'src'   => $img,
        'title' => $m['nombre'] ?? ucfirst($slug),
        'text'  => $text ?: '—'
    ];
}
?>

<h1>Bienvenido a la wiqui del mundo de X‑Men</h1>
<p>Descubre a los mutantes y sus habilidades.</p>

<?php if ($slides): ?>
<div id="carruselMutantes" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($slides as $i => $s): ?>
            <button type="button" data-bs-target="#carruselMutantes" data-bs-slide-to="<?= $i ?>"
                    class="<?= $i===0 ? 'active' : '' ?>"
                    aria-current="<?= $i===0 ? 'true' : 'false' ?>"
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
<?php else: ?>
<div class="alert alert-warning my-3">No hay personajes destacados disponibles para el carrusel.</div>
<?php endif; ?>

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
        <a class="text-decoration-none" href="poderes.php">
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
                    <p class="card-text">X-Men, Hermandad, X-Force… organiza el universo.</p>
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

<?php require __DIR__ . '/partials/footer.php'; ?>
