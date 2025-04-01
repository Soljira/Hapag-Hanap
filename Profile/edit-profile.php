<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        .profile-container {
            position: relative;
            display: inline-block;
        }
        .profile-pic {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid white;
        }
        .verify-button {
            position: absolute;
            top: 10px;
            right: -20px;
            background: #333;
            color: rgb(21, 252, 0);
            border: none;
            border-radius: 50%;
            padding: 5px;
            font-size: 20px;
            cursor: pointer;
        }
        .profile-name {
            border: 3px solid white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            font-size: 18px;
            margin-top: 10px;
        }
        .followers {
            font-size: 14px;
            margin: 8px 0;
        }
        .bio {
            border: 2px solid white;
            padding: 10px;
            border-radius: 15px;
            font-size: 14px;
            display: inline-block;
            max-width: 80%;
        }
        .email-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid white;
            padding: 8px 12px;
            border-radius: 15px;
            color: white;
            margin: 15px auto;
            width: 80%;
        }
        .email-box i {
            font-size: 16px;
        }
        .email-box .delete-icon {
            color: red;
            cursor: pointer;
        }
        .add-icon {
            display: inline-block;
            border: 2px solid white;
            padding: 10px;
            border-radius: 50%;
            font-size: 20px;
            margin-top: 10px;
            cursor: pointer;
        }
        .recipes-container {
            flex: 1;
            padding: 20px;
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

    <div class="profile-section">
        <div class="profile-container">
            <img src="../images/pfp.png" alt="Chef Clawdia" class="profile-pic">
            <button class="verify-button">
                <i class="fa-solid fa-check"></i>
            </button>
        </div>
        <h2 class="profile-name">Chef Clawdia</h2>
        <p class="followers">100 followers | 0 following</p>
        <p class="bio">Just a cat who cooks when my owner isn’t around.</p>

        <div class="email-box">
            <i class="fa-solid fa-envelope"></i>
            <p>chefclawdia@gmail.com</p>
            <i class="fa-solid fa-xmark delete-icon"></i>
        </div>

        <i class="fa-solid fa-plus add-icon"></i>
    </div>


    <div class="recipes-container">
        <div class="tabs">
            <div class="tab active">Saved</div>
            <div class="tab inactive">Submitted Recipes</div>
        </div>

        <div class="recipe-card">
            <img src="../images/bananacue.png" alt="Bananacue" class="recipe-img">
            <div class="recipe-details">
                <span class="tag">Snack</span>
                <h3>Bananacue <span class="star">⭐</span></h3>
                <p>By: Panlasang Pinoy</p>
                <p>Bananacue is term used to call fried skewered plantains cooked with brown sugar.</p>
                <p><strong>Servings:</strong> 3 | <strong>Prep:</strong> 5 min | <strong>Cook:</strong> 7 min | <strong>Total:</strong> 12 min</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow-icon"></i>
        </div>
    </div>
</body>
</html>
