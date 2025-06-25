<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil semua data obat yang stoknya > 0
$query = $conn->query("SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Obat</title>
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
            background: rgba(44, 62, 80, 0.9);
            /* Ubah ke warna gelap */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #fff;
            /* Ubah warna teks menjadi putih */
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            /* Ubah ke semi-transparan */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            /* Ubah warna border */
            color: #fff;
            /* Ubah warna teks menjadi putih */
        }

        th {
            background: #3498db;
            color: white;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
            /* Ubah warna hover */
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            color: #fff;
            /* Ubah warna teks menjadi putih */
            font-style: italic;
        }

        .stock-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .stock-high {
            background: rgba(40, 167, 69, 0.8);
            color: white;
        }

        .stock-medium {
            background: rgba(255, 193, 7, 0.8);
            color: black;
        }

        .stock-low {
            background: rgba(220, 53, 69, 0.8);
            color: white;
        }

        .btn-pesan {
            display: inline-block;
            padding: 5px 15px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-pesan:hover {
            background: #27ae60;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>✨ Daftar Obat yang Tersedia</h2>
        <a href="index.php" class="back-btn">⬅ Kembali ke Dashboard</a>

        <?php if ($query->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $query->fetch_assoc()):
                        $stock_class = $row['stok'] > 20 ? 'stock-high' : ($row['stok'] > 10 ? 'stock-medium' : 'stock-low');
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><span class="stock-status <?= $stock_class ?>"><?= $row['stok'] ?></span></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_kadaluarsa'])) ?></td>
                            <td>
                                <a href="pesan_obat.php?id=<?= $row['id_obat'] ?>" class="btn-pesan">Pesan</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-message">Mohon maaf, saat ini tidak ada obat yang tersedia.</p>
        <?php endif; ?>
    </div>
</body>

</html>