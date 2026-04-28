<?php
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Test réussi', 'maps_link' => 'https://maps.google.com']);
?>