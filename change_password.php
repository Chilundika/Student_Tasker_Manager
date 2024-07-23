<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $username = $_SESSION['username'];
        $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";

        if ($conn->query($sql) === TRUE) {
            echo "Password updated successfully";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Passwords do not match";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Change Password</h1>
        <a href="logout.php">Logout</a>
    </header>
    <main>
        <form method="POST">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="submit" name="change_password" value="Change Password">
        </form>
    </main>
</body>
</html>
