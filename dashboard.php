<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/model/Allergie.php';
require_once __DIR__ . '/model/Traitement.php';

$allergies = Allergie::findAll();
$traitements = Traitement::findAll();

$dons = [];
$associations = [];
$totalAssociations = 0;
$totalDons = 0;
$totalMontant = 0.0;
$statsParStatut = ['pending' => 0, 'confirmed' => 0, 'delivered' => 0, 'cancelled' => 0];
$donsMonetaires = 0;
$donsAlimentaires = 0;
$donsEquipement = 0;
$donationsLoadError = null;

try {
    require_once __DIR__ . '/Association.php';
    require_once __DIR__ . '/Don.php';
    $associationModel = new Association();
    $donModel = new Don();
    $associations = $associationModel->findAll();
    $totalAssociations = count($associations);
    $dons = $donModel->getDonsWithAssociation();
    $totalDons = count($dons);
    $totalMontant = (float) $donModel->getTotalDonations();
    foreach ($dons as $don) {
        $st = $don['status'] ?? '';
        if (isset($statsParStatut[$st])) {
            $statsParStatut[$st]++;
        }
        $type = $don['donation_type'] ?? '';
        if ($type === 'monetary') {
            $donsMonetaires++;
        } elseif ($type === 'food') {
            $donsAlimentaires++;
        } elseif ($type === 'equipment') {
            $donsEquipement++;
        }
    }
} catch (Throwable $e) {
    $donationsLoadError = $e->getMessage();
}

