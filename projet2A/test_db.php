<?php
// test_db.php - Test de connexion à la base de données
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Base de Données NutriFlow AI</h1>";

try {
    // Test de connexion
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    echo "<p style='color:green'>✓ Connexion à la base de données réussie !</p>";
    
    // Vérifier les tables
    $tables = ['recipes', 'instructions', 'categories'];
    foreach($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if($result->rowCount() > 0) {
            $count = $conn->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<p style='color:green'>✓ Table '$table' existe ($count enregistrements)</p>";
        } else {
            echo "<p style='color:red'>✗ Table '$table' n'existe pas !</p>";
        }
    }
    
    // Afficher les recettes
    $recipes = $conn->query("SELECT * FROM recipes");
    echo "<h2>Liste des recettes :</h2>";
    if($recipes->rowCount() > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Titre</th><th>Description</th><th>Temps</th></tr>";
        while($row = $recipes->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($row['description'], 0, 50)) . "...</td>";
            echo "<td>" . ($row['prep_time'] + $row['cook_time']) . " min</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>Aucune recette trouvée. Veuillez insérer des données.</p>";
    }
    
} catch(Exception $e) {
    echo "<p style='color:red'>✗ Erreur: " . $e->getMessage() . "</p>";
    echo "<p>Vérifiez vos identifiants dans config/database.php</p>";
}
?>