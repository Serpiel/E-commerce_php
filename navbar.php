<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="style/border.css">
</head>

<body>
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    ?>
    <div class="navbar">
        <div class="search-bar">
            <form action="search.php" method="get" style="display: flex; align-items: center;">
                <input type="text" name="query" placeholder="Search a product..." required>
                <button type="submit" style="background: none; border: none; padding: 0; margin-left: 5px;">
                    <img src="Assets/loupe_icon.gif" alt="Search" class="search-icon">
                </button>
            </form> 
        </div>
        <div class="navbar-left">
        <bold>Serpiel's Shop</bold>
        </div>

        <div class="navbar-right">
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="welcome.php">Home</a>
                <a href="index.php">Store</a>
                <a href="connection.php" class="cart-link">
                    <img src="Assets/connection.png" alt="connection" class="cart-icon" title="Connection">
                </a>
                <a href="inscription.php" class="cart-link">
                    <img src="Assets/inscription.png" alt="inscription" class="cart-icon" title="Inscription">
                </a>
            <?php else: ?>
                <a href="welcome.php">Home</a>
                <a href="index.php">Store</a>
                <a href="account.php" class="cart-link">
                    <img src="Assets/account.png" alt="user" class="cart-icon" title="Account">
                </a>
                <a href="cart.php" class="cart-link">
                    <img src="Assets/cart.png" alt="cart" class="cart-icon" title="Cart">
                </a>
                <a href="logout.php" class="cart-link">
                    <img src="Assets/logout.png" alt="logout" class="cart-icon" title="Logout">
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>