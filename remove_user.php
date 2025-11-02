<?php
require_once 'db_connect.php';
session_start();

if (!isset($_GET['id'])) {
    echo "User ID missing.";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("UPDATE tblUsers SET role = 'removed' WHERE user_id = ?");
$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;