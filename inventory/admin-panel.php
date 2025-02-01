<?php
session_start();
require '../config/database.php';

// Admin authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
   header("Location: auth/login.php");
    exit();
}

// Fetch all products for display
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
</head>
<body>

    <h1>Admin Panel</h1>
    <h2>Manage Products</h2>

    <a href="admin-add-product.php">Add New Product</a>

    <table border="1">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><img src="../uploads/<?php echo $product['image']; ?>" width="50" alt=""></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <a href="admin-edit-product.php?id=<?php echo $product['id']; ?>">Edit</a> |
                        <a href="admin-delete-product.php?id=<?php echo $product['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
