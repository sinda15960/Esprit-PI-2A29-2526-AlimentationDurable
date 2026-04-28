<?php
require_once __DIR__ . '/ExportController.php';

$controller = new ExportController();
$html = $controller->exportPDF();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF - NutriFlow AI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        h1 { color: #2d5016; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2d5016; color: white; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #2d5016; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">🖨️ Imprimer / Sauvegarder en PDF</button>
        <button onclick="window.close()" style="background: #999; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">❌ Fermer</button>
    </div>
    <?= $html ?>
</body>
</html>