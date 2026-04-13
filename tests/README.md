# tests/

This folder contains the PHPUnit test suite for MealMajor. Tests use a **sandbox subprocess pattern** — each test copies the target source file into a temporary directory, patches its `require_once` paths to point to local stubs, and runs it in an isolated subprocess. This means no live database or web server is needed to run the tests.

## Running Tests

Install dependencies (first time only):
```bash
composer install
```

Run the full test suite from the repository root:
```bash
vendor/bin/phpunit
```

On Windows (Laragon):
```bash
vendor\bin\phpunit
```

PHPUnit reads its configuration from `phpunit.xml` in the repository root.

---

## Test Files

### `SearchRecipeTest.php`
Tests the search, filter, and sorting logic in `recipes.php`. Verifies that the correct SQL query string is built and the correct parameter types and values are bound based on the GET inputs.

| Test | What it checks |
|------|----------------|
| `testDefaultSearchUsesDefaultFiltersAndSorting` | With no filters: expects `prep_time <= 1000`, `cook_time <= 1000`, and `ORDER BY recipe_name ASC` |
| `testSearchByNameAddsLikeClauseAndStringParameter` | Search by name: expects a `LIKE ?` clause and `%pasta%` as the bound parameter |
| `testFiltersAndSortingAreAddedToQuery` | Multiple filters + sort: all conditions appear in the SQL |
| `testOverSixtyMapsToOneThousandAndNameDescendingSort` | The `over_60` time filter maps to the sentinel value 1000; sort by name descending is applied correctly |

---

### `DeleteRecipeTest.php`
Tests the delete recipe logic in `recipes.php`. Verifies the prepared statement, parameter binding, execution, and the guard clause that prevents deletion when the POST flag is absent.

| Test | What it checks |
|------|----------------|
| `testDeleteRecipePreparesCorrectDeleteQuery` | The correct `DELETE FROM ... WHERE` SQL string is prepared |
| `testDeleteRecipeBindsRecipeIdAndUserIdAsIntegers` | Bind types are `"ii"` with the correct `recipe_id` and `user_id` values |
| `testDeleteRecipeExecutesDeleteStatement` | `execute()` is called on the prepared statement |
| `testDeleteIsNotTriggeredWhenDeletePostFlagIsMissing` | No deletion occurs when `$_POST['delete_recipe']` is absent |

---

### `CreateRecipeTest.php`
Tests the AI recipe creation logic in `recipe_creation.php`. Covers ingredient decoding, API call arguments, and the save flow.

| Test | What it checks |
|------|----------------|
| `testCreateRecipeUsesDecodedIngredientsAndMealType` | JSON-encoded ingredients are decoded and passed as a comma-separated string to `createRecipe()` |
| `testCreateRecipeFallsBackToEmptyArrayWhenIngredientsJsonIsInvalid` | Invalid JSON falls back to an empty array, resulting in an empty ingredients string |
| `testSaveRecipeCallsAddRecipeWithNormalizedValues` | On save, `addRecipe()` is called with correctly typed and cast values (trimmed strings, integers, floats) |

---

### `EditRecipeTest.php`
Tests the edit recipe logic in `edit_recipe.php`. Covers redirect behaviour, value normalization on save, and page rendering with existing data.

| Test | What it checks |
|------|----------------|
| `testRedirectsToRecipesWhenRecipeIdIsMissing` | Script exits immediately (redirect) when no `recipe_id` is present in `$_GET` |
| `testSaveRecipeCallsEditRecipeWithNormalizedValues` | `editRecipe()` receives trimmed, cast, and correctly typed values from the POST data |
| `testPageLoadsExistingRecipeIngredientsAndSteps` | The rendered HTML contains the mocked recipe name, ingredients, and preparation steps |

---

### `WeeklyPlanTest.php`
Tests the weekly meal schedule logic in `main_menu_post.php`. Covers the add-meal action, schedule array construction from DB results, recipe dropdown loading, and duplicate prevention.

