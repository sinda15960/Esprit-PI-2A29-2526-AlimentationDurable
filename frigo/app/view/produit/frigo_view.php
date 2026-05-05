<?php
$includeScan = true;
require 'app/view/layout/header.php';
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="text-success fw-bold mb-0">🧊 Mon Frigo</h1>
    <div class="d-flex gap-2 flex-wrap">
      <!-- Bouton micro pour ajouter par voix -->
      <button class="btn btn-danger btn-sm" id="voice-btn">
        🎤 Ajouter par voix
      </button>
      <span id="voice-status" class="small text-muted align-self-center">🎤 Cliquez sur le micro pour parler</span>
      
      <!-- Bouton micro pour lister le contenu du frigo (IA Voice) -->
      <button class="btn btn-info btn-sm" id="voice-lister-btn" onclick="listerFrigoParVoix()">
        🎤 Lister le contenu du frigo
      </button>
      <span id="voice-lister-status" class="small text-muted align-self-center">🎤 Cliquez pour lister le contenu</span>
      
      <button class="btn btn-outline-success btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#modalScan">
        📷 Scanner un produit
      </button>
      <button class="btn btn-success btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#modalAjout">
        + Ajouter un aliment
      </button>
    </div>
  </div>

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

  <!-- Suggestions intelligentes -->
  <?php if (!empty($suggestions)): ?>
  <div class="card border-warning shadow-sm mb-4">
    <div class="card-header bg-warning text-dark fw-bold">
      🤖 Suggestions basées sur votre frigo
    </div>
    <div class="card-body">
      <div class="row g-2">
        <?php foreach ($suggestions as $s): ?>
          <div class="col-md-4">
            <?php if ($s['type'] === 'stock_faible'): ?>
              <div class="alert alert-warning mb-0 d-flex justify-content-between align-items-center py-2">
                <small><strong>⚠️ Stock faible</strong><br><?= htmlspecialchars($s['message']) ?></small>
                <?php if ($s['produit_id']): ?>
                  <form method="post" action="/frigo/index.php?mode=front&controller=commande&action=ajouterPanier">
                    <input type="hidden" name="produit_id" value="<?= $s['produit_id'] ?>">
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn btn-success btn-sm">+ Panier</button>
                  </form>
                <?php endif; ?>
              </div>
            <?php elseif ($s['type'] === 'expiration_proche'): ?>
              <div class="alert alert-danger mb-0 py-2">
                <small><strong>⏰ Expire bientôt !</strong><br><?= htmlspecialchars($s['message']) ?></small>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
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
                  <?= isset($categorieActive) && $categorieActive == $cat['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nom']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="text-danger small" id="err-cat"></div>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100">Filtrer</button>
          </div>
          <?php if (!empty($categorieActive)): ?>
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
    <div class="alert alert-info">
      🍽️ Votre frigo est vide. Ajoutez des aliments ou cliquez sur "Lister le contenu du frigo" pour une assistance vocale !
    </div>
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
        $alerteStock = $item['quantite'] <= ($item['seuil_alerte'] ?? 2);
        $emoji = !empty($item['emoji']) && $item['emoji'] !== 'auto' && $item['emoji'] !== '🥗' 
            ? $item['emoji'] 
            : getEmojiAliment($item['nom']);
      ?>
      <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm <?= $alerteStock ? 'border border-warning border-2' : '' ?>">
          <div class="card-body">
            <div class="text-center mb-2" style="font-size:3rem;line-height:1">
              <?= $emoji ?>
            </div>
            <h5 class="card-title text-center"><?= htmlspecialchars($item['nom']) ?></h5>
            <?php if (!empty($item['categorie_nom'])): ?>
              <p class="small text-muted text-center mb-1"><?= htmlspecialchars($item['categorie_nom']) ?></p>
            <?php endif; ?>
            <div class="text-center mb-2">
              <span class="badge bg-<?= $badgeClass ?>"><?= $badgeLabel ?></span>
            </div>
            <?php if ($alerteStock): ?>
              <div class="alert alert-warning py-1 px-2 mt-2 mb-1 small">
                ⚠️ Stock faible — ajouté au panier !
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

            <form method="post"
                  action="/frigo/index.php?mode=front&controller=produit&action=modifierQuantiteFrigo"
                  id="form_qte_<?= $item['id'] ?>" class="mt-2">
              <input type="hidden" name="frigo_id" value="<?= $item['id'] ?>">
              <label class="form-label small fw-semibold mb-1">Modifier la quantité</label>
              <div class="input-group input-group-sm">
                <input type="number" name="quantite"
                       value="<?= $item['quantite'] ?>"
                       class="form-control"
                       id="qte_frigo_<?= $item['id'] ?>"
                       min="0">
                <button class="btn btn-outline-primary btn-sm" type="submit">OK</button>
              </div>
              <div class="text-danger small" id="err_qte_<?= $item['id'] ?>"></div>
            </form>
            <script>
            (function(){
              document.getElementById('form_qte_<?= $item['id'] ?>').addEventListener('submit',function(e){
                var qte=parseInt(document.getElementById('qte_frigo_<?= $item['id'] ?>').value);
                var err=document.getElementById('err_qte_<?= $item['id'] ?>');
                err.textContent='';
                if(isNaN(qte)||qte<0){e.preventDefault();err.textContent='Quantité invalide (min. 0).';}
              });
            })();
            </script>

            <div class="d-flex gap-2 mt-2">
              <a href="/frigo/index.php?mode=front&controller=produit&action=supprimerDuFrigo&id=<?= $item['id'] ?>"
                 class="btn btn-outline-danger btn-sm"
                 onclick="return confirm('Retirer du frigo ?')">Retirer</a>
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
            <div class="text-center mb-2" style="font-size:2.5rem"><?= $emoji ?></div>
            <h6 class="fw-bold text-center"><?= htmlspecialchars($p['nom']) ?></h6>
            <p class="small text-muted text-center"><?= htmlspecialchars($p['categorie_nom'] ?? '') ?></p>
            <form method="post"
                  action="/frigo/index.php?mode=front&controller=produit&action=ajouterFrigo"
                  id="form_p_<?= $p['id'] ?>">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <div class="input-group input-group-sm mt-2">
                <input type="number" name="quantite" value="1"
                       class="form-control" id="qte_p_<?= $p['id'] ?>">
                <button class="btn btn-outline-success btn-sm" type="submit">+ Frigo</button>
              </div>
              <div class="text-danger small" id="err_p_<?= $p['id'] ?>"></div>
            </form>
            <script>
            (function(){
              document.getElementById('form_p_<?= $p['id'] ?>').addEventListener('submit',function(e){
                var qte=parseInt(document.getElementById('qte_p_<?= $p['id'] ?>').value);
                var err=document.getElementById('err_p_<?= $p['id'] ?>');
                err.textContent='';
                if(isNaN(qte)||qte<1){e.preventDefault();err.textContent='Quantité invalide.';}
              });
            })();
            </script>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal Scan QR/Code-barres -->
