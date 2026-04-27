<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-success fw-bold mb-0">Mon Frigo</h1>
    <div class="d-flex gap-2">
      <button class="btn btn-success"
              data-bs-toggle="modal"
              data-bs-target="#modalAjout">
        + Ajouter un aliment
      </button>
      <button class="btn btn-info text-white"
              data-bs-toggle="modal"
              data-bs-target="#modalQR">
        📷 Scanner QR Code
      </button>
    </div>
  </div>

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

  <!-- Filtre par catégorie -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <form method="post"
            action="/frigo/index.php?mode=front&controller=produit&action=frigo"
            id="form-filtre">
        <div class="row g-3 align-items-end">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Filtrer par catégorie :</label>
            <select name="categorie_id" class="form-select" id="sel-cat">
              <option value="">-- Toutes les catégories --</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                  <?= isset($categorieActive) && $categorieActive == $cat['id']
                      ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nom']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="text-danger small" id="err-cat"></div>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100">Filtrer</button>
          </div>
          <?php if ($categorieActive): ?>
          <div class="col-md-3">
            <a href="/frigo/index.php?mode=front&controller=produit&action=frigo"
               class="btn btn-outline-secondary w-100">Voir tout</a>
          </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Contenu du frigo -->
  <h4 class="fw-bold text-success mb-3">Contenu du frigo</h4>

  <?php if (empty($frigoItems)): ?>
    <div class="alert alert-info">Votre frigo est vide. Ajoutez des aliments !</div>
  <?php else: ?>
  <div class="row g-3 mb-5">
    <?php foreach ($frigoItems as $item): ?>
      <?php
        $badgeClass  = match($item['etat']) {
          'perime'         => 'danger',
          'bientot_perime' => 'warning text-dark',
          default          => 'success'
        };
        $badgeLabel  = match($item['etat']) {
          'perime'         => 'Périmé',
          'bientot_perime' => 'Bientôt périmé',
          default          => 'Frais'
        };
        $alerteStock = $item['quantite'] <= 2;
        $emoji       = getEmojiAliment($item['nom']);
      ?>
      <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm
          <?= $alerteStock ? 'border border-warning border-2' : '' ?>">
          <div class="card-body">

            <!-- Emoji de l'aliment -->
            <div class="text-center mb-2">
              <?php if (!empty($item['image'])): ?>
                <img src="/frigo/public/images/uploads/<?= htmlspecialchars($item['image']) ?>"
                     style="width:80px;height:80px;object-fit:cover;
                            border-radius:50%;border:2px solid #2d6a2d"
                     alt="<?= htmlspecialchars($item['nom']) ?>">
              <?php else: ?>
                <div style="font-size:3rem;line-height:1">
                  <?= $emoji ?>
                </div>
              <?php endif; ?>
            </div>

            <h5 class="card-title text-center">
              <?= htmlspecialchars($item['nom']) ?>
            </h5>

            <?php if (!empty($item['categorie_nom'])): ?>
              <p class="small text-muted text-center mb-1">
                <?= htmlspecialchars($item['categorie_nom']) ?>
              </p>
            <?php endif; ?>

            <div class="text-center mb-2">
              <span class="badge bg-<?= $badgeClass ?>">
                <?= $badgeLabel ?>
              </span>
            </div>

            <?php if ($alerteStock): ?>
              <div class="alert alert-warning py-1 px-2 mt-2 mb-1 small">
                Stock faible — pensez à acheter !
              </div>
            <?php endif; ?>

            <p class="mt-2 mb-1">
              <strong>Quantité :</strong>
              <span class="<?= $item['quantite'] <= 1 ? 'text-danger fw-bold' : '' ?>">
                <?= $item['quantite'] ?>
              </span>
            </p>

            <?php if ($item['date_expiration']): ?>
              <p class="small text-muted">
                Exp : <?= date('d/m/Y', strtotime($item['date_expiration'])) ?>
              </p>
            <?php endif; ?>

            <!-- Modifier quantité -->
            <form method="post"
                  action="/frigo/index.php?mode=front&controller=produit&action=modifierQuantiteFrigo"
                  id="form_qte_<?= $item['id'] ?>" class="mt-2">
              <input type="hidden" name="frigo_id" value="<?= $item['id'] ?>">
              <label class="form-label small fw-semibold mb-1">
                Modifier la quantité
              </label>
              <div class="input-group input-group-sm">
                <input type="number" name="quantite"
                       value="<?= $item['quantite'] ?>"
                       class="form-control"
                       id="qte_frigo_<?= $item['id'] ?>"
                       min="0">
                <button class="btn btn-outline-primary btn-sm" type="submit">
                  OK
                </button>
              </div>
              <div class="text-danger small" id="err_qte_<?= $item['id'] ?>"></div>
              <script>
              (function(){
                var form = document.getElementById('form_qte_<?= $item['id'] ?>');
                form.addEventListener('submit', function(e){
                  var qte = parseInt(
                    document.getElementById('qte_frigo_<?= $item['id'] ?>').value
                  );
                  var err = document.getElementById('err_qte_<?= $item['id'] ?>');
                  err.textContent = '';
                  if(isNaN(qte) || qte < 0){
                    e.preventDefault();
                    err.textContent = 'Quantité invalide (min. 0).';
                  }
                });
              })();
              </script>
            </form>

            <!-- Actions -->
            <div class="d-flex gap-2 mt-2">
              <a href="/frigo/index.php?mode=front&controller=produit&action=supprimerDuFrigo&id=<?= $item['id'] ?>"
                 class="btn btn-outline-danger btn-sm"
                 onclick="return confirm('Retirer du frigo ?')">
                Retirer
              </a>
              <a href="/frigo/index.php?mode=front&controller=produit&action=envoyerAuPanier&id=<?= $item['id'] ?>"
                 class="btn btn-outline-success btn-sm">
                + Panier
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Ajouter depuis le supermarché -->
  <h4 class="fw-bold text-success mb-3">Ajouter depuis le supermarché</h4>
  <div class="row g-3">
    <?php foreach ($produits as $p): ?>
      <?php $emoji = getEmojiAliment($p['nom']); ?>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">

            <!-- Emoji produit -->
            <div class="text-center mb-2" style="font-size:2.5rem">
              <?= $emoji ?>
            </div>

            <h6 class="fw-bold text-center">
              <?= htmlspecialchars($p['nom']) ?>
            </h6>
            <p class="small text-muted text-center">
              <?= htmlspecialchars($p['categorie_nom'] ?? '') ?>
            </p>
            <p class="text-success fw-bold text-center">
              <?= number_format($p['prix'], 2) ?> TND
            </p>

            <form method="post"
                  action="/frigo/index.php?mode=front&controller=produit&action=ajouterFrigo"
                  id="form_<?= $p['id'] ?>">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <div class="input-group input-group-sm mt-2">
                <input type="number" name="quantite" value="1"
                       class="form-control" id="qte_<?= $p['id'] ?>">
                <button class="btn btn-outline-success btn-sm" type="submit">
                  + Frigo
                </button>
              </div>
              <div class="text-danger small" id="err_<?= $p['id'] ?>"></div>
              <script>
              (function(){
                var form = document.getElementById('form_<?= $p['id'] ?>');
                form.addEventListener('submit', function(e){
                  var qte = parseInt(
                    document.getElementById('qte_<?= $p['id'] ?>').value
                  );
                  var err = document.getElementById('err_<?= $p['id'] ?>');
                  err.textContent = '';
                  if(isNaN(qte) || qte < 1){
                    e.preventDefault();
                    err.textContent = 'Quantité invalide.';
                  }
                });
              })();
              </script>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal ajout manuel -->
