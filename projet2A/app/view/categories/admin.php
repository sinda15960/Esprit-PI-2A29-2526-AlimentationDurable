<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">Gestion des catégories</h2>

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

  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">Ajouter une catégorie</div>
    <div class="card-body">
      <form method="post"
            action="/frigo/index.php?controller=categorie&action=store"
            id="form-add-cat">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Nom</label>
            <input type="text" name="nom" class="form-control" id="cat-nom">
            <div class="text-danger small" id="err-cat-nom"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Description</label>
            <input type="text" name="description" class="form-control">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Ajouter</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <table class="table table-bordered table-hover">
    <thead class="table-success">
      <tr><th>#</th><th>Nom</th><th>Description</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $cat): ?>
      <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= htmlspecialchars($cat['nom']) ?></td>
        <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
        <td>
          <a href="/frigo/index.php?controller=categorie&action=edit&id=<?= $cat['id'] ?>"
             class="btn btn-warning btn-sm">Modifier</a>
          <a href="/frigo/index.php?controller=categorie&action=delete&id=<?= $cat['id'] ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById('form-add-cat').addEventListener('submit', function(e){
  var ok = validateNom(document.getElementById('cat-nom').value, 'err-cat-nom');
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>