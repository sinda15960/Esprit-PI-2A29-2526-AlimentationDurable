<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">Mon panier</h2>

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

  <?php $panier = $_SESSION['panier'] ?? []; ?>

  <?php if (empty($panier)): ?>
    <div class="alert alert-info">Votre panier est vide.</div>
    <a href="/frigo/index.php?controller=categorie&action=index"
       class="btn btn-success">Continuer les achats</a>
  <?php else: ?>

    <form method="post"
          action="/frigo/index.php?controller=commande&action=modifierPanier"
          id="form-panier">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-success">
          <tr>
            <th>Produit</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Sous-total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; ?>
          <?php foreach ($panier as $id => $item): ?>
            <?php $sous = $item['prix'] * $item['quantite']; $total += $sous; ?>
            <tr>
              <td><?= htmlspecialchars($item['nom']) ?></td>
              <td><?= number_format($item['prix'], 2) ?> TND</td>
              <td>
                <input type="number"
                       name="quantites[<?= $id ?>]"
                       value="<?= $item['quantite'] ?>"
                       class="form-control form-control-sm qte-input"
                       style="width:80px"
                       data-id="<?= $id ?>"
                       data-prix="<?= $item['prix'] ?>"
                       min="1">
                <div class="text-danger small" id="err-qte-<?= $id ?>"></div>
              </td>
              <td class="sous-total" id="sous_<?= $id ?>">
                <?= number_format($sous, 2) ?> TND
              </td>
              <td>
                <a href="/frigo/index.php?controller=commande&action=retirerPanier&id=<?= $id ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Retirer cet article ?')">
                  Retirer
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <?php
            $promo      = $_SESSION['promo'] ?? null;
            $totalFinal = $total;
            if ($promo) {
              if ($promo['type_reduction'] === 'pourcentage') {
                $totalFinal = $total - ($total * $promo['reduction'] / 100);
              } else {
                $totalFinal = max(0, $total - $promo['reduction']);
              }
              $totalFinal = round($totalFinal, 2);
            }
          ?>
          <tr>
            <td colspan="3" class="text-end fw-bold">Sous-total :</td>
            <td colspan="2"><?= number_format($total, 2) ?> TND</td>
          </tr>
          <?php if ($promo): ?>
          <tr class="table-warning">
            <td colspan="3" class="text-end fw-bold">
              Réduction (<?= htmlspecialchars($promo['code']) ?>) :
            </td>
            <td colspan="2" class="text-danger fw-bold">
              - <?= $promo['type_reduction'] === 'pourcentage'
                    ? $promo['reduction'] . '%'
                    : number_format($promo['reduction'], 2) . ' TND' ?>
            </td>
          </tr>
          <?php endif; ?>
          <tr class="table-success fw-bold">
            <td colspan="3" class="text-end">Total final :</td>
            <td colspan="2"><?= number_format($totalFinal, 2) ?> TND</td>
          </tr>
        </tfoot>
      </table>

      <div class="d-flex gap-2 mt-3 flex-wrap">
        <button type="submit" class="btn btn-warning">Mettre à jour</button>
        <a href="/frigo/index.php?controller=commande&action=checkout"
           class="btn btn-success px-4">Terminer la commande</a>
        <a href="/frigo/index.php?controller=commande&action=annuler"
           class="btn btn-outline-danger">Vider le panier</a>
        <a href="/frigo/index.php?controller=categorie&action=index"
           class="btn btn-outline-secondary">Continuer les achats</a>
      </div>
    </form>

    <!-- Code promo -->
    <div class="card border-0 shadow-sm mt-4" style="max-width:450px">
      <div class="card-header bg-success text-white fw-bold">
        Code promo
      </div>
      <div class="card-body">
        <?php if ($promo): ?>
          <div class="alert alert-success py-2 mb-2">
            Code <strong><?= htmlspecialchars($promo['code']) ?></strong> appliqué !
            Réduction :
            <?= $promo['type_reduction'] === 'pourcentage'
              ? $promo['reduction'] . '%'
              : number_format($promo['reduction'], 2) . ' TND' ?>
          </div>
          <a href="/frigo/index.php?controller=commande&action=supprimerPromo"
             class="btn btn-outline-danger btn-sm">
            Supprimer le code
          </a>
        <?php else: ?>
          <form method="post"
                action="/frigo/index.php?controller=commande&action=appliquerPromo"
                id="form-promo">
            <div class="input-group">
              <input type="text" name="code_promo"
                     class="form-control text-uppercase"
                     id="code-promo"
                     placeholder="Ex: FRIGO10"
                     maxlength="50">
              <button type="submit" class="btn btn-success">
                Appliquer
              </button>
            </div>
            <div class="text-danger small mt-1" id="err-promo"></div>
            <p class="small text-muted mt-1">
              Entrez le code exact fourni par l'administrateur
            </p>
          </form>
        <?php endif; ?>
      </div>
    </div>

  <?php endif; ?>
