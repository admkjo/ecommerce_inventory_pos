<?php
    session_start();
    require '../config/database.php';

    //Vendor Authentication
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
        header(" Location: auth/login.php");
        exit();
    }

    //Check if id is set in the URL and fetch the products details
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("Invalid product ID.");
    }

    $product_id = $_GET['id'];
    $stmt = $pdo -> prepare("SELECT * FROM products WHERE id = ?");
    $stmt -> execute([$product_id]);
    $product = $stmt -> fetch(PDO::FETCH_ASSOC);

    // if product not found, show error
    if (!$product) {
        die("Product not found.");
    }

    //Handle form submission to update the product
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
    }

    //Handle image upload (if a new image is uploaded)
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
    } else{
        $image = $product['image']; //keep original image if not updated
    }

    //Update the product in the database
    $stmt = $pdo -> prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt -> execute([$name, $description, $price, $image, $product_id]);

    header("Location: vendor-panel.php?success=Product updated successfully. ");
        exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
</head>
<body>

    <h1>Edit Product</h1>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required><br>

        <label for="image">Product Image:</label>
        <input type="file" name="image"><br>
        <img src="../uploads/<?php echo $product['image']; ?>" width="100" alt=""><br>

        <button type="submit">Update Product</button>
    </form>

    <a href="vendor-panel.php">Back to Vendor Panel</a>

</body>
</html>

