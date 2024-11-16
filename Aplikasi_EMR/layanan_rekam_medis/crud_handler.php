<?php
include '../config.php';

$action = $_POST['action'];

if ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM layanan_rekam_medis WHERE id_layanan_rekam_medis = ?");
    $stmt->execute([$_POST['id_layanan_rekam_medis']]);
}
?>
