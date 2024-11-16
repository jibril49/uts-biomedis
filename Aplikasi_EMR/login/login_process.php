<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verifikasi password (langsung dibandingkan sebagai teks biasa)
        if ($password === $user['password']) {  // Plain text comparison
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            
            header("Location: ../dashboard/admin.php ");
            exit();
        } else {
            echo "Password salah!";
            // Debugging - tampilkan password yang disimpan di database
            echo "<br>Password di database: " . $user['password'];
            echo "<br>Password yang dimasukkan: " . $password;
        }
    } else {
        echo "Username tidak ditemukan!";
    }
}
?>