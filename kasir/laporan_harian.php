<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Debug: Tampilkan informasi koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil tanggal dari filter atau gunakan tanggal hari ini
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Debug: Tampilkan query yang akan dijalankan
$query = "SELECT p.tanggal, p.jumlah, o.nama_obat, o.harga, (p.jumlah * o.harga) AS total 
         FROM penjualan p 
         INNER JOIN obat o ON p.id_obat = o.id_obat 
         WHERE DATE(p.tanggal) = '$tanggal' 
         ORDER BY p.tanggal DESC";

echo "<!-- Query: $query -->";

$result = $conn->query($query);

// Debug: Tampilkan error jika query gagal
if (!$result) {
    die("Error query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Harian</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 30px;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        h2 {
            text-align: center;
            color: #FFD700;
            font-size: 24px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .filter-form {
            text-align: center;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }

        .filter-form input[type="date"] {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.9);
        }

        .filter-form button {
            padding: 10px 20px;
            background: #FFD700;
            border: none;
            border-radius: 5px;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-form button:hover {
            background: #FFC000;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(255, 215, 0, 0.2);
            color: #FFD700;
            font-weight: bold;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .total-row {
            background: rgba(255, 215, 0, 0.1);
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 20px 0;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .action-buttons a,
        .action-buttons button {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .back-btn {
            background: #e67e22;
            color: white;
        }

        .print-btn {
            background: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
        }

        .back-btn:hover,
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @media print {
            body {
                background: white;
                color: black;
                padding: 20px;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .action-buttons,
            .filter-form {
                display: none;
            }

            table {
                background: white;
            }

            th {
                background: #f0f0f0;
                color: black;
            }

            tr:hover {
                background: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="filter-form">
            <form method="GET">
                <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
                <button type="submit">Filter Data</button>
            </form>
        </div>

        <h2>Laporan Penjualan Harian - <?= date('d F Y', strtotime($tanggal)) ?></h2>

        <?php if ($result->num_rows === 0): ?>
            <div class="no-data">
                <p>Tidak ada data penjualan untuk tanggal <?= date('d F Y', strtotime($tanggal)) ?></p>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
                <?php
                $grand_total = 0;
                while ($row = $result->fetch_assoc()):
                    $grand_total += $row['total'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= htmlspecialchars($row['jumlah']) ?></td>
                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="3">Total Penjualan</td>
                    <td>Rp<?= number_format($grand_total, 0, ',', '.') ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="index.php" class="back-btn">🔙 Kembali ke Dashboard</a>
            <button onclick="window.print()" class="print-btn">🖨️ Cetak Laporan</button>
        </div>
    </div>
</body>

</html>