<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-start mb-4">
    <h1 class="fw-bold text-success">Supermarché virtuel</h1>
    
    <!-- Bouton pour afficher/masquer les favoris -->
    <button class="btn btn-outline-warning position-relative" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#favorisCollapse"
            aria-expanded="false"
            aria-controls="favorisCollapse">
      ⭐ Mes Favoris
      <?php if (!empty($favorisListe)): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?= count($favorisListe) ?>
        </span>
      <?php endif; ?>
    </button>
  </div>

  <!-- ========== SECTION SUGGESTIONS IA (IDÉE 3a) ========== -->
  <?php if (!empty($suggestions)): ?>
  <div class="card border-warning shadow-sm mb-4">
    <div class="card-header bg-warning text-dark fw-bold">
      🤖 Suggestions intelligentes basées sur votre frigo
    </div>
    <div class="card-body">
      <div class="row g-3">
        <?php foreach ($suggestions as $suggestion): ?>
          <?php if ($suggestion['type'] === 'stock_faible'): ?>
            <div class="col-md-4">
              <div class="alert alert-warning mb-0 d-flex justify-content-between align-items-center">
                <div>
                  <strong>⚠️ Stock faible</strong><br>
                  <?= htmlspecialchars($suggestion['message']) ?>
                </div>
                <?php if ($suggestion['produit_id']): ?>
                  <form method="post" action="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=ajouterPanier">
                    <input type="hidden" name="produit_id" value="<?= $suggestion['produit_id'] ?>">
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn btn-success btn-sm">+ Panier</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          <?php elseif ($suggestion['type'] === 'expiration_proche'): ?>
            <div class="col-md-4">
              <div class="alert alert-danger mb-0 d-flex justify-content-between align-items-center">
                <div>
                  <strong>⚠️ Expire bientôt !</strong><br>
                  <?= htmlspecialchars($suggestion['message']) ?>
                </div>
                <a href="<?= FRIGO_INDEX ?>?mode=front&controller=produit&action=frigo" class="btn btn-outline-danger btn-sm">Voir</a>
              </div>
            </div>
          <?php elseif ($suggestion['type'] === 'recommandation'): ?>
            <div class="col-md-4">
              <div class="alert alert-info mb-0 d-flex justify-content-between align-items-center">
                <div>
                  <strong>💡 Idée recette / achat</strong><br>
                  <?= htmlspecialchars($suggestion['message']) ?>
                </div>
                <?php if ($suggestion['produit_id']): ?>
                  <form method="post" action="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=ajouterPanier">
                    <input type="hidden" name="produit_id" value="<?= $suggestion['produit_id'] ?>">
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn btn-info btn-sm text-white">+ Panier</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Zone des favoris (collapse) -->
  <div class="collapse mb-4" id="favorisCollapse">
    <div class="card border-warning shadow-sm">
      <div class="card-header bg-warning text-dark fw-bold">
        ⭐ Mes produits favoris
      </div>
      <div class="card-body">
        <?php if (empty($favorisListe)): ?>
          <p class="text-muted mb-0 text-center">
            Vous n'avez pas encore de favoris. Cliquez sur ☆ Ajouter aux favoris pour en ajouter.
          </p>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($favorisListe as $fav): ?>
              <?php $emoji = getEmojiAliment($fav['nom']); ?>
              <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                  <div class="text-center pt-2" style="font-size:2rem">
                    <?= $emoji ?>
                  </div>
                  <div class="card-body p-2 text-center">
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($fav['nom']) ?></h6>
                    <p class="small text-muted mb-1"><?= htmlspecialchars($fav['categorie_nom'] ?? '') ?></p>
                    <p class="text-success fw-bold mb-2"><?= number_format($fav['prix'], 2) ?> TND</p>
                    <div class="d-flex gap-1 justify-content-center">
                      <form method="post" action="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=ajouterPanier" class="d-inline">
                        <input type="hidden" name="produit_id" value="<?= $fav['id'] ?>">
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="btn btn-success btn-sm">+ Panier</button>
                      </form>
                      <a href="<?= FRIGO_INDEX ?>?mode=front&controller=favori&action=supprimer&produit_id=<?= $fav['id'] ?>&redirect=categorie%26action%3Dindex"
                         class="btn btn-outline-danger btn-sm"
                         onclick="return confirm('Retirer des favoris ?')">
                        ✕ Retirer
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
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

  <!-- Catégories avec emojis -->
  <?php
    $catEmojis = [
      'fruits'           => '🍎',
      'légumes'          => '🥦',
      'legumes'          => '🥦',
      'produits laitiers'=> '🥛',
      'viandes'          => '🥩',
      'boissons'         => '🥤',
      'épicerie'         => '🛒',
      'epicerie'         => '🛒',
      'boulangerie'      => '🍞',
    ];
  ?>
  <div class="row g-3 mb-4">
    <?php foreach ($categories as $cat): ?>
      <?php
        $catNomLower = strtolower($cat['nom']);
        $catEmoji    = '🏪';
        foreach ($catEmojis as $mot => $em) {
          if (str_contains($catNomLower, $mot)) {
            $catEmoji = $em;
            break;
          }
        }
      ?>
      <div class="col-md-3">
        <a href="<?= FRIGO_INDEX ?>?mode=front&controller=categorie&action=index&cat_id=<?= $cat['id'] ?>"
           class="text-decoration-none">
          <div class="card text-center border-0 shadow-sm h-100
            <?= $categorieActive == $cat['id'] ? 'border border-success border-2' : '' ?>">
            <div class="card-body">
              <div style="font-size:2rem"><?= $catEmoji ?></div>
              <h6 class="mt-2 fw-bold text-success">
                <?= htmlspecialchars($cat['nom']) ?>
              </h6>
              <p class="small text-muted">
                <?= htmlspecialchars($cat['description'] ?? '') ?>
              </p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Produits -->
  <?php if (!empty($produits)): ?>
    <h4 class="fw-bold mb-3">Produits disponibles</h4>
    <div class="row g-3">
      <?php foreach ($produits as $p): ?>
        <?php
          $emoji    = getEmojiAliment($p['nom']);
          $estFavori = in_array($p['id'], $favorisIds);
        ?>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="text-center pt-3" style="font-size:2.5rem">
              <?= $emoji ?>
            </div>
            <div class="card-body">
              <h6 class="fw-bold text-center">
                <?= htmlspecialchars($p['nom']) ?>
              </h6>
              <p class="text-success fw-bold text-center">
                <?= number_format($p['prix'], 2) ?> TND
              </p>
              <p class="small text-muted text-center">
                <?= htmlspecialchars($p['description'] ?? '') ?>
              </p>

              <!-- Ajouter au panier -->
              <form method="post"
                    action="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=ajouterPanier"
                    id="form_<?= $p['id'] ?>">
                <input type="hidden" name="produit_id" value="<?= $p['id'] ?>">
                <div class="input-group input-group-sm mb-2">
                  <input type="number" name="quantite" value="1"
                         class="form-control" id="qte_<?= $p['id'] ?>">
                  <button class="btn btn-success btn-sm" type="submit">
                    + Panier
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
                      err.textContent = 'Quantité invalide (min. 1).';
                    }
                  });
                })();
                </script>
              </form>

              <!-- Bouton Favoris -->
              <?php if ($estFavori): ?>
                <a href="<?= FRIGO_INDEX ?>?mode=front&controller=favori&action=supprimer&produit_id=<?= $p['id'] ?>&redirect=categorie%26action%3Dindex%26cat_id%3D<?= $categorieActive ?>"
                   class="btn btn-warning btn-sm w-100 mt-1">
                  ⭐ Retirer des favoris
                </a>
              <?php else: ?>
                <a href="<?= FRIGO_INDEX ?>?mode=front&controller=favori&action=ajouter&produit_id=<?= $p['id'] ?>&redirect=categorie%26action%3Dindex%26cat_id%3D<?= $categorieActive ?>"
                   class="btn btn-outline-warning btn-sm w-100 mt-1">
                  ☆ Ajouter aux favoris
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php elseif ($categorieActive): ?>
    <div class="alert alert-info">Aucun produit dans cette catégorie.</div>
  <?php endif; ?>
</div>

<?php require 'app/view/layout/footer.php'; ?>