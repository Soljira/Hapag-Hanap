<?php
// Start session and check authentication
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($recipe_id <= 0) {
    header('Location: home.php');
    exit();
}

require_once '../dbconnect.php';

// Function to fetch recipe data
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

// Get recipe data
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
    <title><?= htmlspecialchars($recipe['basic']['name']) ?> Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            width: 100vw;
            height: 100vh;
        }
        .sidebar {
            width: 60px;
            background-color: #D16200;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 10px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .icon-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .icon {
            width: 20px;
            height:20px;
            margin: 15px 0;
            display: block;
        }
        .exit-icon {
            width: 30px;
            height: 30px;
            margin-top: auto;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .logo {
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
        }
        .recipe-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: calc(100% - 100px);
            max-width: 1200px;
            padding: 20px;
            margin-left: 100px;
        }
        .left-section {
            width: 55%;
        }
        .right-section {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .recipe-title {
            font-size: 32px;
            font-weight: bold;
        }
        .star {
            color: yellow;
            font-size: 24px;
            cursor: pointer;
        }
        .tag {
            background: #ff6600;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 14px;
            display: inline-block;
            margin-top: 5px;
            margin-right: 5px;
        }
        .instructions, .ingredients, .nutrition {
            margin-top: 20px;
        }
        .recipe-image img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        .nutrition-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .nutrition-item {
            background: #333;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .info-item {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../hapaglogo.jpg" alt="Logo" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
            <a href="../contact-us.php"><img src="../assets/icons/phone.png" class="icon"></a>
        </div>    
        <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>

    <div class="recipe-container">
        <div class="left-section">
            <h1 class="recipe-title">
                <?= htmlspecialchars($recipe['basic']['name']) ?>
                <span class="star">⭐</span>
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
        // Extract YouTube video ID with fixed regex pattern
        $url = $recipe['basic']['video_url'];

        function extractYouTubeId($url) {
            // Corrected regular expression pattern
            $pattern = '~
                (?:https?://)?     # Optional protocol
                (?:www\.)?         # Optional www subdomain
                (?:                # Group host alternatives
                  youtube\.com/    # Either youtube.com
                  |youtu\.be/      # or youtu.be
                )
                (?:                # Group path alternatives
                  watch\?v=        # /watch?v=
                  |embed/          # /embed/
                  |v/              # /v/
                  |shorts/         # /shorts/
                  |                # or video ID as path
                )
                ([^"&?/\s]{11})    # Capture the video ID (11 chars)
                ~x';
        
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
            return '';
        }
        
        // Usage:
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