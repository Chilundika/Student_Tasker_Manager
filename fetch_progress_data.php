<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'User not logged in']));
}

$student_id = $_SESSION['user_id'];

// Fetch progress data
$sql_progress = "SELECT progress FROM progress WHERE student_id = '$student_id'";
$result_progress = $conn->query($sql_progress);

if (!$result_progress) {
    die(json_encode(['error' => 'Error fetching progress data: ' . $conn->error]));
}

$progress_data = [];
while ($row = $result_progress->fetch_assoc()) {
    $progress_data[] = $row['progress'];
}

echo json_encode($progress_data);
?>
