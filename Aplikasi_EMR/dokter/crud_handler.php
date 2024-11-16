<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO dokter (nama, spesialisasi, nomor_telepon) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nama'], $_POST['spesialisasi'], $_POST['nomor_telepon']]);
    $id = $pdo->lastInsertId();
    echo "<tr id='row-$id'>
        <td>{$_POST['nama']}</td>
        <td>{$_POST['spesialisasi']}</td>
        <td>{$_POST['nomor_telepon']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm' onclick='editDoctor($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deleteDoctor($id)'>Hapus</button>
        </td>
    </tr>";
} elseif ($action === 'fetch') {
    $stmt = $pdo->prepare("SELECT * FROM dokter WHERE id_dokter = ?");
    $stmt->execute([$_POST['id_dokter']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} elseif ($action === 'update') {
    $stmt = $pdo->prepare("UPDATE dokter SET nama = ?, spesialisasi = ?, nomor_telepon = ? WHERE id_dokter = ?");
    $stmt->execute([$_POST['nama'], $_POST['spesialisasi'], $_POST['nomor_telepon'], $_POST['id_dokter']]);
    echo json_encode(["success" => true]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM dokter WHERE id_dokter = ?");
    $stmt->execute([$_POST['id_dokter']]);
}
?>
