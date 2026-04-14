<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success">Gestion des produits</h2>
    <a href="/frigo/index.php?controller=produit&action=create"
       class="btn btn-success">+ Ajouter un produit</a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-hover">
    <thead class="table-success">
      <tr>
        <th>#</th><th>Nom</th><th>Catégorie</th><th>Prix</th>
        <th>Quantité</th><th>Expiration</th><th>État</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($produits as $p): ?>
        <?php
          $badgeClass = match($p['etat']) {
            'perime'         => 'danger',
            'bientot_perime' => 'warning text-dark',
            default          => 'success'
          };
          $badgeLabel = match($p['etat']) {
            'perime'         => 'Périmé',
            'bientot_perime' => 'Bientôt périmé',
            default          => 'Frais'
          };
        ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nom']) ?></td>
          <td><?= htmlspecialchars($p['categorie_nom'] ?? '-') ?></td>
          <td><?= number_format($p['prix'], 2) ?> TND</td>
          <td><?= $p['quantite'] ?></td>
          <td><?= $p['date_expiration']
                ? date('d/m/Y', strtotime($p['date_expiration']))
                : '-' ?></td>
          <td><span class="badge bg-<?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
          <td>
            <a href="/frigo/index.php?controller=produit&action=edit&id=<?= $p['id'] ?>"
               class="btn btn-warning btn-sm">Modifier</a>
            <a href="/frigo/index.php?controller=produit&action=delete&id=<?= $p['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require 'app/view/layout/footer.php'; ?>