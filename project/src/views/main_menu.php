<?php include __DIR__ . '/../controllers/main_menu_post.php'; ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Main Menu</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/main_menu_style.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
	<!-- The main menu has a side bar with the different pages we can go to and the profile page on the top right  -->
	<div class="sidebar">
		<div class="top">
			<div class="logo">
				<i class='bx bxs-dish' ></i>
				<span>MealMajor</span>
			</div>
			<i class="bx bx-menu" id="btn" ></i>
		</div>
		<ul>
			<li>
				<a href="<?= BASE_URL ?>/src/views/recipes.php">
					<i class='bx bx-fork' ></i>
					<span class="links_name">Your recipes</span>
				</a>
				<span class="tooltip">Your recipes</span>
			</li>
			<li>
				<a href="<?= BASE_URL ?>/src/views/calorie_tracker.php">
					<i class='bx bxs-heart'></i>
					<span class="links_name">Calorie Tracker</span>
				</a>
				<span class="tooltip">Calorie Tracker</span>
			</li>
			<li>
				<a href="<?= BASE_URL ?>/src/views/recipe_creation.php">
					<i class='bx bxs-bowl-rice' ></i>
					<span class="links_name">Recipe Creator</span>
				</a>
				<span class="tooltip">Recipe Creator</span>
			</li>
			<li id="log-out">
				<a href="<?= BASE_URL ?>/src/controllers/log_out.php">
					<i class='bx bx-log-out' ></i>
					<span class="links_name">Log Out</span>
				</a>
				<span class="tooltip">Log Out</span>
		</ul>
	</div>

	<div class="main-content">
    <div class="schedule-wrapper">
        <h2 class="schedule-title">Weekly Meal Schedule</h2>
		<?= showError($errors['schedule']) ?>
        <div class="week-grid">
            <?php foreach ($days as $day): ?>
            <div class="day-col <?= $day === $today ? 'today' : '' ?>">
                <div class="day-header">
                    <?= $day ?>
                    <?php if ($day === $today): ?><span class="today-badge">Today</span><?php endif; ?>
                </div>

                <?php foreach ($meal_types as $mt): ?>
                <div class="meal-slot">
                    <div class="meal-slot-label"><?= $mt ?></div>

                    <?php
                    
                    $slot_meals = array_filter($schedule[$day], fn($m) => $m['meal_type'] === $mt);
                    foreach ($slot_meals as $meal): ?>
                        <div class="meal-item">
                            <span><?= htmlspecialchars($meal['recipe_name']) ?></span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action"      value="delete">
                                <input type="hidden" name="schedule_id" value="<?= $meal['schedule_id'] ?>">
                                <button type="submit" class="delete-meal-btn" title="Remove">✕</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
					
                    <?php if (empty($slot_meals)): ?>  <!-- Can only have one meal per slot -->
    					<button class="add-meal-btn" 
            				onclick="toggleAddForm('form-<?= $day ?>-<?= $mt ?>')">+ Add</button>

    					<div class="add-meal-form" id="form-<?= $day ?>-<?= $mt ?>" style="display:none;">
        					<form method="POST">
            					<input type="hidden" name="action"      value="add">
            					<input type="hidden" name="day_of_week" value="<?= $day ?>">
            					<input type="hidden" name="meal_type"   value="<?= $mt ?>">
            					<select name="recipe_id" required>
                					<option value="">-- Pick a recipe --</option>
                					<?php foreach ($user_recipe_list as $recipe): ?>
                					<option value="<?= $recipe['recipe_id'] ?>">
                    					<?= htmlspecialchars($recipe['recipe_name']) ?>
                					</option>
                					<?php endforeach; ?>
            						</select>
            						<button type="submit">Add</button>
            						<button type="button"
                    					onclick="toggleAddForm('form-<?= $day ?>-<?= $mt ?>')">Cancel</button>
        					</form>
    					</div>
					<?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>	
	<a href="<?= BASE_URL ?>/src/views/profile.php" class="profile-btn" title="Profile" aria-label="Profile">P</a>

</body>
<script src="<?= BASE_URL ?>/public/js/main_menu_script.js"></script>
</html>