$donsRecents = array_slice($dons, 0, 8);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — NutriFlow Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4ec; color: #1a1a1a; }
        .header { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .header h1 { font-size: 1.35rem; font-weight: 600; }
        .header-actions { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
        .header-actions a { color: white; text-decoration: none; background: rgba(255,255,255,0.18); padding: 0.45rem 0.9rem; border-radius: 8px; font-size: 0.9rem; }
        .header-actions a:hover { background: rgba(255,255,255,0.28); }
        .logout { background: rgba(180,40,40,0.85) !important; }
        .container { max-width: 1400px; margin: 0 auto; padding: 1.5rem 2rem 3rem; }
        .stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: white; padding: 1.25rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e0e8d8; }
        .stat-number { font-size: 1.75rem; font-weight: 700; color: #2d5016; }
        .stat-label { font-size: 0.85rem; color: #555; margin-top: 0.35rem; }
        .section { background: white; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border: 1px solid #e8eee0; }
        .section h2 { color: #2d5016; margin-bottom: 1rem; border-left: 4px solid #4a7c2b; padding-left: 0.75rem; font-size: 1.15rem; }
        .section-toolbar { margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 0.65rem 0.5rem; text-align: left; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
        th { background: #f4f7ef; color: #2d5016; font-weight: 600; }
        tr:hover td { background: #fafcf8; }
        .btn { padding: 0.35rem 0.75rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; margin: 0 0.15rem 0.15rem 0; font-size: 0.8rem; }
        .btn-add { background: #4a7c2b; color: white; }
        .btn-edit { background: #1976d2; color: white; }
        .btn-delete { background: #c62828; color: white; }
        .btn-secondary { background: #5f6368; color: white; }
        .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; text-transform: capitalize; }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        .badge-pending { background: #fff8e1; color: #f57f17; }
        .badge-confirmed { background: #e8f5e9; color: #2e7d32; }
        .badge-delivered { background: #e3f2fd; color: #1565c0; }
        .badge-cancelled { background: #ffebee; color: #c62828; }
        .alert { padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-warn { background: #fff8e1; border: 1px solid #ffe082; color: #6d4c41; }
        .alert-ok { background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; }
        .muted { color: #666; font-size: 0.85rem; }
        .text-clip { max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @media (max-width: 768px) {
            th, td { font-size: 0.75rem; padding: 0.45rem 0.3rem; }
            .text-clip { max-width: 120px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>NutriFlow — Administration</h1>
        <div class="header-actions">
            <a href="form.php">Faire un don</a>
            <a href="list.php">Associations</a>
            <a href="traitements.php">Traitements (site)</a>
            <a href="gestion_allergies.php">Allergies (MVC)</a>
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <?php if ($donationsLoadError): ?>
            <div class="alert alert-warn">
                Section dons / associations non chargée (vérifiez les tables <code>dons</code> et <code>associations</code> dans la base) :
                <?= htmlspecialchars($donationsLoadError) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-ok"><?= htmlspecialchars((string) $_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-warn"><?= htmlspecialchars((string) $_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= count($allergies) ?></div>
                <div class="stat-label">Allergies</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($traitements) ?></div>
                <div class="stat-label">Traitements</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= (int) $totalAssociations ?></div>
                <div class="stat-label">Associations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= (int) $totalDons ?></div>
                <div class="stat-label">Dons enregistrés</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($totalMontant, 2) ?> DT</div>
                <div class="stat-label">Montant (hors annulés)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= (int) $donsAlimentaires ?></div>
                <div class="stat-label">Dons alimentaires</div>
            </div>
        </div>

        <div class="section">
            <h2>Gestion des allergies</h2>
            <div class="section-toolbar">
                <a href="ajouter_allergie.php" class="btn btn-add">+ Ajouter une allergie</a>
            </div>
            <table>
                <thead>
                    <tr><th>Nom</th><th>Catégorie</th><th>Gravité</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($allergies as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a->getNom()) ?></td>
                        <td><?= htmlspecialchars($a->getCategorie()) ?></td>
                        <td><span class="badge badge-<?= htmlspecialchars($a->getGravite()) ?>"><?= htmlspecialchars($a->getGravite()) ?></span></td>
                        <td>
                            <a href="modifier_allergie.php?id=<?= (int) $a->getId() ?>" class="btn btn-edit">Modifier</a>
                            <a href="supprimer_allergie.php?id=<?= (int) $a->getId() ?>" class="btn btn-delete" onclick="return confirm('Supprimer cette allergie ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($allergies) === 0): ?>
                    <tr><td colspan="4" class="muted">Aucune allergie.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Traitements (aperçu)</h2>
            <div class="section-toolbar">
                <a href="traitements.php" class="btn btn-secondary">Ouvrir la liste complète</a>
            </div>
            <table>
                <thead>
                    <tr><th>ID</th><th>Allergie #</th><th>Niveau urgence</th><th>Conseil (extrait)</th></tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($traitements, 0, 15) as $t): ?>
                    <tr>
                        <td><?= (int) $t->getId() ?></td>
                        <td><?= (int) $t->getAllergieId() ?></td>
                        <td><?= htmlspecialchars($t->getNiveauUrgence()) ?></td>
                        <?php
                            $conseil = $t->getConseil();
                            $conseilShort = function_exists('mb_substr')
                                ? (mb_strlen($conseil) > 80 ? mb_substr($conseil, 0, 80) . '…' : $conseil)
                                : (strlen($conseil) > 80 ? substr($conseil, 0, 80) . '…' : $conseil);
                        ?>
                        <td class="text-clip" title="<?= htmlspecialchars($conseil) ?>"><?= htmlspecialchars($conseilShort) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($traitements) === 0): ?>
                    <tr><td colspan="4" class="muted">Aucun traitement.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Dons et partenaires</h2>
            <div class="section-toolbar">
                <a href="form.php" class="btn btn-add">Nouveau don (formulaire)</a>
                <a href="list.php" class="btn btn-secondary">Liste des associations</a>
                <span class="muted">Monétaires : <?= (int) $donsMonetaires ?> · Alimentaires : <?= (int) $donsAlimentaires ?> · Matériel : <?= (int) $donsEquipement ?></span>
            </div>
            <p class="muted" style="margin-bottom:0.75rem;">
                Statuts — En attente : <?= (int) $statsParStatut['pending'] ?> · Confirmés : <?= (int) $statsParStatut['confirmed'] ?> ·
                Livrés : <?= (int) $statsParStatut['delivered'] ?> · Annulés : <?= (int) $statsParStatut['cancelled'] ?>
            </p>
            <table>
                <thead>
                    <tr>
                        <th>Donateur</th>
                        <th>Association</th>
                        <th>Type</th>
                        <th>Détail</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donsRecents as $don): ?>
                    <?php
                        $st = $don['status'] ?? 'pending';
                        $badgeClass = 'badge-pending';
                        if ($st === 'confirmed') {
                            $badgeClass = 'badge-confirmed';
                        } elseif ($st === 'delivered') {
                            $badgeClass = 'badge-delivered';
                        } elseif ($st === 'cancelled') {
                            $badgeClass = 'badge-cancelled';
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($don['donor_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($don['association_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($don['donation_type'] ?? '') ?></td>
                        <td>
                            <?php if (($don['donation_type'] ?? '') === 'monetary'): ?>
                                <?= number_format((float)($don['amount'] ?? 0), 2) ?> DT
                            <?php else: ?>
                                <?= htmlspecialchars((string)($don['food_type'] ?? '')) ?> — <?= (int)($don['quantity'] ?? 0) ?> kg
                            <?php endif; ?>
                        </td>
                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($st) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($donsRecents) === 0 && !$donationsLoadError): ?>
                    <tr><td colspan="5" class="muted">Aucun don enregistré pour le moment.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
