<?php
    session_start();
    require '../config/database.php';

    //Vendor authentication check
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: auth/login.php");
        exit();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Get form data
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Handle file upload for product image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            //Define the target directory
            $targetDir = "../uploads/";
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            $imageFileTYPE = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an image
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check === false) {
                die("File is not an image.");
            }

        //Allow certain file formats (e.g. JPG, JPEG, PNG, GIF)
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        //Attempt to move the uploaded file
        if ( move_uploaded_file($_FILES['image']['tmp_name'],$targetFile)) {
            $image = basename($_FILES['image']['name']);
        } else {
            die("Sorry, there was an error uploading your file.");
        } 
        } else {
            $image = null;
        }

        //Insert the product into the database
        $stmt = $pdo -> prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?) ");
        If ($stmt -> execute ([$name, $description, $price, $image])) {
            header("Location: admin-panel.php?success=Product added successfully.");
            exit;
        } else {
            echo "Error adding product.";
        }
        
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
</head>
<body>

    <h1>Add New Product</h1>

    <form action="vendor-add-product.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>
        </div>
        <div>
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        <div>
            <button type="submit">Add Product</button>
        </div>
    </form>

    <p><a href="vendor-panel.php">Back to Admin Panel</a></p>

</body>
</html>
