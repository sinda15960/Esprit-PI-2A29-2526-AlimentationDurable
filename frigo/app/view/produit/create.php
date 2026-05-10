<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4" style="max-width:600px">
  <h2 class="fw-bold text-success mb-4">Ajouter un produit</h2>

  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($_SESSION['errors'] as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; unset($_SESSION['errors']); ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post"
        action="<?= FRIGO_INDEX ?>?controller=produit&action=store"
        id="form-produit">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nom du produit</label>
      <input type="text" name="nom" class="form-control" id="p-nom">
      <div class="text-danger small" id="err-nom"></div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Description</label>
      <textarea name="description" class="form-control" rows="2"></textarea>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Prix (TND)</label>
        <input type="text" name="prix" class="form-control" id="p-prix">
        <div class="text-danger small" id="err-prix"></div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Quantité</label>
        <input type="text" name="quantite" class="form-control" id="p-qte">
        <div class="text-danger small" id="err-qte"></div>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Date d'expiration</label>
      <input type="text" name="date_expiration" class="form-control"
             id="p-date" placeholder="YYYY-MM-DD">
      <div class="text-danger small" id="err-date"></div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Catégorie</label>
      <select name="categorie_id" class="form-select" id="p-cat">
        <option value="">-- Choisir --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="text-danger small" id="err-cat"></div>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success">Enregistrer</button>
      <a href="<?= FRIGO_INDEX ?>?controller=produit&action=index"
         class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
</div>

<script>
document.getElementById('form-produit').addEventListener('submit', function(e){
  let ok = true;
  ok = validateNom(document.getElementById('p-nom').value, 'err-nom') && ok;
  ok = validatePrix(document.getElementById('p-prix').value, 'err-prix') && ok;
  ok = validateQuantite(document.getElementById('p-qte').value, 'err-qte') && ok;
  ok = validateDate(document.getElementById('p-date').value, 'err-date') && ok;
  ok = validateSelect(document.getElementById('p-cat').value, 'err-cat', 'Choisir une catégorie.') && ok;
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>