<div class="modal fade" id="modalAjout" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Ajouter un aliment manuellement</h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post"
              action="/frigo/index.php?mode=front&controller=produit&action=ajouterManuel"
              id="form-manuel">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de l'aliment</label>
            <input type="text" name="nom_custom" class="form-control" id="m-nom">
            <div class="text-danger small" id="err-m-nom"></div>
          </div>
          
          <!-- Sélecteur d'emoji -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Choisir un emoji pour cet aliment</label>
            <div class="row g-2">
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍎">🍎</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍌">🍌</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍊">🍊</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍓">🍓</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🥦">🥦</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🥕">🥕</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍅">🍅</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🥛">🥛</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🧀">🧀</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🥩">🥩</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍗">🍗</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🥤">🥤</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍞">🍞</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍚">🍚</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍝">🍝</div>
              </div>
              <div class="col-2">
                <div class="emoji-option text-center p-2 border rounded" style="cursor:pointer;font-size:1.8rem" data-emoji="🍪">🍪</div>
              </div>
            </div>
            <input type="hidden" name="emoji" id="selected-emoji" value="🥗">
            <div class="text-center mt-2" id="emoji-preview" style="font-size:3rem">🥗</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Quantité</label>
            <input type="text" name="quantite" class="form-control" id="m-qte">
            <div class="text-danger small" id="err-m-qte"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Date d'expiration</label>
            <input type="text" name="date_expiration" class="form-control"
                   id="m-date" placeholder="YYYY-MM-DD">
            <div class="text-danger small" id="err-m-date"></div>
          </div>

          <button type="submit" class="btn btn-success w-100">
            Ajouter au frigo
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="modalQR" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Scanner un QR Code</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="qr-reader" style="width:100%"></div>
        <div id="qr-result" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// Sélecteur d'emoji
