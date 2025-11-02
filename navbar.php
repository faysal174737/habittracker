<?php
require_once 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="header">
  <div class="logo-container">
    <img src="logo.png" alt="G@iners Logo" class="logo animated-logo">
    <span class="brand-name">G@iners</span>
  </div>
  <div class="navbar">
    <a href="index.php">Home</a>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="logout.php">Logout</a>
    
  </div>
</nav>