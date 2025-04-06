<?php
require_once '../dbconnect.php';

$json = file_get_contents('dummy.json');  // replace with foodsDB.json, foodcategoies.json, etc
// i put dummy.json for safetyreasons
$recipes = json_decode($json, true);

try {
    $pdo->beginTransaction();

    foreach ($recipes as $recipe) {
        // 1. Find the recipe ID by name (or other unique identifier)
        $stmt = $pdo->prepare("SELECT id FROM recipes WHERE name = :name");
        $stmt->execute([':name' => $recipe['name']]);
        $recipe_id = $stmt->fetchColumn();

        if (!$recipe_id) {
            continue; // Skip if recipe doesn't exist
        }

        // 2. Delete existing instructions for this recipe
        $stmt = $pdo->prepare("DELETE FROM recipe_instructions WHERE recipe_id = :recipe_id");
        $stmt->execute([':recipe_id' => $recipe_id]);

        // 3. Insert new instructions (with auto-numbered steps)
        if (!empty($recipe['recipe']['instructions'])) {
            $step_number = 1;
            foreach ($recipe['recipe']['instructions'] as $instruction_text) {
                $stmt = $pdo->prepare("INSERT INTO recipe_instructions 
                    (recipe_id, step_number, instruction) 
                    VALUES (:recipe_id, :step, :instruction)");
                
                $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':step' => $step_number,
                    ':instruction' => $instruction_text
                ]);
                $step_number++;
            }
            echo "Updated instructions for recipe ID: $recipe_id (" . $recipe['name'] . ")\n";
        }
    }

    $pdo->commit();
    echo "Successfully updated instructions!";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}