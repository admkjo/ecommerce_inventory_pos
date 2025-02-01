<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Login first. <a href='../auth/login.php'>Login</a>");
}

// Fetch cart items before placing the order
$cartItems = $pdo->prepare("
    SELECT c.product_id, c.quantity, p.price 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$cartItems->execute([$_SESSION['user_id']]);
$items = $cartItems->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (count($items) === 0) {
        echo "Your cart is empty.";
        exit;
    }

    // Calculate total price
    $totalPrice = 0;
    foreach ($items as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $totalPrice]);
        $orderId = $pdo->lastInsertId();

        // Insert order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        }

        // Clear the cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        $pdo->commit();
        echo "✅ Order placed successfully!";
        header("Refresh:2; url=order_success.php"); // Redirect after 2 seconds
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("⚠️ Order failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>

<h1>Checkout</h1>

<?php if (count($items) > 0): ?>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        <?php $grandTotal = 0; ?>
        <?php foreach ($items as $item): ?>
            <?php $total = $item['price'] * $item['quantity']; ?>
            <tr>
                <td><?php echo $item['product_id']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            <?php $grandTotal += $total; ?>
        <?php endforeach; ?>
    </table>

    <p><strong>Grand Total:</strong> $<?php echo number_format($grandTotal, 2); ?></p>

    <form method="POST">
        <button type="submit">Confirm Order</button>
    </form>

<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

<a href="products.php">← Back to Shop</a>

</body>
</html>
