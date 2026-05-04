<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">Recherche dans le frigo par catégorie</h2>

  <?php
    $frigoModel = new FrigoUtilisateur();
    $categorieModel = new Categorie();
    $categories = $categorieModel->getAll();
  ?>

  <form method="post"
        action="/frigo/index.php?controller=produit&action=rechercherFrigo"
        id="form-recherche">
    <div class="row g-3 align-items-end mb-4">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Sélectionnez une catégorie :</label>
        <select name="categorie_id" class="form-select" id="sel-cat">
          <option value="">-- Toutes les catégories --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
              <?= isset($categorieActive) && $categorieActive == $cat['id']
                  ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="text-danger small" id="err-cat"></div>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-success w-100">
          Rechercher
        </button>
      </div>
    </div>
  </form>

  <!-- Résultats groupés par catégorie -->
  <?php if (isset($resultats)): ?>
    <h4 class="fw-bold mb-3">
      Aliments correspondants dans le frigo :
    </h4>

    <?php if (empty($resultats)): ?>
      <div class="alert alert-info">
        Aucun aliment trouvé dans cette catégorie.
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($resultats as $item): ?>
          <?php
            $badgeClass = match($item['etat']) {
              'perime'         => 'danger',
              'bientot_perime' => 'warning text-dark',
              default          => 'success'
            };
            $badgeLabel = match($item['etat']) {
              'perime'         => 'Périmé',
              'bientot_perime' => 'Bientôt périmé',
              default          => 'Frais'
            };
          ?>
          <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body">
                <h6 class="fw-bold"><?= htmlspecialchars($item['nom']) ?></h6>
                <p class="small text-muted mb-1">
                  <?= htmlspecialchars($item['categorie_nom'] ?? '') ?>
                </p>
                <span class="badge bg-<?= $badgeClass ?>">
                  <?= $badgeLabel ?>
                </span>
                <p class="mt-2 mb-0">
                  <strong>Quantité :</strong> <?= $item['quantite'] ?>
                </p>
                <?php if ($item['date_expiration']): ?>
                  <p class="small text-muted">
                    Exp : <?= date('d/m/Y', strtotime($item['date_expiration'])) ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  <?php else: ?>
    <!-- Afficher tout le frigo groupé par catégorie -->
    <h4 class="fw-bold mb-3">Contenu du frigo par catégorie :</h4>
    <?php
      $grouped = $frigoModel->getAllParCategorie();
    ?>
    <?php if (empty($grouped)): ?>
      <div class="alert alert-info">Le frigo est vide.</div>
    <?php else: ?>
      <?php foreach ($grouped as $categorie => $items): ?>
        <h5 class="text-success mt-4 mb-2">
          <?= htmlspecialchars($categorie) ?>
        </h5>
        <div class="row g-3 mb-3">
          <?php foreach ($items as $item): ?>
            <?php
              $badgeClass = match($item['etat']) {
                'perime'         => 'danger',
                'bientot_perime' => 'warning text-dark',
                default          => 'success'
              };
              $badgeLabel = match($item['etat']) {
                'perime'         => 'Périmé',
                'bientot_perime' => 'Bientôt périmé',
                default          => 'Frais'
              };
            ?>
            <div class="col-md-3">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                  <h6 class="fw-bold"><?= htmlspecialchars($item['nom']) ?></h6>
                  <span class="badge bg-<?= $badgeClass ?>">
                    <?= $badgeLabel ?>
                  </span>
                  <p class="mt-2 mb-0">
                    <strong>Quantité :</strong> <?= $item['quantite'] ?>
                  </p>
                  <?php if ($item['date_expiration']): ?>
                    <p class="small text-muted">
                      Exp : <?= date('d/m/Y', strtotime($item['date_expiration'])) ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
document.getElementById('form-recherche').addEventListener('submit', function(e){
  var cat = document.getElementById('sel-cat').value;
  var err = document.getElementById('err-cat');
  err.textContent = '';
  if(!cat){
    e.preventDefault();
    err.textContent = 'Veuillez sélectionner une catégorie.';
  }
});
</script>

<?php require 'app/view/layout/footer.php'; ?>