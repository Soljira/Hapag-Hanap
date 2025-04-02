<?php
// First, determine if this is an API request or a page request
$isApiRequest = ($_SERVER['REQUEST_METHOD'] === 'POST' && 
                 strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false);

if ($isApiRequest) {
    // Handle API request - start output buffering to capture any errors
    ob_start();
    
    header('Content-Type: application/json');
    session_start();
    
    if (!isset($_SESSION['user'])) {
        ob_end_clean(); // Clear any previous output
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit();
    }
    
    require_once './dbconnect.php';
    require_once './utils/queries.php';
    
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }
        
        $selectedIngredients = $data['ingredients'] ?? [];
        $minMatch = $data['minMatch'] ?? 3;
    
        // Validate: Require at least 3 ingredients
        if (count($selectedIngredients) < 3) {
            throw new Exception("At least 3 ingredients are required.");
        }
    
        $recipes = fetchRecipesByIngredients($pdo, $selectedIngredients, $minMatch);

        foreach ($recipes as &$recipe) {
            if (isset($recipe['image_path'])) {
                $recipe['image_path'] = str_replace('../', '/', $recipe['image_path']);
            }
        }
        
        
        // Clean output buffer and send clean JSON response
        ob_end_clean();
        echo json_encode([
            'success' => true,
            'data' => $recipes
        ]);
        exit();
    } catch (Exception $e) {
        // Clean output buffer and send error JSON
        ob_end_clean();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit();
    }
} 
else {
    // Regular HTML page rendering
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    
    require_once './dbconnect.php';
    require_once './utils/queries.php';
    
    // Get the list of ingredients for the page
    try {
        $ingredients = getAllIngredients($pdo); // Assuming this function exists
    } catch (Exception $e) {
        $ingredients = []; // Default to empty if there's an error
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapag Hanap - Home</title>
    <link rel="stylesheet" href="css/home2.css">
    <link rel="icon" href="hapaglogo.jpg" type="image/ico">
    <script src='./js/home.js'></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="hapaglogo.jpg" alt="Logo" class="logo">

            <div class="icon-container">
                <a href="./home.php"><img src="assets/icons/home.png" class="icon"></a>
                <a href="./profile/profile.php"><img src="assets/icons/person.png" class="icon"></a>
                <a href="./recipes/recipe-list.php"><img src="assets/icons/recipe.png" class="icon"></a>
                <!-- <a href="./contact-us.php"><img src="assets/icons/phone.png" class="icon"></a> -->
            </div>    

            <a href="logout.php" class="exit-icon"><img src="assets/icons/exit.png" ></a>
        </div>
        
        <!-- Left content: Ingredients and search -->
        <main class="content">
            <div class="search-bar">
                <input type="text" placeholder="Search ingredients" id="ingredient-search">
            </div>
            
            <div class="main-section">
                <div class="ingredients-section">
                    <!-- Available Ingredients -->
                    <div class="ingredients-list">
                        <h2>Available Ingredients</h2>
                        <div class="list-box" id="available-ingredients">
                            <?php if (isset($ingredients) && is_array($ingredients)): ?>
                                <?php foreach ($ingredients as $ingredient): ?>
                                    <label>
                                        <input type="checkbox" name="ingredient" value="<?= htmlspecialchars($ingredient) ?>">
                                        <?= htmlspecialchars(ucwords(str_replace('-', ' ', $ingredient))) ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No ingredients available</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Selected Ingredients -->
                    <div class="ingredients-list">
                        <h2>Selected Ingredients</h2>
                        <div class="list-box" id="selected-ingredients"></div>
                    </div>

                    <button class="btn" id="find-recipes">What can I cook?</button>
                </div>
            </div>
        </main>

        <aside class="recipes-container">
            <h2>Recipes</h2>

            <div class="filters">
                <!-- <button class="category-btn">
                   <img src="assets/icons/Filter.png" alt="Filter Icon" class="filter-icon"> Category
                </button> -->
            </div>

            <div class="recipe-grid">
                <!-- Recipe cards will be dynamically inserted by JavaScript -->
            </div>
        </aside>
    </div>
</body>
</html>