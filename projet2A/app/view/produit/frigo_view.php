<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-success fw-bold mb-0">Mon Frigo</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAjout">
      + Ajouter un aliment
    </button>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($_SESSION['errors'] as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; unset($_SESSION['errors']); ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Aliments dans le frigo -->
  <h4 class="fw-bold text-success mb-3">Contenu du frigo</h4>

  <?php if (empty($frigoItems)): ?>
    <div class="alert alert-info">Votre frigo est vide. Ajoutez des aliments !</div>
  <?php else: ?>
  <div class="row g-3 mb-5">
    <?php foreach ($frigoItems as $item): ?>
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
        $alerteStock = $item['quantite'] <= 2;
      ?>
      <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm
          <?= $alerteStock ? 'border border-warning border-2' : '' ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($item['nom']) ?></h5>
            <span class="badge bg-<?= $badgeClass ?>"><?= $badgeLabel ?></span>

            <?php if ($alerteStock): ?>
              <div class="alert alert-warning py-1 px-2 mt-2 mb-1 small">
                Stock faible — ajouté au panier automatiquement !
              </div>
            <?php endif; ?>

            <p class="mt-2 mb-1">
              <strong>Quantité actuelle :</strong>
              <span class="<?= $item['quantite'] <= 1 ? 'text-danger fw-bold' : '' ?>">
                <?= $item['quantite'] ?>
              </span>
            </p>

            <?php if ($item['date_expiration']): ?>
              <p class="small text-muted">
                Exp : <?= date('d/m/Y', strtotime($item['date_expiration'])) ?>
              </p>
            <?php endif; ?>

            <!-- Modifier quantité -->
            <form method="post"
                  action="/frigo/index.php?controller=produit&action=modifierQuantiteFrigo"
                  id="form_qte_<?= $item['id'] ?>" class="mt-2">
              <input type="hidden" name="frigo_id" value="<?= $item['id'] ?>">
              <label class="form-label small fw-semibold mb-1">
                Modifier la quantité
              </label>
              <div class="input-group input-group-sm">
                <input type="number" name="quantite"
                       value="<?= $item['quantite'] ?>"
                       class="form-control"
                       id="qte_frigo_<?= $item['id'] ?>"
                       min="0">
                <button class="btn btn-outline-primary btn-sm" type="submit">
                  OK
                </button>
              </div>
              <div class="text-danger small" id="err_qte_<?= $item['id'] ?>"></div>
              <script>
              (function(){
                var form = document.getElementById('form_qte_<?= $item['id'] ?>');
                form.addEventListener('submit', function(e){
                  var qte = parseInt(
                    document.getElementById('qte_frigo_<?= $item['id'] ?>').value
                  );
                  var err = document.getElementById('err_qte_<?= $item['id'] ?>');
                  err.textContent = '';
                  if(isNaN(qte) || qte < 0){
                    e.preventDefault();
                    err.textContent = 'Quantité invalide (min. 0).';
                  }
                });
              })();
              </script>
            </form>

            <!-- Actions -->
            <div class="d-flex gap-2 mt-2">
              <a href="/frigo/index.php?controller=produit&action=supprimerDuFrigo&id=<?= $item['id'] ?>"
                 class="btn btn-outline-danger btn-sm"
                 onclick="return confirm('Retirer du frigo ?')">
                Retirer
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Produits disponibles à ajouter -->
  <h4 class="fw-bold text-success mb-3">Ajouter depuis le supermarché</h4>
  <div class="row g-3">
    <?php foreach ($produits as $p): ?>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold"><?= htmlspecialchars($p['nom']) ?></h6>
            <p class="small text-muted">
              <?= htmlspecialchars($p['categorie_nom'] ?? '') ?>
            </p>
            <form method="post"
                  action="/frigo/index.php?controller=produit&action=ajouterFrigo"
                  id="form_<?= $p['id'] ?>">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <div class="input-group input-group-sm mt-2">
                <input type="number" name="quantite" value="1"
                       class="form-control" id="qte_<?= $p['id'] ?>">
                <button class="btn btn-outline-success btn-sm" type="submit">
                  + Frigo
                </button>
              </div>
              <div class="text-danger small" id="err_<?= $p['id'] ?>"></div>
              <script>
              (function(){
                var form = document.getElementById('form_<?= $p['id'] ?>');
                form.addEventListener('submit', function(e){
                  var qte = parseInt(
                    document.getElementById('qte_<?= $p['id'] ?>').value
                  );
                  var err = document.getElementById('err_<?= $p['id'] ?>');
                  err.textContent = '';
                  if(isNaN(qte) || qte < 1){
                    e.preventDefault();
                    err.textContent = 'Quantité invalide.';
                  }
                });
              })();
              </script>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal ajout manuel -->
<div class="modal fade" id="modalAjout" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Ajouter un aliment manuellement</h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post"
              action="/frigo/index.php?controller=produit&action=ajouterManuel"
              id="form-manuel">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de l'aliment</label>
            <input type="text" name="nom_custom" class="form-control" id="m-nom">
            <div class="text-danger small" id="err-m-nom"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Quantité</label>
            <input type="text" name="quantite" class="form-control" id="m-qte">
            <div class="text-danger small" id="err-m-qte"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Date d'expiration</label>
            <input type="text" name="date_expiration" class="form-control"
                   id="m-date" placeholder="YYYY-MM-DD">
            <div class="text-danger small" id="err-m-date"></div>
          </div>
          <button type="submit" class="btn btn-success w-100">
            Ajouter au frigo
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('form-manuel').addEventListener('submit', function(e){
  let ok = true;
  ok = validateNom(document.getElementById('m-nom').value, 'err-m-nom') && ok;
  ok = validateQuantite(document.getElementById('m-qte').value, 'err-m-qte') && ok;
  ok = validateDate(document.getElementById('m-date').value, 'err-m-date') && ok;
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>