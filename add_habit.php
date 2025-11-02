<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch habit templates
$template_stmt = $conn->prepare("SELECT * FROM tblHabitTemplates ORDER BY category, habit_name");
$template_stmt->execute();
$templates = $template_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle template habit submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_template_habit'])) {
    $template_id = $_POST['template_id'];
    $stmt = $conn->prepare("SELECT habit_name, frequency, category FROM tblHabitTemplates WHERE template_id = ?");
    $stmt->execute([$template_id]);
    $template = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($template) {
        $stmt = $conn->prepare("INSERT INTO tblHabits (user_id, habit_name, frequency, category, goal_days, start_date, created_at) VALUES (?, ?, ?, ?, 30, CURDATE(), NOW())");
        $stmt->execute([$user_id, $template['habit_name'], $template['frequency'], $template['category']]);
        $success = "Habit added from template.";
    } else {
        $error = "Template not found.";
    }
}

// Handle custom habit creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_habit'])) {
    $habit_name = trim($_POST['habit_name']);
    $frequency = $_POST['frequency'];
    $category = trim($_POST['category']);
    $goal_days = $_POST['goal_days'];

    if (empty($habit_name) || empty($frequency) || empty($goal_days)) {
        $error = "All fields except category are required.";
    } elseif (!is_numeric($goal_days) || $goal_days <= 0) {
        $error = "Goal must be a positive number.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tblHabits (user_id, habit_name, frequency, category, goal_days, start_date, created_at) VALUES (?, ?, ?, ?, ?, CURDATE(), NOW())");
        $stmt->execute([$user_id, $habit_name, $frequency, $category, $goal_days]);
        $success = "Habit added successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Habit - G@iners Habit Tracker</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <main class="main-content fade-in">
    <h1>Add a New Habit</h1>

    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

    <h2>💡 Choose from Habit List</h2>
    <form method="POST" class="form-box">
      <select name="template_id" required>
        <option value="">-- Select a habit template --</option>
        <?php foreach ($templates as $t): ?>
          <option value="<?php echo $t['template_id']; ?>">
            <?php echo htmlspecialchars($t['habit_name'] . " (" . $t['frequency'] . ")"); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" name="add_template_habit" class="cta-button">Add Selected Habit</button>
    </form>

    <h2>➕ Or Create Your Own Habit</h2>
    <form method="POST" class="form-box">
      <input type="text" name="habit_name" placeholder="Habit name" required>
      <select name="frequency" required>
        <option value="">Select frequency</option>
        <option value="Daily">Daily</option>
        <option value="Weekly">Weekly</option>
      </select>
      <input type="text" name="category" placeholder="Category (optional)">
      <input type="number" name="goal_days" placeholder="Goal (in days)" required>
      <button type="submit" name="add_habit" class="cta-button">Add Habit</button>
    </form>

    <p><a href="dashboard.php">← Back to My Dashboard</a></p>
  </main>
</body>
</html>