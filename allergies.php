<?php
require_once __DIR__ . '/../../Controller/AllergieController.php';
$controller = new AllergieController();
$allergies = $controller->getAllAllergies();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>NutriFlow AI - Allergies</title>
    <link rel="stylesheet" href="assets/css/front.css">
</head>
<body>
    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>Liste des allergies</p>
    </div>
    
    <div class="container">
        <div class="search-box">
            <input type="text" id="search" placeholder="Rechercher une allergie...">
        </div>
        
        <table class="result-table">
            <thead>
                <tr><th>Nom</th><th>Catégorie</th><th>Description</th><th>Symptômes</th><th>Gravité</th></tr>
            </thead>
            <tbody id="table-body">
                <?php foreach ($allergies as $a): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($a->getNom()) ?></strong></td>
                    <td><?= htmlspecialchars($a->getCategorie()) ?></td>
                    <td><?= htmlspecialchars(substr($a->getDescription(), 0, 100)) ?>...</td>
                    <td><?= htmlspecialchars(substr($a->getSymptomes(), 0, 80)) ?>...</td>
                    <td><span class="badge badge-<?= $a->getGravite() ?>"><?= $a->getGravite() ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#table-body tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>