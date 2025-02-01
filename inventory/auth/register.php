<?php
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $password, 'admin'])) {
        header("Location: login.php?success=Account Created. Login now.");
        exit;
    } else {
        echo "Error registering user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title> Register</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <form method="POST">
        <h2>INVENTORY Register Account</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
	<div>
	<a href="login.php" >click here to login</a>
	</div>
</body>
</html>
