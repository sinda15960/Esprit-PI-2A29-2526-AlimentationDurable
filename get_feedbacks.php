<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/FeedbackController.php';

$controller = new FeedbackController();
$result = $controller->getApprovedFeedbacks();
echo json_encode($result);
?>