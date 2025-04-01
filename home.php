<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];

}else{
    header("Location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredient Selector</title>
    <link rel="stylesheet" href="CSS/home2.css">

</head>
<body>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="hapaglogo.jpg" alt="Logo" class="logo">

            <div class="icon-container">
                <a href="./home.php"><img src="assets/icons/home.png" class="icon"></a>
                <a href="./profile/profile.php"><img src="assets/icons/person.png" class="icon"></a>
                <a href="./recipes/recipe-list.php"><img src="assets/icons/recipe.png" class="icon"></a>
                <a href="./contact-us.php"><img src="assets/icons/phone.png" class="icon"></a>
            </div>    

            <a href="logout.php" class="exit-icon"><img src="assets/icons/exit.png" ></a>
        </div>
        
            <!-- Left content: Ingredients and search -->
            <main class="content">
                <div class="search-bar">
                    <input type="text" placeholder="Search ingredients">
                </div>
                
                <div class="main-section">
                    

                    <div class="ingredients-section">

                        <!-- Available Ingredients -->
                        <div class="ingredients-list">

                            <h2>Available Ingredients</h2>

                            <div class="list-box">
                                <label><input type="checkbox"> Almond</label> <br>
                                <label><input type="checkbox"> Ampalaya</label> <br>
                                <label><input type="checkbox"> Annatto Oil</label> <br>
                                <label><input type="checkbox"> Avocado</label> <br>
                                <label><input type="checkbox"> Baking Powder</label> <br>
                                <label><input type="checkbox"> Banana</label> <br>
                                <label><input type="checkbox"> Banana Ketchup</label>
                            </div>
                        </div>

                        <!-- Selected Ingredients -->
                        <div class="ingredients-list">
                            <h2>Selected Ingredients</h2>
                            <div class="list-box"></div>
                        </div>
                        
                        <button class="btn">What can I cook?</button>

                    </div>
                


            </main>
                    <aside class="recipes-container">
                        <h2>2 Recipes Found</h2>

                        <div class="filters">
                            <button class="category-btn">
                               <img src="assets/icons/Filter.png" alt="Filter Icon" class="filter-icon"> Category
                            </button>
                            <label class="checkbox-container">
                                <input type="checkbox" checked id="user-submitted"> 
                                <label for="user-submitted">Include user-submitted recipes</label>
                            </label>
                        </div>

                        <div class="recipe-grid">
                            <div class="recipe-card">
                                <img src="assets/images/bananacue.png" alt="Bananacue">
                                <div class="details">
                                    <div class="header">
                                        <h2>Bananacue</h2>
                                        <span class="favorite">⭐</span>

                                    </div>
                                    <span class="tag">Snack</span>
                                    <p class="author">By:Panglasang Pinoy</p>
                                    <p class="description">
                                        Bananacue is a term used to call fried skewered plantains cooked with brown sugar. 
                                        This is a staple in the Philippines and is mostly consumed as a mid-afternoon snack.

                                    </p>
                                    <p class="info"><strong>Servings:</strong> 3 people</p>
                                    <p class="info"><strong>Prep:</strong> 5 minutes</p>
                                    <p class="info"><strong>Cook:</strong> 7 minutes</p>
                                    <p class="info"><strong>Total:</strong> 12 minutes</p>
                                    <span class="arrow">❯</span>
                                </div>
                            </div>
                            <div class="recipe-card">
                                <img src="assets/images/turon.png" alt="Turon">
                                <div class="details">
                                    <div class="header">
                                        <h2>Turon</h2>
                                        <span class="favorite">⭐</span>
                                    </div>
                                    <span class="tag">Snack | Dessert</span>
                                    <p class="author">By:Panlasang Pinoy</p>
                                    <p class="description">
                                        Deep Fried Banana. This is a popular Filipino street food.
                                    </p>
                                    <p class="info"><strong>Servings:</strong> 6 people</p>
                                    <p class="info"><strong>Prep:</strong> 10 minutes</p>
                                    <p class="info"><strong>Cook:</strong> 12 minutes</p>
                                    <p class="info"><strong>Total:</strong> 22 minutes</p>
                                    <span class="arrow">❯</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>    
        
    </div>

</body>
</html>
