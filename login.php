<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<?php include("header.php"); ?>
    <div class="login-container">
        <div class="login-card">
            <h1 class="title">Welcome Back!</h1>
            <p class="subtitle">Please log in to continue.</p>
            <form action="actions/authenticate.php" method="POST" class="form">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required placeholder="Enter your username">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="primary-button">Login</button>
            </form>
            <p class="register-link">New here? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>