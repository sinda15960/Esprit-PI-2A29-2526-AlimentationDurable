<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">Gestion des commandes</h2>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>
  <!-- Gestion codes promo -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-success text-white fw-bold">
    Gestion des codes promo
  </div>
  <div class="card-body">

    <!-- Ajouter un code -->
    <form method="post"
          action="<?= FRIGO_INDEX ?>?controller=commande&action=ajouterPromo"
          id="form-add-promo" class="mb-3">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-semibold">Code</label>
          <input type="text" name="code" class="form-control text-uppercase"
                 id="promo-code" placeholder="Ex: FRIGO10">
          <div class="text-danger small" id="err-promo-code"></div>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">Réduction</label>
          <input type="text" name="reduction" class="form-control"
                 id="promo-reduction" placeholder="Ex: 10">
          <div class="text-danger small" id="err-promo-red"></div>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">Type</label>
          <select name="type_reduction" class="form-select">
            <option value="pourcentage">Pourcentage (%)</option>
            <option value="montant">Montant (TND)</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">Expiration</label>
          <input type="text" name="date_expiration" class="form-control"
                 placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-success w-100">
            Ajouter le code
          </button>
        </div>
      </div>
    </form>

    <!-- Liste des codes -->
    <table class="table table-bordered table-sm">
      <thead class="table-success">
        <tr>
          <th>Code</th>
          <th>Réduction</th>
          <th>Type</th>
          <th>Expiration</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($codesPromo ?? [] as $cp): ?>
        <tr>
          <td><strong><?= htmlspecialchars($cp['code']) ?></strong></td>
          <td><?= $cp['reduction'] ?></td>
          <td><?= $cp['type_reduction'] === 'pourcentage' ? '%' : 'TND' ?></td>
          <td><?= $cp['date_expiration'] ?? 'Illimitée' ?></td>
          <td>
            <span class="badge bg-<?= $cp['actif'] ? 'success' : 'danger' ?>">
              <?= $cp['actif'] ? 'Actif' : 'Inactif' ?>
            </span>
          </td>
          <td>
            <a href="<?= FRIGO_INDEX ?>?controller=commande&action=togglePromo&id=<?= $cp['id'] ?>"
               class="btn btn-warning btn-sm">
              <?= $cp['actif'] ? 'Désactiver' : 'Activer' ?>
            </a>
            <a href="<?= FRIGO_INDEX ?>?controller=commande&action=supprimerCodePromo&id=<?= $cp['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Supprimer ce code ?')">
              Supprimer
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-success">
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Client</th>
          <th>Téléphone</th>
          <th>Adresse</th>
          <th>Paiement</th>
          <th>Total</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($historique as $h): ?>
        <tr>
          <td><?= $h['id'] ?></td>
          <td><?= date('d/m/Y H:i', strtotime($h['date_commande'])) ?></td>
          <td><?= htmlspecialchars($h['nom_client']) ?></td>
          <td><?= htmlspecialchars($h['telephone']) ?></td>
          <td><?= htmlspecialchars($h['adresse']) ?></td>
          <td><?= ucfirst($h['methode_paiement']) ?></td>
          <td><?= number_format($h['total'], 2) ?> TND</td>
          <td>
            <span class="badge bg-<?= $h['statut'] === 'confirmee'
              ? 'success' : ($h['statut'] === 'annulee' ? 'danger' : 'warning text-dark') ?>">
              <?= ucfirst($h['statut']) ?>
            </span>
          </td>
          <td>
            <button class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEdit<?= $h['id'] ?>">
              Modifier
            </button>
            <a href="<?= FRIGO_INDEX ?>?controller=commande&action=deleteCommande&id=<?= $h['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Supprimer cette commande ?')">
              Supprimer
            </a>
          </td>
        </tr>

        <!-- Modal modification commande -->
        <div class="modal fade" id="modalEdit<?= $h['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">
                  Modifier commande #<?= $h['id'] ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form method="post"
                      action="<?= FRIGO_INDEX ?>?controller=commande&action=updateCommande"
                      id="form-cmd-<?= $h['id'] ?>">
                  <input type="hidden" name="id" value="<?= $h['id'] ?>">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Nom client</label>
                      <input type="text" name="nom_client" class="form-control"
                             id="cn_<?= $h['id'] ?>"
                             value="<?= htmlspecialchars($h['nom_client']) ?>">
                      <div class="text-danger small" id="err-cn-<?= $h['id'] ?>"></div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Téléphone</label>
                      <input type="text" name="telephone" class="form-control"
                             id="ct_<?= $h['id'] ?>"
                             value="<?= htmlspecialchars($h['telephone']) ?>"
                             maxlength="8">
                      <div class="text-danger small" id="err-ct-<?= $h['id'] ?>"></div>
                    </div>
                    <div class="col-12">
                      <label class="form-label fw-semibold">Adresse</label>
                      <textarea name="adresse" class="form-control"
                                id="ca_<?= $h['id'] ?>"
                                rows="2"><?= htmlspecialchars($h['adresse']) ?></textarea>
                      <div class="text-danger small" id="err-ca-<?= $h['id'] ?>"></div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Méthode paiement</label>
                      <select name="methode_paiement" class="form-select">
                        <option value="especes"
                          <?= $h['methode_paiement'] === 'especes' ? 'selected' : '' ?>>
                          Espèces
                        </option>
                        <option value="carte"
                          <?= $h['methode_paiement'] === 'carte' ? 'selected' : '' ?>>
                          Carte bancaire
                        </option>
                        <option value="virement"
                          <?= $h['methode_paiement'] === 'virement' ? 'selected' : '' ?>>
                          Virement
                        </option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Statut</label>
                      <select name="statut" class="form-select">
                        <option value="en_attente"
                          <?= $h['statut'] === 'en_attente' ? 'selected' : '' ?>>
                          En attente
                        </option>
                        <option value="confirmee"
                          <?= $h['statut'] === 'confirmee' ? 'selected' : '' ?>>
                          Confirmée
                        </option>
                        <option value="annulee"
                          <?= $h['statut'] === 'annulee' ? 'selected' : '' ?>>
                          Annulée
                        </option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Total (TND)</label>
                      <input type="text" name="total" class="form-control"
                             value="<?= $h['total'] ?>">
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-warning w-100">
                      Enregistrer les modifications
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
<?php foreach ($historique as $h): ?>
document.getElementById('form-cmd-<?= $h['id'] ?>').addEventListener('submit', function(e){
  let ok = true;
  ok = validateNom(document.getElementById('cn_<?= $h['id'] ?>').value,
       'err-cn-<?= $h['id'] ?>') && ok;
  ok = validateTelephone(document.getElementById('ct_<?= $h['id'] ?>').value,
       'err-ct-<?= $h['id'] ?>') && ok;
  ok = validateAdresse(document.getElementById('ca_<?= $h['id'] ?>').value,
       'err-ca-<?= $h['id'] ?>') && ok;
  if(!ok) e.preventDefault();
});
<?php endforeach; ?>
</script>

<?php require 'app/view/layout/footer.php'; ?>