<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Login first. <a href='../auth/login.php'>Login</a>");
}

if (isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])) {
    $productId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    if ($productId) {
        // Check if product already exists in the cart
        $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $productId]);
        $item = $stmt->fetch();

        if ($item) {
            // Update quantity if product exists
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $productId]);
        } else {
            // Insert new product if it doesn't exist
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->execute([$_SESSION['user_id'], $productId]);
        }
    } else {
        echo "Invalid product ID.";
    }
}

// Fetch cart items with product details
$cartItems = $pdo->prepare("
    SELECT c.product_id, c.quantity, p.name, p.price 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$cartItems->execute([$_SESSION['user_id']]);
$items = $cartItems->fetchAll();

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/ecommerce/cart.css">
   
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (count($items) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): 
                $itemTotal = $item['price'] * $item['quantity'];
                $totalPrice += $itemTotal;
            ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($itemTotal, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Cart Total: $<?php echo number_format($totalPrice, 2); ?></p>
        
        <a href="products.php">Continue Shopping</a>
        <a href="checkout.php">Proceed to Checkout</a>
        


<?php else: ?>
    <p>Your cart is empty.</p>
    <a href="products.php">← Back to Shop</a>
<?php endif; ?>

</body>
</html>
