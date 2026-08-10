<?php
// reset_password.php - Reset Password Admin
include 'config/database.php';

echo "<h2>Reset Password Admin</h2>";

// Hapus user admin lama
$pdo->exec("DELETE FROM users WHERE username = 'admin'");

// Insert user admin baru
$password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password, nama_admin, email) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', $password, 'Administrator', 'admin@si-pari.go.id']);

echo "<p style='color:green;'>✅ Password admin berhasil direset!</p>";
echo "<p><strong>Username:</strong> admin</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><a href='login.php'>Klik disini untuk login</a></p>";
?>