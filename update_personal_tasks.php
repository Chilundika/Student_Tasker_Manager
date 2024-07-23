<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $clarity = $_POST['clarity'];

    $sql_insert = "INSERT INTO personal_tasks (student_id, subject, clarity_percentage) VALUES ('$student_id', '$subject', '$clarity')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Personal task added successfully";
    } else {
        echo "Error adding personal task: " . $conn->error;
    }
}
?>
