<?php
    session_start();
    require '../config/database.php';

    if(!isset($_SESSION['user_id']) || empty($_SESSION['role'])) {
        header("Location: ../auth/login.php");
        exit();
    }
    // Check if 'id' is set in the URL and fetch the product details
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$userId = $_GET['id'];

//Fetch user info to be edited
    try{
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo "No user found with ID: " . htmlspecialchars($userId);
            exit();
        }
    
    } catch(PDOException $e){
        error_log("Database query error: " . $e->getMessage());
        echo "An error occurred while fetching user data. Please try again later.";
        exit();
    }

    //Handle updates
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        if (empty($username)) {
            die("Username cannot be empty.");
        }
   
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $targetDir = "../uploads/";
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            //check if file is a real image
            if (!getimagesize($_FILES['image']['tmp_name'])){
                die("File is not a valid image.");
            }

            // Validate file type
            if (!in_array($imageFileType, $allowedTypes)) {
                die("Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // check image file limit
            if ($_FILES['image']['size'] > 5 * 1024 *1024){
                die("File size exceeds the maximum limit of 5MB.");
            }

            $image = uniqid('img_', true) . '.' . $imageFileType;
            $targetFile = $targetDir . $image;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                echo "Image uploaded successfully: " . htmlspecialchars($image);
            } else {
                die("There was an error uploading your file.");
            }
        } else {
            $image = !empty($user['image']) ? $user['image'] : null;
        }

         //Update DB
    $stmt = $pdo->prepare("UPDATE users SET username = ?, image = ? WHERE id = ?");
    $stmt->execute([$username, $image, $userId]);

    header("Location: profile-panel.php?success=Profile updated successfully");
    exit;

        }
    

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">Edit Profile</h1>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">User Name:</label>
                    <input type="text" id="username" name="username" class="form-control" 
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">User Image:</label>
                    <input type="file" id="image" name="image" class="form-control"><br>
                    <?php if (!empty($user['image']) && file_exists("../uploads/" . $user['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($user['image']); ?>" width="100" alt="profile pix">
                <?php else: ?>
                    <p>No profile picture available.</p>
                <?php endif; ?>

                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="profile-panel.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
