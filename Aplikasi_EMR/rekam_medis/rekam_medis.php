<?php
include '../config.php';

// Ambil data rekam medis dengan nama pasien dan nama dokter
$stmt = $pdo->query("
    SELECT rekam_medis.*, pasien.nama AS nama_pasien, dokter.nama AS nama_dokter
    FROM rekam_medis
    LEFT JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien
    LEFT JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter
");
$rekam_medis = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Daftar Rekam Medis</h2>
        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">Data Rekam Medis</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#rekamModal" onclick="resetModal('Tambah Rekam Medis', 'add')">Tambah Rekam Medis Baru</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Diagnosis</th>
                        <th>Pengobatan</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="rekamTable">
                    <?php foreach ($rekam_medis as $r) : ?>
                        <tr id="row-<?= $r['id_rekam_medis']; ?>">
                            <td><?= htmlspecialchars($r['nama_pasien']); ?></td>
                            <td><?= htmlspecialchars($r['nama_dokter']); ?></td>
                            <td><?= htmlspecialchars($r['tanggal_kunjungan']); ?></td>
                            <td><?= htmlspecialchars($r['diagnosis']); ?></td>
                            <td><?= htmlspecialchars($r['pengobatan']); ?></td>
                            <td><?= htmlspecialchars($r['catatan']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rekamModal" onclick="editRekamMedis(<?= $r['id_rekam_medis']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteRekamMedis(<?= $r['id_rekam_medis']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="rekamModal" tabindex="-1" aria-labelledby="rekamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rekamModalLabel">Form Tambah Rekam Medis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rekamForm">
                        <input type="hidden" id="id_rekam_medis" name="id_rekam_medis">
                        <div class="mb-3">
                            <label for="id_pasien" class="form-label">Pasien</label>
                            <select class="form-select" id="id_pasien" name="id_pasien" required>
                                <!-- Populate options dynamically -->
                                <?php
                                $pasienStmt = $pdo->query("SELECT id_pasien, nama FROM pasien");
                                $pasienList = $pasienStmt->fetchAll();
                                foreach ($pasienList as $pasien) {
                                    echo "<option value='{$pasien['id_pasien']}'>{$pasien['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_dokter" class="form-label">Dokter</label>
                            <select class="form-select" id="id_dokter" name="id_dokter" required>
                                <!-- Populate options dynamically -->
                                <?php
                                $dokterStmt = $pdo->query("SELECT id_dokter, nama FROM dokter");
                                $dokterList = $dokterStmt->fetchAll();
                                foreach ($dokterList as $dokter) {
                                    echo "<option value='{$dokter['id_dokter']}'>{$dokter['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" required>
                        </div>
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="pengobatan" class="form-label">Pengobatan</label>
                            <textarea class="form-control" id="pengobatan" name="pengobatan" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function resetModal(title, mode) {
            $('#rekamModalLabel').text(title);
            $('#id_rekam_medis').val('');
            $('#rekamForm')[0].reset();
            $('#saveButton').attr('onclick', mode === 'add' ? 'saveRekamMedis()' : 'updateRekamMedis()');
        }

        function editRekamMedis(id) {
            $.post('../rekam_medis/crud_handler.php', { action: 'fetch', id_rekam_medis: id }, function(data) {
                const rekam = JSON.parse(data);
                resetModal('Edit Rekam Medis', 'edit');
                $('#id_rekam_medis').val(rekam.id_rekam_medis);
                $('#id_pasien').val(rekam.id_pasien);
                $('#id_dokter').val(rekam.id_dokter);
                $('#tanggal_kunjungan').val(rekam.tanggal_kunjungan);
                $('#diagnosis').val(rekam.diagnosis);
                $('#pengobatan').val(rekam.pengobatan);
                $('#catatan').val(rekam.catatan);
                $('#saveButton').attr('onclick', 'updateRekamMedis()');
            });
        }

        function updateRekamMedis() {
            const formData = $('#rekamForm').serialize() + '&action=update';
            $.post('../rekam_medis/crud_handler.php', formData, function(response) {
                const id = $('#id_rekam_medis').val();
                const row = `
                    <td>${$('#id_pasien option:selected').text()}</td>
                    <td>${$('#id_dokter option:selected').text()}</td>
                    <td>${$('#tanggal_kunjungan').val()}</td>
                    <td>${$('#diagnosis').val()}</td>
                    <td>${$('#pengobatan').val()}</td>
                    <td>${$('#catatan').val()}</td>
                    <td class='text-center'>
                        <button class='btn btn-warning btn-sm' onclick='editRekamMedis(${id})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteRekamMedis(${id})'>Hapus</button>
                    </td>
                `;
                $(`#row-${id}`).html(row);
                $('#rekamModal').modal('hide');
            });
        }

        function saveRekamMedis() {
            const formData = $('#rekamForm').serialize() + '&action=add';
            $.post('../rekam_medis/crud_handler.php', formData, function(response) {
                $('#rekamTable').append(response);
                $('#rekamModal').modal('hide');
            });
        }

        function deleteRekamMedis(id) {
            if (confirm('Yakin ingin menghapus rekam medis ini?')) {
                $.post('../rekam_medis/crud_handler.php', { action: 'delete', id_rekam_medis: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
