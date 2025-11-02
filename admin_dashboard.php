<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $conn->prepare("SELECT username FROM tblAdmins WHERE admin_id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM tblUsers ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - G@iners Habit Tracker</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header">
    <div class="logo-container">
      <img src="logo.png" alt="G@iners Logo" class="logo animated-logo">
      <span class="brand-name">G@iners</span>
    </div>
    <nav class="navbar">
      <a href="index.php">Home</a>
      <a href="view_user_habits.php?user_id=<?php echo $u['user_id']; ?>" class="view-button">View User Habits</a>
      <a href="admin_dashboard.php" class="active">Admin</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main class="main-content fade-in">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</p>

    <div class="form-container">
      <h2>Manage Users</h2>
      <a href="add_user.php" class="cta-button" style="margin-bottom: 20px;">Add New User</a>
      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?php echo $u['user_id']; ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo $u['role']; ?></td>
              <td><?php echo $u['created_at']; ?></td>
              <td>
                <a href="edit_user.php?id=<?php echo $u['user_id']; ?>" class="edit-button">Edit</a>
                <?php if ($u['role'] !== 'admin'): ?>
                  <a href="delete_user.php?id=<?php echo $u['user_id']; ?>" class="delete-button" onclick="return confirm('Delete this user permanently?')">Delete</a>
                  <a href="remove_user.php?id=<?php echo $u['user_id']; ?>" class="remove-button">Remove</a>
                <?php else: ?>
                  <span style="color: gray;">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>