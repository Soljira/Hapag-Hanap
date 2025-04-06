<?php
ob_start();
session_start();

header('Content-Type: application/json');

error_log('Session data: ' . print_r($_SESSION, true));

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Please login to favorite recipes']));
}

require_once '../dbconnect.php';
require_once '../utils/queries.php';

$recipe_id = filter_input(INPUT_POST, 'recipe_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$recipe_id) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid recipe ID']));
}

try {
    $is_favorite = isRecipeFavorite($pdo, $_SESSION['user_id'], $recipe_id);
    
    if ($is_favorite) {
        $success = removeFavoriteRecipe($pdo, $_SESSION['user_id'], $recipe_id);
        $message = 'Recipe removed from favorites';
    } else {
        $success = addFavoriteRecipe($pdo, $_SESSION['user_id'], $recipe_id);
        $message = 'Recipe added to favorites!';
    }
    
    die(json_encode([
        'success' => $success,
        'is_favorite' => !$is_favorite,
        'message' => $message
    ]));
    
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database error']));
}