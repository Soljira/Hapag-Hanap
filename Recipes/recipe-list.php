<?php
require_once '../dbconnect.php';
require_once '../utils/queries.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

try {
    $recipes = getAllRecipes($pdo);
    $tags = getAllUniqueTags($pdo);
} catch (Exception $e) {
    $error = $e->getMessage();
    $recipes = [];
    $tags = [];
}

$selectedTag = $_GET['tag'] ?? null;

if ($selectedTag) {
    $recipes = array_filter($recipes, function($recipe) use ($selectedTag) {
        return strpos($recipe['tags'], $selectedTag) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapag Hanap - Recipes</title>
    <link rel="stylesheet" href="../css/recipe-list.css"> 
    <link rel="icon" href="../hapaglogo.jpg" type="image/ico">
</head>
<body>
    <div class="sidebar">
        <img src="../hapaglogo.jpg" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
        </div>    
        <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>
    
    <div class="main-content">
        <header>
            <h1>All Recipes</h1>
            <div class="actions">
                <div class="tag-dropdown">
                    <button class="filter-btn">
                        <svg class="filter-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="white" d="M3 4h18a1 1 0 0 1 .78 1.625l-6.14 7.675V18a1 1 0 0 1-.553.894l-4 2A1 1 0 0 1 10 20v-6.7L3.22 5.625A1 1 0 0 1 3 4z"/>
                        </svg>
                        <span><?= $selectedTag ? htmlspecialchars($selectedTag) : 'Filter by Tag' ?></span>
                        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="white" d="M7 10l5 5 5-5H7z"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        <a href="?" class="clear-filter">All Tags</a>
                        <?php foreach ($tags as $tag): ?>
                            <a href="?tag=<?= urlencode($tag) ?>" 
                               class="<?= $selectedTag === $tag ? 'active' : '' ?>">
                                <?= htmlspecialchars($tag) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="text" placeholder="Search recipes" class="search-box" id="recipe-search">
            </div>
        </header>
        
        <div class="recipe-grid">
            <?php if (!empty($recipes)): ?>
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card" onclick="window.location.href='selected-recipe.php?id=<?= $recipe['id'] ?>'">
                        <?php if (!empty($recipe['image_path'])): ?>
                            <img src="<?= str_replace('../', '/', $recipe['image_path']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>">
                        <?php endif; ?>
                        <div class="details">
                            <div class="header">
                                <h2><?= htmlspecialchars($recipe['name']) ?></h2>
                            </div>
                            <?php if (!empty($recipe['tags'])): ?>
                                <span class="tag"><?= htmlspecialchars(explode(' | ', $recipe['tags'])[0]) ?></span>
                            <?php endif; ?>
                            <p class="author">By: <?= htmlspecialchars($recipe['author'] ?? 'Unknown') ?></p>
                            <?php if (!empty($recipe['description'])): ?>
                                <p class="description"><?= htmlspecialchars($recipe['description']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($recipe['prep_time']) || !empty($recipe['cook_time'])): ?>
                                <p class="info"><strong>Total Time:</strong> 
                                    <?= ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0) ?> minutes
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($recipe['servings'])): ?>
                                <p class="info"><strong>Servings:</strong> <?= $recipe['servings'] ?></p>
                            <?php endif; ?>
                            <span class="arrow">❯</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <?= isset($error) ? "Error loading recipes: " . htmlspecialchars($error) : "No recipes found" ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Search functionality with tag filtering DO NOT TOUCH VERY IMPORTANT
    document.getElementById('recipe-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const urlParams = new URLSearchParams(window.location.search);
        const selectedTag = urlParams.get('tag');
        
        document.querySelectorAll('.recipe-card').forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            const desc = card.querySelector('.description')?.textContent.toLowerCase() || '';
            const tags = card.querySelector('.tag')?.textContent.toLowerCase() || '';
            
            // This part checks if a recipe card matches the user's search input and selected tag.
            // matchesSearch is true if the recipe's name or description contains the search term.
            // matchesTag is true if no tag is selected or if the recipe's tags include the selected tag (case-insensitive).
            const matchesSearch = name.includes(searchTerm) || desc.includes(searchTerm);
            const matchesTag = !selectedTag || tags.includes(selectedTag.toLowerCase());
            
            card.style.display = (matchesSearch && matchesTag) ? 'flex' : 'none';
        });
    });

    // Highlight selected tag in dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const selectedTag = urlParams.get('tag');
        const searchTerm = urlParams.get('search');

        
        if (selectedTag) {
            document.querySelector('.filter-btn span').textContent = selectedTag;
        }
        
        if (searchTerm) {
            document.getElementById('recipe-search').value = searchTerm;
            document.getElementById('recipe-search').dispatchEvent(new Event('input'));
        }
    });

    
    const filterBtn = document.querySelector('.filter-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    filterBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

        document.addEventListener('click', function() {
        dropdownContent.style.display = 'none';
    });

    dropdownContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    
    </script>
</body>
</html>