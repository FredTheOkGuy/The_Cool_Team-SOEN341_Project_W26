<?php 
    include __DIR__ . '/../controllers/preference_post.php';
    include __DIR__ . '/../controllers/allergy_post.php'; 
    include __DIR__ . '/../controllers/calories_post.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/profile_page_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<nav class="top-nav">
    <a href="<?= BASE_URL ?>/src/views/main_menu.php" class="nav-brand">
        <i class='bx bxs-dish'></i>
        MealMajor
    </a>
    <div class="nav-actions">
        <a href="<?= BASE_URL ?>/src/views/main_menu.php" class="btn-back">
            <i class='bx bx-arrow-back'></i> Back to Main
        </a>
        <a href="<?= BASE_URL ?>/src/controllers/log_out.php" class="btn-logout">
            <i class='bx bx-log-out'></i> Log Out
        </a>
    </div>
</nav>

<main class="page">

    <div class="profile-header">
        <i class='bx bxs-user-circle'></i>
        <div class="profile-header-info">
            <div class="profile-header-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
            <div class="profile-header-email"><?= htmlspecialchars($_SESSION['email']) ?></div>
        </div>
    </div>

    <div class="card">
        <h3>Allergies</h3>
        <div class="row">
            <form method="POST" class="row">
                <input type="text" name="allergy_name" placeholder="Allergy name" required>
                <button type="submit" name="add_allergy" class="btn btn-primary">Add Allergy</button>
            </form>
        </div>
        <div class="list">
            <?php if (empty($allergies)): ?>
                <div class="list-item"><span>No allergies yet</span></div>
            <?php else: ?>
                <?php foreach ($allergies as $allergy): ?>
                    <div class="list-item">
                        <span><?= htmlspecialchars($allergy['allergy']) ?></span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="allergy_id" value="<?= (int)$allergy['allergy_id'] ?>">
                            <button type="submit" name="delete_allergy" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3>Dietary Preferences</h3>
        <div class="row">
            <form method="POST" class="row">
                <input type="text" name="preference_name" placeholder="Preference name" required>
                <button type="submit" name="add_preference" class="btn btn-primary">Add Dietary Preference</button>
            </form>
        </div>
        <div class="list">
            <?php if (empty($preferences)): ?>
                <div class="list-item"><span>No dietary preferences</span></div>
            <?php else: ?>
                <?php foreach ($preferences as $preference): ?>
                    <div class="list-item">
                        <span><?= htmlspecialchars($preference['preference']) ?></span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="preference_id" value="<?= (int)$preference['preference_id'] ?>">
                            <button type="submit" name="delete_preference" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3>Daily Calorie Goal</h3>
        <?php if ($current_goal): ?>
            <div class="list-item" style="margin-bottom: 0.8rem;">
                <span>Current goal: <strong><?= $current_goal ?> kcal</strong></span>
            </div>
        <?php endif; ?>
        <form method="POST" class="row">
            <input type="number" name="daily_goal" placeholder="e.g. 2000"
                min="500" max="9999" required
                value="<?= $current_goal ?? '' ?>">
            <button type="submit" name="set_goal" class="btn btn-primary">
                <?= $current_goal ? 'Update Goal' : 'Set Goal' ?>
            </button>
        </form>
    </div>

</main>
</body>
</html>