<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session. Please log in again.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignment_id = $_POST['assignment_id'];

    $sql_delete = "DELETE FROM assignments WHERE id = '$assignment_id' AND student_id = '{$_SESSION['user_id']}'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: student_portal.php");
    } else {
        echo "Error deleting assignment: " . $conn->error;
    }
} else {
    header("Location: student_portal.php");
}
?>
