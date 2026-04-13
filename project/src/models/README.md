# src/models/

This folder contains PHP files with reusable functions for database queries and external API calls. Model files contain only logic — no HTML is output from these files.

## Files

### `sql_login_register_functions.php`
Functions for user authentication and registration.
- `registerUser($conn, ...)` — inserts a new user record into the database
- `loginUser($conn, ...)` — validates credentials and returns the user record on success

### `sql_recipe_functions.php`
Functions for managing recipes in the database, plus the AI recipe generation wrapper.
- `addRecipe($conn, ...)` — inserts a new recipe with its ingredients and preparation steps
- `editRecipe($conn, ...)` — updates an existing recipe, replacing its ingredient and step rows
- `deleteRecipe($conn, ...)` — removes a recipe by `recipe_id` and `user_id`
- `getRecipes($conn, ...)` — fetches the user's recipes with optional search, filter, and sort parameters
- `createRecipe($ingredients, $mealType)` — calls the Anthropic Claude API to generate a recipe from a comma-separated ingredient list and a meal type; returns the generated recipe as a structured array

### `sql_meals_functions.php`
Functions for managing the weekly meal schedule.
- `addMealToSchedule($conn, ...)` — assigns a recipe to a specific day of the week and meal type
- `deleteMealFromSchedule($conn, ...)` — removes a scheduled meal entry
- `getMealsForSchedule($conn, $userId)` — returns all scheduled meals for a user, keyed by day and meal type

### `sql_calorie_functions.php`
Functions for the calorie tracker.
- `checkCalories($conn, $userId)` — initializes or resets the user's daily calorie row if it doesn't exist for today
- `addCalories($conn, $userId, $amount)` — increments the user's daily calorie total
- `removeCalories($conn, $userId, $amount)` — decrements the user's daily calorie total
- `getTotalCalories($conn, $userId)` — returns the user's current calorie count for today
- `getDailyGoal($conn, $userId)` — returns the user's calorie goal from their profile

### `sql_allergy_functions.php`
Functions for managing the user's food allergies.
- Handles inserting, updating, and retrieving allergy records tied to the user's profile in the database

### `sql_preference_functions.php`
Functions for managing the user's dietary preferences.
- Handles inserting, updating, and retrieving dietary preference records tied to the user's profile

### `api_calorie_functions.php`
Wrapper for the Claude API calorie tip feature.
- `getCalorieTip($calories, $goal)` — sends the user's current calorie intake and daily goal to the Claude API and returns a short motivational message

## Dependencies

| File | Requires |
|------|---------|
| `sql_*` files | `config/login_page_config.php` (for `$conn`) |
| `api_calorie_functions.php` | `config/api_config.php` (for Anthropic API key) |
| `sql_recipe_functions.php` | Both config files (DB for CRUD, API for `createRecipe()`) |

## Notes

- SQL functions accept `$conn` as a parameter; `sql_recipe_functions.php` may also use `global $conn` in some functions
- All database interactions use prepared statements (`$conn->prepare()`) to prevent SQL injection
- The Anthropic PHP SDK (`anthropic-ai/sdk`) is used for all Claude API calls and is installed via Composer into `vendor/`
