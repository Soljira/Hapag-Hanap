<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../dbconnect.php';
require_once '../utils/queries.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$favorites = getFavoriteRecipes($pdo, $user_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Handle profile picture upload
    $profile_pic = $user['profile_pic'] ?? '../images/pfp.png';
    
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $new_filename;
        
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = $target_file;
            }
        }
    }
    
    // Update database
    $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_pic = ?, description = ? WHERE id = ?");
    $stmt->execute([$username, $profile_pic, $description, $user_id]);
    
    // Refresh page
    header("Location: profile.php");
    exit();
}
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
        </div>    
        <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>
    
    <div class="profile-section">
        <div class="profile-display">
            <div class="profile-pic-container">
                <img src="<?= htmlspecialchars($user['profile_pic'] ?? '../images/pfp.png') ?>" alt="Profile Picture" class="profile-pic">
                <i class="fa-solid fa-pen edit-icon" id="edit-toggle"></i>
            </div>
            
            <h2 id="username-display"><?= htmlspecialchars($user['username'] ?? 'Chef Clawdia') ?></h2>
            <p id="description-display"><?= htmlspecialchars($user['description'] ?? 'Just a cat who cooks when my owner isn\'t around.') ?></p>
            
            <div class="email">
                <i class="fa-solid fa-envelope"></i>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
        
        <form method="POST" enctype="multipart/form-data" id="profile-form" class="hidden">
            <div class="profile-pic-container">
                <img src="<?= htmlspecialchars($user['profile_pic'] ?? '../images/pfp.png') ?>" alt="Profile Picture" class="profile-pic" id="profile-pic-preview">
                <label for="profile-pic-input" class="edit-icon">
                    <i class="fa-solid fa-pen"></i>
                </label>
                <input type="file" id="profile-pic-input" name="profile_pic" accept="image/*" style="display: none;">
            </div>
            
            <div class="form-group">
                <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? 'Chef Clawdia') ?>" class="profile-input">
            </div>
            
            <!-- <p class="followers">100 followers | 0 following</p> -->
            
            <div class="form-group">
                <textarea name="description" class="profile-textarea"><?= htmlspecialchars($user['description'] ?? 'Just a cat who cooks when my owner isn\'t around.') ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" id="cancel-edit" class="cancel-btn">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
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
            <h3 class="recipe-name"><?= htmlspecialchars($recipe['name']) ?></h3>
            
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

    <script>
        const editToggle = document.getElementById('edit-toggle');
        const profileDisplay = document.querySelector('.profile-display');
        const profileForm = document.getElementById('profile-form');
        const cancelEdit = document.getElementById('cancel-edit');
        
        // Toggle edit mode
        editToggle.addEventListener('click', () => {
            profileDisplay.classList.add('hidden');
            profileForm.classList.remove('hidden');
        });
        
        // Cancel edit
        cancelEdit.addEventListener('click', () => {
            profileForm.classList.add('hidden');
            profileDisplay.classList.remove('hidden');
        });
        
        // Profile picture preview
        document.getElementById('profile-pic-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile-pic-preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</div>
</body>
</html>
