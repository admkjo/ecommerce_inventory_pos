<?php
session_start();
require '../config/database.php';

// Vendor authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: auth/login.php");
    exit();
}

// Check if product ID is set and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, show error
if (!$product) {
    die("Product not found.");
}

// Handle the deletion when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);

    // Optionally delete the image file from the server
    $imagePath = "../uploads/" . $product['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Redirect back to the vendor panel
    header("Location: vendor-panel.php?success=Product deleted successfully.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Product</title>
</head>
<body>

    <h1>Delete Product</h1>

    <p>Are you sure you want to delete the following product?</p>

    <div>
        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="100" alt="">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
    </div>

    <form method="POST">
        <button type="submit">Yes, Delete Product</button>
        <a href="vendor-panel.php">Cancel</a>
    </form>

</body>
</html>
