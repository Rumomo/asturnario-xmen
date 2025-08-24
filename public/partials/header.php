<?php
// Valores por defecto (puedes sobreescribirlos antes del require)
$pageTitle = $pageTitle ?? 'Wiki X-Men';
$pageCss   = $pageCss   ?? [];          // array de CSS extra: ['css/imagenes.css', ...]
$active    = $active    ?? 'inicio';    // para resaltar item del menú: inicio|personajes|xmen|hermandad
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS propios -->
  <link rel="stylesheet" href="css/global.css">
  <?php foreach ($pageCss as $href): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
  <?php endforeach; ?>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="content-wrap">
  <!-- NAV -->
  <nav class="navbar navbar-expand bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Wiki X-Men</a>

      <!-- Sin burger, siempre expandido -->
      <div class="navbar-collapse d-flex justify-content-between align-items-center flex-wrap">
        <ul class="navbar-nav me-auto mb-2 flex-wrap small">
          <li class="nav-item">
            <a class="nav-link <?= $active === 'inicio' ? 'active' : '' ?>" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $active === 'personajes' ? 'active' : '' ?>" href="personajes.php">Ficha de personajes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $active === 'xmen' ? 'active' : '' ?>" href="xmen.php">X-men</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $active === 'hermandad' ? 'active' : '' ?>" href="#">Hermandad de mutantes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active === 'poderes' ? 'active' : '' ?>" href="poderes.php">Poderes</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">Más</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Contacto</a></li>
              <li><a class="dropdown-item" href="#">Créditos</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Ayuda</a></li>
            </ul>
          </li>
        </ul>

        <div class="d-flex align-items-center flex-wrap gap-2">
          <form class="d-flex me-2" role="search">
            <input class="form-control me-2" type="search" placeholder="Buscar mutante" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Buscar</button>
          </form>
          <button id="themeToggle" class="btn btn-outline-secondary btn-icon" type="button" aria-label="Cambiar tema">
            <i id="themeIcon" class="bi bi-moon-fill"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Contenido principal de cada página -->
  <main class="container py-4">