<div class="modal fade" id="modalScan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">📷 Scanner un produit</h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal" onclick="stopScan()"></button>
      </div>
      <div class="modal-body text-center">
        <p class="text-muted small mb-3">
          Pointez votre caméra vers le code-barres ou QR code du produit
        </p>
        <div id="qr-reader" style="width:100%"></div>
        <div id="scan-result" class="mt-3"></div>
        <div id="scan-status" class="text-muted small mt-2">
          En attente du scan...
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ajout manuel -->
<div class="modal fade" id="modalAjout" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">+ Ajouter un aliment manuellement</h5>
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
          <!-- Choix emoji -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Choisir un emoji</label>
            <div class="text-center mb-2">
              <span id="emoji-preview" style="font-size:3rem">🥗</span>
            </div>
            <div class="d-flex flex-wrap gap-1 justify-content-center">
              <?php
                $emojisList = [
                  '🍎','🍌','🍊','🍓','🍇','🥭','🍅','🥕','🥒',
                  '🥬','🫑','🧅','🥛','🍶','🧀','🧈','🍦','🍗',
                  '🥩','🌭','🐟','💧','🧃','🥤','☕','🍵','🍋',
                  '🍝','🍚','🫙','🍬','🍞','🥐','🍪','🥚','🧄',
                  '🥗','🍕','🌮','🥙','🥜','🫐','🍑','🍒','🥝'
                ];
                foreach ($emojisList as $em):
              ?>
                <button type="button"
                        class="btn btn-outline-secondary btn-sm emoji-btn"
                        style="font-size:1.3rem;padding:3px 7px"
                        data-emoji="<?= $em ?>"
                        onclick="choisirEmoji('<?= $em ?>', this)">
                  <?= $em ?>
                </button>
              <?php endforeach; ?>
            </div>
            <input type="hidden" name="emoji" id="emoji-choisi" value="🥗">
          </div>
          <button type="submit" class="btn btn-success w-100">Ajouter au frigo</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// ===== Emoji picker =====
