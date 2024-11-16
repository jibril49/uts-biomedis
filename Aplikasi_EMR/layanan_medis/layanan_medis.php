<?php
include '../config.php';

// Fetch data layanan medis
$stmt = $pdo->query("SELECT * FROM layanan_medis");
$layanan_medis = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Layanan Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Daftar Layanan Medis</h2>
        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">Data Layanan Medis</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#layananModal" onclick="resetModal('Tambah Layanan Medis', 'add')">Tambah Layanan Medis Baru</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="layananTable">
                    <?php foreach ($layanan_medis as $l) : ?>
                        <tr id="row-<?= $l['id_layanan']; ?>">
                            <td><?= htmlspecialchars($l['nama_layanan']); ?></td>
                            <td><?= htmlspecialchars($l['deskripsi']); ?></td>
                            <td><?= htmlspecialchars($l['biaya']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#layananModal" onclick="editLayanan(<?= $l['id_layanan']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteLayanan(<?= $l['id_layanan']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="layananModal" tabindex="-1" aria-labelledby="layananModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="layananModalLabel">Form Tambah Layanan Medis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="layananForm">
                        <input type="hidden" id="id_layanan" name="id_layanan">
                        <div class="mb-3">
                            <label for="nama_layanan" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="biaya" class="form-label">Biaya</label>
                            <input type="number" class="form-control" id="biaya" name="biaya" required>
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
            $('#layananModalLabel').text(title);
            $('#id_layanan').val('');
            $('#layananForm')[0].reset();
            $('#saveButton').attr('onclick', mode === 'add' ? 'saveLayanan()' : 'updateLayanan()');
        }

        function editLayanan(id) {
            $.post('../layanan_medis/crud_handler.php', { action: 'fetch', id_layanan: id }, function(data) {
                const layanan = JSON.parse(data);
                resetModal('Edit Layanan Medis', 'edit');
                $('#id_layanan').val(layanan.id_layanan);
                $('#nama_layanan').val(layanan.nama_layanan);
                $('#deskripsi').val(layanan.deskripsi);
                $('#biaya').val(layanan.biaya);
                $('#saveButton').attr('onclick', 'updateLayanan()');
            });
        }

        function updateLayanan() {
            const formData = $('#layananForm').serialize() + '&action=update';
            $.post('../layanan_medis/crud_handler.php', formData, function(response) {
                const id = $('#id_layanan').val();
                const row = `
                    <td>${$('#nama_layanan').val()}</td>
                    <td>${$('#deskripsi').val()}</td>
                    <td>${$('#biaya').val()}</td>
                    <td class='text-center'>
                        <button class='btn btn-warning btn-sm' onclick='editLayanan(${id})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteLayanan(${id})'>Hapus</button>
                    </td>
                `;
                $(`#row-${id}`).html(row);
                $('#layananModal').modal('hide');
            });
        }

        function saveLayanan() {
            const formData = $('#layananForm').serialize() + '&action=add';
            $.post('../layanan_medis/crud_handler.php', formData, function(response) {
                $('#layananTable').append(response);
                $('#layananModal').modal('hide');
            });
        }

        function deleteLayanan(id) {
            if (confirm('Yakin ingin menghapus layanan ini?')) {
                $.post('../layanan_medis/crud_handler.php', { action: 'delete', id_layanan: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
