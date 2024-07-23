<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['user_id'];
    $module = $_POST['module'];
    $group = $_POST['group'];
    $completion = $_POST['completion'];

    // Escape input data to prevent SQL injection
    $student_id = $conn->real_escape_string($student_id);
    $module = $conn->real_escape_string($module);
    $group = $conn->real_escape_string($group);
    $completion = intval($completion);

    // Updated SQL query with backticks around the reserved keyword
    $sql_insert = "INSERT INTO group_assignments (student_id, module, `group_name`, completion_percentage) VALUES ('$student_id', '$module', '$group', '$completion')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Group assignment added successfully";
    } else {
        echo "Error adding group assignment: " . $conn->error;
    }
}
?>
