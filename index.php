<?php
    session_start();
    $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in to Hapag-Hanap</title>
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="hapaglogo.jpg" type="image/ico">

</head>
<body>

<!-- Tutorial used for authentication: https://www.youtube.com/watch?v=mQhu19VmOPo  -->
    <div class="container">
        <div class="image-section">
            <h1>Filipino recipes made simple.</h1>
        </div>

        <div class="form-section">
            <img src="hapaglogo.jpg" alt="hapaglogo">
            
        <div class="form-container">   
            <div class="title-container">
                <h2>Log-in</h2>
                <p>Don't have an account yet? <a href="register.php">Register</a></p>
            
            </div>

            <?php
            if (isset($errors['login'])) {
            echo '<div class="error-main">
                            <p>' . $errors['login'] . '</p>
                            </div>';
            unset($errors['login']);
            }
            ?>

            <form method="POST" action="user-account.php">
                <div class="input-group">
                    <div class="form-group">
                        <div class="input-icon-group">
                            <input type="email" name="email" id="email" placeholder="Email" required>
                            <?php
                                if (isset($errors['email'])) {
                                echo ' <div class="error">
                                            <p>' . $errors['email'] . '</p>
                                        </div>';
                                }
                            ?>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <input type="text" placeholder="Username" required>
                    </div> -->

                    <div class="form-group">
                        <div class="input-icon-group">
                            <!-- <i class="fas fa-lock"></i> -->
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <!-- <i class="fas fa-eye" id="eyeLogin"></i> -->

                            <?php
                                if (isset($errors['password'])) {
                                echo ' <div class="error">
                                            <p>' . $errors['password'] . '</p>
                                        </div>';
                                }
                            ?>
                        </div>
                    </div>
                    

                    <!-- <div class="form-group">
                        <input type="password" placeholder="Confirm Password" required>
                    </div> -->
                    <input type="submit" class="btn" value="Log In" name="login"></input>
                </div>


            </form>
    
        </div>    
        
        </div>
    </div>
</body>
</html>

<?php
if (isset($_SESSION['errors'])) {
  unset($_SESSION['errors']);
}
?>