<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Ambil notifikasi status yang belum dibaca
$notif = $conn->query("SELECT COUNT(*) AS jml FROM pemesanan WHERE id_user = $user_id AND notifikasi_dibaca = 0");
$jumlah_notif = $notif->fetch_assoc()['jml'] ?? 0;

// Ambil riwayat pemesanan
$result = $conn->query("
    SELECT p.id, p.tanggal, o.nama_obat, p.jumlah, o.harga, (p.jumlah * o.harga) AS total,
           p.bukti_pembayaran, p.status
    FROM pemesanan p
    JOIN obat o ON p.id_obat = o.id_obat
    WHERE p.id_user = $user_id
    ORDER BY p.tanggal DESC
");

// Tandai notifikasi sudah dibaca
$conn->query("UPDATE pemesanan SET notifikasi_dibaca = 1 WHERE id_user = $user_id");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pelanggan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            margin: 0;
            padding: 30px;
            color: #fff;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        h2,
        h3 {
            text-align: center;
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .nav-buttons a {
            background: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .nav-buttons a:hover {
            background: #2980b9;
        }

        .notif {
            background: #f1c40f;
            color: #000;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            color: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f0f8ff;
        }

        .status {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .status.Menunggu {
            background: #f39c12;
            color: white;
        }

        .status.Diproses {
            background: #3498db;
            color: white;
        }

        .status.Selesai {
            background: #2ecc71;
            color: white;
        }

        .status.Ditolak {
            background: #e74c3c;
            color: white;
        }

        .empty {
            text-align: center;
            color: #ccc;
        }

        .nota-link {
            text-decoration: none;
            color: #27ae60;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>👋 Halo, <?= htmlspecialchars($username) ?>!</h2>

        <!-- Tambahkan di bagian nav-buttons -->
        <div class="nav-buttons">
            <a href="lihat_obat.php">🔍 Lihat Daftar Obat</a>
            <a href="pesan.php">🛒 Pesan Obat</a>
            <a href="riwayat_pesanan.php">📋 Riwayat Pesanan</a>
            <a href="../auth/logout.php">🚪 Logout</a>
        </div>

        <?php if ($jumlah_notif > 0): ?>
            <div class="notif">
                🔔 Anda memiliki <?= $jumlah_notif ?> pemesanan dengan status baru. Silakan cek!
            </div>
        <?php endif; ?>

        <h3>📄 Riwayat Pemesanan Anda</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                            <td>
                                <?php if ($row['bukti_pembayaran']): ?>
                                    <a href="../uploads/<?= $row['bukti_pembayaran'] ?>" target="_blank">📎 Lihat</a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td>
                                <a class="nota-link" href="nota.php?id=<?= $row['id'] ?>" target="_blank">🖨️ Cetak</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">Belum ada pemesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>