<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO pasien (nama, tanggal_lahir, alamat, nomor_telepon, jenis_kelamin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['nama'], $_POST['tanggal_lahir'], $_POST['alamat'], $_POST['nomor_telepon'], $_POST['jenis_kelamin']]);
    $id = $pdo->lastInsertId();
    echo "<tr id='row-$id'>
        <td>{$_POST['nama']}</td>
        <td>{$_POST['tanggal_lahir']}</td>
        <td>{$_POST['alamat']}</td>
        <td>{$_POST['nomor_telepon']}</td>
        <td>{$_POST['jenis_kelamin']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm' onclick='editPatient($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deletePatient($id)'>Hapus</button>
        </td>
    </tr>";
} elseif ($action === 'fetch') {
    $stmt = $pdo->prepare("SELECT * FROM pasien WHERE id_pasien = ?");
    $stmt->execute([$_POST['id_pasien']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} elseif ($action === 'update') {
    $stmt = $pdo->prepare("UPDATE pasien SET nama = ?, tanggal_lahir = ?, alamat = ?, nomor_telepon = ?, jenis_kelamin = ? WHERE id_pasien = ?");
    $stmt->execute([$_POST['nama'], $_POST['tanggal_lahir'], $_POST['alamat'], $_POST['nomor_telepon'], $_POST['jenis_kelamin'], $_POST['id_pasien']]);
    echo json_encode(["success" => true]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM pasien WHERE id_pasien = ?");
    $stmt->execute([$_POST['id_pasien']]);
}
