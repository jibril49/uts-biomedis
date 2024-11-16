<?php
include '../config.php';

// Ambil data dokter
$stmt = $pdo->query("SELECT * FROM dokter");
$dokter = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokter</title>
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
        <h2 class="text-center text-primary mb-4">Daftar Dokter</h2>
        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">Data Dokter</h5>
                <!-- Button to trigger Add Modal -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dokterModal" onclick="resetModal('Tambah Dokter', 'add')">Tambah Dokter Baru</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Nomor Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="dokterTable">
                    <?php foreach ($dokter as $d) : ?>
                        <tr id="row-<?= $d['id_dokter']; ?>">
                            <td><?= htmlspecialchars($d['nama']); ?></td>
                            <td><?= htmlspecialchars($d['spesialisasi']); ?></td>
                            <td><?= htmlspecialchars($d['nomor_telepon']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#dokterModal" onclick="editDokter(<?= $d['id_dokter']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteDokter(<?= $d['id_dokter']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="dokterModal" tabindex="-1" aria-labelledby="dokterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dokterModalLabel">Form Tambah Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dokterForm">
                        <input type="hidden" id="id_dokter" name="id_dokter">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="spesialisasi" class="form-label">Spesialis</label>
                            <textarea class="form-control" id="spesialisasi" name="spesialisasi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
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
            $('#dokterModalLabel').text(title);
            $('#id_dokter').val('');
            $('#dokterForm')[0].reset();
            $('#saveButton').attr('onclick', mode === 'add' ? 'saveDokter()' : 'updateDokter()');
        }

        function editDokter(id) {
            $.post('../dokter/crud_handler.php', { action: 'fetch', id_dokter: id }, function(data) {
                const dokter = JSON.parse(data);
                resetModal('Edit Dokter', 'edit');
                $('#id_dokter').val(dokter.id_dokter);
                $('#nama').val(dokter.nama);
                $('#spesialisasi').val(dokter.spesialis);
                $('#nomor_telepon').val(dokter.nomor_telepon);
                $('#saveButton').attr('onclick', 'updateDokter()');
            });
        }

        function updateDokter() {
            const formData = $('#dokterForm').serialize() + '&action=update';
            $.post('../dokter/crud_handler.php', formData, function(response) {
                const id = $('#id_dokter').val();
                const row = `
                    <td>${$('#nama').val()}</td>
                    <td>${$('#spesialisasi').val()}</td>
                    <td>${$('#nomor_telepon').val()}</td>
                    <td class='text-center'>
                        <button class='btn btn-warning btn-sm' onclick='editDokter(${id})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteDokter(${id})'>Hapus</button>
                    </td>
                `;
                $(`#row-${id}`).html(row);
                $('#dokterModal').modal('hide');
            });
        }

        function saveDokter() {
            const formData = $('#dokterForm').serialize() + '&action=add';
            $.post('../dokter/crud_handler.php', formData, function(response) {
                $('#dokterTable').append(response);
                $('#dokterModal').modal('hide');
            });
        }

        function deleteDokter(id) {
            if (confirm('Yakin ingin menghapus dokter ini?')) {
                $.post('../dokter/crud_handler.php', { action: 'delete', id_dokter: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
