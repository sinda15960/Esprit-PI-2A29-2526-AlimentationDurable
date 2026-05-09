<?php
// test_front.php - Test simple d'affichage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'models/Recipe.php';

echo "<h1>🍃 NutriFlow AI - Test FrontOffice Simplifié</h1>";

try {
    $recipeModel = new Recipe();
    $stmt = $recipeModel->readAll();
    $recipes = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recipes[] = $row;
    }
    
    if(empty($recipes)) {
        echo "<div style='background:#fff3cd; padding:20px; border-radius:8px;'>";
        echo "<h2>Aucune recette trouvée !</h2>";
        echo "<p>Veuillez d'abord ajouter des recettes via le backoffice.</p>";
        echo "<a href='public/index.php?action=backCreateRecipe' style='background:#2ecc71; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>➕ Ajouter une recette</a>";
        echo "</div>";
    } else {
        echo "<h2>Nos recettes (" . count($recipes) . ")</h2>";
        echo "<div style='display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:20px;'>";
        foreach($recipes as $r) {
            echo "<div style='background:white; padding:15px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);'>";
            echo "<h3>" . htmlspecialchars($r['title']) . "</h3>";
            echo "<p>" . htmlspecialchars(substr($r['description'], 0, 100)) . "...</p>";
            echo "<small>⏱️ " . ($r['prep_time'] + $r['cook_time']) . " min | 🔥 " . ($r['calories'] ?? 'N/A') . " cal</small>";
            echo "<br><a href='public/index.php?action=frontShowRecipe&id=" . $r['id'] . "' style='color:#2ecc71;'>Voir la recette →</a>";
            echo "</div>";
        }
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<p>";
    echo "<a href='public/index.php?action=backRecipes' style='margin-right:15px;'>🔐 BackOffice</a>";
    echo "<a href='test_db.php'>📊 Test BDD</a>";
    echo "</p>";
    
} catch(Exception $e) {
    echo "<p style='color:red'>Erreur: " . $e->getMessage() . "</p>";
}
?>