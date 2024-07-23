<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role == 'student') {
    header("Location: student_portal.php");
} elseif ($role == 'lecturer') {
    header("Location: lecturer_portal.php");
} elseif ($role == 'admin') {
    header("Location: admin_portal.php");
} else {
    echo "Invalid role";
}
?>
