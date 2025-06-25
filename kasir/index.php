<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data penjualan + obat
$result = $conn->query("
    SELECT p.tanggal, p.jumlah, o.nama_obat, o.harga, (p.jumlah * o.harga) AS total
    FROM penjualan p
    JOIN obat o ON p.id_obat = o.id_obat
    ORDER BY p.tanggal DESC
");

// Ambil notifikasi pemesanan menunggu
$notif = $conn->query("SELECT COUNT(*) as total FROM pemesanan WHERE status = 'Menunggu'");
$notif_count = $notif->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #f5af19, #f12711);
        color: #fff;
        padding: 40px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background: rgba(0, 0, 0, 0.75);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 0 12px rgba(255, 215, 0, 0.4);
    }

    h2,
    h3 {
        text-align: center;
        color: #FFD700;
        margin-bottom: 20px;
    }

    .notif-box {
        background-color: #f39c12;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .nav-buttons {
        text-align: center;
        margin-bottom: 30px;
    }

    .nav-buttons a {
        display: inline-block;
        background: #FFD700;
        color: #000;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        margin: 5px;
        font-weight: bold;
    }

    .nav-buttons a:hover {
        background: #e0c200;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        color: #fff;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #555;
    }

    th {
        background-color: #222;
        color: #FFD700;
    }

    tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .footer-nav {
        text-align: center;
        margin-top: 30px;
    }

    .footer-nav a {
        display: inline-block;
        background-color: #e74c3c;
        color: white;
        padding: 10px 18px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>👩‍💼 Selamat Datang, Kasir <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</h2>

        <div class="notif-box">
            🔔 Ada <strong><?= $notif_count ?></strong> pemesanan baru menunggu diproses!
        </div>

        <div class="nav-buttons">
            <a href="transaksi.php">🛒 Transaksi Penjualan</a>
            <a href="data_pemesanan.php">📋 Lihat Pemesanan</a>
            <a href="laporan_harian.php">📅 Laporan Harian</a> <!-- ✅ Tambahan -->
            <a href="laporan_bulanan.php">🗓️ Laporan Bulanan</a> <!-- ✅ Tambahan -->
            <a href="../auth/logout.php" style="background-color: #e74c3c; color: #fff;">🚪 Logout</a>
        </div>

        <h3>📊 Riwayat Penjualan</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">Belum ada transaksi penjualan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tambahan Tabel Stok Obat -->
        <h3 style="margin-top: 40px;">📦 Stok Obat Tersedia</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Tanggal Kadaluarsa</th>
                </tr>
            </thead>
            <tbody>
                <?php
        $stok = $conn->query("SELECT * FROM obat ORDER BY nama_obat ASC");
        if ($stok && $stok->num_rows > 0):
            while ($row = $stok->fetch_assoc()):
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td><?= $row['tgl_kadaluarsa'] ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5">Tidak ada data obat.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>