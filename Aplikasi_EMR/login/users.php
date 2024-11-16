<?php
include '../config.php';

// Contoh menambahkan pengguna baru dengan password dalam bentuk teks biasa
$username = 'admin';
$password_plain = 'admin123'; // Password dalam bentuk teks biasa

$stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (:username, :password, :nama_lengkap, :role)");
$stmt->execute([
    'username' => $username,
    'password' => $password_plain,  // Store password as plain text
    'nama_lengkap' => 'Admin User',
    'role' => 'admin'
]);

echo "Pengguna berhasil ditambahkan dengan password dalam bentuk teks biasa!";
?>