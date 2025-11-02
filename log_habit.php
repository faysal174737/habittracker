<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$habit_id = $_GET['id'] ?? null;

if (!$habit_id) {
    echo "Invalid habit.";
    exit;
}

// Check if already logged today
$stmt = $conn->prepare("SELECT COUNT(*) FROM tblHabitLogs WHERE habit_id = ? AND log_date = CURDATE()");
$stmt->execute([$habit_id]);
$alreadyLogged = $stmt->fetchColumn();

if ($alreadyLogged > 0) {
    echo "✅ Already logged for today.";
} else {
    $stmt = $conn->prepare("INSERT INTO tblHabitLogs (habit_id, log_date, status) VALUES (?, CURDATE(), 'done')");
    $stmt->execute([$habit_id]);
    echo "✅ Habit logged for today.";
}

echo '<br><a href="dashboard.php">← Back to Dashboard</a>';
?>