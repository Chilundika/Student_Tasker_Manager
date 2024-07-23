<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>registration</title>
        <link rel="icon" href="images/favicon1.png" type="image/png">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header style="display: flex; justify-content: space-between; align-items: center; background-color: #4CAF50; padding: 10px;">
            <img src="images/logo1.png" alt="logo" width="60px" height="60px" style="margin-right: 15px;">
            <h1 style="font-size: 30px; color: white; margin: 0;text-align:left;">StudentTasker: Progress Manager</h1>
    <div class="nav-container" style="margin-left: auto;">
        <nav>
            <ul style="list-style-type: none; margin: 0; padding: 0; display: flex; align-items: center;">
                <li style="text-align: right;">
                    <a href="login.php" style="background-color: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login</a>
                </li>
            </ul>
        </nav>
    </div>
        
        
        </header>
 
<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
      <div class="container">
        <main>
             <form method="POST">
                   Username: <input type="text" name="username" required><br>
                   Password: <input type="password" name="password" required><br>
                   Role: 
                   <select name="role">
                   <option value="" disabled selected style="color:lightslategray">Select role type..</option>
                   <option value="student">Student</option>
                   <!--option value="lecturer">Lecturer</option-->
                   <!--option value="admin">Admin</option-->
                   </select><br>
                   <input type="submit" value="Register">
              </form>

        </main>
      </div>
<footer>
        &copy; 2024 Chipo Chilundika_StudentTasker: Progress Manager. All rights reserved.
</footer>
</body>
</html>
