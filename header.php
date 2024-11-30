<!-- <?php session_start(); ?> -->
<header class="material-header">
    <!-- Logo Section -->
    <div class="logo">
        <span class="material-icons">eco</span> <span>GreenLeaf</span>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-container">
            <input type="text" placeholder="Search for products, categories..." aria-label="Search">
            <button class="search-button"><span class="material-icons">search</span></button>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <button class="material-button" onclick="window.location.href='cart.php'">
            <span class="material-icons">shopping_cart</span>
            <span>Cart</span>
        </button>
        <button class="material-button" onclick="window.location.href='c_order.php'">
            <span class="material-icons">inventory</span>
            <span>Orders</span>
        </button>

        <!-- User Icon with Dropdown -->
        <div class="user-dropdown">
            <?php if (isset($_SESSION['user_id'])): ?>

                <!-- If user is logged in -->
                <div class="user-icon" onclick="toggleDropdown()">
                    <img src="assets/user_icon.png" alt="User Icon" />
                </div>
                <div class="dropdown-menu" id="dropdown-menu">
                    <p class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <a href="<?php echo $_SESSION['isConsumer'] ? 'c_profile.php' : 'f_profile.php'; ?>" class="dropdown-link">Profile</a>
                    <a href="logout.php" class="dropdown-link">Logout</a>
                </div>
            <?php else: ?>  
                <button class="material-button" onclick="window.location.href='login.php'">
                    <span class="material-icons">person</span>
                    <span>Login</span>
                </button>

            <?php endif; ?>
        </div>
    </div>
</header>

<script>
function toggleDropdown() {
    const dropdownMenu = document.getElementById("dropdown-menu");
    dropdownMenu.classList.toggle("show-dropdown");
}

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    const dropdownMenu = document.getElementById("dropdown-menu");
    const userIcon = document.querySelector(".user-icon");
    if (dropdownMenu && userIcon && !userIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove("show-dropdown");
    }
});
</script>

<link rel="stylesheet" href="styles.css">