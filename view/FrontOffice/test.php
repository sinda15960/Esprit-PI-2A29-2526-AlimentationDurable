<?php
echo "Chemin actuel : " . __DIR__ . "<br>";
echo "Chemin vers Config : " . realpath(__DIR__ . '/../Config/Database.php') . "<br>";

if (file_exists(__DIR__ . '/../Config/Database.php')) {
    echo "✅ Fichier Database.php trouvé !";
    require_once __DIR__ . '/../Config/Database.php';
    echo "<br>✅ Inclusion réussie !";
} else {
    echo "❌ Fichier Database.php NON trouvé !";
}
?>