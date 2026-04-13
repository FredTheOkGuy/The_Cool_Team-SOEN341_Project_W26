<?php
session_start();
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../models/sql_meals_functions.php';
$userId = $_SESSION['user_id'];
// Tried to make it after logging out, you can't come back on this page (doesn't work D;)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit();
}

// Same error function used in the login page, shoutout Fred
function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

//Weekly schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'add') {
        $recipe_id  = intval($_POST['recipe_id']);
        $day        = $_POST['day_of_week'];
        $meal_type  = $_POST['meal_type'];
        addMealToSchedule($conn, $userId, $recipe_id, $day, $meal_type);
    }

    if ($_POST['action'] === 'delete') { //destroy that thing
		deleteMealFromSchedule($conn, $userId, $schedule_id);
    }
    
    header("Location: " . BASE_URL . "/src/views/main_menu.php");
    exit();
}

// Grab and clear the error, again shoutout Fred
$errors = ['schedule' => $_SESSION['duplicate_error'] ?? ''];
unset($_SESSION['duplicate_error']);

// CHARLES DON"T FORGET TO DELETE THIS
//var_dump($errors);

$meals_result = getMealsForSchedule($conn, $userId); //find every meal

//array of days (collumns) and meal_type (row) for the schedule table
$schedule = [];
$days      = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$meal_types = ['Breakfast','Lunch','Dinner','Snack'];
foreach ($days as $d) {$schedule[$d] = [];}
while ($row = $meals_result->fetch_assoc()) {
    $schedule[$row['day_of_week']][] = $row;
}

// recipes in the cool "Add" dropdown menu
$user_recipes = $conn->prepare("SELECT recipe_id, recipe_name FROM recipes WHERE user_id=? ORDER BY recipe_name ASC");
$user_recipes->bind_param('i', $userId);
$user_recipes->execute();
$recipes_result = $user_recipes->get_result();
$user_recipe_list = [];
while ($r = $recipes_result->fetch_assoc()) {$user_recipe_list[] = $r;}

date_default_timezone_set('America/Toronto'); //Adjusted for mtl time (mtl not available but whatever)
$today = date('l');
?>
