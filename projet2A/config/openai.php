<?php
// config/openai.php

// 🔑 TA CLÉ API (à remplacer par ta nouvelle clé après régénération)
define('OPENAI_API_KEY', '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');

// Configuration de l'API
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_MODEL', 'gpt-3.5-turbo');

// Fonction pour appeler l'API OpenAI
function callOpenAI($prompt, $maxTokens = 400) {
    $data = [
        'model' => OPENAI_MODEL,
        'messages' => [
            [
                'role' => 'system', 
                'content' => 'Tu es un assistant expert en nutrition et en gestion de plateforme. Tu donnes des conseils professionnels, encourageants et précis. Tu réponds en français.'
            ],
            [
                'role' => 'user', 
                'content' => $prompt
            ]
        ],
        'max_tokens' => $maxTokens,
        'temperature' => 0.7
    ];
    
    $ch = curl_init(OPENAI_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($httpCode === 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas pu générer une réponse.";
    } else {
        error_log("OpenAI API error: " . $response);
        return "⚠️ Service IA temporairement indisponible. Veuillez réessayer plus tard.";
    }
}
?>
