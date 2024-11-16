<?php
include '../config.php';

// Ambil data layanan rekam medis dengan nama pasien dan nama dokter
$stmt = $pdo->query("
    SELECT lr.id_layanan_rekam_medis, rm.id_pasien, rm.id_dokter, lm.nama_layanan, 
           p.nama AS nama_pasien, d.nama AS nama_dokter
    FROM layanan_rekam_medis lr
    JOIN rekam_medis rm ON lr.id_rekam_medis = rm.id_rekam_medis
    JOIN layanan_medis lm ON lr.id_layanan = lm.id_layanan
    LEFT JOIN pasien p ON rm.id_pasien = p.id_pasien
    LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter
");

$layananRekamMedis = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Layanan Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Daftar Layanan Rekam Medis</h2>
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Nama Layanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="layananRekamMedisTable">
                    <?php foreach ($layananRekamMedis as $lr) : ?>
                        <tr id="row-<?= $lr['id_layanan_rekam_medis']; ?>">
                            <td><?= htmlspecialchars($lr['nama_pasien']); ?></td>
                            <td><?= htmlspecialchars($lr['nama_dokter']); ?></td>
                            <td><?= htmlspecialchars($lr['nama_layanan']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="deleteLayananRekamMedis(<?= $lr['id_layanan_rekam_medis']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function deleteLayananRekamMedis(id) {
            if (confirm('Yakin ingin menghapus layanan rekam medis ini?')) {
                $.post('layanan_rekam_medis_crud_handler.php', { action: 'delete', id_layanan_rekam_medis: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
