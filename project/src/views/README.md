# src/views/

This folder contains the front-end PHP pages of the application. Files here are primarily HTML with embedded PHP for rendering dynamic data. Views do not process form submissions — that is handled by controllers.

## Files

### `index.php` *(lives at `project/index.php`, not in this folder)*
The application entry point and login/registration page. Displays two toggling forms (login and register) driven by `public/js/login_page_script.js`. Form submissions go to `src/controllers/login_page_register.php`.

### `main_menu.php`
The main dashboard shown after login. Displays the weekly meal schedule as a 7-column grid with rows for each meal type (breakfast, lunch, dinner, snack). Each cell shows the assigned recipe (if any) and provides add/remove controls. Navigation to all other pages is available via a collapsible sidebar.

### `recipes.php`
Displays all of the user's saved recipes. Includes:
- A search bar (by recipe name)
- Filter controls (prep time, cook time, difficulty, cost, dietary tags)
- Sort options (name, time, cost — ascending/descending)
- Expandable recipe cards that show full details, ingredients, and steps on click
- Edit link and delete button per recipe

### `add_recipe.php`
A form for manually creating a new recipe. Includes fields for name, description, prep/cook time, difficulty, estimated cost, dietary tags, dynamic ingredient rows, and dynamic preparation step rows. Submits to `src/controllers/add_recipe_post.php`.

### `edit_recipe.php`
The same form layout as `add_recipe.php` but pre-populated with an existing recipe's data (loaded via `recipe_id` from the URL). Submits to `src/controllers/edit_recipe_post.php`.

### `recipe_creation.php`
The AI recipe generator page. Users select a meal type and enter ingredients they have available. On submission, the Claude API generates a full recipe which is displayed on the page. Users can save the generated recipe to their recipe list.

### `calorie_tracker.php`
Shows the user's daily calorie intake vs their goal as a progress bar. Provides controls to manually add or remove calories. Displays an AI-generated motivational tip from the Claude API (via `getCalorieTip()`).

### `profile.php`
Displays and manages the user's profile:
- Allergy checkboxes (submits to `allergy_post.php`)
- Dietary preference selectors (submits to `preference_post.php`)
- Daily calorie goal input

## Notes

- All views require `config/login_page_config.php` for `$conn` and `BASE_URL`
- `BASE_URL` is used for all internal links, asset `src`/`href` attributes, and form `action` URLs
- Views load model functions directly for read operations (e.g., fetching recipes to display); write operations are always delegated to controllers
