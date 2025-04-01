<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes UI</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
        body {
    display: flex;
    font-family: Arial, sans-serif;
    background-color: #222;
    color: white;
    margin: 0;
    padding: 0;
}

.main-content {
    flex-grow: 1;
    padding: 70px;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.actions {
    display: flex;
    gap: 10px;
}

.add-btn {
    width: 40px;
    height: 40px;
    background-color: transparent;
    border: 3px solid #ffcc00;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    position: relative;
}

.add-btn::before, 
.add-btn::after {
    content: "";
    position: absolute;
    background-color: #ffcc00;
}

.add-btn::before {
    width: 60%;
    height: 3px;
}

.add-btn::after {
    width: 3px;
    height: 60%;
}


.search-box {
    padding: 10px;
    border: none;
    border-radius: 20px;
    width: 200px;
}

.recipe-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.recipe-card {
    display: flex;
    background-color: #1a1a1a;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.recipe-card img {
    width: 40%;
    object-fit: cover;
}

.details {
    width: 40%;
    padding: 15px;
    display: flex;
    flex-direction: column;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.favorite {
    color: yellow;
    font-size: 20px;
    cursor: pointer;
}

.tag {
    background: #ff6600;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 10px;
    display: inline-block;
    max-width: 50px;
    text-align: center;
    white-space: nowrap; 
    overflow: hidden; 
    text-overflow: ellipsis;
}

.tag.seafood {
    background: #ff6600;
}

.tag.dessert{
    background:#ff6600;
}

.author {
    font-size: 14px;
    color: #ccc;
}

.description {
    font-size: 14px;
    margin: 5px 0;
}

.info {
    font-size: 13px;
    margin: 2px 0;
}

.arrow {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: white;
    cursor: pointer;
    font-size: 30px;
}

.category-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background-color: #cc6600;
    border: none;
    color: white;
    font-weight: bold;
    font-size: 16px;    
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.filter-icon {
    width: 18px;
    height: 18px;
}

.dropdown-icon {
    width: 14px;
    height: 14px;
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
    <div class="main-content">
        <header>
            <h1>Recipes</h1>
            <div class="actions">

                <button class="category-btn">
                    <svg class="filter-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="white" d="M3 4h18a1 1 0 0 1 .78 1.625l-6.14 7.675V18a1 1 0 0 1-.553.894l-4 2A1 1 0 0 1 10 20v-6.7L3.22 5.625A1 1 0 0 1 3 4z"/>
                    </svg>
                    <span>Category</span>
                    <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="white" d="M7 10l5 5 5-5H7z"/>
                    </svg>
                </button>
                
                
                
                
                <button class="add-btn">➕</button>
                <input type="text" placeholder="Search recipes" class="search-box">
            </div>
        </header>
        <div class="recipe-grid">
            <div class="recipe-card">
                <img src="../images/bananacue.png" alt="Bananaque">
                <div class="details">
                    <div class="header">
                        <h2>Bananacue</h2>
                        <span class="favorite">⭐</span>
                    </div>
                    <span class="tag">Snack</span>
                    <p class="author">By: Panlasang Pinoy</p>
                    <p class="description">
                        Bananacue is a term used to call a fried skewered plantains cooked with brown sugar. 
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
                <img src="../images/friedtilapia.png" alt="Fried Tilapia">
                <div class="details">
                    <div class="header">
                        <h2>Fried Tilapia</h2>
                        <span class="favorite">⭐</span>
                    </div>
                    <span class="tag seafood">Seafood</span>
                    <p class="author">By: Chef Clawdia</p>
                    <p class="description">My favorite fish ever.</p>
                    <p class="info"><strong>Servings:</strong> 3 people</p>
                    <p class="info"><strong>Prep:</strong> 5 minutes</p>
                    <p class="info"><strong>Cook:</strong> 15 minutes</p>
                    <p class="info"><strong>Total:</strong> 20 minutes</p>
                    <span class="arrow">❯</span>
                </div>
            </div>

            <div class="recipe-card">
                <img src="turon.jpg" alt="Turon">
                <div class="details">
                    <div class="header">
                        <h2>Turon</h2>
                        <span class="favorite">⭐</span>
                    </div>
                    <div class="tags">
                        <span class="tag">Snack</span>
                        <span class="tag dessert">Dessert</span>
                    </div>                    
                    <p class="author">By: Panlasang Pinoy</p>
                    <p class="description">Deep-fried banana rolls, a Filipino favorite.</p>
                    <p class="info"><strong>Servings:</strong> 6 people</p>
                    <p class="info"><strong>Prep:</strong> 10 minutes</p>
                    <p class="info"><strong>Cook:</strong> 12 minutes</p>
                    <p class="info"><strong>Total:</strong> 22 minutes</p>
                    <span class="arrow">❯</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>