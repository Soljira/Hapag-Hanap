<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers</title>
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
        .followers-container {
            flex: 1;
            padding: 20px;
        }
        .followers-title {
            font-size: 24px;
            font-weight: bold;
        }
        .follower {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }
        .follower-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .follower-name {
            font-size: 18px;
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
    <div class="followers-container">
        <h2 class="followers-title">Followers</h2>
        <div class="follower">
            <img src="../images/follower1.png" alt="GingerEats" class="follower-img">
            <p class="follower-name">GingerEats</p>
        </div>
        <div class="follower">
            <img src="../images/follower2.png" alt="Soljira" class="follower-img">
            <p class="follower-name">Soljira</p>
        </div>
        <div class="follower">
            <img src="../images/follower3.png" alt="☆M30W☆" class="follower-img">
            <p class="follower-name">☆M30W☆</p>
        </div>
        <div class="follower">
            <img src="../images/follower1.png" alt="GingerEats" class="follower-img">
            <p class="follower-name">GingerEats</p>
        </div>
        <div class="follower">
            <img src="../images/follower2.png" alt="Soljira" class="follower-img">
            <p class="follower-name">Soljira</p>
        </div>
        <div class="follower">
            <img src="../images/follower3.png" alt="☆M30W☆" class="follower-img">
            <p class="follower-name">☆M30W☆</p>
        </div>
    </div>
</body>
</html>