<?php
$includeMap = true;
require 'app/view/layout/header.php';
?>

<div class="container py-4">
  <h2 class="fw-bold text-success">🛒 Finaliser la commande</h2>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
        action="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=confirmer">

    <div class="row g-4">
      <!-- Colonne gauche : infos client -->
      <div class="col-md-6">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-success text-white fw-bold">
            📋 Informations de livraison
          </div>
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
            <div class="mb-3">
              <label class="form-label fw-semibold">Adresse de livraison</label>
              <textarea name="adresse" class="form-control" rows="2"
                        id="adresse"></textarea>
              <div class="text-danger small" id="err-adresse"></div>
            </div>
            <input type="hidden" name="latitude" id="latitude" value="">
            <input type="hidden" name="longitude" id="longitude" value="">
            <input type="hidden" id="adresse_lat" value="">
            <input type="hidden" id="adresse_lng" value="">
          </div>
        </div>

        <!-- Carte de livraison -->
        <div class="card border-0 shadow-sm mt-3">
          <div class="card-header bg-success text-white fw-bold">
            🗺️ Choisir votre adresse sur la carte
          </div>
          <div class="card-body p-2">
            <p class="small text-muted mb-2">
              Cliquez sur la carte pour définir votre adresse de livraison
            </p>
            <div id="map" style="height:300px;border-radius:8px;background-color:#e9ecef;"></div>
            <div id="distance_info" class="mt-2 small text-success"></div>
            <div id="zone-info" class="mt-1"></div>
            <input type="hidden" id="frais_livraison" name="frais_livraison" value="0">
          </div>
        </div>
      </div>

      <!-- Colonne droite : paiement -->
      <div class="col-md-6">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-success text-white fw-bold">
            💳 Méthode de paiement
          </div>
          <div class="card-body">
            <div class="mb-3">
              <select name="methode_paiement" class="form-select" id="methode"
                      onchange="afficherFormulairePaiement(this.value)">
                <option value="">-- Choisir --</option>
                <option value="especes">💵 Espèces à la livraison</option>
                <option value="carte">💳 Carte bancaire</option>
                <option value="virement">🏦 Virement bancaire</option>
              </select>
              <div class="text-danger small" id="err-methode"></div>
            </div>

            <!-- Formulaire carte bancaire -->
            <div id="cb-form" style="display:none">
              <div class="card bg-light border-0 p-3 mb-3">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Numéro de carte (16 chiffres)</label>
                  <input type="text" name="carte_numero" class="form-control" id="carte_numero"
                         placeholder="1234 5678 9012 3456"
                         maxlength="19"
                         oninput="formaterNumeroCarte(this)">
                  <div class="text-danger small" id="err-carte-num"></div>
                  <small class="text-muted" id="type-carte"></small>
                </div>
                <div class="row g-3">
                  <div class="col-6">
                    <label class="form-label fw-semibold">Expiration (MM/AA)</label>
                    <input type="text" name="carte_exp" class="form-control" id="carte_exp"
                           placeholder="12/27" maxlength="5"
                           oninput="formaterExpiration(this)">
                    <div class="text-danger small" id="err-carte-exp"></div>
                  </div>
                  <div class="col-6">
                    <label class="form-label fw-semibold">CVV</label>
                    <input type="text" name="carte_cvv" class="form-control" id="carte_cvv"
                           placeholder="123" maxlength="3">
                    <div class="text-danger small" id="err-carte-cvv"></div>
                  </div>
                </div>
                <div class="mt-3">
                  <label class="form-label fw-semibold">Nom du titulaire</label>
                  <input type="text" name="carte_titulaire" class="form-control" id="carte_titulaire"
                         placeholder="NOM PRENOM">
                  <div class="text-danger small" id="err-carte-titulaire"></div>
                </div>
                <div class="alert alert-info mt-3 small">
                  🔒 Paiement simulé — aucune donnée réelle transmise
                </div>
              </div>
            </div>

            <!-- Récapitulatif -->
            <?php
              $panier    = $_SESSION['panier'] ?? [];
              $promo     = $_SESSION['promo'] ?? null;
              $sousTotal = array_sum(array_map(fn($i) => $i['prix'] * $i['quantite'], $panier));
              $totalFinal = $sousTotal;
              if ($promo) {
                if ($promo['type_reduction'] === 'pourcentage') {
                  $totalFinal = $sousTotal - ($sousTotal * $promo['reduction'] / 100);
                } else {
                  $totalFinal = max(0, $sousTotal - $promo['reduction']);
                }
              }
            ?>
            <div class="card bg-light border-0 p-3">
              <h6 class="fw-bold mb-3">Récapitulatif</h6>
              <?php foreach ($panier as $item): ?>
                <div class="d-flex justify-content-between small mb-1">
                  <span><?= htmlspecialchars($item['nom']) ?> x<?= $item['quantite'] ?></span>
                  <span><?= number_format($item['prix'] * $item['quantite'], 2) ?> TND</span>
                </div>
              <?php endforeach; ?>
              <hr>
              <div class="d-flex justify-content-between">
                <span>Sous-total :</span>
                <span><?= number_format($sousTotal, 2) ?> TND</span>
              </div>
              <?php if ($promo): ?>
                <div class="d-flex justify-content-between text-success">
                  <span>Promo (<?= htmlspecialchars($promo['code']) ?>) :</span>
                  <span>- <?= $promo['type_reduction'] === 'pourcentage'
                    ? $promo['reduction'] . '%'
                    : number_format($promo['reduction'], 2) . ' TND' ?></span>
                </div>
              <?php endif; ?>
              <div class="d-flex justify-content-between fw-bold text-success mt-1">
                <span>Frais livraison :</span>
                <span id="frais_livraison_span">0.00 TND</span>
              </div>
              <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                <span>Total :</span>
                <span id="total-final"><?= number_format($totalFinal, 2) ?> TND</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-success px-5">✅ Confirmer la commande</button>
      <a href="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=annuler"
         class="btn btn-outline-danger">❌ Annuler</a>
    </div>
  </form>

  <!-- Historique -->
  <?php if (!empty($historique)): ?>
  <h4 class="mt-5 fw-bold">📋 Historique récent</h4>
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
        <td><?= number_format($h['total'], 2) ?> TND</span></td>
        <td>
          <span class="badge bg-<?= $h['statut'] === 'confirmee'
            ? 'success' : ($h['statut'] === 'annulee' ? 'danger' : 'warning text-dark') ?>">
            <?= ucfirst($h['statut']) ?>
          </span>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  追赶
  <?php endif; ?>
