<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['report_title']);

    if (empty($title)) {
        $error = "Report title is required.";
    } else {
        // Create report
        $stmt = $conn->prepare("INSERT INTO tblReports (admin_id, report_title, generated_at) VALUES (?, ?, NOW())");
        $stmt->execute([$admin_id, $title]);
        $report_id = $conn->lastInsertId();

        // Fetch all users and their habits
        $stmt = $conn->query("
            SELECT u.user_id, h.habit_id, h.goal_days,
            (SELECT COUNT(DISTINCT log_date) FROM tblHabitLogs WHERE habit_id = h.habit_id AND status = 'done') AS done
            FROM tblUsers u
            JOIN tblHabits h ON u.user_id = h.user_id
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert report details
        foreach ($rows as $row) {
            $goal = $row['goal_days'] ?: 30;
            $rate = round(($row['done'] / $goal) * 100);
            $note = $rate >= 80 ? "Great consistency!" : ($rate >= 50 ? "Needs improvement." : "Low engagement.");

            $stmt = $conn->prepare("INSERT INTO tblReportDetails (report_id, user_id, habit_id, completion_rate, notes) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$report_id, $row['user_id'], $row['habit_id'], $rate, $note]);
        }

        $success = "Report generated successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Generate Report</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <main class="main-content fade-in">
    <h1>Generate Habit Report</h1>

    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

    <form method="POST" class="form-box">
      <input type="text" name="report_title" placeholder="Report Title" required>
      <button type="submit" class="cta-button">Generate Report</button>
    </form>

    <p><a href="report_summary.php">← View Reports</a></p>
  </main>
</body>
</html>