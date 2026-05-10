<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4" style="max-width:500px">
  <h2 class="fw-bold text-success mb-4">Modifier la catégorie</h2>

  <form method="post"
        action="<?= FRIGO_INDEX ?>?controller=categorie&action=update"
        id="form-edit-cat">
    <input type="hidden" name="id" value="<?= $categorie['id'] ?>">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nom</label>
      <input type="text" name="nom" class="form-control" id="cat-nom"
             value="<?= htmlspecialchars($categorie['nom']) ?>">
      <div class="text-danger small" id="err-nom"></div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Description</label>
      <input type="text" name="description" class="form-control"
             value="<?= htmlspecialchars($categorie['description'] ?? '') ?>">
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-warning">Modifier</button>
      <a href="<?= FRIGO_INDEX ?>?controller=categorie&action=admin"
         class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
</div>

<script>
document.getElementById('form-edit-cat').addEventListener('submit', function(e){
  var ok = validateNom(document.getElementById('cat-nom').value, 'err-nom');
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>