| Test | What it checks |
|------|----------------|
| `testAddActionAssignsRecipeToDayAndMealType` | When `action=add` is POSTed, `addMealToSchedule()` is called with the correct connection, user ID (int), recipe ID (int), day of week, and meal type |
| `testScheduleArrayIsBuiltFromMealsGroupedByDay` | On a GET request, the `$schedule` array is keyed by day of week and each entry contains the correct `meal_type` and `recipe_name` |
| `testRecipeDropdownListIsLoadedForUser` | On a GET request, `$user_recipe_list` is populated with the user's recipes (correct count, `recipe_id`, and `recipe_name`) |
| `testAddMealToSchedulePreventsDuplicateMealsAndSetsSessionError` | When a recipe already exists in the schedule, `$_SESSION['duplicate_error']` is set to the expected message and no `INSERT` is executed |

Note: `testAddMealToSchedulePreventsDuplicateMealsAndSetsSessionError` uses a separate `makeDuplicateSandbox()` that directly exercises the real `addMealToSchedule()` function body with a fake connection whose `SELECT` stub always returns `num_rows = 1`.

---

### `CaloriesPageTest.php`
Tests the calorie tracker logic in `calorie_tracker_post.php`. Covers the add and remove calorie actions, initial page-load data assignment, and AI tip sanitization.

| Test | What it checks |
|------|----------------|
| `testAddCaloriesCallsAddCaloriesWithUserCaloriesAndTodayDate` | When `add_calories` is POSTed, `addCalories()` is called with the correct connection marker, user ID (int), calorie amount (int), and a `YYYY-MM-DD` date string; script does not continue past the redirect |
| `testRemoveCaloriesCallsRemoveCaloriesWithUserCaloriesAndTodayDate` | When `remove_calories` is POSTed, `removeCalories()` is called with matching argument shape; script does not continue past the redirect |
| `testDailyCalorieFunctionsAreCalledAndValuesAssigned` | On a GET request, `getScheduledCalories()`, `checkCalories()`, `getTotalCalories()`, and `getDailyGoal()` are each called with the correct arguments, and their return values are assigned to `$scheduled_calories` (1800), `$total_calories` (1450), and `$current_goal` (2000) |
| `testTipIsSanitizedByRemovingCodeFences` | The raw API response (which contains ` ``` ` fences) is stripped, leaving only the plain tip text |

---

### `AllergyScriptTest.php`
Tests the allergy management logic in `allergy_post.php`. Covers adding, deleting, and listing allergies, and the guard that prevents writes when no action flag is set.

| Test | What it checks |
|------|----------------|
| `testAddAllergyCallsAddAllergyWithTrimmedNameAndUserId` | When `add_allergy` is POSTed with `allergy_name = "  peanuts  "`, `addAllergy()` is called with the trimmed name `"peanuts"` and the correct user ID; `getAllergies()` is then called to refresh the list |
| `testDeleteAllergyCallsDeleteAllergyWithAllergyIdAndUserId` | When `delete_allergy` is POSTed with `allergy_id = "9"`, `deleteAllergy()` is called with `"9"` and the correct user ID; `getAllergies()` is called afterward |
| `testGetAllergiesAssignsReturnedValueToAllergiesVariable` | On a plain GET request, `$allergies` is assigned the array returned by `getAllergies()` (two entries: peanuts and soy) |
| `testNoAddOrDeleteWhenNoPostFlagsAreSet` | With no POST flags present, neither `addAllergy_log.json` nor `deleteAllergy_log.json` is created, but `getAllergies()` is still called |

---

## How the Sandbox Works

Each test class implements one or more `makeSandbox()` helpers that:
1. Create a temporary directory with the subdirectory layout the subject script expects
2. Copy the target source file into it as `subject.php`
3. Patch all `require_once` paths so they resolve to local stub files instead of the real application
4. Provide fake implementations of `$conn`, model functions, and constants like `BASE_URL`
5. Write a `runner.php` script that `include`s `subject.php` and writes results (variable dumps, call argument logs, flag files) to JSON or text files in the sandbox
6. Execute `runner.php` via a subprocess (`exec(PHP_BINARY . ' ' . ...)`)
7. Read and assert against the result files

This pattern keeps tests fully isolated from the database and the web server while still exercising the real application code paths. Some tests (e.g., `WeeklyPlanTest::testAddMealToSchedulePreventsDuplicateMealsAndSetsSessionError`) bypass the sandbox runner entirely and call model functions directly with fake connection objects to test internal branching logic in isolation.