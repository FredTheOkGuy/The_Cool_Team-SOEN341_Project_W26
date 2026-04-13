# public/css/

This folder contains all stylesheets for the MealMajor application. Each CSS file is scoped to a specific page or feature to keep styles isolated and maintainable.

## Files

| File | Used By | Description |
|------|---------|-------------|
| `login_page_style.css` | `index.php` | Styles for the login and registration page, including form layout and toggle animation |
| `main_menu_style.css` | `src/views/main_menu.php` | Dashboard layout including the sidebar, weekly meal schedule grid, and meal slot cards |
| `recipes_style.css` | `src/views/recipes.php` | Recipe listing page including search bar, filter panel, and expandable recipe cards |
| `add_recipe_style.css` | `src/views/add_recipe.php`, `src/views/edit_recipe.php` | Shared styles for the recipe creation and edit forms, including dynamic ingredient/step rows |
| `recipe_creation_style.css` | `src/views/recipe_creation.php` | Styles for the AI recipe generator page (ingredient input, meal type selector, and result display) |
| `calorie_tracker_style.css` | `src/views/calorie_tracker.php` | Calorie tracker layout including progress bar, add/remove controls, and AI tip display |
| `profile_page_style.css` | `src/views/profile.php` | User profile page including allergy checkboxes, dietary preference selectors, and calorie goal input |

## Usage

Link a stylesheet in a PHP view using `BASE_URL` (defined in `config/login_page_config.php`):

```html
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/main_menu_style.css">
```

## Notes

- All stylesheets are plain CSS — no preprocessors or build tools are used
- `BASE_URL` is resolved dynamically so links work regardless of the server directory depth
