<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">📊 Statistiques & Rapports</h2>

  <!-- KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">📦</div>
          <h3 class="fw-bold text-success"><?= number_format($statsGlobales['total_commandes'] ?? 0) ?></h3>
          <p class="text-muted mb-0">Total commandes</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">💰</div>
          <h3 class="fw-bold text-success">
            <?= number_format($statsGlobales['ca_total'] ?? 0, 2) ?> TND
          </h3>
          <p class="text-muted mb-0">Chiffre d'affaires total</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">🛒</div>
          <h3 class="fw-bold text-success">
            <?= number_format($statsGlobales['panier_moyen'] ?? 0, 2) ?> TND
          </h3>
          <p class="text-muted mb-0">Panier moyen</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">✅</div>
          <h3 class="fw-bold text-success"><?= number_format($statsGlobales['commandes_confirmees'] ?? 0) ?></h3>
          <p class="text-muted mb-0">Commandes confirmées</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Deuxième ligne de KPIs -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">⏳</div>
          <h3 class="fw-bold text-warning"><?= number_format($statsGlobales['commandes_attente'] ?? 0) ?></h3>
          <p class="text-muted mb-0">En attente</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">❌</div>
          <h3 class="fw-bold text-danger"><?= number_format($statsGlobales['commandes_annulees'] ?? 0) ?></h3>
          <p class="text-muted mb-0">Commandes annulées</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm kpi-card text-center">
        <div class="card-body">
          <div style="font-size:2rem">🎯</div>
          <h3 class="fw-bold text-info"><?= $tauxConversion ?>%</h3>
          <p class="text-muted mb-0">Taux de conversion</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Alertes -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="alert alert-warning">
        ⚠️ <strong><?= $statsGlobales['stocks_faibles'] ?? 0 ?></strong> produits en stock faible
      </div>
    </div>
    <div class="col-md-4">
      <div class="alert alert-danger">
        🗑️ <strong><?= $statsGlobales['produits_perimes'] ?? 0 ?></strong> produits périmés
      </div>
    </div>
    <div class="col-md-4">
      <div class="alert alert-info">
        ⏰ <strong><?= $statsGlobales['alerte_expiration'] ?? 0 ?></strong> aliments expirent bientôt
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- CA par jour -->
    <div class="col-md-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between">
          <span>📈 Chiffre d'affaires (30 derniers jours)</span>
          <a href="/frigo/index.php?mode=back&controller=statistique&action=exportCSV&type=ca"
             class="btn btn-light btn-sm">⬇️ CSV</a>
        </div>
        <div class="card-body">
          <?php if (empty($caJournalier)): ?>
            <p class="text-muted text-center">Aucune donnée disponible</p>
          <?php else: ?>
            <canvas id="caChart" style="max-height:300px"></canvas>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Ventes par catégorie -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
          🥗 Ventes par catégorie
        </div>
        <div class="card-body">
          <?php if (empty($ventesParCategorie)): ?>
            <p class="text-muted text-center">Aucune donnée disponible</p>
          <?php else: ?>
            <canvas id="catChart" style="max-height:250px"></canvas>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Top produits -->
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between">
          <span>🏆 Top 10 produits vendus</span>
          <a href="/frigo/index.php?mode=back&controller=statistique&action=exportCSV&type=produits"
             class="btn btn-light btn-sm">⬇️ CSV</a>
        </div>
        <div class="card-body p-0">
          <?php if (empty($topProduits) || array_sum(array_column($topProduits, 'total_vendu')) == 0): ?>
            <p class="text-muted text-center p-3">Aucun produit vendu pour le moment</p>
          <?php else: ?>
            <table class="table table-hover mb-0">
              <thead class="table-success">
                <tr><th>Produit</th><th>Prix</th><th>Qté vendue</th><th>Revenu</th></tr>
              </thead>
              <tbody>
                <?php foreach ($topProduits as $p): ?>
                  <?php if ($p['total_vendu'] > 0): ?>
                  <tr>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= number_format($p['prix'], 2) ?> TND</td>
                    <td><?= $p['total_vendu'] ?></td>
                    <td><?= number_format($p['revenue'], 2) ?> TND</td>
                  </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Méthodes de paiement -->
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between">
          <span>💳 Méthodes de paiement</span>
          <a href="/frigo/index.php?mode=back&controller=statistique&action=exportCSV&type=paiement"
             class="btn btn-light btn-sm">⬇️ CSV</a>
        </div>
        <div class="card-body">
          <?php if (empty($ventesParPaiement)): ?>
            <p class="text-muted text-center">Aucune donnée disponible</p>
          <?php else: ?>
            <canvas id="paiementChart" style="max-height:200px"></canvas>
            <div class="mt-3">
              <?php foreach ($ventesParPaiement as $paiement): ?>
                <div class="d-flex justify-content-between small mb-1">
                  <span>
                    <?= $paiement['methode_paiement'] === 'especes' ? '💵 Espèces' : 
                       ($paiement['methode_paiement'] === 'carte' ? '💳 Carte bancaire' : '🏦 Virement') ?>
                  </span>
                  <span class="fw-bold"><?= $paiement['nb_commandes'] ?> cmd</span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Taux de conversion détaillé -->
    <div class="col-md-3">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-header bg-success text-white fw-bold">
          📊 Répartition des commandes
        </div>
        <div class="card-body">
          <canvas id="statutChart" style="max-height:200px"></canvas>
          <div class="mt-3">
            <div class="d-flex justify-content-between small">
              <span><span class="badge bg-success">✓</span> Confirmées</span>
              <span><?= number_format($statsGlobales['commandes_confirmees'] ?? 0) ?></span>
            </div>
            <div class="d-flex justify-content-between small">
              <span><span class="badge bg-warning text-dark">⏳</span> En attente</span>
              <span><?= number_format($statsGlobales['commandes_attente'] ?? 0) ?></span>
            </div>
            <div class="d-flex justify-content-between small">
              <span><span class="badge bg-danger">✗</span> Annulées</span>
              <span><?= number_format($statsGlobales['commandes_annulees'] ?? 0) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// CA par jour
