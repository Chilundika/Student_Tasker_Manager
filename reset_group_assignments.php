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

$sql_reset = "UPDATE group_assignments SET completion_percentage = 0 WHERE student_id = '{$_SESSION['user_id']}'";
if ($conn->query($sql_reset) === TRUE) {
    header("Location: student_portal.php");
} else {
    echo "Error resetting group assignments: " . $conn->error;
}
?>
