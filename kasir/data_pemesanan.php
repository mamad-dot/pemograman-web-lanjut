<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Jika kasir mengubah status
if (isset($_POST['ubah_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Ambil data pemesanan
    $stmt = $conn->prepare("SELECT * FROM pemesanan WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    $jumlah = $data['jumlah'];
    $id_obat = $data['id_obat'];

    // Jika status diubah ke Diproses atau Selesai → kurangi stok
    if ($status === 'Diproses' || $status === 'Selesai') {
        $conn->query("UPDATE obat SET stok = stok - $jumlah WHERE id_obat = $id_obat");
    }

    // Update status
    $stmt = $conn->prepare("UPDATE pemesanan SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

// Ambil semua data pemesanan
$result = $conn->query("
    SELECT p.*, u.nama AS nama_pelanggan, o.nama_obat
    FROM pemesanan p
    JOIN users u ON p.id_user = u.id
    JOIN obat o ON p.id_obat = o.id_obat
    ORDER BY p.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pemesanan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #f5af19, #f12711);
        color: #fff;
        padding: 30px;
    }

    .container {
        background: rgba(0, 0, 0, 0.85);
        padding: 25px 30px;
        border-radius: 12px;
        max-width: 1100px;
        margin: auto;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #FFD700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background-color: #fff;
        color: #333;
        border-radius: 8px;
        overflow: hidden;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #2c3e50;
        color: white;
    }

    form select {
        padding: 6px;
        border-radius: 6px;
    }

    form button {
        padding: 6px 10px;
        background-color: #27ae60;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
    }

    a.bukti {
        color: #2980b9;
        text-decoration: underline;
    }

    .back-btn {
        display: inline-block;
        margin-top: 20px;
        background-color: #e74c3c;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>📋 Daftar Pemesanan Obat</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Obat</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>
                        <?php if ($row['bukti_pembayaran']): ?>
                        <a class="bukti" href="../uploads/<?= $row['bukti_pembayaran'] ?>" target="_blank">Lihat</a>
                        <?php else: ?>
                        Tidak Ada
                        <?php endif; ?>
                    </td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <select name="status">
                                <option <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option <?= $row['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option <?= $row['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            <button type="submit" name="ubah_status">Ubah</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">Belum ada pemesanan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="text-align: center;">
            <a class="back-btn" href="index.php">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>