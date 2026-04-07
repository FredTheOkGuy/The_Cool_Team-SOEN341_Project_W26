<?php include __DIR__ . '/../controllers/add_recipe_post.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Recipe</title>

  <link rel="stylesheet" href="<?= BASE_URL ?> /public/css/add_recipe_style.css" />
</head>

<body>

<header class="site-header">
  <div class="brand">
    <img class="logo" src="<?= BASE_URL ?> /public/images/logo.jpg" alt="Logo">
    <div class="title">The Cool Team App</div>
  </div>

  <!-- Simple back button  -->
  <div class="back-button-container">
    <button class="btn btn-primary" onclick="window.location.href='<?= BASE_URL ?>/src/views/recipes.php'">
      Back to Recipes
    </button>
  </div>
</header>

<main class="page">
  <section class="card">

    <h2>Add Recipe</h2>

    <form id="main-form" method="POST" class="form">

      <div class="field">
        <label for="recipe_name">Recipe Name</label>
        <input id="recipe_name" type="text" name="recipe_name" placeholder="Enter recipe name" required>
      </div>

      <div class="field">
        <label for="recipe_description">Recipe Description</label>
        <textarea id="recipe_description" name="recipe_description" placeholder="Enter recipe description" required></textarea>
      </div>

      <section class="section">
        <h3>Ingredients</h3>

        <div class="row">
          <input type="text" id="ingredient_name" placeholder="Ingredient name">
          <button type="button" id="add_ingredient" class="btn btn-primary">Add Ingredient</button>
        </div>

        <div class="table-wrap">
          <table id="ingredients-list" class="table">
            <thead>
              <tr>
                <th>Ingredient</th>
                <th style="width: 140px;">Remove</th>
              </tr>
            </thead>
            <tbody id="ingredients-tbody"></tbody>
          </table>
        </div>
        <!-- When we add an ingredient, we're gonna add it into the table, but also here, because the php won't
             be able to take from the table, so we're kind of saving it into a "list", where we're putting it in
             JSON format for the php -->
        <input type="hidden" name="ingredients" id="ingredients_input">
      </section>

      <!-- QUICK INFO -->
      <div class="grid-2">
        <div class="field">
          <label for="prep_time">Prep Time (minutes)</label>
          <input id="prep_time" type="number" min="0" name="prep_time" placeholder="e.g., 15" required>
        </div>

        <div class="field">
          <label for="cook_time">Cook Time (minutes)</label>
          <input id="cook_time" type="number" min="0" name="cook_time" placeholder="e.g., 30" required>
        </div>

        <div class="field">
          <label for="difficulty">Difficulty</label>
          <select id="difficulty" name="difficulty" required>
            <option value="">Select difficulty</option>
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
        </div>

        <div class="field">
          <label for="meal_type">Meal Type</label>
          <select id="meal_type" name="meal_type" required>
            <option value="">Select meal type</option>
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
          </select>
        </div>

        <div class="field">
          <label for="calories">Calories</label>
          <input id="calories" type="number" min="0" name="calories" placeholder="e.g., 450" required>
        </div>

        <div class="field">
          <label>Dietary Tags</label>
          <div class="checkbox-group">
            <label><input type="checkbox" name="dietary_tags[]" value="gmo_free"> GMO-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="gluten_free"> Gluten-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="lactose_free"> Lactose-Free</label>
            <label><input type="checkbox" name="dietary_tags[]" value="vegan"> Vegan</label>
            <label><input type="checkbox" name="dietary_tags[]" value="vegetarian"> Vegetarian</label>
          </div>
        </div>
      </div>

      <section class="section">
        <h3>Steps</h3>

        <div class="row">
          <textarea id="step_name" placeholder="Step description"></textarea>
          <button type="button" id="add_step" class="btn btn-primary">Add Step</button>
        </div>

        <div class="table-wrap">
          <table id="steps-list" class="table">
            <thead>
              <tr>
                <th>Step</th>
                <th style="width: 140px;">Remove</th>
              </tr>
            </thead>
            <tbody id="steps-tbody"></tbody>
          </table>
        </div>

        <input type="hidden" name="steps" id="steps_input">
      </section>
      <!-- Exact same thing as the ingredients -->
      <button type="submit" name="save_recipe" class="btn btn-primary btn-block">
        Save Recipe
      </button>

    </form>

  </section>
</main>

</body>
</html>

<script src="<?= BASE_URL ?>/public/js/recipes_script.js"></script>
</html>