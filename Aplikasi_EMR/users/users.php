<?php
include '../config.php';

// Ambil data users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Daftar Pengguna</h2>
        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-muted">Data Pengguna</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetModal('Tambah Pengguna', 'add')">Tambah Pengguna Baru</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php foreach ($users as $user) : ?>
                        <tr id="row-<?= $user['id']; ?>">
                            <td><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['role']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#userModal" onclick="editUser(<?= $user['id']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Form Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="dokter">Dokter</option>
                                <option value="staff">Staff</option>
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
            $('#userModalLabel').text(title);
            $('#id').val('');
            $('#userForm')[0].reset();
            
            // Reset tombol dan aksi
            if (mode === 'add') {
                $('#saveButton').text('Simpan').attr('onclick', 'saveUser()');
            } else if (mode === 'edit') {
                $('#saveButton').text('Update').attr('onclick', 'updateUser()');
            }
        }

        function editUser(id) {
            $.post('../users/crud_handler.php', { action: 'fetch', id: id }, function(data) {
                const user = JSON.parse(data);
                resetModal('Edit Pengguna', 'edit');
                $('#id').val(user.id);
                $('#nama_lengkap').val(user.nama_lengkap);
                $('#username').val(user.username);
                $('#role').val(user.role);
            });
        }

        function updateUser() {
            const formData = $('#userForm').serialize() + '&action=update';
            $.post('../users/crud_handler.php', formData, function(response) {
                const id = $('#id').val();
                const row = `
                    <td>${$('#nama_lengkap').val()}</td>
                    <td>${$('#username').val()}</td>
                    <td>${$('#role').val()}</td>
                    <td class='text-center'>
                        <button class='btn btn-warning btn-sm' onclick='editUser(${id})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteUser(${id})'>Hapus</button>
                    </td>
                `;
                $(`#row-${id}`).html(row);
                $('#userModal').modal('hide');
            });
        }

        function saveUser() {
            const formData = $('#userForm').serialize() + '&action=add';
            $.post('../users/crud_handler.php', formData, function(response) {
                $('#userTable').append(response);
                $('#userModal').modal('hide');
            });
        }

        function deleteUser(id) {
            if (confirm('Yakin ingin menghapus pengguna ini?')) {
                $.post('../users/crud_handler.php', { action: 'delete', id: id }, function() {
                    $('#row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
