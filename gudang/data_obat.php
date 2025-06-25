<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil semua data obat
$query = $conn->query("SELECT * FROM obat ORDER BY nama_obat ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Obat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
    * {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f1f5f9;
        padding: 40px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.06);
    }

    h2 {
        margin-bottom: 20px;
        color: #1e293b;
        text-align: center;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .top-bar a {
        background-color: #0ea5e9;
        color: white;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .top-bar a:hover {
        background-color: #0284c7;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #cbd5e1;
        padding: 12px 10px;
        text-align: center;
        font-size: 14px;
    }

    th {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    td {
        background-color: #f8fafc;
    }

    .aksi a {
        margin: 0 4px;
        color: #0ea5e9;
        text-decoration: none;
        font-weight: bold;
    }

    .aksi a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            gap: 10px;
        }

        table {
            font-size: 13px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Data Obat</h2>
        <div class="top-bar">
            <a href="tambah_obat.php">➕ Tambah Obat</a>
            <a href="index.php">⬅ Kembali ke Dashboard</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Stok</th>
                    <th>Tanggal Kedaluwarsa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($query->num_rows > 0): $no = 1; ?>
                <?php while ($row = $query->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td><?= $row['tgl_kadaluarsa'] ?></td>
                    <td class="aksi">
                        <a href="edit_obat.php?id=<?= $row['id_obat'] ?>">Edit</a> |
                        <a href="hapus_obat.php?id=<?= $row['id_obat'] ?>"
                            onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">Belum ada data obat.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>