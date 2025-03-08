<?php
    session_start();
    require '../config/database.php';

    if(!isset($_SESSION['user_id']) || empty($_SESSION['role'])){
        header("Location: auth/login.php"); 
        exit();
    }

$userId = intval($_SESSION['user_id']);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <style>
        /* Center the profile container */
        .profile-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Profile image and placeholder styling */
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #555;
            margin: 0 auto 20px; /* Center and add bottom spacing */
            object-fit: cover;
        }

        /* Centered text */
        h2, p {
            margin: 10px 0;
        }

        /* Button styles (if needed) */
        .btn {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-warning {
            background-color: #f0ad4e;
        }

        .btn-danger {
            background-color: #d9534f;
        }

        .btn-warning:hover, .btn-danger:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if (!empty($user['image'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Picture" class="profile-picture">
        <?php else: ?>
            <div class="profile-picture">
                <?php 
                $initials = strtoupper(substr($user['username'] ?? 'N/A', 0, 1)); 
                echo $initials; 
                ?>
            </div>
        <?php endif; ?>

        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

        <a href="profile-edit.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="profile-delete-product.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" 
        onclick="return confirm('Are you sure?')">Delete</a>
    </div>
</body>
</html>
