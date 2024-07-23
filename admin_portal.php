<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch users
$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql_delete = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

// Add new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $new_username = $_POST['new_username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $new_role = $_POST['new_role'];
    $sql_add = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', '$new_role')";
    if ($conn->query($sql_add) === TRUE) {
        echo "User added successfully";
    } else {
        echo "Error adding user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $username; ?></h1>
        <a href="logout.php">Logout</a>
    </header>
    <main>
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete_user" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Add New User</h2>
        <form method="POST">
            Username: <input type="text" name="new_username" required><br>
            Password: <input type="password" name="new_password" required><br>
            Role:
            <select name="new_role">
                <option value="" disabled selected style="color:lightslategray">Select role type..</option>
                <option value="student">Student</option>
                <!--option value="lecturer">Lecturer</option-->
                <option value="admin">Admin</option>
            </select><br>
            <input type="submit" name="add_user" value="Add User">
        </form>

        <h2>Change Password</h2>
        <form method="POST" action="change_password.php">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="submit" name="change_password" value="Change Password">
        </form>

        <h2>Student Information</h2>
        <a href="assignstudents.php">Manage Student Info</a>
    </main>
</body>
</html>

