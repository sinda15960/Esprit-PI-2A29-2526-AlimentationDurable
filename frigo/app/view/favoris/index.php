<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">⭐ Mes Favoris</h2>

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

  <?php if (empty($favoris)): ?>
    <div class="alert alert-info">
      Vous n'avez pas encore de favoris.
      <a href="<?= FRIGO_INDEX ?>?controller=categorie&action=index"
         class="alert-link">Parcourir le supermarché</a>
    </div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($favoris as $fav): ?>
        <?php $emoji = getEmojiAliment($fav['nom']); ?>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="text-center pt-3" style="font-size:2.5rem">
              <?= $emoji ?>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start">
                <h6 class="fw-bold"><?= htmlspecialchars($fav['nom']) ?></h6>
                <a href="<?= FRIGO_INDEX ?>?controller=favori&action=supprimer&produit_id=<?= $fav['produit_id'] ?>"
                   class="text-danger fw-bold"
                   onclick="return confirm('Retirer des favoris ?')"
                   title="Retirer">✕</a>
              </div>
              <p class="small text-muted mb-1">
                <?= htmlspecialchars($fav['categorie_nom'] ?? '') ?>
              </p>
              <p class="text-success fw-bold">
                <?= number_format($fav['prix'], 2) ?> TND
              </p>
              <a href="<?= FRIGO_INDEX ?>?controller=favori&action=ajouterAuPanier&produit_id=<?= $fav['produit_id'] ?>"
                 class="btn btn-success btn-sm w-100 mt-2">
                + Panier
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require 'app/view/layout/footer.php'; ?>