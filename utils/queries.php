<?php
/**
 * Home page queries
 */
function fetchRecipesByIngredients($pdo, $selectedIngredients, $minMatch = 1) {
    if (empty($selectedIngredients)) return [];
    
    $placeholders = implode(',', array_fill(0, count($selectedIngredients), '?'));
    $query = "
        SELECT r.*, 
               COUNT(ri.ingredient_id) AS match_count,
               GROUP_CONCAT(DISTINCT rt.tag SEPARATOR ' | ') AS tags
        FROM recipes r
        JOIN recipe_ingredients ri ON r.id = ri.recipe_id
        JOIN ingredients i ON ri.ingredient_id = i.id
        LEFT JOIN recipe_tags rt ON r.id = rt.recipe_id
        WHERE i.name IN ($placeholders)
        GROUP BY r.id
        HAVING match_count >= ?
        ORDER BY match_count DESC
        LIMIT 10
    ";
    
    $params = array_merge($selectedIngredients, [$minMatch]);
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Recipe fetch error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all available ingredients from the database
 */
function getAllIngredients($pdo) {
    try {
        $query = "SELECT name FROM ingredients ORDER BY name";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        // Return just the ingredient names as a simple array
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } catch (PDOException $e) {
        error_log("Ingredient fetch error: " . $e->getMessage());
        return [];
    }
}


function getAllRecipes($pdo) {
    try {
        $query = "
            SELECT r.*, 
                   GROUP_CONCAT(DISTINCT rt.tag SEPARATOR ' | ') AS tags,
                   rd.prep_time, rd.cook_time, rd.servings, rd.calories
            FROM recipes r
            LEFT JOIN recipe_tags rt ON r.id = rt.recipe_id
            LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
            GROUP BY r.id
            ORDER BY r.name
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Recipe fetch error: " . $e->getMessage());
        return [];
    }
}


function addFavoriteRecipe($pdo, $user_id, $recipe_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_favorites (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $recipe_id]);
        return true;
    } catch (PDOException $e) {
        // Check if it's a duplicate entry error
        if ($e->errorInfo[1] == 1062) {
            return true; // Already favorited
        }
        error_log("Error adding favorite: " . $e->getMessage());
        return false;
    }
}

function removeFavoriteRecipe($pdo, $user_id, $recipe_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND recipe_id = ?");
        return $stmt->execute([$user_id, $recipe_id]);
    } catch (PDOException $e) {
        error_log("Error removing favorite: " . $e->getMessage());
        return false;
    }
}

function getFavoriteRecipes($pdo, $user_id) {
    try {
        $query = "
            SELECT r.*, 
                   GROUP_CONCAT(DISTINCT rt.tag SEPARATOR ' | ') AS tags,
                   rd.prep_time, rd.cook_time, rd.servings
            FROM recipes r
            JOIN user_favorites uf ON r.id = uf.recipe_id
            LEFT JOIN recipe_tags rt ON r.id = rt.recipe_id
            LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
            WHERE uf.user_id = ?
            GROUP BY r.id
            ORDER BY uf.created_at DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting favorites: " . $e->getMessage());
        return [];
    }
}

function isRecipeFavorite($pdo, $user_id, $recipe_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking favorite: " . $e->getMessage());
        return false;
    }
}