function choisirEmoji(emoji, btn) {
  document.getElementById('emoji-preview').textContent = emoji;
  document.getElementById('emoji-choisi').value = emoji;
  document.querySelectorAll('.emoji-btn').forEach(function(b){
    b.classList.remove('btn-success');
    b.classList.add('btn-outline-secondary');
  });
  btn.classList.remove('btn-outline-secondary');
  btn.classList.add('btn-success');
}

document.getElementById('m-nom').addEventListener('input', function(){
  var nom = this.value.toLowerCase();
  var emojis = {
    'pomme':'🍎','banane':'🍌','orange':'🍊','fraise':'🍓','raisin':'🍇',
    'mangue':'🥭','tomate':'🍅','carotte':'🥕','courgette':'🥒','salade':'🥬',
    'poivron':'🫑','oignon':'🧅','lait':'🥛','yaourt':'🍶','fromage':'🧀',
    'beurre':'🧈','creme':'🍦','crème':'🍦','poulet':'🍗','boeuf':'🥩',
    'merguez':'🌭','thon':'🐟','eau':'💧','jus':'🧃','coca':'🥤',
    'cafe':'☕','café':'☕','the':'🍵','thé':'🍵','limonade':'🍋',
    'pates':'🍝','pâtes':'🍝','riz':'🍚','huile':'🫙','sucre':'🍬',
    'pain':'🍞','croissant':'🥐','biscuit':'🍪','kiwi':'🥝','ananas':'🍍',
    'cerise':'🍒','myrtille':'🫐','noix':'🥜','chocolat':'🍫'
  };
  var emoji = '🥗';
  for (var mot in emojis) {
    if (nom.includes(mot)) { emoji = emojis[mot]; break; }
  }
  document.getElementById('emoji-preview').textContent = emoji;
  document.getElementById('emoji-choisi').value = emoji;
});

// ===== Validation =====
document.getElementById('form-manuel').addEventListener('submit', function(e){
  var ok = true;
  ok = validateNom(document.getElementById('m-nom').value, 'err-m-nom') && ok;
  ok = validateQuantite(document.getElementById('m-qte').value, 'err-m-qte') && ok;
  ok = validateDate(document.getElementById('m-date').value, 'err-m-date') && ok;
  if(!ok) e.preventDefault();
});

document.getElementById('form-filtre').addEventListener('submit', function(e){
  var cat = document.getElementById('sel-cat').value;
  var err = document.getElementById('err-cat');
  err.textContent = '';
  if(!cat){ e.preventDefault(); err.textContent = 'Veuillez sélectionner une catégorie.'; }
});

// ===== Scan QR/Code-barres =====
var html5QrCode = null;
var scanActive  = false;

document.getElementById('modalScan').addEventListener('shown.bs.modal', function(){
  if (!html5QrCode) {
    html5QrCode = new Html5Qrcode("qr-reader");
  }
  if (scanActive) return;

  var config = { fps: 10, qrbox: { width: 280, height: 180 } };

  html5QrCode.start(
    { facingMode: "environment" },
    config,
    function(decodedText) {
      document.getElementById('scan-result').innerHTML =
        '<div class="alert alert-success">✅ Code détecté : ' + decodedText + '</div>';
      document.getElementById('scan-status').textContent = 'Redirection...';
      stopScan();

      var produitId = null;
      var matchProd = decodedText.match(/PROD[:\s]*(\d+)/i);
      if (matchProd) {
        produitId = matchProd[1];
      } else if (/^\d+$/.test(decodedText)) {
        produitId = decodedText;
      }

      if (produitId) {
        window.location.href =
          '/frigo/index.php?mode=front&controller=produit&action=ajouterParScan&produit_id=' + produitId;
      } else {
        window.location.href =
          '/frigo/index.php?mode=front&controller=produit&action=ajouterParScan&code_barres=' + decodedText;
      }
    },
    function(errorMessage) { }
  );
  scanActive = true;
});

document.getElementById('modalScan').addEventListener('hidden.bs.modal', function(){
  stopScan();
});

function stopScan() {
  if (html5QrCode && scanActive) {
    html5QrCode.stop().then(function(){ scanActive = false; }).catch(function(){});
  }
}
</script>

<!-- Script voix off pour ajouter par voix -->
<script src="/frigo/public/js/voice.js"></script>

<!-- Script voix off pour lister le contenu du frigo -->
<script src="/frigo/public/js/voice-frigo.js"></script>

<?php require 'app/view/layout/footer.php'; ?>