</div>

<script>
var totalSansLivraison = <?= $totalFinal ?>;

function afficherFormulairePaiement(methode) {
  var cbForm = document.getElementById('cb-form');
  if (methode === 'carte') {
    cbForm.style.display = 'block';
    // Rendre les champs requis
    document.getElementById('carte_numero').required = true;
    document.getElementById('carte_exp').required = true;
    document.getElementById('carte_cvv').required = true;
    document.getElementById('carte_titulaire').required = true;
  } else {
    cbForm.style.display = 'none';
    document.getElementById('carte_numero').required = false;
    document.getElementById('carte_exp').required = false;
    document.getElementById('carte_cvv').required = false;
    document.getElementById('carte_titulaire').required = false;
    // Effacer les messages d'erreur
    document.getElementById('err-carte-num').textContent = '';
    document.getElementById('err-carte-exp').textContent = '';
    document.getElementById('err-carte-cvv').textContent = '';
    document.getElementById('err-carte-titulaire').textContent = '';
  }
}

function formaterNumeroCarte(input) {
  var val = input.value.replace(/\D/g, '').substring(0, 16);
  input.value = val.replace(/(.{4})/g, '$1 ').trim();
  var type = detectCarteType(val);
  document.getElementById('type-carte').textContent = val.length > 0 ? '💳 ' + type : '';
}

function formaterExpiration(input) {
  var val = input.value.replace(/\D/g, '').substring(0, 4);
  if (val.length >= 2) val = val.substring(0, 2) + '/' + val.substring(2);
  input.value = val;
}

function mettreAJourTotalPanier() {
  var frais = parseFloat(document.getElementById('frais_livraison').value) || 0;
  var total = totalSansLivraison + frais;
  document.getElementById('frais_livraison_span').textContent = frais.toFixed(2) + ' TND';
  document.getElementById('total-final').textContent = total.toFixed(2) + ' TND';
}

// Validation du formulaire avant soumission
document.getElementById('form-commande').addEventListener('submit', function(e) {
  var ok = true;
  
  // Validation des champs normaux
  ok = validateNom(document.getElementById('nom_client').value, 'err-nom') && ok;
  ok = validateTelephone(document.getElementById('telephone').value, 'err-tel') && ok;
  ok = validateAdresse(document.getElementById('adresse').value, 'err-adresse') && ok;
  ok = validateSelect(document.getElementById('methode').value, 'err-methode', 'Veuillez choisir une méthode de paiement.') && ok;

  // Validation carte bancaire si méthode = carte
  var methode = document.getElementById('methode').value;
  if (methode === 'carte') {
    var carteNumero = document.getElementById('carte_numero');
    var carteExp = document.getElementById('carte_exp');
    var carteCvv = document.getElementById('carte_cvv');
    var carteTitulaire = document.getElementById('carte_titulaire');
    
    // Vérifier que les champs existent et ne sont pas vides
    if (!carteNumero || !carteNumero.value.trim()) {
      document.getElementById('err-carte-num').textContent = 'Numéro de carte obligatoire.';
      ok = false;
    } else {
      var numClean = carteNumero.value.replace(/\s/g, '');
      if (!validateCarteNumero(numClean, 'err-carte-num')) ok = false;
    }
    
    if (!carteExp || !carteExp.value.trim()) {
      document.getElementById('err-carte-exp').textContent = 'Date d\'expiration obligatoire.';
      ok = false;
    } else {
      if (!validateCarteExpiration(carteExp.value, 'err-carte-exp')) ok = false;
    }
    
    if (!carteCvv || !carteCvv.value.trim()) {
      document.getElementById('err-carte-cvv').textContent = 'CVV obligatoire.';
      ok = false;
    } else {
      if (!validateCarteCVV(carteCvv.value, 'err-carte-cvv')) ok = false;
    }
    
    if (!carteTitulaire || !carteTitulaire.value.trim()) {
      document.getElementById('err-carte-titulaire').textContent = 'Nom du titulaire obligatoire.';
      ok = false;
    } else {
      if (!validateCarteTitulaire(carteTitulaire.value, 'err-carte-titulaire')) ok = false;
    }
  }

  if (!ok) {
    e.preventDefault();
    alert('Veuillez corriger les erreurs dans le formulaire avant de confirmer la commande.');
  }
});

