<?php
require '../config/database.php';
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="../css/ecommerce/products.css">
   </head>
<body>

<h1>Our Products</h1>

<?php if (count($products) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><a href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div>
        <p>No products available at the moment. Please check back later!</p>
    </div>
<?php endif; ?>

</body>
</html>
