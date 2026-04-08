<?php
require_once __DIR__ . '/../../config/api_config.php';
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../models/sql_recipe_functions.php';
session_start();
$userId = $_SESSION['user_id'];
$show_current_recipe = false;
$recipe = null;
$recipe_ingredients = [];
$meal_type = '';

if(isset($_POST['save_recipe'])) {
    $recipe = json_decode($_POST['recipe_data'], true);
    $meal_type = $_POST['meal_type'];
    $name         = $recipe['name'];
    $description  = $recipe['description'];
    $prep_time    = (int) $recipe['prep_time_minutes'];
    $cook_time    = (int) $recipe['cook_time_minutes'];
    $difficulty   = $recipe['difficulty'];
    $calories     = (int) $recipe['calories'];
    $gmo_free     = $recipe['gmo_free']     ? 1 : 0;
    $gluten_free  = $recipe['gluten_free']  ? 1 : 0;
    $lactose_free = $recipe['lactose_free'] ? 1 : 0;
    $vegan        = $recipe['vegan']        ? 1 : 0;
    $vegetarian   = $recipe['vegetarian']   ? 1 : 0;

    addRecipe($userId, $name, $description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe['ingredients'], $recipe['steps']);

    header("Location: " . BASE_URL . "/src/views/recipes.php");
    exit();
}

if(isset($_POST['create_recipe'])) {
    $recipe_ingredients = isset($_POST['ingredients']) ? json_decode($_POST['ingredients'], true) : [];
    if (!is_array($recipe_ingredients)) {
        $recipe_ingredients = [];
    }
    $recipe_ingredients_string = implode(", ", $recipe_ingredients);
    $meal_type = $_POST['meal_type'] ?? '';

    $recipe = createRecipe($userId, $recipe_ingredients_string, $meal_type);
    $show_current_recipe = ($recipe !== null);
}
?>