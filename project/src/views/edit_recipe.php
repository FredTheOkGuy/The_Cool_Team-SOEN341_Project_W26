<?php include __DIR__ . '/../controllers/edit_recipe_post.php'; ?>

<!DOCTYPE html>
<!-- Like said at the beginning, instead of placeholders, we put in the value of the current recipe (loaded in the php section)  -->
<html lang="en">

    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Add Recipe</title>

      <link rel="stylesheet" href="<?= BASE_URL ?> /public/css/add_recipe_style.css" />
    </head>

<body>
  <div class="page">
    <div class="card">
      <h2>Edit Recipe</h2>

      <form id="main-form" method="POST" class="form">
        <input type="hidden" name="recipe_id" value="<?=$recipe_id?>">

        <div class="field">
          <label>Recipe Name</label>
          <input type="text" name="recipe_name" value="<?=htmlspecialchars($recipe['recipe_name'])?>" required>
        </div>

        <div class="field">
          <label>Recipe Description</label>
          <textarea name="recipe_description" required><?=htmlspecialchars($recipe['description'])?></textarea>
        </div>

        <div class="section">
          <h3>Ingredients</h3>

          <div class="row">
            <input type="text" id="ingredient_name" placeholder="Ingredient name">
            <button type="button" id="add_ingredient" class="btn btn-primary">Add Ingredient</button>
          </div>

          <div class="table-wrap">
            <table id="ingredients-list" class="table">
              <thead>
                <tr><th>Ingredient</th><th>Remove</th></tr>
              </thead>
              <tbody id="ingredients-tbody">
                <?php foreach($existing_ingredients as $ingredient): ?>
                  <tr>
                    <td><?=htmlspecialchars($ingredient)?></td>
                    <td>
                      <button type="button" class="btn btn-danger" onclick="removeIngredient(this)">Remove</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <input type="hidden" name="ingredients" id="ingredients_input">
        </div>

        <!-- Two-column grid for times + selects -->
        <div class="grid-2">
          <div class="field">
            <label>Prep Time (minutes)</label>
            <input type="number" min="0" name="prep_time" value="<?=$recipe['prep_time']?>" required>
          </div>

          <div class="field">
            <label>Cook Time (minutes)</label>
            <input type="number" min="0" name="cook_time" value="<?=$recipe['cook_time']?>" required>
          </div>

          <div class="field">
            <label>Difficulty</label>
            <select name="difficulty" required>
              <option value="">Select difficulty</option>
              <option value="easy"   <?=($recipe['difficulty_level'] == 'easy')   ? 'selected' : ''?>>Easy</option>
              <option value="medium" <?=($recipe['difficulty_level'] == 'medium') ? 'selected' : ''?>>Medium</option>
              <option value="hard"   <?=($recipe['difficulty_level'] == 'hard')   ? 'selected' : ''?>>Hard</option>
            </select>
          </div>

          <div class="field">
            <label>Meal Type</label>
            <select name="meal_type" required>
              <option value="">Select meal type</option>
              <option value="breakfast" <?=($recipe['meal_type'] == 'breakfast') ? 'selected' : ''?>>Breakfast</option>
              <option value="lunch"     <?=($recipe['meal_type'] == 'lunch')     ? 'selected' : ''?>>Lunch</option>
              <option value="dinner"    <?=($recipe['meal_type'] == 'dinner')    ? 'selected' : ''?>>Dinner</option>
            </select>
          </div>

          <div class="field">
            <label>Calories</label>
            <input type="number" min="0" name="calories" value="<?=$recipe['calories']?>" required>
          </div>

          <div class="field">
            <label>Dietary Tags</label>
            <div class="checkbox-group">
              <label><input type="checkbox" name="dietary_tags[]" value="gmo_free"     <?=$recipe['gmo_free']     ? 'checked' : ''?>> GMO-Free</label>
              <label><input type="checkbox" name="dietary_tags[]" value="gluten_free"  <?=$recipe['gluten_free']  ? 'checked' : ''?>> Gluten-Free</label>
              <label><input type="checkbox" name="dietary_tags[]" value="lactose_free" <?=$recipe['lactose_free'] ? 'checked' : ''?>> Lactose-Free</label>
              <label><input type="checkbox" name="dietary_tags[]" value="vegan"        <?=$recipe['vegan']        ? 'checked' : ''?>> Vegan</label>
              <label><input type="checkbox" name="dietary_tags[]" value="vegetarian"   <?=$recipe['vegetarian']   ? 'checked' : ''?>> Vegetarian</label>
            </div>
          </div>
        </div>

        <div class="section">
          <h3>Steps</h3>

          <div class="row">
            <textarea id="step_name" placeholder="Step description"></textarea>
            <button type="button" id="add_step" class="btn btn-primary">Add Step</button>
          </div>

          <div class="table-wrap">
            <table id="steps-list" class="table">
              <thead>
                <tr><th>Step</th><th>Remove</th></tr>
              </thead>
              <tbody id="steps-tbody">
                <?php foreach($existing_steps as $step): ?>
                  <tr>
                    <td><?=htmlspecialchars($step)?></td>
                    <td>
                      <button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <input type="hidden" name="steps" id="steps_input">
        </div>

        <!-- Save -->
        <button type="submit" name="save_recipe" class="btn btn-primary btn-block">
          Save Recipe
        </button>
      </form>
    </div>
  </div>

<script src="<?= BASE_URL ?>/public/js/recipes_script.js"></script>

</body>
</html>
