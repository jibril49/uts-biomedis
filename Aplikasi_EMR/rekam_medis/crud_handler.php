<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO rekam_medis (id_pasien, id_dokter, tanggal_kunjungan, diagnosis, pengobatan, catatan) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['id_pasien'], $_POST['id_dokter'], $_POST['tanggal_kunjungan'], $_POST['diagnosis'], $_POST['pengobatan'], $_POST['catatan']]);
    $id = $pdo->lastInsertId();
    echo "<tr id='row-$id'>
        <td>{$_POST['id_pasien']}</td>
        <td>{$_POST['id_dokter']}</td>
        <td>{$_POST['tanggal_kunjungan']}</td>
        <td>{$_POST['diagnosis']}</td>
        <td>{$_POST['pengobatan']}</td>
        <td>{$_POST['catatan']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm' onclick='editRekamMedis($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deleteRekamMedis($id)'>Hapus</button>
        </td>
    </tr>";
} elseif ($action === 'fetch') {
    $stmt = $pdo->prepare("SELECT * FROM rekam_medis WHERE id_rekam_medis = ?");
    $stmt->execute([$_POST['id_rekam_medis']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} elseif ($action === 'update') {
    $stmt = $pdo->prepare("UPDATE rekam_medis SET id_pasien = ?, id_dokter = ?, tanggal_kunjungan = ?, diagnosis = ?, pengobatan = ?, catatan = ? WHERE id_rekam_medis = ?");
    $stmt->execute([$_POST['id_pasien'], $_POST['id_dokter'], $_POST['tanggal_kunjungan'], $_POST['diagnosis'], $_POST['pengobatan'], $_POST['catatan'], $_POST['id_rekam_medis']]);
    echo json_encode(["success" => true]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM rekam_medis WHERE id_rekam_medis = ?");
    $stmt->execute([$_POST['id_rekam_medis']]);
}
?>
