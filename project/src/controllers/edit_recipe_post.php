<?php
/*This whole code is legit the same as the add recipes, just we load the values that we already have for the specific
  recipe (the placeholders becomes the current values) */
session_start();
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../models/sql_recipe_functions.php';
$userId = $_SESSION['user_id'];

$recipe_id = $_GET['recipe_id'] ?? null;

$recipes_url = BASE_URL . '/src/views/recipes.php';

if (!$recipe_id) {
    header('Location: ' . $recipes_url);
    exit;
}

// When the user clicks the save recipe button
if(isset($_POST['save_recipe'])) {
    $recipe_name        = trim($_POST['recipe_name']);
    $recipe_description = trim($_POST['recipe_description']);
    $recipe_ingredients = isset($_POST['ingredients']) ? json_decode($_POST['ingredients'], true) : [];
    $recipe_steps       = isset($_POST['steps']) ? json_decode($_POST['steps'], true) : [];
    $prep_time          = intval($_POST['prep_time']);
    $cook_time          = intval($_POST['cook_time']);
    $difficulty         = $_POST['difficulty'];
    $meal_type          = $_POST['meal_type'];
    $calories           = intval($_POST['calories']);
    $dietary_tags       = isset($_POST['dietary_tags']) ? $_POST['dietary_tags'] : [];
    $gmo_free           = in_array('gmo_free', $dietary_tags) ? 1 : 0;
    $gluten_free        = in_array('gluten_free', $dietary_tags) ? 1 : 0;
    $lactose_free       = in_array('lactose_free', $dietary_tags) ? 1 : 0;
    $vegan              = in_array('vegan', $dietary_tags) ? 1 : 0;
    $vegetarian         = in_array('vegetarian', $dietary_tags) ? 1 : 0;

    editRecipe($userId, $recipe_id, $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_ingredients, $recipe_steps);

    header('Location: ' . $recipes_url);
    exit;
}

// We fetch the information of the recipe (works the same as the display recipes)

$recipe = getRecipeInformation($recipe_id, $userId);

if (!$recipe) {
    header('Location: ' . $recipes_url);
    exit;
}

$ingredient_result = getRecipeIngredients($recipe_id);
$existing_ingredients = [];
while ($row = $ingredient_result->fetch_assoc()) {
    $existing_ingredients[] = $row['ingredient_name'];
}

$step_result = getRecipeSteps($recipe_id);
$existing_steps = [];
while ($row = $step_result->fetch_assoc()) {
    $existing_steps[] = $row['step_instruction'];
}
?>
