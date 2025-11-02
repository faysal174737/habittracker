<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo "Invalid user.";
    exit;
}

$stmt = $conn->prepare("SELECT username FROM tblUsers WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM tblHabits WHERE user_id = ?");
$stmt->execute([$user_id]);
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Habits for <?php echo htmlspecialchars($user['username']); ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Habits for <?php echo htmlspecialchars($user['username']); ?></h1>
  <a href="admin_dashboard.php">← Back to Admin Dashboard</a>

  <?php if (count($habits) === 0): ?>
    <p>No habits found for this user.</p>
  <?php else: ?>
    <table class="styled-table">
      <thead>
        <tr>
          <th>Habit</th>
          <th>Frequency</th>
          <th>Start Date</th>
          <th>Goal</th>
          <th>Progress</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($habits as $h): 
          $stmt = $conn->prepare("SELECT COUNT(*) FROM tblHabitLogs WHERE habit_id = ? AND status = 'done'");
          $stmt->execute([$h['habit_id']]);
          $done = $stmt->fetchColumn();
          $progress = round(($done / $h['goal_days']) * 100);
        ?>
          <tr>
            <td><?php echo htmlspecialchars($h['name']); ?></td>
            <td><?php echo $h['frequency']; ?></td>
            <td><?php echo $h['start_date']; ?></td>
            <td><?php echo $h['goal_days']; ?></td>
            <td><?php echo $progress; ?>%</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>