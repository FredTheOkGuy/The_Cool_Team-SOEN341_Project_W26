<?php include __DIR__ . '/../controllers/recipe_post.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/recipes_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<nav class="top-nav">
    <span class="nav-brand">
        <i class='bx bxs-dish'></i>
        MealMajor
    </span>
    <div class="nav-actions">
        <a href="<?= BASE_URL ?>/src/views/add_recipe.php" class="btn-add">
            <i class='bx bx-plus'></i> Add Recipe
        </a>
        <a href="<?= BASE_URL ?>/src/views/main_menu.php" class="btn-back">
            <i class='bx bx-arrow-back'></i> Back to Main
        </a>
    </div>
</nav>

<main class="page">
    <h2 class="page-title">My Recipes</h2>

    <form method="get" action="" class="controls-card">
        <div class="search-section">
            <h1>Search</h1>
            <input type="text" name="search" placeholder="Search recipes..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>

        <div class="sort-section">
            <h1>Sort By</h1>
            <select name="sort" onchange="this.form.submit()">
                <option value="">Sort By</option>
                <option value="name_desc"      <?= ($sort_by == 'name_desc')      ? 'selected' : '' ?>>Name Descending</option>
                <option value="name_asc"       <?= ($sort_by == 'name_asc')       ? 'selected' : '' ?>>Name Ascending</option>
                <option value="cook_time_desc" <?= ($sort_by == 'cook_time_desc') ? 'selected' : '' ?>>Cook Time Descending</option>
                <option value="cook_time_asc"  <?= ($sort_by == 'cook_time_asc')  ? 'selected' : '' ?>>Cook Time Ascending</option>
                <option value="prep_time_desc" <?= ($sort_by == 'prep_time_desc') ? 'selected' : '' ?>>Prep Time Descending</option>
                <option value="prep_time_asc"  <?= ($sort_by == 'prep_time_asc')  ? 'selected' : '' ?>>Prep Time Ascending</option>
            </select>
        </div>

        <div class="filter-section">
            <h1>Filters</h1>
            <select name="prep_time_filter" onchange="this.form.submit()">
                <option value="">Prep Time</option>
                <option value="under_15" <?= ($prep_time_filter == 'under_15') ? 'selected' : '' ?>>Under 15 mins</option>
                <option value="under_30" <?= ($prep_time_filter == 'under_30') ? 'selected' : '' ?>>Under 30 mins</option>
                <option value="under_60" <?= ($prep_time_filter == 'under_60') ? 'selected' : '' ?>>Under 60 mins</option>
                <option value="over_60"  <?= ($prep_time_filter == 'over_60')  ? 'selected' : '' ?>>Over 60 mins</option>
            </select>
            <select name="cook_time_filter" onchange="this.form.submit()">
                <option value="">Cook Time</option>
                <option value="under_15" <?= ($cook_time_filter == 'under_15') ? 'selected' : '' ?>>Under 15 mins</option>
                <option value="under_30" <?= ($cook_time_filter == 'under_30') ? 'selected' : '' ?>>Under 30 mins</option>
                <option value="under_60" <?= ($cook_time_filter == 'under_60') ? 'selected' : '' ?>>Under 60 mins</option>
                <option value="over_60"  <?= ($cook_time_filter == 'over_60')  ? 'selected' : '' ?>>Over 60 mins</option>
            </select>
            <div class="tag-filters">
                <label><input type="checkbox" name="filter_gmo_free"     value="1" <?= isset($_GET['filter_gmo_free'])     ? 'checked' : '' ?> onchange="this.form.submit()"> GMO Free</label>
                <label><input type="checkbox" name="filter_gluten_free"  value="1" <?= isset($_GET['filter_gluten_free'])  ? 'checked' : '' ?> onchange="this.form.submit()"> Gluten Free</label>
                <label><input type="checkbox" name="filter_lactose_free" value="1" <?= isset($_GET['filter_lactose_free']) ? 'checked' : '' ?> onchange="this.form.submit()"> Lactose Free</label>
                <label><input type="checkbox" name="filter_vegan"        value="1" <?= isset($_GET['filter_vegan'])        ? 'checked' : '' ?> onchange="this.form.submit()"> Vegan</label>
                <label><input type="checkbox" name="filter_vegetarian"   value="1" <?= isset($_GET['filter_vegetarian'])   ? 'checked' : '' ?> onchange="this.form.submit()"> Vegetarian</label>
            </div>
            <div class="diff-filters">
                <label><input type="checkbox" name="easy_diff"   value="1" <?= isset($_GET['easy_diff'])   ? 'checked' : '' ?> onchange="this.form.submit()"> Easy</label>
                <label><input type="checkbox" name="medium_diff" value="1" <?= isset($_GET['medium_diff']) ? 'checked' : '' ?> onchange="this.form.submit()"> Medium</label>
                <label><input type="checkbox" name="hard_diff"   value="1" <?= isset($_GET['hard_diff'])   ? 'checked' : '' ?> onchange="this.form.submit()"> Hard</label>
            </div>
            <a class="clear-filters-btn" href="?search=<?=htmlspecialchars($search_name)?>&sort=<?=htmlspecialchars($sort_by)?>">Clear Filters</a>
        </div>
    </form>

    <div class="recipes-list">
        <?php
        if ($recipes->num_rows > 0) {
            while ($row = $recipes->fetch_assoc()) {
                $recipe_id = $row['recipe_id'];
                $ingredients_result = getRecipeIngredients($recipe_id);
                $ingredients = [];
                while ($ingredient = $ingredients_result->fetch_assoc()) {
                    $ingredients[] = htmlspecialchars($ingredient['ingredient_name']);
                }
                $ingredients_display = !empty($ingredients) ? implode(', ', $ingredients) : 'No ingredients listed.';
                $steps_result = getRecipesWithStepNumber($recipe_id);
                $steps_display = '';
                while ($step = $steps_result->fetch_assoc()) {
                    $steps_display .= '<p><strong>Step ' . htmlspecialchars($step['step_number']) . ':</strong> ' . htmlspecialchars($step['step_instruction']) . '</p>';
                }

                $tags = [];
                if ($row['gmo_free'])     $tags[] = '<span>GMO Free &#9989</span>';
                if ($row['gluten_free'])  $tags[] = '<span>Gluten Free &#9989</span>';
                if ($row['lactose_free']) $tags[] = '<span>Lactose Free &#9989</span>';
                if ($row['vegan'])        $tags[] = '<span>Vegan &#9989</span>';
                if ($row['vegetarian'])   $tags[] = '<span>Vegetarian &#9989</span>';
                $tags_display = !empty($tags) ? implode(' ', $tags) : '<span>No dietary tags &#10060</span>';

                echo '
                <div class="recipe-card" onclick="toggleRecipe(this)">
                    <div class="recipe-card-summary">
                        <h3 class="recipe-title">' . htmlspecialchars($row['recipe_name']) . '</h3>
                        <p class="recipe-description">' . htmlspecialchars($row['description']) . '</p>
                        <div class="recipe-info2">
                            <span>Prep: ' . htmlspecialchars($row['prep_time']) . ' min</span>
                            <span>Cook: ' . htmlspecialchars($row['cook_time']) . ' min</span>
                            <span>Difficulty: ' . htmlspecialchars($row['difficulty_level']) . '</span>
                            <span>Calories: ' . htmlspecialchars($row['calories']) . ' cal</span>
                            <span>Meal Type: ' . htmlspecialchars($row['meal_type']) . '</span>
                        </div>
                    </div>
                    <div class="recipe-tags">' . $tags_display . '</div>
                    <div class="recipe-ingredients">' . $ingredients_display . '</div>
                    <div class="recipe-steps">' . $steps_display . '</div>
                    <div class="recipe-actions">
                        <a href="' . BASE_URL . '/src/views/edit_recipe.php?recipe_id=' . $recipe_id . '">Edit</a>
                        <form method="POST">
                            <input type="hidden" name="recipe_id" value="' . $recipe_id . '">
                            <button type="submit" name="delete_recipe" onclick="return confirm(\'Are you sure you want to delete this recipe?\')">Delete</button>
                        </form>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="no-recipes">No recipes found. Click "Add Recipe" to create your first one!</div>';
        }
        ?>
    </div>
</main>

<script>
    function toggleRecipe(element) {
        if (event.target.closest('.recipe-actions')) return;
        element.classList.toggle('open');
    }
</script>
</body>
</html>