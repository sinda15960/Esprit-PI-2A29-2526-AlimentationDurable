</main>
<footer class="text-center py-3 mt-4">
  <div class="container">
    <div class="row">
      <div class="col-md-6 text-md-start">
        <p class="mb-0">© 2025 Frigo Intelligent — Projet Faculté</p>
      </div>
      <div class="col-md-6 text-md-end">
        <small class="text-muted">
          🧊 Gérez votre frigo intelligemment | Livraison à domicile
        </small>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= FRIGO_BASE ?>/public/js/validation.js"></script>

<?php if (isset($includeMap) && $includeMap === true): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= FRIGO_BASE ?>/public/js/map.js"></script>
<?php endif; ?>

<?php if (isset($includeScan) && $includeScan === true): ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<?php endif; ?>

</body>
</html>