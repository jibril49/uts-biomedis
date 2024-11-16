<?php
session_start();
include '../config.php'; // Pastikan file ini berisi koneksi database

// Periksa apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

// Fungsi logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login/login.php");
    exit();
}

// Query untuk mendapatkan data dari tabel
$doctors = mysqli_query($conn, "SELECT * FROM dokter LIMIT 5");
$patients = mysqli_query($conn, "SELECT * FROM pasien LIMIT 5");
$services = mysqli_query($conn, "SELECT * FROM layanan_medis LIMIT 5");
$users = mysqli_query($conn, "SELECT * FROM users LIMIT 5");

// Mengambil data rekam medis dan mengganti ID Pasien dengan Nama Pasien
$medical_records = mysqli_query($conn, "
    SELECT rm.id_rekam_medis, rm.tanggal_kunjungan, rm.pengobatan, rm.catatan, p.nama AS nama_pasien, d.nama AS nama_dokter, rm.diagnosis 
    FROM rekam_medis rm
    JOIN pasien p ON rm.id_pasien = p.id_pasien
    JOIN dokter d ON rm.id_dokter = d.id_dokter
    LIMIT 5
");

// Mengambil data layanan rekam medis dan mengganti ID Rekam Medis dan ID Layanan dengan Diagnosis dan Nama Layanan
$services_medical_records = mysqli_query($conn, "
    SELECT lrm.id_layanan_rekam_medis, rm.diagnosis, lm.nama_layanan
    FROM layanan_rekam_medis lrm
    JOIN rekam_medis rm ON lrm.id_rekam_medis = rm.id_rekam_medis
    JOIN layanan_medis lm ON lrm.id_layanan = lm.id_layanan
    LIMIT 5
");

// Jika terjadi error dalam query, tampilkan pesan error
if (!$doctors || !$patients || !$services || !$medical_records || !$users) {
    die("Error dalam query database: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Styling yang telah ditingkatkan */
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #4e54c8;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .sidebar {
            background: #4e54c8;
            color: white;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding: 2rem 1rem;
        }
        .sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 10px 0;
            font-size: 1rem;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #3c42b7;
            padding-left: 10px;
        }
        .container {
            margin-left: 220px;
            padding: 2rem;
        }
        h1 {
            color: #4e54c8;
            margin-bottom: 20px;
        }
        .table-container {
            margin-bottom: 2rem;
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .table-container h2 {
            margin: 0 0 15px;
            color: #4e54c8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #4e54c8;
            color: white;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        .logout-btn {
            text-decoration: none;
            color: white;
            background: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?> (Admin)
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
    <a href="../dokter/dokter.php">Tabel Dokter</a>
    <a href="../pasien/pasien.php">Tabel Pasien</a>
    <a href="../layanan_medis/layanan_medis.php">Tabel Layanan Medis</a>
    <a href="../rekam_medis/rekam_medis.php">Tabel Rekam Medis</a>
    <a href="../users/users.php">Tabel Pengguna</a>
    <a href="../layanan_rekam_medis/layanan_rekam_medis.php">Tabel Layanan Rekam Medis</a>
</div>


    <!-- Main Content -->
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Tabel Dokter -->
        <div id="doctors" class="table-container">
            <h2>Daftar Dokter</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($doctor = mysqli_fetch_assoc($doctors)): ?>
                        <tr>
                            <td><?= htmlspecialchars($doctor['id_dokter']); ?></td>
                            <td><?= htmlspecialchars($doctor['nama']); ?></td>
                            <td><?= htmlspecialchars($doctor['spesialisasi']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Pasien -->
        <div id="patients" class="table-container">
            <h2>Daftar Pasien</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient['id_pasien']); ?></td>
                            <td><?= htmlspecialchars($patient['nama']); ?></td>
                            <td><?= htmlspecialchars($patient['jenis_kelamin']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Layanan -->
        <div id="services" class="table-container">
            <h2>Layanan Medis</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service = mysqli_fetch_assoc($services)): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['id_layanan']); ?></td>
                            <td><?= htmlspecialchars($service['nama_layanan']); ?></td>
                            <td><?= htmlspecialchars($service['deskripsi']); ?></td>
                            <td><?= htmlspecialchars($service['biaya']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>


        <!-- Tabel Rekam Medis -->
        <div id="medical_records" class="table-container">
            <h2>Daftar Rekam Medis</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Diagnosis</th>
                        <th>Pengobatan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service = mysqli_fetch_assoc($medical_records)): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['id_rekam_medis']); ?></td>
                            <td><?= htmlspecialchars($service['nama_pasien']); ?></td>
                            <td><?= htmlspecialchars($service['nama_dokter']); ?></td>
                            <td><?= htmlspecialchars($service['tanggal_kunjungan']); ?></td>
                            <td><?= htmlspecialchars($service['diagnosis']); ?></td>
                            <td><?= htmlspecialchars($service['pengobatan']); ?></td>
                            <td><?= htmlspecialchars($service['catatan']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>


        <!-- Tabel Pengguna -->
        <div id="users" class="table-container">
            <h2>Daftar Pengguna</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']); ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Layanan Rekam Medis -->
        <div id="services_medical_records" class="table-container">
            <h2>Daftar Layanan Rekam Medis</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Layanan Rekam Medis</th>
                        <th>Diagnosa</th>
                        <th>Layanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service_record = mysqli_fetch_assoc($services_medical_records)): ?>
                        <tr>
                            <td><?= htmlspecialchars($service_record['id_layanan_rekam_medis']); ?></td>
                            <td><?= htmlspecialchars($service_record['diagnosis']); ?></td>
                            <td><?= htmlspecialchars($service_record['nama_layanan']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html
