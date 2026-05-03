<?php require 'app/view/layout/header.php'; ?>

<div class="container py-4">
  <h2 class="fw-bold text-success mb-4">📊 Tableau de bord statistiques</h2>

  <!-- Cartes KPI -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
          <h3 class="text-success"><?= number_format($statsGlobales['ca_total'] ?? 0, 0) ?> TND</h3>
          <p class="text-muted mb-0">Chiffre d'affaires total</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
          <h3 class="text-primary"><?= $statsGlobales['total_commandes'] ?? 0 ?></h3>
          <p class="text-muted mb-0">Commandes confirmées</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
          <h3 class="text-warning"><?= number_format($statsGlobales['panier_moyen'] ?? 0, 2) ?> TND</h3>
          <p class="text-muted mb-0">Panier moyen</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body">
          <h3 class="text-info"><?= $statsGlobales['commandes_attente'] ?? 0 ?></h3>
          <p class="text-muted mb-0">Commandes à traiter</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">📈 Évolution du CA (30 jours)</div>
        <div class="card-body">
          <canvas id="caChart" height="200"></canvas>
          <a href="/frigo/index.php?mode=back&controller=statistique&action=exportCSV&type=ca" 
             class="btn btn-sm btn-outline-success mt-2">Exporter CSV</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">🥇 Top 10 produits</div>
        <div class="card-body">
          <?php if (!empty($topProduits)): ?>
          <table class="table table-sm">
            <thead>
              <tr><th>Produit</th><th>Quantité</th><th>CA généré</th></tr>
            </thead>
            <tbody>
              <?php foreach ($topProduits as $i => $p): ?>
              <tr>
                <td><?= $i+1 ?>. <?= htmlspecialchars($p['nom']) ?></td>
                <td><?= $p['total_vendu'] ?></td>
                <td><?= number_format($p['revenue'], 2) ?> TND</td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <a href="/frigo/index.php?mode=back&controller=statistique&action=exportCSV&type=produits" 
             class="btn btn-sm btn-outline-success">Exporter CSV</a>
          <?php else: ?>
          <p class="text-muted">Aucune donnée disponible.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-5">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">🥧 Ventes par catégorie</div>
        <div class="card-body">
          <canvas id="categorieChart" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">💳 Par méthode</div>
        <div class="card-body">
          <table class="table table-sm">
            <?php foreach ($ventesParPaiement as $vp): ?>
            <tr>
              <td><?= ucfirst($vp['methode_paiement']) ?></td>
              <td><?= $vp['nb_commandes'] ?> cmd</td>
              <td><?= number_format($vp['total'], 0) ?> TND</td>
            </tr>
            <?php endforeach; ?>
          </table>
          <p class="small text-muted mt-2">Taux conversion: <strong><?= $tauxConversion ?>%</strong></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white fw-bold">⚠️ Alertes</div>
        <div class="card-body">
          <p class="mb-1">📦 Stocks faibles: <strong><?= $statsGlobales['stocks_faibles'] ?? 0 ?></strong> produits</p>
          <p class="mb-1">🍽️ Produits périmés: <strong class="text-danger"><?= $statsGlobales['produits_perimes'] ?? 0 ?></strong></p>
          <p class="mb-0">⏰ Alertes expiration frigo: <strong class="text-warning"><?= $statsGlobales['alerte_expiration'] ?? 0 ?></strong></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique CA
const caCtx = document.getElementById('caChart').getContext('2d');
const caData = <?= json_encode(array_column($caJournalier, 'ca')) ?>;
const caLabels = <?= json_encode(array_map(function($d) { return substr($d['jour'], 5); }, $caJournalier)) ?>;

new Chart(caCtx, {
  type: 'line',
  data: {
    labels: caLabels,
    datasets: [{
      label: 'CA journalier (TND)',
      data: caData,
      borderColor: '#2d6a2d',
      backgroundColor: 'rgba(45, 106, 45, 0.1)',
      fill: true
    }]
  },
  options: { responsive: true, maintainAspectRatio: true }
});

// Graphique catégories
const catCtx = document.getElementById('categorieChart').getContext('2d');
const catData = <?= json_encode(array_column($ventesParCategorie, 'revenue')) ?>;
const catLabels = <?= json_encode(array_column($ventesParCategorie, 'categorie')) ?>;

new Chart(catCtx, {
  type: 'pie',
  data: {
    labels: catLabels,
    datasets: [{
      data: catData,
      backgroundColor: ['#2d6a2d', '#4CAF50', '#8BC34A', '#CDDC39', '#FFC107']
    }]
  }
});
</script>

<?php require 'app/view/layout/footer.php'; ?>