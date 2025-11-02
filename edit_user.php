<?php
require_once 'db_connect.php';
session_start();

if (!isset($_GET['id'])) {
    echo "User ID missing.";
    exit;
}

$id = $_GET['id'];
$error = '';
$success = '';

$stmt = $conn->prepare("SELECT * FROM tblUsers WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE tblUsers SET username = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->execute([$username, $email, $role, $id]);
    $success = "User updated successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <main class="main-content fade-in">
    <h1>Edit User</h1>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <form method="POST" class="form-box">
      <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
      <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
      <select name="role" required>
        <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
        <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
      </select>
      <button type="submit" class="cta-button">Update User</button>
    </form>
  </main>
</body>
</html>