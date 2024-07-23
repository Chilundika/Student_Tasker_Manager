<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
    $task = $_POST['task'];
    $description = $_POST['description'];

    $sql_assign = "INSERT INTO assignments (student_id, semester, subject, task, description) VALUES ('$student_id', '$semester', '$subject', '$task', '$description')";
    if ($conn->query($sql_assign) === TRUE) {
        echo "Assignment added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

$sql_students = "SELECT id, username FROM users WHERE role = 'student'";
$result_students = $conn->query($sql_students);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Students</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <h1>Assign Students</h1>
        <a href="admin_portal.php">Back to Admin Portal</a>
    </header>
    <main>
        <form method="POST">
            <label for="student_id">Select Student:</label>
            <select name="student_id" required>
                <?php while ($row = $result_students->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                <?php endwhile; ?>
            </select><br>

            <label for="semester">Semester:</label>
            <select name="semester" required>
                <option value="Year 1_Semester 1">Year 1_Semester 1</option>
                <option value="Year 1_Semester 2">Year 1_Semester 2</option>
                <option value="Year 2_Semester 1">Year 2_Semester 1</option>
                <option value="Year 2_Semester 2">Year 2_Semester 2</option>
                <option value="Year 3_Semester 1">Year 3_Semester 1</option>
                <option value="Year 3_Semester 2">Year 3_Semester 2</option>
                <option value="Year 4_Semester 1">Year 4_Semester 1</option>
                <option value="Year 4_Semester 2">Year 4_Semester 2</option>
            </select><br>

            <label for="subject">Subject:</label>
            <select name="subject" required>
                <option value="psychology">Psychology</option>
                <option value="philosophy">Philosophy</option>
                <option value="business_coms">Business Communications</option>
                <option value="economics">Economics</option>
                <option value="mathematics">Mathematics</option>
                <option value="ict">ICT</option>
                <option value="sdlc">SDLC</option>
                <option value="hardware_maintenance">Hardware and Maintenance</option>
                <option value="c_programming">C Programming</option>
                <option value="ecom">E-Commerce</option>
                <option value="ethics">Ethics</option>
                <option value="operation_research">Operations Research</option>
                <option value="web_design">Web Design</option>
                <option value="java_programming">Java Programming</option>
                <option value="databases">Databases</option>
                <option value="networks">Networks</option>
                <option value="ai">AI</option>
                <option value="dissertation">Dissertation</option>
            </select><br>

            <label for="task">Task:</label>
            <select name="task" required>
                <option value="assignments">Assignments</option>
                <option value="cats">C.A.Ts</option>
                <option value="exams">Exams</option>
            </select><br>

            <label for="description">Description:</label>
            <textarea name="description" rows="4" cols="50"></textarea><br>

            <input type="submit" value="Submit">
        </form>
    </main>
</body>
</html>
