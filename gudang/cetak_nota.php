<?php
require_once('../config/db.php');
session_start();

// Cek role gudang
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'gudang') {
    header('Location: ../auth/login.php');
    exit();
}

// Ambil ID pesanan
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan
$query = "SELECT p.*, o.nama_obat, o.harga, u.nama 
          FROM pesanan p 
          JOIN obat o ON p.id_obat = o.id_obat 
          JOIN users u ON p.user_id = u.id 
          WHERE p.id = ? AND p.status = 'approved'";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param('i', $id_pesanan);
if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error);
}

$result = $stmt->get_result();
if ($result === false) {
    die('Error getting result: ' . $stmt->error);
}

if ($result->num_rows === 0) {
    die('Pesanan tidak ditemukan atau belum disetujui');
}

$pesanan = $result->fetch_assoc();
$total = $pesanan['jumlah'] * $pesanan['harga'];
$tanggal = date('d/m/Y', strtotime($pesanan['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan #<?= $id_pesanan ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        .nota {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="nota">
        <div class="header">
            <h2>NOTA PESANAN OBAT</h2>
            <p>Apotek Sehat Sejahtera</p>
        </div>

        <div class="info">
            <div class="info-row">
                <span>No. Pesanan:</span>
                <span>#<?= $id_pesanan ?></span>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span><?= $tanggal ?></span>
            </div>
            <div class="info-row">
                <span>Nama Pelanggan:</span>
                <span><?= htmlspecialchars($pesanan['nama']) ?></span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($pesanan['nama_obat']) ?></td>
                    <td><?= $pesanan['jumlah'] ?></td>
                    <td>Rp <?= number_format($pesanan['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total Pembayaran: Rp <?= number_format($total, 0, ',', '.') ?>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di Apotek Sehat Sejahtera</p>
            <p>Semoga lekas sembuh!</p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()">Cetak Nota</button>
            <button onclick="window.history.back()">Kembali</button>
        </div>
    </div>
</body>

</html>