<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'lecturer') {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'];

$sql_select = "SELECT * FROM lecturer_comments WHERE student_id = '$student_id'";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Lecturer ID:</strong> " . $row['lecturer_id'] . "<br>";
        echo "<strong>Comment:</strong> " . $row['comment'] . "<br>";
        echo "<strong>Created At:</strong> " . $row['created_at'] . "</p>";
    }
} else {
    echo "No comments found for this student.";
}

$conn->close();
?>
