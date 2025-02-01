<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Login first. <a href='../auth/login.php'>Login</a>");
}

// Fetch the latest order for the user
$orderQuery = $pdo->prepare("
    SELECT id, total_price, created_at FROM orders 
    WHERE user_id = ? ORDER BY created_at DESC LIMIT 1
");
$orderQuery->execute([$_SESSION['user_id']]);
$order = $orderQuery->fetch();

// Fetch order items
$orderItemsQuery = $pdo->prepare("
    SELECT oi.product_id, oi.quantity, oi.price, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$orderItemsQuery->execute([$order['id']]);
$orderItems = $orderItemsQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    
</head>
<body>

<div >
    <h1>✅ Thank You for Your Order!</h1>
    <p>Your order <strong>#<?php echo $order['id']; ?></strong> was placed successfully on <?php echo date("F j, Y, g:i a", strtotime($order['created_at'])); ?>.</p>

    <h2>Order Summary</h2>
    <?php if (count($orderItems) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>Grand Total: $<?php echo number_format($order['total_price'], 2); ?></p>

    <div >
        <a href="products.php">🛍️ Continue Shopping</a>
    </div>
</div>

</body>
</html>
