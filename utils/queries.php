<!-- 1. Get All Recipes with Basic Information
sql
Copy

SELECT r.id, r.name, r.tagalog_name, r.description, r.image_path, 
       rd.prep_time, rd.cook_time, rd.servings, rd.calories
FROM recipes r
LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
ORDER BY r.name;

2. Search Recipes by Ingredient
sql
Copy

SELECT r.id, r.name, r.image_path
FROM recipes r
JOIN recipe_ingredients ri ON r.id = ri.recipe_id
JOIN ingredients i ON ri.ingredient_id = i.id
WHERE i.name LIKE '%chicken%'  -- Replace with search term
GROUP BY r.id;

3. Get Complete Recipe with All Related Data
sql
Copy

-- Recipe basic info
SELECT * FROM recipes WHERE id = 1;

-- Recipe details
SELECT * FROM recipe_details WHERE recipe_id = 1;

-- Recipe ingredients
SELECT ri.*, i.image_path as ingredient_image 
FROM recipe_ingredients ri
LEFT JOIN ingredients i ON ri.ingredient_id = i.id
WHERE ri.recipe_id = 1;

-- Recipe instructions
SELECT * FROM recipe_instructions 
WHERE recipe_id = 1 
ORDER BY step_number;

-- Recipe nutrition
SELECT * FROM recipe_nutrition 
WHERE recipe_id = 1;

-- Recipe tags
SELECT tag FROM recipe_tags WHERE recipe_id = 1;
SELECT tag FROM recipe_special_tags WHERE recipe_id = 1;

4. Get Recipes by Cooking Time (Quick Meals)
sql
Copy

SELECT r.id, r.name, r.image_path, 
       (rd.prep_time + rd.cook_time) AS total_time
FROM recipes r
JOIN recipe_details rd ON r.id = rd.recipe_id
WHERE (rd.prep_time + rd.cook_time) <= 30  -- 30 minutes or less
ORDER BY total_time;

5. Get Recipes by Category/Tag
sql
Copy

SELECT r.id, r.name, r.image_path
FROM recipes r
JOIN recipe_tags rt ON r.id = rt.recipe_id
WHERE rt.tag = 'Filipino'  -- Replace with desired tag
GROUP BY r.id;

6. Get Popular Ingredients (Statistics)
sql
Copy

SELECT i.name, COUNT(ri.recipe_id) AS recipe_count
FROM ingredients i
JOIN recipe_ingredients ri ON i.id = ri.ingredient_id
GROUP BY i.name
ORDER BY recipe_count DESC
LIMIT 10;

7. Get Recipes with Nutrition Constraints
sql
Copy

SELECT r.id, r.name, r.image_path, rd.calories
FROM recipes r
JOIN recipe_details rd ON r.id = rd.recipe_id
WHERE rd.calories <= 500  -- Low-calorie recipes
ORDER BY rd.calories;

8. Get Recently Added Recipes
sql
Copy

SELECT id, name, image_path, created_at
FROM recipes
ORDER BY created_at DESC
LIMIT 5;

9. Get Recipes by Author
sql
Copy

SELECT id, name, image_path, description
FROM recipes
WHERE author LIKE '%Juan Dela Cruz%'  -- Replace with author name
ORDER BY name;

10. Get Recipes with Video Tutorials
sql
Copy

SELECT id, name, video_url, image_path
FROM recipes
WHERE video_url IS NOT NULL;

11. Complex Search (Multiple Criteria)
sql
Copy

SELECT DISTINCT r.id, r.name, r.image_path
FROM recipes r
LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
LEFT JOIN ingredients i ON ri.ingredient_id = i.id
LEFT JOIN recipe_tags rt ON r.id = rt.recipe_id
WHERE 
  (rd.prep_time + rd.cook_time) <= 60  -- Under 1 hour
  AND rd.calories <= 800               -- Under 800 calories
  AND rt.tag = 'Filipino'              -- Filipino cuisine
  AND i.name LIKE '%chicken%'          -- Contains chicken
ORDER BY r.name;

12. Get Nutritional Summary for a Recipe
sql
Copy

SELECT 
  MAX(CASE WHEN name = 'Calories' THEN amount END) AS calories,
  MAX(CASE WHEN name = 'Protein' THEN amount END) AS protein,
  MAX(CASE WHEN name = 'Carbohydrates' THEN amount END) AS carbs,
  MAX(CASE WHEN name = 'Fat' THEN amount END) AS fat
FROM recipe_nutrition
WHERE recipe_id = 1; -->



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