// Initialisation de la carte
document.addEventListener('DOMContentLoaded', function() {
  if (typeof L !== 'undefined') {
    initMap();
  } else {
    var script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = function() {
      initMap();
    };
    document.head.appendChild(script);
  }
});

function initMap() {
  var latitudeMagasin = 36.8065;
  var longitudeMagasin = 10.1815;
  
  var defaultLat = parseFloat(document.getElementById('latitude').value) || latitudeMagasin;
  var defaultLng = parseFloat(document.getElementById('longitude').value) || longitudeMagasin;
  
  var map = L.map('map').setView([defaultLat, defaultLng], 13);
  
  L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    subdomains: 'abcd',
    maxZoom: 19
  }).addTo(map);
  
  var magasinIcon = L.divIcon({
    html: '<div style="background-color:#2d6a2d; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">🏪</div>',
    iconSize: [36, 36],
    className: 'magasin-marker'
  });
  L.marker([latitudeMagasin, longitudeMagasin], {icon: magasinIcon})
    .addTo(map)
    .bindPopup('<strong>🏪 Notre magasin</strong><br>Livraison depuis ce point');
  
  L.circle([latitudeMagasin, longitudeMagasin], {
    color: '#2d6a2d',
    fillColor: '#2d6a2d',
    fillOpacity: 0.1,
    radius: 10000
  }).addTo(map);
  
  var marker = null;
  
  function ajouterMarqueur(lat, lng) {
    if (marker) map.removeLayer(marker);
    var userIcon = L.divIcon({
      html: '<div style="background-color:#f0a500; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-size:18px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">📍</div>',
      iconSize: [32, 32],
      className: 'user-marker'
    });
    marker = L.marker([lat, lng], {icon: userIcon, draggable: true}).addTo(map);
    marker.on('dragend', function(e) {
      var pos = marker.getLatLng();
      calculerFrais(pos.lat, pos.lng);
      geocoderInverse(pos.lat, pos.lng);
    });
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
  }
  
  function calculerFrais(lat, lng) {
    var R = 6371;
    var dLat = (lat - latitudeMagasin) * Math.PI / 180;
    var dLng = (lng - longitudeMagasin) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(latitudeMagasin * Math.PI / 180) * Math.cos(lat * Math.PI / 180) *
            Math.sin(dLng/2) * Math.sin(dLng/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var distance = R * c;
    
    var frais = 0;
    var livrable = true;
    var message = '';
    
    if (distance <= 2) {
      frais = 0;
      message = 'Livraison OFFERTE';
    } else if (distance <= 5) {
      frais = 3.00;
      message = 'Frais de livraison : 3.00 TND';
    } else if (distance <= 10) {
      frais = 5.00;
      message = 'Frais de livraison : 5.00 TND';
    } else {
      livrable = false;
      message = 'Zone non livrable (plus de 10km)';
    }
    
    document.getElementById('distance_info').innerHTML = 'Distance: ' + distance.toFixed(2) + ' km | ' + message;
    document.getElementById('frais_livraison').value = frais;
    
    if (livrable) {
      document.getElementById('distance_info').style.color = '#2d6a2d';
      document.getElementById('zone-info').innerHTML = '<div class="alert alert-success py-1">✅ Zone livrable</div>';
    } else {
      document.getElementById('distance_info').style.color = '#c0392b';
      document.getElementById('zone-info').innerHTML = '<div class="alert alert-danger py-1">❌ Zone non livrable</div>';
    }
    
    if (typeof mettreAJourTotalPanier !== 'undefined') mettreAJourTotalPanier();
  }
  
  function geocoderInverse(lat, lng) {
    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18')
      .then(response => response.json())
      .then(data => {
        if (data && data.display_name) {
          document.getElementById('adresse').value = data.display_name.substring(0, 200);
        }
      })
      .catch(error => console.log('Erreur géocodage:', error));
  }
  
  map.on('click', function(e) {
    ajouterMarqueur(e.latlng.lat, e.latlng.lng);
    calculerFrais(e.latlng.lat, e.latlng.lng);
    geocoderInverse(e.latlng.lat, e.latlng.lng);
  });
  
  setTimeout(function() {
    map.invalidateSize();
  }, 200);
}
</script>

<?php require 'app/view/layout/footer.php'; ?>