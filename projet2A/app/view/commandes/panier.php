<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">Mon panier</h2>

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
                <input type="number" name="quantites[<?= $id ?>]"
                       value="<?= $item['quantite'] ?>"
                       class="form-control form-control-sm qte-input"
                       style="width:80px"
                       data-id="<?= $id ?>"
                       data-prix="<?= $item['prix'] ?>"
                       min="1">
                <div class="text-danger small err-qte" id="err-qte-<?= $id ?>"></div>
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
          <tr class="table-success fw-bold">
            <td colspan="3" class="text-end">Total :</td>
            <td colspan="2" id="total-affiche">
              <?= number_format($total, 2) ?> TND
            </td>
          </tr>
        </tfoot>
      </table>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-warning">
          Mettre à jour le panier
        </button>
        <a href="/frigo/index.php?controller=commande&action=checkout"
           class="btn btn-success px-4">Terminer la commande</a>
        <a href="/frigo/index.php?controller=commande&action=annuler"
           class="btn btn-outline-danger">Vider le panier</a>
        <a href="/frigo/index.php?controller=categorie&action=index"
           class="btn btn-outline-secondary">Continuer les achats</a>
      </div>
    </form>
  <?php endif; ?>
</div>

<script>
document.querySelectorAll('.qte-input').forEach(function(input){
  input.addEventListener('input', function(){
    var id    = this.dataset.id;
    var prix  = parseFloat(this.dataset.prix);
    var qte   = parseInt(this.value);
    var err   = document.getElementById('err-qte-' + id);
    err.textContent = '';
    if(isNaN(qte) || qte < 1){
      err.textContent = 'Min. 1';
      return;
    }
    document.getElementById('sous_' + id).textContent =
      (prix * qte).toFixed(2) + ' TND';
    var total = 0;
    document.querySelectorAll('.qte-input').forEach(function(inp){
      var q = parseInt(inp.value);
      var p = parseFloat(inp.dataset.prix);
      if(!isNaN(q) && q > 0) total += q * p;
    });
    document.getElementById('total-affiche').textContent = total.toFixed(2) + ' TND';
  });
});

document.getElementById('form-panier').addEventListener('submit', function(e){
  var ok = true;
  document.querySelectorAll('.qte-input').forEach(function(inp){
    var id  = inp.dataset.id;
    var qte = parseInt(inp.value);
    var err = document.getElementById('err-qte-' + id);
    err.textContent = '';
    if(isNaN(qte) || qte < 1){
      err.textContent = 'Quantité invalide.';
      ok = false;
    }
  });
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>