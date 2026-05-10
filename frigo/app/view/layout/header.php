<?php
$mode = $_GET['mode'] ?? 'front';

function getEmojiAliment(string $nom): string {
    $nom = strtolower(trim($nom));
    $emojis = [
        'pomme'      => '🍎', 'banane'    => '🍌', 'orange'    => '🍊',
        'fraise'     => '🍓', 'raisin'    => '🍇', 'mangue'    => '🥭',
        'tomate'     => '🍅', 'carotte'   => '🥕', 'courgette' => '🥒',
        'salade'     => '🥬', 'poivron'   => '🫑', 'oignon'    => '🧅',
        'lait'       => '🥛', 'yaourt'    => '🍶', 'fromage'   => '🧀',
        'beurre'     => '🧈', 'crème'     => '🍦', 'creme'     => '🍦',
        'poulet'     => '🍗', 'boeuf'     => '🥩', 'merguez'   => '🌭',
        'thon'       => '🐟', 'eau'       => '💧', 'jus'       => '🧃',
        'coca'       => '🥤', 'café'      => '☕', 'cafe'      => '☕',
        'thé'        => '🍵', 'the'       => '🍵', 'limonade'  => '🍋',
        'pâtes'      => '🍝', 'pates'     => '🍝', 'riz'       => '🍚',
        'huile'      => '🫙', 'sucre'     => '🍬', 'pain'      => '🍞',
        'croissant'  => '🥐', 'biscuit'   => '🍪', 'biscuits'  => '🍪',
        'oeuf'       => '🥚', 'ail'       => '🧄', 'pizza'     => '🍕',
    ];
    foreach ($emojis as $mot => $emoji) {
        if (str_contains($nom, $mot)) return $emoji;
    }
    return '🥗';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🧊 Frigo Intelligent</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= FRIGO_BASE ?>/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand text-white fw-bold fs-4"
       href="<?= FRIGO_INDEX ?>">🧊 Frigo Intelligent</a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <div class="btn-group btn-group-sm">
        <a href="<?= FRIGO_INDEX ?>?mode=front&controller=produit&action=frigo"
           class="btn <?= $mode === 'front' ? 'btn-light' : 'btn-outline-light' ?>">
          FrontOffice
        </a>
        <a href="<?= FRIGO_INDEX ?>?mode=back&controller=produit&action=index"
           class="btn <?= $mode === 'back' ? 'btn-warning' : 'btn-outline-light' ?>">
          BackOffice
        </a>
      </div>
      <?php if ($mode === 'front'): ?>
        <a href="<?= FRIGO_INDEX ?>?mode=front&controller=produit&action=frigo"
           class="text-white text-decoration-none">Mon Frigo</a>
        <a href="<?= FRIGO_INDEX ?>?mode=front&controller=categorie&action=index"
           class="text-white text-decoration-none">Supermarché</a>
        <a href="<?= FRIGO_INDEX ?>?mode=front&controller=commande&action=panier"
           class="btn btn-light btn-sm">
          🛒 Panier (<?= count($_SESSION['panier'] ?? []) ?>)
        </a>
      <?php else: ?>
        <a href="<?= FRIGO_INDEX ?>?mode=back&controller=produit&action=index"
           class="text-white text-decoration-none">Produits</a>
        <a href="<?= FRIGO_INDEX ?>?mode=back&controller=categorie&action=admin"
           class="text-white text-decoration-none">Catégories</a>
        <a href="<?= FRIGO_INDEX ?>?mode=back&controller=commande&action=index"
           class="text-white text-decoration-none">Commandes</a>
        <a href="<?= FRIGO_INDEX ?>?mode=back&controller=statistique&action=index"
           class="text-white text-decoration-none">📊 Statistiques</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<main class="py-3">