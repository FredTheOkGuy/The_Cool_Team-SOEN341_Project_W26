# src/controllers/

This folder contains PHP scripts that handle user actions, form submissions, and business logic. Controllers receive POST (or GET) input, validate and process it, call the appropriate model functions, then redirect to the correct view. No HTML is rendered directly from controllers.

## Pattern

All controllers follow the **Post/Redirect/Get (PRG)** pattern:
1. Receive form data via `$_POST` or `$_GET`
2. Call one or more model functions
3. Redirect with `header('Location: ...')` + `exit()`

This prevents duplicate form submissions on browser refresh.

## Files

### `login_page_register.php`
Handles both login and registration submissions from `index.php`. Validates credentials against the database, starts a session on success, and redirects to `main_menu.php` (login) or back to `index.php` (registration or failure).

### `log_out.php`
Destroys the active session (`session_destroy()`) and redirects the user to `index.php`.

### `main_menu_post.php`
Handles form submissions from the dashboard (`main_menu.php`): adding a recipe to a day/meal-type slot or removing an existing entry from the weekly schedule. Calls the relevant functions in `sql_meals_functions.php` and redirects to `main_menu.php`.

### `add_recipe_post.php`
Handles the form submission for manually creating a new recipe. Collects recipe details, ingredients, and steps from `$_POST`, calls `addRecipe()` from `sql_recipe_functions.php`, then redirects to `recipes.php`.

### `edit_recipe_post.php`
Loads an existing recipe by `recipe_id` (from the URL), pre-fills the edit form, and handles the save submission by calling `editRecipe()`. Redirects to `recipes.php` on success.

### `recipe_post.php`
Handles general recipe-level POST actions such as deleting a recipe. Calls the appropriate model function and redirects to `recipes.php`.

### `recipe_creation_post.php`
Handles the form submission from `recipe_creation.php`. Passes the user's ingredient list and chosen meal type to the Claude API via `createRecipe()` in `sql_recipe_functions.php`. Displays the generated recipe and, if the user chooses to save it, calls `addRecipe()`.

### `calorie_tracker_post.php`
Handles form submissions from the calorie tracker page. Routes to `addCalories()` or `removeCalories()` based on the submitted action, then redirects to `calorie_tracker.php`.

### `calories_post.php`
Handles adding or removing individual calorie entries. Calls `addCalories()` or `removeCalories()` from `sql_calorie_functions.php` and redirects accordingly.

### `allergy_post.php`
Handles form submissions for updating the user's allergy information on the profile page. Calls the relevant function in `sql_allergy_functions.php` and redirects to `profile.php`.

### `preference_post.php`
Handles form submissions for updating the user's dietary preferences. Calls the relevant function in `sql_preference_functions.php` and redirects to `profile.php`.

## Notes

- Every controller requires `config/login_page_config.php` for `$conn` and `BASE_URL`
- Session authentication should be verified at the top of any controller that requires a logged-in user
- Controllers never echo HTML — all output goes through views reached via redirect
