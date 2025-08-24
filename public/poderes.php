<?php
// -------------------------------------
// Config de página
// -------------------------------------
$pageTitle = 'Poderes | Wiki X‑Men';
$active    = 'poderes';
$pageCss   = ['css/imagenes.css'];   // opcional si quieres estilos propios
require __DIR__ . '/partials/header.php';

// -------------------------------------
// Cargar mutantes desde JSON
// (prueba dos ubicaciones: dentro/fuera de /public)
// -------------------------------------
$posibles = [
  __DIR__ . '/data/mutantes.json',    // dentro de /public
  __DIR__ . '/../data/mutantes.json', // fuera de /public
];
$mutantesFile = null;
foreach ($posibles as $p) { if (is_readable($p)) { $mutantesFile = $p; break; } }

if (!$mutantesFile) {
  echo '<div class="alert alert-danger">No se encontró <code>mutantes.json</code>.</div>';
  require __DIR__ . '/partials/footer.php';
  exit;
}

$mutantes = json_decode(file_get_contents($mutantesFile), true) ?? [];

// -------------------------------------
// Helpers
// -------------------------------------
/** Convierte "snake_case" u otras variantes a etiqueta legible */
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

/** Normaliza clave de poder para agrupar (minúsculas + sin guiones/barras) */
function key_poder(string $p): string {
  $p = mb_strtolower($p);
  $p = str_replace(['-', '/'], '_', $p);
  return preg_replace('/\s+/', '_', str_replace('_', ' ', $p));
}

// -------------------------------------
// Construir índice de poderes: poder => lista de mutantes
// -------------------------------------
$poderesIndex = []; // key => ['label'=>string, 'items'=> [ ['id'=>int,'nombre'=>string] ... ]]

foreach ($mutantes as $m) {
  $id      = (int)($m['id'] ?? 0);
  $nombre  = $m['nombre'] ?? ucfirst($m['slug'] ?? 'Mutante');
  $poderes = $m['poderes'] ?? [];

  foreach ($poderes as $p) {
    $k = key_poder($p);
    if (!isset($poderesIndex[$k])) {
      $poderesIndex[$k] = ['label' => pretty_poder($p), 'items' => []];
    }
    $poderesIndex[$k]['items'][] = ['id' => $id, 'nombre' => $nombre];
  }
}

// Ordenar poderes por etiqueta
uasort($poderesIndex, fn($a, $b) => strcasecmp($a['label'], $b['label']));
// Ordenar cada lista de mutantes por nombre
foreach ($poderesIndex as &$grupo) {
  usort($grupo['items'], fn($a, $b) => strcasecmp($a['nombre'], $b['nombre']));
}
unset($grupo);

// Conteo total
$totalPoderes   = count($poderesIndex);
$totalMutantes  = count($mutantes);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page">Poderes</li>
  </ol>
</nav>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Poderes</h1>
  <div>
    <span class="badge bg-secondary me-1"><?= $totalPoderes ?> poderes</span>
    <span class="badge bg-dark"><?= $totalMutantes ?> mutantes</span>
  </div>
</div>

<!-- Buscador simple en cliente -->
<div class="input-group mb-4">
  <span class="input-group-text" id="labelSearch"><i class="bi bi-search"></i></span>
  <input id="filtroPoder" type="text" class="form-control" placeholder="Filtrar poderes..."
         aria-label="Filtrar poderes" aria-describedby="labelSearch">
</div>

<div class="accordion" id="accordionPoderes">
  <?php if (!$poderesIndex): ?>
    <div class="alert alert-warning">No se han encontrado poderes en los datos.</div>
  <?php else: ?>
    <?php $i = 0; foreach ($poderesIndex as $key => $grupo): $i++; ?>
      <?php
        $headingId = "heading{$i}";
        $collapseId = "collapse{$i}";
        $count = count($grupo['items']);
      ?>
      <div class="accordion-item poder-item" data-poder-label="<?= htmlspecialchars(mb_strtolower($grupo['label'])) ?>">
        <h2 class="accordion-header" id="<?= $headingId ?>">
          <button class="accordion-button collapsed" type="button"
                  data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>"
                  aria-expanded="false" aria-controls="<?= $collapseId ?>">
            <?= htmlspecialchars($grupo['label']) ?>
            <span class="badge bg-primary ms-2"><?= $count ?></span>
          </button>
        </h2>
        <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $headingId ?>"
             data-bs-parent="#accordionPoderes">
          <div class="accordion-body">
            <ul class="list-group list-group-flush">
              <?php foreach ($grupo['items'] as $it): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <a href="personaje.php?id=<?= (int)$it['id'] ?>" class="text-decoration-none">
                    <?= htmlspecialchars($it['nombre']) ?>
                  </a>
                  <a class="btn btn-sm btn-outline-secondary" href="personaje.php?id=<?= (int)$it['id'] ?>">
                    Ver ficha
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<script>
// Filtro rápido en cliente por nombre del poder
document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('filtroPoder');
  if (!input) return;
  input.addEventListener('input', function () {
    var term = (this.value || '').trim().toLowerCase();
    document.querySelectorAll('.poder-item').forEach(function (item) {
      var label = item.getAttribute('data-poder-label') || '';
      item.style.display = label.indexOf(term) !== -1 ? '' : 'none';
    });
  });
});
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
