<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT username FROM tblUsers WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user habits
$stmt = $conn->prepare("SELECT * FROM tblHabits WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - G@iners Habit Tracker</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <main class="main-content fade-in">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Here are your current habits and progress:</p>

    <a href="add_habit.php" class="cta-button">Add New Habit</a>
    

    <?php if (count($habits) === 0): ?>
      <p>You haven’t added any habits yet.</p>
    <?php else: ?>
      <table class="styled-table">
        <thead>
          <tr>
            <th>Habit</th>
            <th>Frequency</th>
            <th>Start Date</th>
            <th>Goal (days)</th>
            <th>Progress</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($habits as $h): 
            // Safe fallback for goal_days
            $goal = isset($h['goal_days']) && $h['goal_days'] > 0 ? $h['goal_days'] : 30;

            // Count completed logs
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tblHabitLogs WHERE habit_id = ? AND status = 'done'");
            $stmt->execute([$h['habit_id']]);
            $done = $stmt->fetchColumn();

            // Calculate progress
            $progress = round(($done / $goal) * 100);
          ?>
            <tr>
              <td><?php echo htmlspecialchars($h['habit_name']); ?></td>
              <td><?php echo $h['frequency']; ?></td>
              <td><?php echo $h['start_date'] ?? '—'; ?></td>
              <td><?php echo $goal; ?></td>
              <td><?php echo $progress; ?>%</td>
              <td>
                <a href="log_habit.php?id=<?php echo $h['habit_id']; ?>" class="edit-button">Log Today</a>
                <a href="report_summary.php" class="cta-button">View My Reports</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
</body>
</html>