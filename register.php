<?php
require_once 'db_connect.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check for duplicate email or username
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tblUsers WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            $error = "Email or username already exists.";
        } else {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO tblUsers (username, email, password_hash, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
            if ($stmt->execute([$username, $email, $hash])) {
                $success = "Account created successfully. You can now log in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - G@iners Habit Tracker</title>
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
      <a href="login.php">Login</a>
      <a href="register.php" class="active">Register</a>
      <a href="habits.php">Habits</a>
    </nav>
  </header>

  <main class="main-content fade-in">
    <h1>Create Your Account</h1>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <form method="POST" class="form-box">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="cta-button">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </main>
</body>
</html>