<?php
require_once 'db_connect.php';

$username = 'fa_admin';
$email = 'admin@gmail.com';
$password = '12345678';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO tblAdmins (username, email, password_hash, created_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$username, $email, $hash]);

echo "✅ Admin created successfully.";
?>