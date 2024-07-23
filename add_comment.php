<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'lecturer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $lecturer_id = $_SESSION['user_id']; // Assuming user_id represents lecturer_id
    $comment = $_POST['comment'];

    $comment = $conn->real_escape_string($comment);

    $sql_insert = "INSERT INTO lecturer_comments (student_id, lecturer_id, comment)
                   VALUES ('$student_id', '$lecturer_id', '$comment')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Comment added successfully.";
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}

$conn->close();
?>
