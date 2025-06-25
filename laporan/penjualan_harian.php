<?php
include '../config/db.php';
$tanggal = date('Y-m-d');

$query = "SELECT penjualan.*, obat.nama_obat, (penjualan.jumlah * obat.harga) AS total
          FROM penjualan 
          JOIN obat ON penjualan.id_obat = obat.id 
          WHERE penjualan.tanggal = '$tanggal'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Harian</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #2c3e50;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:hover {
            background: #f5f6fa;
        }

        tr:last-child {
            background: #f1f2f6;
            font-weight: bold;
        }

        .total-row td {
            background: #2c3e50;
            color: #fff;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .print-btn {
            float: right;
            padding: 10px 20px;
            background: #27ae60;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            background: #219a52;
            transform: translateY(-2px);
        }

        @media print {
            body {
                background: white;
                padding: 20px;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .back-link,
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="../index.php" class="back-link">⬅ Kembali ke Dashboard</a>
        <button onclick="window.print()" class="print-btn">🖨️ Cetak Laporan</button>
        <h2>📊 Laporan Penjualan Harian (<?= date('d F Y', strtotime($tanggal)) ?>)</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
            <?php
            $no = 1;
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $total += $row['total'];
                echo "<tr>
                    <td>$no</td>
                    <td>{$row['nama_obat']}</td>
                    <td>{$row['jumlah']}</td>
                    <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                  </tr>";
                $no++;
            }
            ?>
            <tr class="total-row">
                <td colspan="3" align="right">Total Penjualan</td>
                <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>
</body>

</html>