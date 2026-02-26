<?php
session_start();
require_once 'login_page_config.php';
$userId = $_SESSION['user_id'];

$recipe_id = $_GET['recipe_id'] ?? null;

if (!$recipe_id) {
    header('Location: recipes.php');
    exit;
}
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

    $update_query = "UPDATE recipes SET recipe_name=?, description=?, prep_time=?, cook_time=?, difficulty_level=?, calories=?, gmo_free=?, gluten_free=?, lactose_free=?, vegan=?, vegetarian=?, meal_type=? WHERE recipe_id=? AND user_id=?";
    $result = $conn->prepare($update_query);
    $result->bind_param('ssiisiiiiiissi', $recipe_name, $recipe_description, $prep_time, $cook_time, $difficulty, $calories, $gmo_free, $gluten_free, $lactose_free, $vegan, $vegetarian, $meal_type, $recipe_id, $userId);
    $result->execute();

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

    header('Location: recipes.php');
    exit;
}
$result = $conn->prepare("SELECT * FROM recipes WHERE recipe_id = ? AND user_id = ?");
$result->bind_param('ii', $recipe_id, $userId);
$result->execute();
$recipe = $result->get_result()->fetch_assoc();

if (!$recipe) {
    header('Location: recipes.php');
    exit;
}
$ingredient_result = $conn->prepare("SELECT i.ingredient_name FROM ingredients i JOIN recipe_ingredients ri ON i.ingredient_id = ri.ingredient_id WHERE ri.recipe_id = ?");
$ingredient_result->bind_param('i', $recipe_id);
$ingredient_result->execute();
$ingredient_result = $ingredient_result->get_result();
$existing_ingredients = [];
while ($row = $ingredient_result->fetch_assoc()) {
    $existing_ingredients[] = $row['ingredient_name'];
}
$step_result = $conn->prepare("SELECT step_instruction FROM recipe_steps WHERE recipe_id = ? ORDER BY step_number");
$step_result->bind_param('i', $recipe_id);
$step_result->execute();
$step_result = $step_result->get_result();
$existing_steps = [];
while ($row = $step_result->fetch_assoc()) {
    $existing_steps[] = $row['step_instruction'];
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="add_recipe_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<div class="main">
    <form id="main-form" method="POST">
        <input type="hidden" name="recipe_id" value="<?=$recipe_id?>">

        <div>
            <h1>Recipe Name</h1>
            <input type="text" name="recipe_name" value="<?=htmlspecialchars($recipe['recipe_name'])?>" required>
        </div>

        <div>
            <h1>Recipe Description</h1>
            <textarea name="recipe_description" required><?=htmlspecialchars($recipe['description'])?></textarea>
        </div>

        <div>
            <h1>Ingredients</h1>
            <div id="ingredients-div">
                <input type="text" id="ingredient_name" placeholder="Ingredient name">
                <button type="button" id="add_ingredient" class="btn btn-primary">Add Ingredient</button>
            </div>
            <table id="ingredients-list">
                <thead><tr><th>Ingredient</th><th>Remove</th></tr></thead>
                <tbody id="ingredients-tbody">
                    <?php foreach($existing_ingredients as $ingredient): ?>
                        <tr>
                            <td><?=htmlspecialchars($ingredient)?></td>
                            <td><button type="button" class="btn btn-danger" onclick="removeIngredient(this)">Remove</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" name="ingredients" id="ingredients_input">
        </div>

        <div>
            <h1>Prep Time</h1>
            <input type="number" min="0" name="prep_time" value="<?=$recipe['prep_time']?>" required>
        </div>

        <div>
            <h1>Cook Time</h1>
            <input type="number" min="0" name="cook_time" value="<?=$recipe['cook_time']?>" required>
        </div>

        <div>
            <h1>Difficulty</h1>
            <select name="difficulty" required>
                <option value="">Select difficulty</option>
                <option value="easy"   <?=($recipe['difficulty_level'] == 'easy')   ? 'selected' : ''?>>Easy</option>
                <option value="medium" <?=($recipe['difficulty_level'] == 'medium') ? 'selected' : ''?>>Medium</option>
                <option value="hard"   <?=($recipe['difficulty_level'] == 'hard')   ? 'selected' : ''?>>Hard</option>
            </select>
        </div>

        <div>
            <h1>Meal Type</h1>
            <select name="meal_type" required>
                <option value="">Select meal type</option>
                <option value="breakfast" <?=($recipe['meal_type'] == 'breakfast') ? 'selected' : ''?>>Breakfast</option>
                <option value="lunch"     <?=($recipe['meal_type'] == 'lunch')     ? 'selected' : ''?>>Lunch</option>
                <option value="dinner"    <?=($recipe['meal_type'] == 'dinner')    ? 'selected' : ''?>>Dinner</option>
            </select>
        </div>

        <div>
            <h1>Calories</h1>
            <input type="number" min="0" name="calories" value="<?=$recipe['calories']?>" required>
        </div>

        <div>
            <h1>Dietary Tags</h1>
            <label><input type="checkbox" name="dietary_tags[]" value="gmo_free"     <?=$recipe['gmo_free']     ? 'checked' : ''?>> GMO-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="gluten_free"  <?=$recipe['gluten_free']  ? 'checked' : ''?>> Gluten-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="lactose_free" <?=$recipe['lactose_free'] ? 'checked' : ''?>> Lactose-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="vegan"        <?=$recipe['vegan']        ? 'checked' : ''?>> Vegan</label>
            <label><input type="checkbox" name="dietary_tags[]" value="vegetarian"   <?=$recipe['vegetarian']   ? 'checked' : ''?>> Vegetarian</label>
        </div>

        <div>
            <h1>Steps</h1>
            <div id="steps-div">
                <textarea id="step_name" placeholder="Step description"></textarea>
                <button type="button" id="add_step" class="btn btn-primary">Add Step</button>
            </div>
            <table id="steps-list">
                <thead><tr><th>Step</th><th>Remove</th></tr></thead>
                <tbody id="steps-tbody">
                    <?php foreach($existing_steps as $step): ?>
                        <tr>
                            <td><?=htmlspecialchars($step)?></td>
                            <td><button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" name="steps" id="steps_input">
        </div>

        <button type="submit" name="save_recipe" class="btn btn-success">Save Recipe</button>
    </form>
</div>

<script>
    function checkIngredient(ingredientName) {
        const rows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].cells[0].innerText === ingredientName) return true;
        }
        return false;
    }

    function removeIngredient(btn) { btn.closest('tr').remove(); }
    function removeStep(btn) { btn.closest('tr').remove(); }

    document.getElementById('add_ingredient').addEventListener('click', function () {
        const input = document.getElementById('ingredient_name');
        const name  = input.value.trim();
        if (name !== '' && !checkIngredient(name)) {
            const tbody = document.getElementById('ingredients-tbody');
            const row   = document.createElement('tr');
            row.innerHTML = `<td>${name}</td><td><button type="button" class="btn btn-danger" onclick="removeIngredient(this)">Remove</button></td>`;
            tbody.appendChild(row);
            input.value = '';
        }
    });

    document.getElementById('add_step').addEventListener('click', function () {
        const input = document.getElementById('step_name');
        const name  = input.value.trim();
        if (name !== '') {
            const tbody = document.getElementById('steps-tbody');
            const row   = document.createElement('tr');
            row.innerHTML = `<td>${name}</td><td><button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button></td>`;
            tbody.appendChild(row);
            input.value = '';
        }
    });

    document.getElementById('main-form').addEventListener('submit', function () {
        const ingredientRows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
        document.getElementById('ingredients_input').value = JSON.stringify(Array.from(ingredientRows).map(r => r.cells[0].innerText));

        const stepRows = document.getElementById('steps-tbody').getElementsByTagName('tr');
        document.getElementById('steps_input').value = JSON.stringify(Array.from(stepRows).map(r => r.cells[0].innerText));
    });
</script>
</body>
</html>
