<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
    <script>
        function validateForm() {
            const pincode = document.getElementById("pincode").value;
            const pincodeRegex = /^\d{6}$/;
            if (!pincodeRegex.test(pincode)) {
                alert("Pincode must be exactly 6 digits.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
<?php include("header.php"); ?>
    <div class="login-container">
        <div class="login-card">
            <h1 class="title">Create an Account</h1>
            <p class="subtitle">Sign up to get started.</p>
            <form action="actions/register_user.php" method="POST" class="form" onsubmit="return validateForm();">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required placeholder="Enter your username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Create a password">
                </div>
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter your full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="contact">Contact Number</label>
                    <input type="text" name="contact" id="contact" required placeholder="Enter your contact number" value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="role">Register as:</label>
                    <div class="role-selection">
                        <label class="role-card">
                            <input type="radio" name="role" value="consumer" <?= (isset($_POST['role']) && $_POST['role'] === 'consumer') ? 'checked' : '' ?> required>
                            <div class="card-content">
                                <img src="assets/consumer.jpg" alt="Consumer">
                                <span>Consumer</span>
                            </div>
                        </label>
                        <label class="role-card">
                            <input type="radio" name="role" value="farmer" <?= (isset($_POST['role']) && $_POST['role'] === 'farmer') ? 'checked' : '' ?> required>
                            <div class="card-content">
                                <img src="assets/farmer.jpg" alt="Farmer">
                                <span>Farmer</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="input-group">
                    <label for="address1">Address Line 1</label>
                    <input type="text" name="address1" id="address1" required placeholder="Enter address line 1" value="<?= htmlspecialchars($_POST['address1'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="address2">Address Line 2</label>
                    <input type="text" name="address2" id="address2" placeholder="Enter address line 2" value="<?= htmlspecialchars($_POST['address2'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" required placeholder="Enter your city" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="state">State</label>
                    <input type="text" name="state" id="state" required placeholder="Enter your state" value="<?= htmlspecialchars($_POST['state'] ?? '') ?>">
                </div>
                <div class="input-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" name="pincode" id="pincode" required placeholder="Enter your pincode" value="<?= htmlspecialchars($_POST['pincode'] ?? '') ?>">
                </div>
                <button type="submit" class="primary-button">Register</button>
            </form>
            <p class="register-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
