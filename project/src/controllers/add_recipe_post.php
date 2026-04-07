<?php
session_start();
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../models/sql_recipe_functions.php';
$userId = $_SESSION['user_id'];

// If the save recipe button was pressed, then add the info in the database
if(isset($_POST['save_recipe'])) {
    // First we get all the info and save it to seperate variables
    $recipe_name = trim($_POST['recipe_name']);
    $recipe_description = trim($_POST['recipe_description']);

    $recipe_ingredients = isset($_POST['ingredients']) ? json_decode($_POST['ingredients'], true) : [];
    $recipe_steps = isset($_POST['steps']) ? json_decode($_POST['steps'], true) : [];

    $prep_time = intval($_POST['prep_time']);
    $cook_time = intval($_POST['cook_time']);

    $difficulty = $_POST['difficulty'];

    $meal_type = $_POST['meal_type'];

    $calories = intval($_POST['calories']);

    $dietary_tags = isset($_POST['dietary_tags']) ? $_POST['dietary_tags'] : [];
    $gmo_free = in_array('gmo_free', $dietary_tags) ? 1 : 0;
    $gluten_free = in_array('gluten_free', $dietary_tags) ? 1 : 0;
    $lactose_free = in_array('lactose_free', $dietary_tags) ? 1 : 0;
    $vegan = in_array('vegan', $dietary_tags) ? 1 : 0;
    $vegetarian = in_array('vegetarian', $dietary_tags) ? 1 : 0;

    addRecipe($userId, $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_ingredients, $recipe_steps);
    // When we add a recipe, after adding it, we head back to the recipes
    header('Location: ' . BASE_URL . '/src/views/recipes.php');
    exit;
}

?>