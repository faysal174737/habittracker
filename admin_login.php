<?php
require_once 'db_connect.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, username, password_hash FROM tblAdmins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - G@iners Habit Tracker</title>
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
      <a href="login.php">User Login</a>
      <a href="admin_login.php" class="active">Admin Login</a>
    </nav>
  </header>

  <main class="main-content fade-in">
    <h1>Admin Login</h1>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <form method="POST" class="form-box">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="cta-button">Login</button>
    </form>
  </main>
</body>
</html>