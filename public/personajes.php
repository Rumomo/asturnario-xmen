<?php
require_once __DIR__ . '/../src/Xmen/Domain/Mutantes.php';
require_once __DIR__ . '/../src/Xmen/Domain/Poder.php';

$mutantes = [
    new Mutante(1, "jeangrey", Poder::Telepatia, "Mutante con habilidades psíquicas avanzadas."),
    new Mutante(2, "Nightcrawler", Poder::Invisibilidad, "Puede teletransportarse a voluntad."),
    new Mutante(3, "Storm", Poder::Volar, "Controla el clima y puede volar."),
    new Mutante(4, "Colossus", Poder::Fuerza, "Fuerza sobrehumana gracias a su cuerpo metálico."),
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>X-Men Cards</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/imagenes.css">
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 JS (para el menú desplegable) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- NAV -->
<div class="content-wrap">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Wiki X-Men</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
            </li>

            <li class="nav-item">
            <a class="nav-link" href="personajes.php">Ficha de personajes</a>
            </li>

            <li class="nav-item">
            <a class="nav-link" href="#">X-men</a>
            </li>

            <li class="nav-item">
            <a class="nav-link" href="#">Hermandad de mutantes</a>
            </li>

            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Más
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Contacto</a></li>
                <li><a class="dropdown-item" href="#">Créditos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Ayuda</a></li>
            </ul>
            </li>
        </ul>

        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Buscar mutante" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>
        </div>
    </div>
    </nav>
    
    <main>
        <h1>Bienvenido a la wiqui del mundo de X-Men</h1>
        <p>Descubre a los mutantes y sus habilidades.</p>
        <div class="container mt-4">
            <div class="row">
                <?php foreach ($mutantes as $mutante): ?>
                <?php
                $nombre = strtolower(str_replace(' ', '', $mutante->getNombre()));
                $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
                $imagen = 'images/default.jpg';

                foreach ($extensiones as $ext) {
                    if (file_exists(__DIR__ . "/images/{$nombre}.{$ext}")) {
                        $imagen = "images/{$nombre}.{$ext}";
                        break;
                    }
                }
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                    <img src="<?= $imagen ?>" class="card-img-top" alt="Imagen de <?= $mutante->getNombre() ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= ucfirst($mutante->getNombre()) ?></h5>
                        <p class="card-text"><?= $mutante->getDescripcion() ?></p>
                        <span class="badge bg-primary"><?= $mutante->getPoder()->value ?></span>
                    </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        
    </main>
</div>
<!-- BODY -->


<!-- FOOTER -->
<footer class="footer">
  © 2025 Asturnario X-Men | Proyecto educativo con Rumom y Adrián
</footer>

</body>
</html>