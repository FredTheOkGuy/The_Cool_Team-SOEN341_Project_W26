<?php
use Anthropic\Client;
function getCalorieTip($total_calories, $current_goal) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
    require_once __DIR__ . '/../../config/api_config.php';
    global $ANTHROPIC_API_KEY;
    $prompt = "User ate $total_calories/$current_goal kcal today. Give a short motivational message + 1 tip. Be warm and concise.";
    $client = new Client($ANTHROPIC_API_KEY);

    $response = $client->messages->create(
        maxTokens: 300,
        messages : [
            ['role' => 'user', 'content' => $prompt]
        ],
        model : 'claude-haiku-4-5-20251001',
    );

    return $response;
}
?>