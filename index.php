<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS, Inventory & eCommerce System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <!-- Navigation Bar -->
    <header>
        <div class="logo">RADAAH app</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="eCommerce/products.php">Start shopping</a></li>
                    <li><a href="auth/logout.php" class="btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="auth/login.php" class="btn-login">Login</a></li>
                    <li><a href="auth/register.php" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to RADAAH online store</h1>
            <p>Click here to login and start shopping</p>
            <a href="auth/login.php" class="btn-primary">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>Why Choose Our System?</h2>
        <div class="feature-container">
            <div class="feature-card">
                <img src="assets/pos.png" alt="POS System">
                <h3>Point of Sale</h3>
                <p>Fast and efficient sales transactions with invoice generation.</p>
            </div>
            <div class="feature-card">
                <img src="assets/inventory.png" alt="Inventory Management">
                <h3>Inventory Management</h3>
                <p>Track stock levels and manage product categories effortlessly.</p>
            </div>
            <div class="feature-card">
                <img src="assets/ecommerce.png" alt="E-commerce">
                <h3>eCommerce Integration</h3>
                <p>Sell your products online and sync inventory in real-time.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?>. RADAAH POS Inventory eCommerce System | All rights reserved.</p>
    </footer>

</body>
</html>
