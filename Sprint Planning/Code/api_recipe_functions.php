<?php
function createRecipe($userId, $recipe_ingredients_string, $meal_type){
    require 'api_config.php';
    global $conn, $ANTHROPIC_API_KEY;
    $sql_query = "
        SELECT al.allergy_id, al.allergy
        FROM user_allergies ual
        JOIN allergies al ON ual.allergy_id = al.allergy_id
        WHERE ual.user_id = ?
        ORDER BY al.allergy
    ";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $allergies = $result->fetch_all(MYSQLI_ASSOC);
    $allergiesList = array_column($allergies, 'allergy');
    $allergiesString = implode(", ", $allergiesList);

    $sql_query = "
        SELECT dp.preference_id, dp.preference
        FROM user_preferences udp
        JOIN diet_preferences dp ON udp.preference_id = dp.preference_id
        WHERE udp.user_id = ?
        ORDER BY dp.preference
    ";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $preferences = $result->fetch_all(MYSQLI_ASSOC);
    $preferencesList = array_column($preferences, 'preference');
    $preferencesString = implode(", ", $preferencesList);

    $prompt = "
    Create a recipe using only the provided ingredients.

    Ingredients: $recipe_ingredients_string
    Dietary preferences: $preferencesString
    Allergies: $allergiesString
    Meal type: $meal_type

    Return ONLY valid JSON with this exact structure:

    {
    \"name\": \"\",
    \"description\": \"\",
    \"prep_time_minutes\": number,
    \"cook_time_minutes\": number,
    \"difficulty\": \"easy|medium|hard\",
    \"calories\": number,
    \"gmo_free\": boolean,
    \"gluten_free\": boolean,
    \"lactose_free\": boolean,
    \"vegan\": boolean,
    \"vegetarian\": boolean,
    \"ingredients\": [\"ingredient1\", \"ingredient2\"],
    \"steps\": [\"step1\",\"step2\",\"step3\"]
    }

    Rules:
    - Use only the ingredients listed.
    - Respect dietary preferences and allergies.
    - Output JSON only.
    ";

    $data = [
        "model" => "claude-haiku-4-5-20251001",
        "max_tokens" => 1000,
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ]
    ];

    $ch = curl_init("https://api.anthropic.com/v1/messages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "x-api-key: " . $ANTHROPIC_API_KEY,
        "anthropic-version: 2023-06-01"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if ($response === false) {
        die("cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result['content'][0]['text'])) {
        die("<pre>API Error: " . htmlspecialchars($response) . "</pre>");
    }

    $recipeJson = $result['content'][0]['text'];
    $recipeJson = preg_replace('/^```(?:json)?\s*/i', '', trim($recipeJson));
    $recipeJson = preg_replace('/\s*```$/', '', $recipeJson);

    $recipe = json_decode($recipeJson, true);
    return $recipe;
}

?>