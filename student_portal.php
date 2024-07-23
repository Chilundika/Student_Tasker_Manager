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

$student_id = $_SESSION['user_id'];

// Fetch assignments
$sql_assignments = "SELECT * FROM assignments WHERE student_id = '$student_id'";
$result_assignments = $conn->query($sql_assignments);
if (!$result_assignments) {
    die("Error fetching assignments: " . $conn->error);
}

// Fetch progress data
$sql_progress = "SELECT * FROM progress WHERE student_id = '$student_id'";
$result_progress = $conn->query($sql_progress);
if (!$result_progress) {
    die("Error fetching progress data: " . $conn->error);
}

// Fetch personal tasks
$sql_personal_tasks = "SELECT * FROM personal_tasks WHERE student_id = '$student_id'";
$result_personal_tasks = $conn->query($sql_personal_tasks);
if (!$result_personal_tasks) {
    die("Error fetching personal tasks: " . $conn->error);
}

// Fetch group assignments
$sql_group_assignments = "SELECT * FROM group_assignments WHERE student_id = '$student_id'";
$result_group_assignments = $conn->query($sql_group_assignments);
if (!$result_group_assignments) {
    die("Error fetching group assignments: " . $conn->error);
}

// Prepare data for charts
$progress_data = [];
$personal_tasks = [];
$group_assignments = [];

while ($row = $result_progress->fetch_assoc()) {
    $progress_data[] = $row['progress'];
}

while ($row = $result_personal_tasks->fetch_assoc()) {
    $personal_tasks[] = ['subject' => $row['subject'], 'clarity' => $row['clarity_percentage']];
}

while ($row = $result_group_assignments->fetch_assoc()) {
    $group_assignments[] = ['module' => $row['module'], 'group_name' => $row['group_name'], 'completion' => $row['completion_percentage']];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <link rel="icon" href="images/favicon1.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <a href="logout.php">Logout</a>
    </header>
    <main>
        <h2>Your Assignments</h2>
        <table>
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Task</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_assignments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['semester']; ?></td>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['task']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <form method="POST" action="delete_assignment.php" style="display:inline;">
                                <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Progress Bar Section -->
        <h2>Update Task Progress</h2>
        <form method="POST" action="update_progress.php">
            <label for="task">Task:</label>
            <input type="text" name="task" id="task" required><br>
            <label for="progress">Progress (0-100%):</label>
            <input type="number" name="progress" id="progress" min="0" max="100" required><br>
            <input type="submit" value="Update Progress">
        </form>

        <h2>Your Progress</h2>
        <div>
            <canvas id="progressChart"></canvas>
        </div>

        <!-- Personal Tasks Section -->
         <br><br>
         <hr>
        <h2>Personal Tasks</h2>
        <form method="POST" action="update_personal_tasks.php">
            <label for="subject">Subject:</label>
            <select name="subject" id="subject" required>
                <?php
                $subjects = ['psychology', 'philosophy', 'business coms', 'economics', 'mathematics', 'ICT', 'SDLC', 'hardware and maintenance', 'C-programming', 'ecom', 'ethics', 'operation research', 'web design', 'java programming', 'databases', 'networks', 'AI', 'dissertation'];
                foreach ($subjects as $subject) {
                    echo "<option value=\"$subject\">$subject</option>";
                }
                ?>
            </select><br>
            <label for="clarity">Clarity Percentage (0-100%):</label>
            <input type="number" name="clarity" id="clarity" min="0" max="100" required><br>
            <input type="submit" value="Add Personal Task">
        </form>

        <form method="POST" action="reset_personal_tasks.php" style="display:inline;">
            <input type="submit" value="Reset Personal Tasks">
        </form>

        <h2>Your Personal Tasks</h2>
        <div>
            <canvas id="personalTasksChart"></canvas>
        </div>

        <!-- Group Assignments Section -->
         <br><br>
         <hr>
        <h2>Group Assignments</h2>
        <form method="POST" action="update_group_assignments.php">
            <label for="module">Module:</label>
            <select name="module" id="module" required>
                <?php
                $modules = ['psychology', 'philosophy', 'business coms', 'economics', 'mathematics', 'ICT', 'SDLC', 'hardware and maintenance', 'C-programming', 'ecom', 'ethics', 'operation research', 'web design', 'java programming', 'databases', 'networks', 'AI', 'dissertation'];
                foreach ($modules as $module) {
                    echo "<option value=\"$module\">$module</option>";
                }
                ?>
            </select><br>
            <label for="group">Group:</label>
            <select name="group" id="group" required>
                <?php
                $groups = ['group 1', 'group 2', 'group 3', 'group 4', 'group 5'];
                foreach ($groups as $group) {
                    echo "<option value=\"$group\">$group</option>";
                }
                ?>
            </select><br>
            <label for="completion">Completion Percentage (0-100%):</label>
            <input type="number" name="completion" id="completion" min="0" max="100" required><br>
            <input type="submit" value="Add Group Assignment">
        </form>

        <form method="POST" action="reset_group_assignments.php" style="display:inline;">
            <input type="submit" value="Reset Group Assignments">
        </form>

        <h2>Your Group Assignments</h2>
        <div>
            <canvas id="groupAssignmentsChart"></canvas>
        </div>

    </main>

    <script>
        // Progress Chart
        const progressData = <?php echo json_encode($progress_data); ?>;
        const completed = progressData.reduce((a, b) => a + b, 0);
        const remaining = 100 - completed; // Assuming progress is out of 100

        const progressCtx = document.getElementById('progressChart').getContext('2d');
        new Chart(progressCtx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Remaining'],
                datasets: [{
                    data: [completed, remaining],
                    backgroundColor: ['#4caf50', '#f44336']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                }
            }
        });

        // Personal Tasks Chart
        const personalTasksData = <?php echo json_encode($personal_tasks); ?>;
        const personalTasksLabels = personalTasksData.map(task => task.subject);
        const personalTasksValues = personalTasksData.map(task => task.clarity);

        const personalTasksCtx = document.getElementById('personalTasksChart').getContext('2d');
        new Chart(personalTasksCtx, {
            type: 'bar',
            data: {
                labels: personalTasksLabels,
                datasets: [{
                    label: 'Subject Clarity',
                    data: personalTasksValues,
                    backgroundColor: '#2196f3'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Group Assignments Chart
        const groupAssignmentsData = <?php echo json_encode($group_assignments); ?>;
        const groupAssignmentsLabels = groupAssignmentsData.map(assignment => `${assignment.module} - ${assignment.group}`);
        const groupAssignmentsValues = groupAssignmentsData.map(assignment => assignment.completion);

        const groupAssignmentsCtx = document.getElementById('groupAssignmentsChart').getContext('2d');
        new Chart(groupAssignmentsCtx, {
            type: 'bar',
            data: {
                labels: groupAssignmentsLabels,
                datasets: [{
                    label: 'Group Assignment Completion',
                    data: groupAssignmentsValues,
                    backgroundColor: '#ff5722'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>
