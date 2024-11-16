<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi pengguna
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        echo "Pengguna ditemukan: " . $user['username'] . "<br>";
        echo "Password di database: " . $user['password'] . "<br>";
        echo "Password yang dimasukkan: " . $password . "<br>";

        // Verifikasi password (langsung dibandingkan sebagai teks biasa)
        if ($password === $user['password']) {  // Password plain text comparison
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            echo "Password tidak cocok.";
        }
    } else {
        echo "Pengguna tidak ditemukan.";
    }
}