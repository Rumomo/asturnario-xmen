<?php
$pageJs = $pageJs ?? []; // array de JS extra: ['js/personajes.js', ...]
?>
    <!-- BOTÓN VOLVER ARRIBA -->
    <button id="btnScrollTop" class="btn btn-primary">
        <i class="bi bi-arrow-up-circle"></i>
    </button>
    <script src="js/scrollTop.js"></script>
  </main> <!-- /container -->

</div> <!-- /content-wrap -->

<footer class="footer">
  © 2025 Asturnario X-Men | Proyecto educativo con Ruymán y Adrián
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php foreach ($pageJs as $src): ?>
  <script src="<?= htmlspecialchars($src) ?>"></script>
<?php endforeach; ?>
<script src="js/theme.js"></script>
</body>
</html>