<?php
require 'login_page_config.php';
session_start();
$userId = $_SESSION['user_id'];
date_default_timezone_set('America/Toronto');
$today = date('l');
$today_date = date('Y-m-d');

$check_today_row = $conn->prepare("SELECT log_date, total_calories FROM user_daily_calories WHERE user_id = ?");
$check_today_row->bind_param('i', $userId);
$check_today_row->execute();
$existing_row = $check_today_row->get_result()->fetch_assoc();

if (!$existing_row) {
    $scheduled_calories_query = "SELECT COALESCE(SUM(r.calories), 0) AS total
                   FROM meal_schedule ms
                   JOIN recipes r ON ms.recipe_id = r.recipe_id
                   WHERE ms.user_id = ? AND ms.day_of_week = ?";
    $scheduled_calories_stmt = $conn->prepare($scheduled_calories_query);
    $scheduled_calories_stmt->bind_param('is', $userId, $today);
    $scheduled_calories_stmt->execute();
    $scheduled_calories = $scheduled_calories_stmt->get_result()->fetch_assoc();

    $insert_row = $conn->prepare("INSERT INTO user_daily_calories (user_id, log_date, total_calories) VALUES (?, ?, ?)");
    $insert_row->bind_param('isi', $userId, $today_date, $scheduled_calories['total']);
    $insert_row->execute();

} elseif ($existing_row['log_date'] !== $today_date) {
    $scheduled_calories_query = "SELECT COALESCE(SUM(r.calories), 0) AS total
                   FROM meal_schedule ms
                   JOIN recipes r ON ms.recipe_id = r.recipe_id
                   WHERE ms.user_id = ? AND ms.day_of_week = ?";
    $scheduled_calories_stmt = $conn->prepare($scheduled_calories_query);
    $scheduled_calories_stmt->bind_param('is', $userId, $today);
    $scheduled_calories_stmt->execute();
    $scheduled_calories = $scheduled_calories_stmt->get_result()->fetch_assoc();

    $reset_row = $conn->prepare("UPDATE user_daily_calories SET log_date = ?, total_calories = ? WHERE user_id = ?");
    $reset_row->bind_param('sii', $today_date, $scheduled_calories['total'], $userId);
    $reset_row->execute();
}

if (isset($_POST['add_calories'])) {
    $calories = intval($_POST['calories_added']);
    $add_stmt = $conn->prepare("UPDATE user_daily_calories SET total_calories = total_calories + ? WHERE user_id = ?");
    $add_stmt->bind_param('ii', $calories, $userId);
    $add_stmt->execute();
}

if (isset($_POST['remove_calories'])) {
    $calories = intval($_POST['calories_removed']);
    $remove_stmt = $conn->prepare("UPDATE user_daily_calories SET total_calories = total_calories - ? WHERE user_id = ?");
    $remove_stmt->bind_param('ii', $calories, $userId);
    $remove_stmt->execute();
}

$get_total_calories = $conn->prepare("SELECT total_calories FROM user_daily_calories WHERE user_id = ?");
$get_total_calories->bind_param('i', $userId);
$get_total_calories->execute();
$total_calories = $get_total_calories->get_result()->fetch_assoc()['total_calories'];

$get_goal = $conn->prepare("SELECT daily_goal FROM calorie_goals WHERE user_id = ?");
$get_goal->bind_param('i', $userId);
$get_goal->execute();
$goal_row = $get_goal->get_result()->fetch_assoc();
$current_goal = $goal_row['daily_goal'] ?? 0;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Creation</title>
    <link rel="stylesheet" href="recipe_creation_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="introduction">
        <h1>Welcome to your calorie tracker!</h1>
        <p>Here you can track your daily calorie intake depending on today's meal plan.</p>
        <p>You can add extra calories you consume, or remove calories</p>
    </div>
    <div class="calories">
        <h2>Calorie Intake</h2>
        <p>Total Calories: <span id="total-calories"><?php echo $total_calories; ?>/<?php echo $current_goal; ?></span></p>
        <form method="POST" class="add-calories">
            <input type="number" name="calories_added" placeholder="Calories to add" required>
            <button type="submit" name="add_calories">Add Calories</button>
        </form>
        <form method="POST" class="remove-calories">
            <input type="number" name="calories_removed" placeholder="Calories to remove" required>
            <button type="submit" name="remove_calories">Remove Calories</button>
        </form>
    </div>
</body>
</html>
