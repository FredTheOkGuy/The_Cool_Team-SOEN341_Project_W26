<?php
require 'api_config.php';
require 'login_page_config.php';
function addRecipe($userId, $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_ingredients, $recipe_steps){
    global $conn;
    $recipe_insert_query = "INSERT INTO recipes (user_id, recipe_name, description, prep_time, cook_time, difficulty_level, calories, gmo_free, gluten_free, lactose_free, vegan, vegetarian, meal_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $recipe_stmt = $conn->prepare($recipe_insert_query);
    $recipe_stmt->bind_param('issiisiiiiiis', $userId, $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type);
    $recipe_stmt->execute();
    $recipe_id = $conn->insert_id;

    // Lets check if the ingridients already exist
    foreach($recipe_ingredients as $ingredient){
        $ingredient = trim($ingredient);

        $ingredient_query = "SELECT ingredient_id FROM ingredients WHERE ingredient_name = ?";
        $ingredient_result = $conn->prepare($ingredient_query);
        $ingredient_result->bind_param('s', $ingredient);
        $ingredient_result->execute();
        $ingredient_result->store_result();

        // If the ingredient already exists, then we don't need to add it to the ingredient table
        if($ingredient_result->num_rows > 0){
            $ingredient_result->bind_result($ingredient_id);
            $ingredient_result->fetch();
        } else { // If it doesn't, then we add it to the ingredient table
            $insert_query = "INSERT INTO ingredients (ingredient_name) VALUES (?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('s', $ingredient);
            $insert_stmt->execute();
            $ingredient_id = $conn->insert_id;
        }
        $ingredient_result->close();

        // Then we add the ingredient to the recipe_ingredients (with its associated recipe id)
        $recipe_ingredient_insert_query = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (?, ?)";
        $recipe_ingredient_result = $conn->prepare($recipe_ingredient_insert_query);
        $recipe_ingredient_result->bind_param('ii', $recipe_id, $ingredient_id);
        $recipe_ingredient_result->execute();
    }

    // Then we add te steps
    foreach($recipe_steps as $index => $step){
        $step = trim($step);
        $recipe_step_insert_query = "INSERT INTO recipe_steps (recipe_id, step_number, step_instruction) VALUES (?, ?, ?)";
        $recipe_step_result = $conn->prepare($recipe_step_insert_query);
        $step_number = $index + 1;
        $recipe_step_result->bind_param('iis', $recipe_id, $step_number, $step);
        $recipe_step_result->execute();
    }
}

function editRecipe($userID, $recipe_id, $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_ingredients, $recipe_steps){
    global $conn;
    $update_query = "UPDATE recipes SET recipe_name=?, description=?, prep_time=?, cook_time=?, difficulty_level=?, calories=?, gmo_free=?, gluten_free=?, lactose_free=?, vegan=?, vegetarian=?, meal_type=? WHERE recipe_id=? AND user_id=?";
    $result = $conn->prepare($update_query);
    $result->bind_param('ssiisiiiiiisii', $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_id, $userID);
    $result->execute();

    // Since its kind of a pain to edit ingredients (can't simply do the UPDATE keyword), we just delete the ingredients and reinsert them
    // Same for the steps
    $conn->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?")->execute([$recipe_id]);
    $conn->prepare("DELETE FROM recipe_steps WHERE recipe_id = ?")->execute([$recipe_id]);

    foreach($recipe_ingredients as $ingredient) {
        $ingredient = trim($ingredient);
        $check = $conn->prepare("SELECT ingredient_id FROM ingredients WHERE ingredient_name = ?");
        $check->bind_param('s', $ingredient);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0) {
            $check->bind_result($ingredient_id);
            $check->fetch();
        } else {
            $ins = $conn->prepare("INSERT INTO ingredients (ingredient_name) VALUES (?)");
            $ins->bind_param('s', $ingredient);
            $ins->execute();
            $ingredient_id = $conn->insert_id;
        }
        $check->close();
        $ri = $conn->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (?, ?)");
        $ri->bind_param('ii', $recipe_id, $ingredient_id);
        $ri->execute();
    }

    foreach($recipe_steps as $index => $step) {
        $step        = trim($step);
        $step_number = $index + 1;
        $rs = $conn->prepare("INSERT INTO recipe_steps (recipe_id, step_number, step_instruction) VALUES (?, ?, ?)");
        $rs->bind_param('iis', $recipe_id, $step_number, $step);
        $rs->execute();
    }
}

?>