<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h1 class="fw-bold text-success mb-4">Supermarché virtuel</h1>

  <div class="row g-3 mb-4">
    <?php foreach ($categories as $cat): ?>
      <div class="col-md-3">
        <a href="/frigo/index.php?controller=categorie&action=index&cat_id=<?= $cat['id'] ?>"
           class="text-decoration-none">
          <div class="card text-center border-0 shadow-sm h-100
            <?= $categorieActive == $cat['id'] ? 'border border-success border-2' : '' ?>">
            <div class="card-body">
              <h6 class="mt-2 fw-bold text-success"><?= htmlspecialchars($cat['nom']) ?></h6>
              <p class="small text-muted"><?= htmlspecialchars($cat['description'] ?? '') ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (!empty($produits)): ?>
    <h4 class="fw-bold mb-3">Produits disponibles</h4>
    <div class="row g-3">
      <?php foreach ($produits as $p): ?>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h6 class="fw-bold"><?= htmlspecialchars($p['nom']) ?></h6>
              <p class="text-success fw-bold"><?= number_format($p['prix'], 2) ?> TND</p>
              <p class="small text-muted"><?= htmlspecialchars($p['description'] ?? '') ?></p>
              <form method="post"
                    action="/frigo/index.php?controller=commande&action=ajouterPanier"
                    id="form_<?= $p['id'] ?>">
                <input type="hidden" name="produit_id" value="<?= $p['id'] ?>">
                <div class="input-group input-group-sm mb-2">
                  <input type="number" name="quantite" value="1"
                         class="form-control" id="qte_<?= $p['id'] ?>">
                  <button class="btn btn-success btn-sm" type="submit">+ Panier</button>
                </div>
                <div class="text-danger small" id="err_<?= $p['id'] ?>"></div>
                <script>
                (function(){
                  var form = document.getElementById('form_<?= $p['id'] ?>');
                  form.addEventListener('submit', function(e){
                    var qte = parseInt(document.getElementById('qte_<?= $p['id'] ?>').value);
                    var err = document.getElementById('err_<?= $p['id'] ?>');
                    err.textContent = '';
                    if(isNaN(qte) || qte < 1){
                      e.preventDefault();
                      err.textContent = 'Quantité invalide (min. 1).';
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
  <?php elseif ($categorieActive): ?>
    <div class="alert alert-info">Aucun produit dans cette catégorie.</div>
  <?php endif; ?>
</div>

<?php require 'app/view/layout/footer.php'; ?>