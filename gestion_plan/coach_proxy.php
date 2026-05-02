<?php
// ─────────────────────────────────────────────────────────
// coach_proxy.php
// Place ce fichier à la racine : C:/xampp/htdocs/gestion_plan/
// ─────────────────────────────────────────────────────────

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

// !! Remplace par ta vraie clé API !!
define('ANTHROPIC_API_KEY', 'sk-ant-METS-TA-CLE-ICI');

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['messages']) || !isset($input['system'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$messages = $input['messages'];
$system   = $input['system'];

if (empty($messages) || !is_array($messages)) {
    http_response_code(400);
    echo json_encode(['error' => 'Messages invalides']);
    exit;
}

$dernierMessage = end($messages);
if (empty(trim($dernierMessage['content'] ?? ''))) {
    http_response_code(400);
    echo json_encode(['error' => 'Message vide']);
    exit;
}

if (mb_strlen($dernierMessage['content']) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Message trop long']);
    exit;
}

$payload = json_encode([
    'model'      => 'claude-sonnet-4-20250514',
    'max_tokens' => 1000,
    'system'     => $system,
    'messages'   => $messages,
]);

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: ' . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur réseau : ' . $curlError]);
    exit;
}

http_response_code($httpCode);
echo $response;
?>