</div>

<script>
// Mise à jour quantités
document.querySelectorAll('.qte-input').forEach(function(input){
  input.addEventListener('input', function(){
    var id   = this.dataset.id;
    var prix = parseFloat(this.dataset.prix);
    var qte  = parseInt(this.value);
    document.getElementById('err-qte-' + id).textContent = '';
    if(!isNaN(qte) && qte >= 1){
      document.getElementById('sous_' + id).textContent =
        (prix * qte).toFixed(2) + ' TND';
    }
  });
});

// Validation panier
document.getElementById('form-panier').addEventListener('submit', function(e){
  var ok = true;
  document.querySelectorAll('.qte-input').forEach(function(inp){
    var qte = parseInt(inp.value);
    var err = document.getElementById('err-qte-' + inp.dataset.id);
    err.textContent = '';
    if(isNaN(qte) || qte < 1){
      err.textContent = 'Quantité invalide (min. 1).';
      ok = false;
    }
  });
  if(!ok) e.preventDefault();
});

<?php if (!$promo): ?>
// Validation code promo — sans HTML5
document.getElementById('form-promo').addEventListener('submit', function(e){
  var code = document.getElementById('code-promo').value.trim();
  var err  = document.getElementById('err-promo');
  err.textContent = '';

  if(code.length === 0){
    e.preventDefault();
    err.textContent = 'Veuillez entrer un code promo.';
    return;
  }
  if(code.length < 3){
    e.preventDefault();
    err.textContent = 'Le code promo doit contenir au moins 3 caractères.';
    return;
  }
  if(!/^[A-Za-z0-9]+$/.test(code)){
    e.preventDefault();
    err.textContent = 'Le code promo ne doit contenir que des lettres et chiffres.';
    return;
  }
});
<?php endif; ?>
</script>
<!-- À la fin du fichier panier.php, avant le footer, ajouter : -->

<!-- Suggestions de produits complémentaires -->
<?php if (!empty($suggestionsComplementaires)): ?>
<div class="card border-0 shadow-sm mt-4">
  <div class="card-header bg-info text-white fw-bold">
    💡 Vous pourriez aussi aimer
  </div>
  <div class="card-body">
    <div class="row g-3">
      <?php foreach ($suggestionsComplementaires as $sugg): ?>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center">
            <div style="font-size:2rem"><?= getEmojiAliment($sugg['nom']) ?></div>
            <h6 class="fw-bold mt-2"><?= htmlspecialchars($sugg['nom']) ?></h6>
            <p class="text-success fw-bold"><?= number_format($sugg['prix'], 2) ?> TND</p>
            <form method="post" action="/frigo/index.php?mode=front&controller=commande&action=ajouterPanier">
              <input type="hidden" name="produit_id" value="<?= $sugg['id'] ?>">
              <input type="hidden" name="quantite" value="1">
              <button type="submit" class="btn btn-sm btn-outline-success">+ Ajouter</button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require 'app/view/layout/footer.php'; ?>