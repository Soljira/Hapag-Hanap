<!-- DO NOT RUN THIS SCRIPT ANYMORE KASI NAIMPORT NA LAHAT -->

<?php
require_once '../dbconnect.php'; 

$json = file_get_contents('dummy.json');
$recipes = json_decode($json, true);

try {
    $pdo->beginTransaction();

    foreach ($recipes as $recipe) {
        // Insert into recipes table (corrected columns)
        $stmt = $pdo->prepare("INSERT INTO recipes 
            (name, tagalog_name, description, video_url, image_path, author) 
            VALUES (:name, :tagalog, :description, :video, :image, :author)");
        
        $stmt->execute([
            ':name' => $recipe['name'],
            ':tagalog' => $recipe['tagalog'],
            ':description' => $recipe['description'],
            ':video' => $recipe['video'],
            ':image' => $recipe['image'],
            ':author' => $recipe['author']
        ]);
        
        $recipe_id = $pdo->lastInsertId();

        // Insert into recipe_details
        if (isset($recipe['recipe']['details'])) {
            $stmt = $pdo->prepare("INSERT INTO recipe_details 
                (recipe_id, prep_time, cook_time, total_time, servings, calories) 
                VALUES (:recipe_id, :prep, :cook, :total, :servings, :calories)");
            
            $details = $recipe['recipe']['details'];
            $time = $details['time'] ?? ['prep' => null, 'cook' => null, 'total' => null];
            
            $stmt->execute([
                ':recipe_id' => $recipe_id,
                ':prep' => $time['prep'],
                ':cook' => $time['cook'],
                ':total' => $time['total'],
                ':servings' => $details['servings'] ?? null,
                ':calories' => $details['calories'] ?? null
            ]);
        }

        // Insert into recipe_information
        if (!empty($recipe['information'])) {
            foreach ($recipe['information'] as $info) {
                $stmt = $pdo->prepare("INSERT INTO recipe_information 
                    (recipe_id, title, content) 
                    VALUES (:recipe_id, :title, :content)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':title' => $info['title'] ?? '',
                    ':content' => $info['content'] ?? ''
                ]);
            }
        }

        // Insert ingredients
        if (!empty($recipe['recipe']['ingredients'])) {
            foreach ($recipe['recipe']['ingredients'] as $ingredient) {
                // Check if ingredient exists
                $checkStmt = $pdo->prepare("SELECT id FROM ingredients WHERE name = :name");
                $checkStmt->execute([':name' => $ingredient['name']]);
                $ingredient_id = $checkStmt->fetchColumn();

                if (!$ingredient_id && !empty($ingredient['name'])) {
                    // Insert new ingredient if not found
                    $stmt = $pdo->prepare("INSERT INTO ingredients 
                        (name, tagalog_name, image_path) 
                        VALUES (:name, :tagalog, :image)");
                    
                    $stmt->execute([
                        ':name' => $ingredient['name'],
                        ':tagalog' => $ingredient['tagalog'] ?? null,
                        ':image' => $ingredient['image'] ?? null
                    ]);
                    $ingredient_id = $pdo->lastInsertId();
                }

                if ($ingredient_id) {
                    // Insert into recipe_ingredients
                    $stmt = $pdo->prepare("INSERT INTO recipe_ingredients 
                        (recipe_id, ingredient_id, name, amount, tagalog_name, type, image_path) 
                        VALUES (:recipe_id, :ingredient_id, :name, :amount, :tagalog, :type, :image)");
                    
                    $stmt->execute([
                        ':recipe_id' => $recipe_id,
                        ':ingredient_id' => $ingredient_id,
                        ':name' => $ingredient['name'],
                        ':amount' => $ingredient['amount'] ?? null,
                        ':tagalog' => $ingredient['tagalog'] ?? null,
                        ':type' => $ingredient['type'] ?? null,
                        ':image' => $ingredient['image'] ?? null
                    ]);
                }
            }
        }

        // Insert instructions
        if (!empty($recipe['recipe']['instructions'])) {
            foreach ($recipe['recipe']['instructions'] as $instruction) {
                $stmt = $pdo->prepare("INSERT INTO recipe_instructions 
                    (recipe_id, step_number, instruction) 
                    VALUES (:recipe_id, :step, :instruction)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':step' => $instruction['step'] ?? 0,
                    ':instruction' => $instruction['instruction'] ?? ''
                ]);
            }
        }

        // Insert nutrition
        if (!empty($recipe['recipe']['nutrition'])) {
            foreach ($recipe['recipe']['nutrition'] as $nutrition) {
                $stmt = $pdo->prepare("INSERT INTO recipe_nutrition 
                    (recipe_id, name, amount) 
                    VALUES (:recipe_id, :name, :amount)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':name' => $nutrition['name'] ?? '',
                    ':amount' => $nutrition['amount'] ?? ''
                ]);
            }
        }

        // Insert tags
        if (!empty($recipe['type'])) {
            foreach ($recipe['type'] as $tag) {
                $stmt = $pdo->prepare("INSERT INTO recipe_tags 
                    (recipe_id, tag) 
                    VALUES (:recipe_id, :tag)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':tag' => $tag
                ]);
            }
        }

        // Insert special tags
        if (!empty($recipe['special'])) {
            foreach ($recipe['special'] as $tag) {
                $stmt = $pdo->prepare("INSERT INTO recipe_special_tags 
                    (recipe_id, tag) 
                    VALUES (:recipe_id, :tag)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':tag' => $tag
                ]);
            }
        }
    }

    $pdo->commit();
    echo "Successfully imported " . count($recipes) . " recipes!";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}