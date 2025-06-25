<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'gudang'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$today = date('Y-m-d');
$threshold = date('Y-m-d', strtotime('+30 days'));

$query = $conn->query("
    SELECT * FROM obat 
    WHERE tgl_kadaluarsa <= '$threshold'
    ORDER BY tgl_kadaluarsa ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Obat Kedaluwarsa</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);
        padding: 40px;
        margin: 0;
        color: #fff;
    }

    .container {
        max-width: 900px;
        margin: auto;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    a {
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        background-color: #f1c40f;
        color: #000;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: bold;
    }

    a:hover {
        background-color: #d4ac0d;
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
        color: #fff;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .kadaluarsa {
        color: red;
        font-weight: bold;
    }

    .hampir {
        color: orange;
        font-weight: bold;
    }

    .no-data {
        margin-top: 20px;
        color: #ffc107;
        text-align: center;
        font-style: italic;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>⚠ Laporan Obat Kadaluarsa</h2>
        <a href="../<?= $_SESSION['role'] ?>/index.php">⬅ Kembali ke Dashboard</a>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Stok</th>
                <th>Tanggal Kadaluarsa</th>
                <th>Status</th>
            </tr>
            <?php if ($query->num_rows > 0): ?>
            <?php $no = 1; while ($row = $query->fetch_assoc()): 
                    $status = ($row['tgl_kadaluarsa'] < $today) ? '❌ Kadaluarsa' : '⚠ Hampir Kadaluarsa';
                    $class = ($row['tgl_kadaluarsa'] < $today) ? 'kadaluarsa' : 'hampir';
                ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                <td><?= $row['stok'] ?></td>
                <td><?= $row['tgl_kadaluarsa'] ?></td>
                <td class="<?= $class ?>"><?= $status ?></td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="5" class="no-data">Tidak ada obat yang kadaluarsa atau mendekati kadaluarsa.</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>