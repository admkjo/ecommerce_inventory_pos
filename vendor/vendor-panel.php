<?php
session_start();
require '../config/database.php';

// Vendor Authentication Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: auth/login.php");
    exit();
}

// Fetch all Products for display
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h1 class="text-center">Vendor Panel</h1>
    <h2 class="text-center">Manage Products</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="vendor-add-product.php" class="btn btn-primary">Add New Product</a>
        <a href="vendor-dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <a href="vendor-edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="vendor-delete-product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
