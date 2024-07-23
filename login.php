<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['user_id'] = $row['id']; // Store the user_id in the session

                if ($row['role'] == 'admin') {
                    header("Location: admin_portal.php");
                } elseif ($row['role'] == 'student') {
                    header("Location: student_portal.php");
                } elseif ($row['role'] == 'lecturer') {
                    header("Location: lecturer_portal.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that username.";
        }
    } elseif (isset($_POST['validate_username'])) {
        $reset_username = $_POST['reset_username'];

        $sql = "SELECT * FROM users WHERE username = '$reset_username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['reset_username'] = $reset_username;
            $reset_stage = 2;
        } else {
            $error = "User does not exist! Enter a valid username.";
            $reset_stage = 1;
        }
    } elseif (isset($_POST['reset_password'])) {
        $new_password = $_POST['new_password'];
        $reset_username = $_SESSION['reset_username'];

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$reset_username'";

        if ($conn->query($sql) === TRUE) {
            $reset_stage = 3;
        } else {
            $error = "Error updating password: " . $conn->error;
            $reset_stage = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - StudentTasker: Progress Manager</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Login to StudentProgress_Tasker</h1>
        <div class="nav-container" style="margin-left: auto;">
            <nav>
                <ul style="list-style-type: none; margin: 0; padding: 0; display: flex; align-items: center;">
                    <li style="text-align: right;">
                        <a href="index.php" style="background-color: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Home</a>
                        <a href="login.php" style="background-color: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">refresh</a>

                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <main>
            <?php if (!empty($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!isset($reset_stage) || $reset_stage == 1): ?>
            <form method="POST" action="login.php">
                <img src="images/logo1.png" alt="login-logo" width="50px" height="50px" style="display: block; margin: 0 auto;">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                
                <input type="submit" name="login" value="Login">
            </form>
            
            <form method="POST" action="login.php">
                <hr>
                <label for="reset_username">Enter correct Username to Reset Password:</label>
                <input type="text" name="reset_username" id="reset_username" required>
                <input type="submit" name="validate_username" value="Reset Password" style="background-color:lightcoral;">
            </form>
            
            <div class="mt-20">
                <br>
                <hr>
                <p>Don't have an account? <a href="registration.php">REGISTER</a></p>
            </div>
            
            <?php elseif ($reset_stage == 2): ?>
            <form method="POST" action="login.php">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
                <input type="submit" name="reset_password" value="Update Password">
            </form>
            <?php elseif ($reset_stage == 3): ?>
            <div class="message success">Password has been updated successfully!</div>
            <div class="mt-20">
                <a href="login.php" class="button">Login</a>
                <a href="index.php" class="button">Exit</a>
            </div>
            <?php endif; ?>
        </main>
    </div><br><br>
    <footer>
        &copy; 2024-Chipo Chilundika_StudentTasker: Progress Manager. All rights reserved.
    </footer>
</body>
</html>
