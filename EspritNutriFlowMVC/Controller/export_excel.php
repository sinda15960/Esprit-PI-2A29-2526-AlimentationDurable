<?php
require_once __DIR__ . '/ExportController.php';

$controller = new ExportController();
$csv = $controller->exportExcel();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="allergies_' . date('Y-m-d') . '.csv"');
echo $csv;
?>