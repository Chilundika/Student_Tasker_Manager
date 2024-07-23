<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session. Please log in again.");
}

$student_id = $_SESSION['user_id'];
$task = $_POST['task'];
$progress = $_POST['progress'];

// Ensure input data is properly escaped to prevent SQL injection
$task = $conn->real_escape_string($task);
$progress = intval($progress); // Ensure progress is an integer

// Update progress query
$sql_update = "UPDATE progress SET progress = '$progress' WHERE student_id = '$student_id' AND task = '$task'";

if ($conn->query($sql_update) === TRUE) {
    echo "Progress updated successfully.";
} else {
    echo "Error updating progress: " . $conn->error;
}

$conn->close();
?>
