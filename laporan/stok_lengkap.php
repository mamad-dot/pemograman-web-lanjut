<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'gudang'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$today = date('Y-m-d');
$threshold = date('Y-m-d', strtotime('+30 days'));

// Gabungkan data obat dan total pembelian
$query = $conn->query("
    SELECT 
        o.id,
        o.nama_obat,
        o.stok,
        o.tgl_kadaluarsa,
        IFNULL(SUM(p.jumlah), 0) AS total_pembelian
    FROM obat o
    LEFT JOIN pembelian p ON o.id = p.obat_id
    GROUP BY o.id, o.nama_obat, o.stok, o.tgl_kadaluarsa
    ORDER BY o.nama_obat ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Obat Lengkap</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h2>Laporan Stok Obat Lengkap</h2>
    <a href="../<?= $_SESSION['role'] ?>/index.php">⬅ Kembali ke Dashboard</a><br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Stok Sekarang</th>
            <th>Total Pembelian</th>
            <th>Tanggal Kadaluarsa</th>
            <th>Status</th>
        </tr>
        <?php $no = 1; while ($row = $query->fetch_assoc()): 
            if ($row['tgl_kadaluarsa'] < $today) {
                $status = '❌ Kadaluarsa';
            } elseif ($row['tgl_kadaluarsa'] <= $threshold) {
                $status = '⚠ Hampir Kadaluarsa';
            } else {
                $status = '✅ Aman';
            }
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
            <td><?= $row['stok'] ?></td>
            <td><?= $row['total_pembelian'] ?></td>
            <td><?= $row['tgl_kadaluarsa'] ?></td>
            <td><?= $status ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($query->num_rows === 0): ?>
    <p>Tidak ada data obat dalam sistem.</p>
    <?php endif; ?>
</body>

</html>