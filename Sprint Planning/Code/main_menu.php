<?php
// Simple Main Menu
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Main Menu</title>
    <link rel="stylesheet" href="main_menu_style.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
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
				<a href="recipes.php">
					<i class='bx bx-fork' ></i>
					<span class="links_name">Your recipes</span>
				</a>
				<span class="tooltip">Your recipes</span>
			</li>
			<li>
				<a href="calorie_tracker.php">
					<i class='bx bxs-heart'></i>
					<span class="links_name">Calorie Tracker</span>
				</a>
				<span class="tooltip">Calorie Tracker</span>
			</li>
			<li>
				<a href="recipe_creation.php">
					<i class='bx bxs-bowl-rice' ></i>
					<span class="links_name">Recipe Creator</span>
				</a>
				<span class="tooltip">Recipe Creator</span>
			</li>
			<li>
				<a href="log_out.php">
					<i class='bx bx-log-out' ></i>
					<span class="links_name">Log Out</span>
				</a>
				<span class="tooltip">Log Out</span>
		</ul>
	</div>

	<a href="profile.php" class="profile-btn" title="Profile" aria-label="Profile">P</a>

</body>

<script>
	let btn = document.querySelector('#btn');
	let sidebar = document.querySelector('.sidebar');

	btn.onclick = function(){
		sidebar.classList.toggle('active');
	};
</script>

</html>

