<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>
<?php include("header.php"); ?>
    <div class="login-container">
        <div class="login-card">
            <h1 class="title">Create an Account</h1>
            <p class="subtitle">Sign up to get started.</p>
            <form action="register_user.php" method="POST" class="form">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter your full name">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Create a password">
                </div>
                <div class="input-group">
                    <label for="role">Register as:</label>
                    <div class="role-selection">
                        <label class="role-card">
                            <input type="radio" name="role" value="consumer" required>
                            <div class="card-content">
                                <img src="assets/consumer.jpg" alt="Consumer">
                                <span>Consumer</span>
                            </div>
                        </label>
                        <label class="role-card">
                            <input type="radio" name="role" value="farmer" required>
                            <div class="card-content">
                                <img src="assets/farmer.jpg" alt="Farmer">
                                <span>Farmer</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" required placeholder="Enter your city">
                </div>
                <div class="input-group">
                    <label for="address1">Address Line 1</label>
                    <input type="text" name="address1" id="address1" required placeholder="Enter address line 1">
                </div>
                <div class="input-group">
                    <label for="address2">Address Line 2</label>
                    <input type="text" name="address2" id="address2" placeholder="Enter address line 2">
                </div>
                <div class="input-group">
                    <label for="state">State</label>
                    <input type="text" name="state" id="state" required placeholder="Enter your state">
                </div>
                <div class="input-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" name="pincode" id="pincode" required placeholder="Enter your pincode">
                </div>
                <button type="submit" class="primary-button">Register</button>
            </form>
            <p class="register-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>