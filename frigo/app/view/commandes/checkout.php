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
        
    <div class="row">
      <!-- Colonne gauche : coordonnées client -->
      <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-success text-white fw-bold">Vos coordonnées</div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nom complet</label>
              <input type="text" name="nom_client" class="form-control" id="nom_client">
              <div class="text-danger small" id="err-nom"></div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Téléphone (8 chiffres)</label>
              <input type="text" name="telephone" class="form-control"
                     id="telephone" maxlength="8">
              <div class="text-danger small" id="err-tel"></div>
            </div>
          </div>
        </div>
        
        <!-- Carte interactive (Idée 1) -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-success text-white fw-bold">
            📍 Choisissez votre adresse de livraison
          </div>
          <div class="card-body">
            <div id="map" style="height: 300px; border-radius: 8px; margin-bottom: 10px;"></div>
            <input type="hidden" name="latitude" id="latitude" value="">
            <input type="hidden" name="longitude" id="longitude" value="">
            <input type="hidden" name="adresse_lat" id="adresse_lat">
            <input type="hidden" name="adresse_lng" id="adresse_lng">
            <textarea name="adresse" class="form-control" rows="2" id="adresse" 
                      placeholder="Adresse complète (cliquez sur la carte pour placer le point)"></textarea>
            <div id="zone-info" class="mt-2"></div>
            <div class="text-danger small" id="err-adresse"></div>
          </div>
        </div>
      </div>
      
      <!-- Colonne droite : paiement -->
      <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-success text-white fw-bold">💰 Paiement</div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Méthode de paiement</label>
              <select name="methode_paiement" class="form-select" id="methode">
                <option value="">-- Choisir --</option>
                <option value="especes">Espèces (à la livraison)</option>
                <option value="carte">Carte bancaire (paiement sécurisé)</option>
                <option value="virement">Virement bancaire</option>
              </select>
              <div class="text-danger small" id="err-methode"></div>
            </div>
            
            <!-- Formulaire carte bancaire (caché par défaut, Idée 2) -->
            <div id="cb-form" style="display: none;">
              <div class="border rounded p-3 mt-2 bg-light">
                <h6 class="fw-bold mb-3">💳 Informations carte bancaire</h6>
                
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Numéro de carte</label>
                  <input type="text" name="carte_numero" class="form-control" 
                         id="carte_numero" placeholder="1234 5678 9012 3456" maxlength="19">
                  <div class="text-danger small" id="err-carte-numero"></div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 mb-2">
                    <label class="form-label small fw-semibold">Date expiration</label>
                    <input type="text" name="carte_expiration" class="form-control" 
                           id="carte_expiration" placeholder="MM/AA" maxlength="5">
                    <div class="text-danger small" id="err-carte-exp"></div>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label small fw-semibold">CVV</label>
                    <input type="password" name="carte_cvv" class="form-control" 
                           id="carte_cvv" placeholder="123" maxlength="3">
                    <div class="text-danger small" id="err-carte-cvv"></div>
                  </div>
                </div>
                
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Nom du titulaire</label>
                  <input type="text" name="carte_titulaire" class="form-control" 
                         id="carte_titulaire" placeholder="Jean DUPONT">
                  <div class="text-danger small" id="err-carte-titulaire"></div>
                </div>
                
                <div class="alert alert-info small mt-2 mb-0">
                  🔒 Test: utilisez 4242 4242 4242 4242, expiration 12/30, CVV 123
                </div>
              </div>
            </div>
            
            <!-- Résumé total avec frais de livraison -->
            <div class="mt-3 pt-2 border-top">
              <div class="d-flex justify-content-between">
                <span>Sous-total panier :</span>
                <span id="total_display"><?= number_format($total ?? 0, 2) ?> TND</span>
              </div>
              <div class="d-flex justify-content-between text-muted small">
                <span>Frais de livraison :</span>
                <span id="frais_livraison_span">0.00 TND</span>
              </div>
              <div class="d-flex justify-content-between fw-bold mt-2">
                <span>Total à payer :</span>
                <span class="text-success" id="total_final_span">
                  <?= number_format($total ?? 0, 2) ?> TND
                </span>
              </div>
            </div>
            
            <input type="hidden" name="total_initial" id="total_initial" value="<?= $total ?? 0 ?>">
            <input type="hidden" name="frais_livraison" id="frais_livraison" value="0">
            <input type="hidden" name="total_final" id="total_final_input" value="<?= $total ?? 0 ?>">
          </div>
        </div>
        
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success px-4" id="btn-confirmer">Confirmer la commande</button>
          <a href="/frigo/index.php?controller=commande&action=annuler"
             class="btn btn-outline-danger">Annuler</a>
        </div>
      </div>
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
  讲
  <?php endif; ?>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/frigo/public/js/map.js"></script>
