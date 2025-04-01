<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Recipes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            width: 100vw;
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
            height: 20px;
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
        .profile-section {
            margin-left: 80px;
            padding: 20px;
            text-align: center;
        }
        .profile-pic {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid white;
        }
        .edit-icon {
            position: absolute;
            top: 10px;
            left: 20%;
            background: white;
            padding: 8px;
            border-radius: 50%;
            color: black;
            font-size: 18px;
            cursor: pointer;
        }
        .email {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        .email i {
            font-size: 18px;
        }
        .tabs {
            display: flex;
            gap: 5px;
            margin: 20px 0;
            justify-content: center;
        }
        .tab {
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            min-width: 160px;
        }
        .active {
            background-color: #ffcc00;
            color: black;
        }
        .inactive {
            background-color: white;
            color: black;
        }
        .recipes-container {
            flex: 1;
            padding: 20px;
        }
        .recipe-card {
            background-color: #444;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        .recipe-img {
            width: 180px;
            height: 130px;
            border-radius: 10px;
            object-fit: cover;
        }
        .recipe-details {
            flex: 1;
        }
        .tag {
            background: #ff6600;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 5px;
        }
        .star {
            color: yellow;
            font-size: 22px;
            cursor: pointer;
        }
        .arrow-icon {
            font-size: 22px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../hapaglogo.jpg" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
            <a href="../contact-us.php"><img src="../assets/icons/phone.png" class="icon"></a>
        </div>    

    <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>
    <div class="profile-section">
        <img src="../images/pfp.png" alt="Chef Clawdia" class="profile-pic">
        <i class="fa-solid fa-pen edit-icon"></i>
        <h2>Chef Clawdia</h2>
        <p>100 followers | 0 following</p>
        <p>Just a cat who cooks when my owner isn't around.</p>
        <div class="email">
            <i class="fa-solid fa-envelope"></i>
            <p>chefclawdia@gmail.com</p>
        </div>
    </div>
    <div class="recipes-container">
        <div class="tabs">
            <div class="tab inactive">Saved</div>
            <div class="tab active">Submitted Recipes</div>
        </div>
        <div class="recipe-card">
            <img src="../images/friedtilapia.png" alt="Fried Tilapia" class="recipe-img">
            <div class="recipe-details">
                <span class="tag">Seafood</span>
                <h3>Fried Tilapia <span class="star">⭐</span></h3>
                <p>By: Chef Clawdia</p>
                <p>My favorite food ever.</p>
                <p><strong>Servings:</strong> 3 | <strong>Prep:</strong> 5 min | <strong>Cook:</strong> 15 min | <strong>Total:</strong> 20 min</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow-icon"></i>
        </div>
    </div>
</body>
</html>
