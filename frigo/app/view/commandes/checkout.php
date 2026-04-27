<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success">Finaliser la commande</h2>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible">
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

  <form id="form-commande" method="post"
        action="/frigo/index.php?controller=commande&action=confirmer">
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Nom complet</label>
        <input type="text" name="nom_client" class="form-control" id="nom_client">
        <div class="text-danger small" id="err-nom"></div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Téléphone (8 chiffres)</label>
        <input type="text" name="telephone" class="form-control"
               id="telephone" maxlength="8">
        <div class="text-danger small" id="err-tel"></div>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Adresse de livraison</label>
        <textarea name="adresse" class="form-control" rows="2" id="adresse"></textarea>
        <div class="text-danger small" id="err-adresse"></div>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Méthode de paiement</label>
        <select name="methode_paiement" class="form-select" id="methode">
          <option value="">-- Choisir --</option>
          <option value="especes">Espèces</option>
          <option value="carte">Carte bancaire</option>
          <option value="virement">Virement</option>
        </select>
        <div class="text-danger small" id="err-methode"></div>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success px-4">Confirmer</button>
      <a href="/frigo/index.php?controller=commande&action=annuler"
         class="btn btn-outline-danger">Annuler</a>
    </div>
  </form>

  <?php if (!empty($historique)): ?>
  <h4 class="mt-5 fw-bold">Historique récent</h4>
  <table class="table table-bordered table-hover mt-2">
    <thead class="table-success">
      <tr><th>#</th><th>Date</th><th>Client</th><th>Total</th><th>Statut</th></tr>
    </thead>
    <tbody>
      <?php foreach ($historique as $h): ?>
      <tr>
        <td><?= $h['id'] ?></td>
        <td><?= date('d/m/Y H:i', strtotime($h['date_commande'])) ?></td>
        <td><?= htmlspecialchars($h['nom_client']) ?></td>
        <td><?= number_format($h['total'], 2) ?> TND</td>
        <td>
          <span class="badge bg-<?= $h['statut'] === 'confirmee'
            ? 'success' : ($h['statut'] === 'annulee' ? 'danger' : 'warning text-dark') ?>">
            <?= ucfirst($h['statut']) ?>
          </span>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<script>
document.getElementById('form-commande').addEventListener('submit', function(e){
  let ok = true;
  ok = validateNom(document.getElementById('nom_client').value, 'err-nom') && ok;
  ok = validateTelephone(document.getElementById('telephone').value, 'err-tel') && ok;
  ok = validateAdresse(document.getElementById('adresse').value, 'err-adresse') && ok;
  ok = validateSelect(document.getElementById('methode').value, 'err-methode', 'Choisir une méthode.') && ok;
  if(!ok) e.preventDefault();
});
</script>

<?php require 'app/view/layout/footer.php'; ?>