<script>
// Afficher/masquer formulaire CB selon méthode de paiement
document.getElementById('methode').addEventListener('change', function() {
  var cbForm = document.getElementById('cb-form');
  if (this.value === 'carte') {
    cbForm.style.display = 'block';
  } else {
    cbForm.style.display = 'none';
  }
});

// Mettre à jour l'affichage du total
function mettreAJourTotal() {
  var totalInitial = parseFloat(document.getElementById('total_initial').value) || 0;
  var frais = parseFloat(document.getElementById('frais_livraison').value) || 0;
  var totalFinal = totalInitial + frais;
  document.getElementById('total_final_span').textContent = totalFinal.toFixed(2) + ' TND';
  document.getElementById('total_final_input').value = totalFinal.toFixed(2);
}

// Validation complète du formulaire
document.getElementById('form-commande').addEventListener('submit', function(e){
  let ok = true;
  
  // Validation des coordonnées
  ok = validateNom(document.getElementById('nom_client').value, 'err-nom') && ok;
  ok = validateTelephone(document.getElementById('telephone').value, 'err-tel') && ok;
  
  // Validation adresse (latitude/longitude doivent être présents)
  var lat = document.getElementById('latitude').value;
  var lng = document.getElementById('longitude').value;
  if (!lat || !lng) {
    document.getElementById('err-adresse').textContent = 'Veuillez cliquer sur la carte pour définir votre adresse.';
    ok = false;
  } else {
    document.getElementById('err-adresse').textContent = '';
  }
  
  // Validation méthode de paiement
  ok = validateSelect(document.getElementById('methode').value, 'err-methode', 'Choisir une méthode de paiement.') && ok;
  
  // Validation carte bancaire si méthode = carte
  if (document.getElementById('methode').value === 'carte') {
    ok = validateCarteNumero(document.getElementById('carte_numero').value, 'err-carte-numero') && ok;
    ok = validateCarteExpiration(document.getElementById('carte_expiration').value, 'err-carte-exp') && ok;
    ok = validateCarteCVV(document.getElementById('carte_cvv').value, 'err-carte-cvv') && ok;
    ok = validateCarteTitulaire(document.getElementById('carte_titulaire').value, 'err-carte-titulaire') && ok;
  }
  
  if(!ok) e.preventDefault();
});

// Formatage automatique du numéro de carte
document.getElementById('carte_numero').addEventListener('input', function(e) {
  var val = this.value.replace(/\s/g, '');
  if (val.length > 16) val = val.substr(0, 16);
  var formatted = val.replace(/(\d{4})(?=\d)/g, '$1 ');
  this.value = formatted;
});

// Formatage automatique date expiration
document.getElementById('carte_expiration').addEventListener('input', function(e) {
  var val = this.value.replace(/\//g, '');
  if (val.length > 4) val = val.substr(0, 4);
  if (val.length >= 2) {
    this.value = val.substr(0, 2) + (val.length > 2 ? '/' + val.substr(2) : '');
  } else {
    this.value = val;
  }
});

// Initialiser la carte
document.addEventListener('DOMContentLoaded', function() {
  initMap();
  mettreAJourTotal();
});

// Définir la fonction globalement pour map.js
window.mettreAJourTotalPanier = function() {
  var frais = parseFloat(document.getElementById('frais_livraison').value) || 0;
  var totalInitial = parseFloat(document.getElementById('total_initial').value) || 0;
  var totalFinal = totalInitial + frais;
  document.getElementById('frais_livraison_span').textContent = frais.toFixed(2) + ' TND';
  document.getElementById('total_final_span').textContent = totalFinal.toFixed(2) + ' TND';
  document.getElementById('total_final_input').value = totalFinal.toFixed(2);
};
</script>

<?php require 'app/view/layout/footer.php'; ?>