# public/js/

This folder contains standalone JavaScript files for the MealMajor application. Most page-specific scripts are written inline at the bottom of their respective PHP view files; only scripts that are large enough, reusable, or need to be loaded separately live here.

## Files

### `login_page_script.js`
Handles toggling between the login and register forms on `index.php`. Exposes a `showForm(formName)` function that is called by anchor links on each form to show one panel and hide the other without a page reload.

### `main_menu_script.js`
Handles client-side interactivity for the main menu dashboard (`main_menu.php`):
- Sidebar open/close toggle
- Dynamic meal slot forms — adding and removing meals by day of week and meal type
- Any client-side state management for the weekly schedule grid

### `recipes_script.js`
Handles client-side interactivity for the recipes page (`recipes.php`):
- Expand/collapse recipe cards on click to reveal full details, ingredients, and steps
- Dynamic filtering, sorting, and search behaviour applied to the visible recipe list

## Usage

Include a script in a PHP view using `BASE_URL`:

```html
<script src="<?= BASE_URL ?>/public/js/login_page_script.js"></script>
```

## Notes

- Scripts that grew large or are shared across pages are kept in this folder
- Smaller page-specific scripts may still be written inline at the bottom of their respective view file
- No bundler or transpiler is used — all scripts are plain vanilla JavaScript