document.querySelectorAll('.emoji-option').forEach(function(opt){
  opt.addEventListener('click', function(){
    var emoji = this.dataset.emoji;
    document.getElementById('selected-emoji').value = emoji;
    document.getElementById('emoji-preview').textContent = emoji;
    // Style de sélection
    document.querySelectorAll('.emoji-option').forEach(function(el){
      el.classList.remove('bg-success', 'bg-opacity-25');
    });
    this.classList.add('bg-success', 'bg-opacity-25');
  });
});

// Mettre à jour l'aperçu selon le nom saisi
document.getElementById('m-nom').addEventListener('input', function(){
  var nom = this.value.toLowerCase();
  var emojis = {
    'pomme':'🍎','banane':'🍌','orange':'🍊','fraise':'🍓',
    'raisin':'🍇','mangue':'🥭','tomate':'🍅','carotte':'🥕',
    'courgette':'🥒','salade':'🥬','poivron':'🫑','oignon':'🧅',
    'lait':'🥛','yaourt':'🍶','fromage':'🧀','beurre':'🧈',
    'creme':'🍦','crème':'🍦','poulet':'🍗','boeuf':'🥩',
    'merguez':'🌭','thon':'🐟','eau':'💧','jus':'🧃',
    'coca':'🥤','cafe':'☕','café':'☕','the':'🍵','thé':'🍵',
    'limonade':'🍋','pates':'🍝','pâtes':'🍝','riz':'🍚',
    'huile':'🫙','sucre':'🍬','pain':'🍞','croissant':'🥐',
    'biscuit':'🍪'
  };
  var emoji = '🥗';
  for (var mot in emojis) {
    if (nom.includes(mot)) { emoji = emojis[mot]; break; }
  }
  document.getElementById('emoji-preview').textContent = emoji;
  document.getElementById('selected-emoji').value = emoji;
});

// Validation formulaire manuel
document.getElementById('form-manuel').addEventListener('submit', function(e){
  let ok = true;
  ok = validateNom(document.getElementById('m-nom').value, 'err-m-nom') && ok;
  ok = validateQuantite(document.getElementById('m-qte').value, 'err-m-qte') && ok;
  ok = validateDate(document.getElementById('m-date').value, 'err-m-date') && ok;
  if(!ok) e.preventDefault();
});

// Validation filtre
document.getElementById('form-filtre').addEventListener('submit', function(e){
  var cat = document.getElementById('sel-cat').value;
  var err = document.getElementById('err-cat');
  err.textContent = '';
  if(!cat){
    e.preventDefault();
    err.textContent = 'Veuillez sélectionner une catégorie.';
  }
});

// QR Code Scanner
const modalQR = document.getElementById('modalQR');
let html5QrCode = null;

modalQR.addEventListener('shown.bs.modal', function() {
  if (html5QrCode === null) {
    html5QrCode = new Html5Qrcode("qr-reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    
    html5QrCode.start({ facingMode: "environment" }, config, (decodedText, decodedResult) => {
      // Extraire l'ID du produit depuis le QR code
      const match = decodedText.match(/id=(\d+)/);
      if (match && match[1]) {
        window.location.href = `/frigo/index.php?mode=front&controller=produit&action=ajouterFrigoQR&id=${match[1]}`;
      } else {
        document.getElementById('qr-result').innerHTML = '<div class="alert alert-danger">QR Code non reconnu</div>';
      }
    }, (errorMessage) => {
      // console.log(errorMessage);
    });
  }
});

modalQR.addEventListener('hidden.bs.modal', function() {
  if (html5QrCode && html5QrCode.isScanning) {
    html5QrCode.stop().then(() => {
      // Scanner arrêté
    });
  }
});
</script>

<?php require 'app/view/layout/footer.php'; ?>