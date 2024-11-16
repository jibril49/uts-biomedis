<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO layanan_medis (nama_layanan, deskripsi, biaya) 
                            VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nama_layanan'], $_POST['deskripsi'], $_POST['biaya']]);
    $id = $pdo->lastInsertId();
    echo "<tr id='row-$id'>
        <td>{$_POST['nama_layanan']}</td>
        <td>{$_POST['deskripsi']}</td>
        <td>{$_POST['biaya']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm' onclick='editLayanan($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deleteLayanan($id)'>Hapus</button>
        </td>
    </tr>";
} elseif ($action === 'fetch') {
    $stmt = $pdo->prepare("SELECT * FROM layanan_medis WHERE id_layanan = ?");
    $stmt->execute([$_POST['id_layanan']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} elseif ($action === 'update') {
    $stmt = $pdo->prepare("UPDATE layanan_medis SET nama_layanan = ?, deskripsi = ?, biaya = ? WHERE id_layanan = ?");
    $stmt->execute([$_POST['nama_layanan'], $_POST['deskripsi'], $_POST['biaya'], $_POST['id_layanan']]);
    echo json_encode(["success" => true]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM layanan_medis WHERE id_layanan = ?");
    $stmt->execute([$_POST['id_layanan']]);
}
?>
