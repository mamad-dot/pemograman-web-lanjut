<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Ambil bulan dan tahun dari filter atau gunakan bulan dan tahun saat ini
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query = "
    SELECT p.tanggal, p.jumlah, o.nama_obat, o.harga, (p.jumlah * o.harga) AS total
    FROM penjualan p
    JOIN obat o ON p.id_obat = o.id_obat
    WHERE MONTH(p.tanggal) = $bulan AND YEAR(p.tanggal) = $tahun
    ORDER BY p.tanggal DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #28a745;
            color: #fff;
        }

        @media print {
            .footer-nav {
                display: none;
            }

            @page {
                margin: 2cm;
            }
        }
    </style>
</head>

<body>
    <!-- Tambahkan form filter bulan dan tahun -->
    <div style="margin-bottom: 20px; text-align: center;">
        <form method="GET" style="display: inline-block;">
            <select name="bulan" style="padding: 5px;">
                <?php
                $bulan_list = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                foreach ($bulan_list as $key => $value) {
                    $selected = (isset($_GET['bulan']) ? $_GET['bulan'] : date('m')) == $key ? 'selected' : '';
                    echo "<option value=\"$key\" $selected>$value</option>";
                }
                ?>
            </select>
            <select name="tahun" style="padding: 5px;">
                <?php
                $tahun_sekarang = date('Y');
                for ($i = $tahun_sekarang - 5; $i <= $tahun_sekarang; $i++) {
                    $selected = (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y')) == $i ? 'selected' : '';
                    echo "<option value=\"$i\" $selected>$i</option>";
                }
                ?>
            </select>
            <button type="submit"
                style="background-color: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Filter</button>
        </form>
    </div>
    <h2>Laporan Penjualan Bulanan - <?= date('F Y') ?></h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
        <?php
        $grand_total = 0;
        while ($row = $result->fetch_assoc()):
            $grand_total += $row['total'];
        ?>
            <tr>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['nama_obat'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="4"><strong>Total Penjualan Bulan Ini</strong></td>
            <td><strong>Rp<?= number_format($grand_total, 0, ',', '.') ?></strong></td>
        </tr>
    </table>
    <div class="footer-nav" style="text-align:center; margin-top: 30px;">
        <a href="index.php"
            style="background-color: #e67e22; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">🔙
            Kembali ke Dashboard Kasir</a>
    </div>
    <!-- Tombol Print -->
    <div class="footer-nav" style="text-align:center; margin-top: 20px;">
        <button onclick="window.print()"
            style="background-color: #27ae60; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold;">
            🖨️ Print Laporan
        </button>
    </div>


</body>

</html>