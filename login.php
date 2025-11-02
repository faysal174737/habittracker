<?php
require_once 'db_connect.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check tblUsers first
    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM tblUsers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    }

    // Check tblAdmins next
    $stmt = $conn->prepare("SELECT admin_id, password_hash FROM tblAdmins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];

        header("Location: admin_dashboard.php");
        exit;
    }

    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - G@iners Habit Tracker</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="logo-container">
      <img src="logo.png" alt="G@iners Logo" class="logo animated-logo">
      <span class="brand-name">G@iners</span>
    </div>
    <nav class="navbar">
      <a href="index.php">Home</a>
      <a href="login.php" class="active">Login</a>
      
    </nav>
  </header>

  <main class="main-content fade-in">
    <h1>Login to Your Account</h1>
    <form method="POST" class="form-box">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="cta-button">Login</button>
    </form>
    <?php if ($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </main>
</body>
</html>