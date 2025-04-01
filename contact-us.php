<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        body {
            background-color: #222;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 60px;
            background-color: #D16200;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 10px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
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
        
        .main-content {
            flex: 1;
            margin-left: 60px; /* Same as sidebar width */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        h2 {
            margin: 0;
        }
        
        textarea, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid orange;
            border-radius: 5px;
            background-color: #444;
            color: white;
            resize: none;
            box-sizing: border-box;
        }
        
        .input-group {
            display: flex;
            gap: 15px; /* Increased gap between fields */
            margin-top: 10px;
        }
        
        .input-group > div {
            flex: 1; /* Makes both fields take equal space */
        }
        
        button {
            background-color: yellow;
            color: black;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
            width: 100%;
        }
        
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="hapaglogo.jpg" alt="Logo" class="logo">
        <div class="icon-container">
            <a href="./home.php"><img src="assets/icons/home.png" class="icon"></a>
            <a href="./profile/profile.php"><img src="assets/icons/person.png" class="icon"></a>
            <a href="./recipes/recipe-list.php"><img src="assets/icons/recipe.png" class="icon"></a>
            <a href="./contact-us.php"><img src="assets/icons/phone.png" class="icon"></a>
        </div>    
        <a href="logout.php" class="exit-icon"><img src="assets/icons/exit.png"></a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Contact</h2>
            <p>Encountered a bug? Contact us!</p>
            <label for="message">Message:</label>
            <textarea id="message" rows="4"></textarea>
            
            <div class="input-group">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email">
                </div>
            </div>
            
            <button>SUBMIT →</button>
        </div>
    </div>
</body>
</html>