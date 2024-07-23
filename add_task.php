<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO tasks (subject_id, title, description, status, deadline) VALUES ('$subject_id', '$title', '$description', 'pending', '$deadline')";

    if ($conn->query($sql) === TRUE) {
        echo "New task created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<form method="POST">
    Subject ID: <input type="text" name="subject_id"><br>
    Title: <input type="text" name="title"><br>
    Description: <textarea name="description"></textarea><br>
    Deadline: <input type="date" name="deadline"><br>
    <input type="submit" value="Add Task">
</form>
