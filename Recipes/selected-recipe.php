<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bananacue Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            width: 100vw;
            height: 100vh;
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
    height:20px;
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

        .recipe-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: calc(100% - 100px);
            max-width: 1200px;
            padding: 20px;
            margin-left: 100px;
        }

        .left-section {
            width: 55%;
        }

        .right-section {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .recipe-title {
            font-size: 32px;
            font-weight: bold;
        }

        .star {
            color: yellow;
            font-size: 24px;
            cursor: pointer;
        }

        .tag {
            background: #ff6600;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 14px;
            display: inline-block;
            margin-top: 5px;
        }

        .instructions, .ingredients {
            margin-top: 20px;
        }

        .recipe-image img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <img src="../hapaglogo.jpg" alt="Logo" class="logo">
        <div class="icon-container">
            <a href="../home.php"><img src="../assets/icons/home.png" class="icon"></a>
            <a href="../profile/profile.php"><img src="../assets/icons/person.png" class="icon"></a>
            <a href="../recipes/recipe-list.php"><img src="../assets/icons/recipe.png" class="icon"></a>
            <a href="../contact-us.php"><img src="../assets/icons/phone.png" class="icon"></a>
        </div>    

    <a href="../logout.php" class="exit-icon"><img src="../assets/icons/exit.png"></a>
    </div>

    <div class="recipe-container">
        <div class="left-section">
            <h1 class="recipe-title">Bananacue <span class="star">⭐</span></h1>
            <p class="tag">Snack</p>
            <p>By: Panlasang Pinoy</p>
            <p>Bananacue is a term used to call fried skewered plantains cooked with brown sugar. This is a staple in the Philippines, mostly consumed as a mid-afternoon snack.</p>
            <p><strong>Servings:</strong> 3 people</p>
            <p><strong>Prep:</strong> 5 minutes</p>
            <p><strong>Cook:</strong> 7 minutes</p>
            <p><strong>Total:</strong> 12 minutes</p>

            <div class="instructions">
                <h2>Instructions</h2>
                <ol>
                    <li>Heat a cooking pot then pour in cooking oil.</li>
                    <li>When the oil becomes hot, deep-fry the bananas for 2 minutes.</li>
                    <li>Gradually put in the brown sugar, adjust the heat to medium-low, and continue cooking until the melted brown sugar coats the bananas.</li>
                    <li>Remove the cooked bananas one by one and immediately skewer them using a bamboo skewer.</li>
                    <li>Let cool, then serve with cold soda.</li>
                </ol>
            </div>
        </div>

        <div class="right-section">
            <div class="recipe-image">
                <img src="../images/bananacue.png" alt="Bananacue">
            </div>
            <div class="ingredients">
                <h2>Ingredients</h2>
                <ul>
                    <li>6 pieces banana</li>
                    <li>2 cups brown sugar</li>
                    <li>4 cups cooking oil</li>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>
