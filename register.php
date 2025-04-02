<?php

session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapag-Hanap</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="hapaglogo.jpg" type="image/ico">

</head>
<body> 
    <div class="container">
        <div class="image-section">
            <h1>Filipino recipes made simple.</h1>
        </div>
        <div class="form-section">
            <img src="hapaglogo.jpg" alt="hapaglogo">
            <div class="title-container">
            <h2>Create an Account</h2>
            <p>Already have an account? <a href="index.php">Log In</a></p>
            </div>

            <?php
                if (isset($errors['user_exist'])) {
                    echo '<div class="error-main">
                            <p>' . $errors['user_exist'] . '</p>
                            </div>';
                            unset($errors['user_exist']);
                }
            ?>

            <form method="POST" action="user-account.php">
                <div class="form-group">
                    <!-- <i class="fas fa-envelope"></i> -->
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <?php
                        if (isset($errors['email'])) {
                            echo '<div class="error-main">
                                    <p>' . $errors['email'] . '</p>
                                    </div>';
                                    unset($errors['email']);
                        }
                    ?>
                </div>
                <div class="form-group">
                    <!-- <i class="fas fa-user"></i> -->
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <!-- <i class="fas fa-lock"></i> -->
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <?php
                        if (isset($errors['password'])) {
                            echo '<div class="error-main">
                                    <p>' . $errors['password'] . '</p>
                                    </div>';
                                    unset($errors['password']);
                        }
                    ?>
                    <!-- <i class="fas fa-eye" id="eyeRegister"></i> -->
                </div>
                <div class="form-group">
                    <!-- <i class="fas fa-lock"></i> -->
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                    <?php
                        if (isset($errors['confirm_password'])) {
                            echo '<div class="error-main">
                                    <p>' . $errors['confirm_password'] . '</p>
                                    </div>';
                                    unset($errors['confirm_password']);
                        }
                    ?>
                    <!-- <i class="fas fa-eye" id="eyeRegisterConfirm"></i> -->
                </div>
                <!-- <button class="btn" type="submit">Create account</button> -->
                <input type="submit" class="btn" value="Create account" name="register"></input>

            </form>

        </div>

    </div>
</body>
</html>

<?php
if(isset($_SESSION['errors'])){
unset($_SESSION['errors']);
}