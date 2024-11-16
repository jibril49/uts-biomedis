<?php
include '../config.php';

// Ambil data pasien
$stmt = $pdo->query("SELECT * FROM pasien");
$pasien = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Daftar Pasien</h2>
        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">Data Pasien</h5>
                <!-- Button to trigger Add Modal -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#patientModal" onclick="resetModal('Tambah Pasien', 'add')">Tambah Pasien Baru</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pasienTable">
                    <?php foreach ($pasien as $p) : ?>
                        <tr id="row-<?= $p['id_pasien']; ?>">
                            <td><?= htmlspecialchars($p['nama']); ?></td>
                            <td><?= htmlspecialchars($p['tanggal_lahir']); ?></td>
                            <td><?= htmlspecialchars($p['alamat']); ?></td>
                            <td><?= htmlspecialchars($p['nomor_telepon']); ?></td>
                            <td><?= htmlspecialchars($p['jenis_kelamin']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal" onclick="editPatient(<?= $p['id_pasien']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deletePatient(<?= $p['id_pasien']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Form Tambah Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="patientForm">
                        <input type="hidden" id="id_pasien" name="id_pasien">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
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
            $('#patientModalLabel').text(title);
            $('#id_pasien').val('');
            $('#patientForm')[0].reset();
            $('#saveButton').attr('onclick', mode === 'add' ? 'savePatient()' : 'updatePatient()');
        }

        function editPatient(id) {
    $.post('crud_handler.php', { action: 'fetch', id_pasien: id }, function(data) {
        const patient = JSON.parse(data);
        resetModal('Edit Pasien', 'edit');
        $('#id_pasien').val(patient.id_pasien);
        $('#nama').val(patient.nama);
        $('#tanggal_lahir').val(patient.tanggal_lahir);
        $('#alamat').val(patient.alamat);
        $('#nomor_telepon').val(patient.nomor_telepon);
        $('#jenis_kelamin').val(patient.jenis_kelamin);
        $('#saveButton').attr('onclick', 'updatePatient()');
    });
}

function updatePatient() {
    const formData = $('#patientForm').serialize() + '&action=update';
    $.post('crud_handler.php', formData, function(response) {
        const id = $('#id_pasien').val();
        const row = `
            <td>${$('#nama').val()}</td>
            <td>${$('#tanggal_lahir').val()}</td>
            <td>${$('#alamat').val()}</td>
            <td>${$('#nomor_telepon').val()}</td>
            <td>${$('#jenis_kelamin').val()}</td>
            <td class='text-center'>
                <button class='btn btn-warning btn-sm' onclick='editPatient(${id})'>Edit</button>
                <button class='btn btn-danger btn-sm' onclick='deletePatient(${id})'>Hapus</button>
            </td>
        `;
        $(`#row-${id}`).html(row);
        $('#patientModal').modal('hide');
    });
}

        function savePatient() {
            const formData = $('#patientForm').serialize() + '&action=add';
            $.post('crud_handler.php', formData, function(response) {
                $('#pasienTable').append(response);
                $('#patientModal').modal('hide');
            });
        }

        function deletePatient(id) {
            if (confirm('Yakin ingin menghapus pasien ini?')) {
                $.post('crud_handler.php', { action: 'delete', id_pasien: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
