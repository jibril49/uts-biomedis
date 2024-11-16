<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'add') {
    $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, username, password, role) 
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['nama_lengkap'], $_POST['username'], $passwordHash, $_POST['role']]);
    $id = $pdo->lastInsertId();
    echo "<tr id='row-$id'>
        <td>{$_POST['nama_lengkap']}</td>
        <td>{$_POST['username']}</td>
        <td>{$_POST['role']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm' onclick='editUser($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deleteUser($id)'>Hapus</button>
        </td>
    </tr>";
} elseif ($action === 'fetch') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} elseif ($action === 'update') {
    $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, username = ?, password = ?, role = ? WHERE id = ?");
    $stmt->execute([$_POST['nama_lengkap'], $_POST['username'], $passwordHash, $_POST['role'], $_POST['id']]);
    echo json_encode(["success" => true]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}
?>