<?php if (!empty($caJournalier)): ?>
var caLabels = <?= json_encode(array_column($caJournalier, 'jour')) ?>;
var caData   = <?= json_encode(array_column($caJournalier, 'ca')) ?>;

new Chart(document.getElementById('caChart'), {
  type: 'line',
  data: {
    labels: caLabels,
    datasets: [{
      label: 'CA (TND)',
      data: caData,
      borderColor: '#2d6a2d',
      backgroundColor: 'rgba(45,106,45,0.1)',
      fill: true,
      tension: 0.4
    }]
  },
  options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } } }
});
<?php endif; ?>

// Catégories
<?php if (!empty($ventesParCategorie)): ?>
var catLabels = <?= json_encode(array_column($ventesParCategorie, 'categorie')) ?>;
var catData   = <?= json_encode(array_column($ventesParCategorie, 'revenue')) ?>;

new Chart(document.getElementById('catChart'), {
  type: 'doughnut',
  data: {
    labels: catLabels,
    datasets: [{
      data: catData,
      backgroundColor: ['#2d6a2d','#f0a500','#c0392b','#3498db','#9b59b6','#1abc9c','#e67e22','#2ecc71']
    }]
  },
  options: { responsive: true, maintainAspectRatio: true }
});
<?php endif; ?>

// Paiements
<?php if (!empty($ventesParPaiement)): ?>
var paiLabels = <?= json_encode(array_map(function($v) {
    return $v['methode_paiement'] === 'especes' ? 'Espèces' : ($v['methode_paiement'] === 'carte' ? 'Carte' : 'Virement');
}, $ventesParPaiement)) ?>;
var paiData   = <?= json_encode(array_column($ventesParPaiement, 'nb_commandes')) ?>;

new Chart(document.getElementById('paiementChart'), {
  type: 'pie',
  data: {
    labels: paiLabels,
    datasets: [{
      data: paiData,
      backgroundColor: ['#2d6a2d','#f0a500','#3498db']
    }]
  },
  options: { responsive: true, maintainAspectRatio: true }
});
<?php endif; ?>

// Statut des commandes
<?php if (($statsGlobales['commandes_confirmees'] ?? 0) > 0 || ($statsGlobales['commandes_attente'] ?? 0) > 0 || ($statsGlobales['commandes_annulees'] ?? 0) > 0): ?>
var statutLabels = ['Confirmées', 'En attente', 'Annulées'];
var statutData = [
  <?= $statsGlobales['commandes_confirmees'] ?? 0 ?>,
  <?= $statsGlobales['commandes_attente'] ?? 0 ?>,
  <?= $statsGlobales['commandes_annulees'] ?? 0 ?>
];

new Chart(document.getElementById('statutChart'), {
  type: 'doughnut',
  data: {
    labels: statutLabels,
    datasets: [{
      data: statutData,
      backgroundColor: ['#2d6a2d', '#f0a500', '#c0392b']
    }]
  },
  options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
});
<?php endif; ?>
</script>

<?php require 'app/view/layout/footer.php'; ?>