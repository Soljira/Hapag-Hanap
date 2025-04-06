<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($recipe_id <= 0) {
    header('Location: home.php');
    exit();
}

require_once '../dbconnect.php';
require_once '../utils/queries.php';

// Checksss if recipe is favorite
$is_favorite = false;
if (isset($_SESSION['user_id'])) {
    $is_favorite = isRecipeFavorite($pdo, $_SESSION['user_id'], $recipe_id);
}

// Handle favorite toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_favorite'])) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to favorite recipes']);
        exit(); 
    }
    
    try {
        if ($is_favorite) {
            $success = removeFavoriteRecipe($pdo, $_SESSION['user_id'], $recipe_id);
            $message = 'Recipe removed from favorites';
        } else {
            $success = addFavoriteRecipe($pdo, $_SESSION['user_id'], $recipe_id);
            $message = 'Recipe added to favorites!';
        }
        
        echo json_encode([
            'success' => $success,
            'is_favorite' => !$is_favorite, 
            'message' => $message
        ]);
        exit(); 
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}

/** Function to fetch recipe data:
    * Basic recipe information
    * Recipe details
    * Categories
    * Ingredients
    * Instructions
    * Nutrition information
    * Tags
    *  Additional information
*/
function getRecipeData($pdo, $recipe_id) {
    $recipe = [];
    
    try {
        // Basic recipe info
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$recipe_id]);
        $recipe['basic'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recipe['basic']) {
            return null; // Recipe not found
        }

        // Recipe details
        $stmt = $pdo->prepare("SELECT * FROM recipe_details WHERE recipe_id = ?");
        $stmt->execute([$recipe_id]);
        $recipe['details'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Categories
        $stmt = $pdo->prepare("
            SELECT fc.name 
            FROM recipe_categories rc
            JOIN food_categories fc ON rc.category_id = fc.id
            WHERE rc.recipe_id = ?
        ");
        $stmt->execute([$recipe_id]);
        $recipe['categories'] = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Ingredients
        $stmt = $pdo->prepare("
            SELECT ri.*, i.name AS ingredient_name, i.image_path AS ingredient_image
            FROM recipe_ingredients ri
            LEFT JOIN ingredients i ON ri.ingredient_id = i.id
            WHERE ri.recipe_id = ?
            ORDER BY ri.id
        ");
        $stmt->execute([$recipe_id]);
        $recipe['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Instructions
        $stmt = $pdo->prepare("
            SELECT * FROM recipe_instructions 
            WHERE recipe_id = ? 
            ORDER BY step_number
        ");
        $stmt->execute([$recipe_id]);
        $recipe['instructions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nutrition
        $stmt = $pdo->prepare("
            SELECT * FROM recipe_nutrition 
            WHERE recipe_id = ?
        ");
        $stmt->execute([$recipe_id]);
        $recipe['nutrition'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tags
        $stmt = $pdo->prepare("
            SELECT tag FROM recipe_tags 
            WHERE recipe_id = ?
            UNION
            SELECT tag FROM recipe_special_tags 
            WHERE recipe_id = ?
        ");
        $stmt->execute([$recipe_id, $recipe_id]);
        $recipe['tags'] = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Additional information
        $stmt = $pdo->prepare("
            SELECT * FROM recipe_information 
            WHERE recipe_id = ?
        ");
        $stmt->execute([$recipe_id]);
        $recipe['information'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $recipe;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}

$recipe = getRecipeData($pdo, $recipe_id);
if (!$recipe) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['basic']['name']) ?>Hapag Hanap - Recipe List</title>
    <style>
        .star-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: <?= $is_favorite ? '#ffcc00' : '#ccc' ?>;
            padding: 0;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
    <script src='../js/toggleFavorite.js'></script>
    <link rel="stylesheet" href="../css/selected-recipe.css">
    <link rel="icon" href="../hapaglogo.jpg" type="image/ico">

</head>
<body>
<div id="notification" class="notification"></div>

    <div class="sidebar">
        <img src="../hapaglogo.jpg" alt="Logo" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
            <!-- <a href="../contact-us.php"><img src="../assets/icons/phone.png" class="icon"></a> -->
        </div>    
        <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>

    <div class="recipe-container">
        <div class="left-section">
        <h1 class="recipe-title">
        <?= htmlspecialchars($recipe['basic']['name']) ?>
        <button id="favorite-btn" class="star-btn" onclick="toggleFavorite()">
            <?= $is_favorite ? '★' : '☆' ?>
        </button>
    </h1>
            
            <?php if (!empty($recipe['categories']) || !empty($recipe['tags'])): ?>
                <div>
                    <?php foreach ($recipe['categories'] as $category): ?>
                        <span class="tag"><?= htmlspecialchars($category) ?></span>
                    <?php endforeach; ?>
                    <?php foreach ($recipe['tags'] as $tag): ?>
                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recipe['basic']['author'])): ?>
                <p>By: <?= htmlspecialchars($recipe['basic']['author']) ?></p>
            <?php endif; ?>
            
            <?php if (!empty($recipe['basic']['description'])): ?>
                <p><?= htmlspecialchars($recipe['basic']['description']) ?></p>
            <?php endif; ?>
            
            <?php if (!empty($recipe['details'])): ?>
                <div class="info-item">
                    <strong>Servings:</strong> <?= htmlspecialchars($recipe['details']['servings']) ?> people
                </div>
                <div class="info-item">
                    <strong>Prep:</strong> <?= htmlspecialchars($recipe['details']['prep_time']) ?> minutes
                </div>
                <div class="info-item">
                    <strong>Cook:</strong> <?= htmlspecialchars($recipe['details']['cook_time']) ?> minutes
                </div>
                <div class="info-item">
                    <strong>Total:</strong> <?= htmlspecialchars($recipe['details']['total_time']) ?> minutes
                </div>
                <div class="info-item">
                    <strong>Calories:</strong> <?= htmlspecialchars($recipe['details']['calories']) ?> kcal
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recipe['information'])): ?>
                <div class="info-section">
                    <?php foreach ($recipe['information'] as $info): ?>
                        <h3><?= htmlspecialchars($info['title']) ?></h3>
                        <p><?= htmlspecialchars($info['content']) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="instructions">
                <h2>Instructions</h2>
                <ol>
                    <?php foreach ($recipe['instructions'] as $instruction): ?>
                        <li><?= htmlspecialchars($instruction['instruction']) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
            
            <?php if (!empty($recipe['nutrition'])): ?>
                <div class="nutrition">
                    <h2>Nutrition Information</h2>
                    <div class="nutrition-grid">
                        <?php foreach ($recipe['nutrition'] as $nutrient): ?>
                            <div class="nutrition-item">
                                <strong><?= htmlspecialchars($nutrient['name']) ?></strong><br>
                                <?= htmlspecialchars($nutrient['amount']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="right-section">
            <?php if (!empty($recipe['basic']['image_path'])): ?>
                <div class="recipe-image">
                    <img src="<?= htmlspecialchars($recipe['basic']['image_path']) ?>" alt="<?= htmlspecialchars($recipe['basic']['name']) ?>">
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recipe['basic']['video_url'])): ?>
                <div class="video-container" style="margin-top: 20px; width: 100%;">
                    <?php
                    $url = $recipe['basic']['video_url'];

                    function extractYouTubeId($url) {
                        // i dont understand what this is fuck regex
                        $pattern = '~
                            (?:https?://)?     
                            (?:www\.)?         
                            (?:                
                              youtube\.com/    # Either youtube.com
                              |youtu\.be/      # or youtu.be
                            )
                            (?:                
                              watch\?v=        
                              |embed/          
                              |v/              
                              |shorts/         
                              |                
                            )
                            ([^"&?/\s]{11})    
                            ~x';
                    
                        if (preg_match($pattern, $url, $matches)) {
                            return $matches[1];
                        }
                        return '';
                    }
                    
                    $video_id = extractYouTubeId($recipe['basic']['video_url']);

                    ?>
                    <?php if ($video_id): ?>
                        <iframe 
                            width="100%" 
                            height="315" 
                            src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($video_id) ?>?rel=0" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                        ></iframe>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer">
                            Watch Video (opens in new tab)
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="ingredients">
                <h2>Ingredients</h2>
                <ul>
                    <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                        <li>
                            <?php if (!empty($ingredient['amount'])): ?>
                                <?= htmlspecialchars($ingredient['amount']) ?> 
                            <?php endif; ?>
                            <?= htmlspecialchars($ingredient['name'] ?? $ingredient['ingredient_name']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>