<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Only admin can deactivate
$stmt = $conn->prepare("SELECT role FROM tblUsers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM tblUsers WHERE user_id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: admin_dashboard.php");
    exit;
}