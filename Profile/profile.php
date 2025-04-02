<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../dbconnect.php';
require_once '../utils/queries.php';

$favorites = getFavoriteRecipes($pdo, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapag Hanap - Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="icon" href="../hapaglogo.jpg" type="image/ico">

</head>
<body>
    <div class="sidebar">
        <img src="../hapaglogo.jpg" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
            <!-- <a href="../contact-us.php"><img src="../assets/icons/phone.png" class="icon"></a> -->
        </div>    

    <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>
    <div class="profile-section">
        <img src="../images/pfp.png" alt="Chef Clawdia" class="profile-pic">
        <i class="fa-solid fa-pen edit-icon"></i>
        <h2>Chef Clawdia</h2>
        <p>100 followers | 0 following</p>
        <p>Just a cat who cooks when my owner isn't around.</p>
        <div class="email">
            <i class="fa-solid fa-envelope"></i>
            <p>chefclawdia@gmail.com</p>
        </div>
    </div>
    <div class="recipes-container">
    <div class="tabs">
        <div class="tab active">Saved</div>
    </div>
    
    <?php if (empty($favorites)): ?>
        <div class="no-recipes" style="text-align: center; margin-top: 50px;">
            <i class="fas fa-heart" style="font-size: 48px; color: #ff6600;"></i>
            <h3>No saved recipes yet</h3>
            <p>Save your favorite recipes and they'll appear here</p>
        </div>
    <?php else: ?>
        <?php foreach ($favorites as $recipe): ?>
    <a href="../recipes/selected-recipe.php?id=<?= $recipe['id'] ?>" class="recipe-card-link">
        <div class="recipe-card">
            <?php if (!empty($recipe['image_path'])): ?>
                <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>" class="recipe-img">
            <?php else: ?>
                <img src="../images/default-recipe.jpg" alt="Default recipe image" class="recipe-img">
            <?php endif; ?>
            
            <div class="recipe-details">
                <?php if (!empty($recipe['tags'])): ?>
                    <span class="tag"><?= htmlspecialchars(explode(' | ', $recipe['tags'])[0]) ?></span>
                <?php endif; ?>
                
                
                <?php if (!empty($recipe['author'])): ?>
                    <p>By: <?= htmlspecialchars($recipe['author']) ?></p>
                <?php endif; ?>
                
                <?php if (!empty($recipe['description'])): ?>
                    <p><?= htmlspecialchars($recipe['description']) ?></p>
                <?php endif; ?>
                
                <?php if (!empty($recipe['prep_time']) && !empty($recipe['cook_time'])): ?>
                    <p>
                        <strong>Servings:</strong> <?= htmlspecialchars($recipe['servings'] ?? 'N/A') ?> | 
                        <strong>Prep:</strong> <?= htmlspecialchars($recipe['prep_time']) ?> min | 
                        <strong>Cook:</strong> <?= htmlspecialchars($recipe['cook_time']) ?> min | 
                        <strong>Total:</strong> <?= htmlspecialchars($recipe['prep_time'] + $recipe['cook_time']) ?> min
                    </p>
                <?php endif; ?>
            </div>
            <i class="fa-solid fa-chevron-right arrow-icon"></i>
        </div>
    </a>
<?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
