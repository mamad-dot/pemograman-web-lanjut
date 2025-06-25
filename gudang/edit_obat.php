<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'gudang', 'kasir'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$id_obat = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data obat
$stmt = $conn->prepare("SELECT * FROM obat WHERE id_obat = ?");
$stmt->bind_param("i", $id_obat);
$stmt->execute();
$result = $stmt->get_result();
$obat = $result->fetch_assoc();

if (!$obat) {
    echo "<h3>Obat tidak ditemukan.</h3>";
    exit;
}

// Update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_obat = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];
    $kategori = $_POST['kategori'];

    $stmt = $conn->prepare("UPDATE obat SET nama_obat=?, stok=?, harga=?, tgl_kadaluarsa=?, kategori=? WHERE id_obat=?");
    $stmt->bind_param("siissi", $nama_obat, $stok, $harga, $tgl_kadaluarsa, $kategori, $id_obat);
    $stmt->execute();

    header("Location: ../laporan/stok.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Obat</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #f12711, #f5af19);
        padding: 40px;
        color: #fff;
    }

    .container {
        max-width: 500px;
        margin: auto;
        background: rgba(0, 0, 0, 0.7);
        padding: 30px;
        border-radius: 12px;
    }

    h2 {
        text-align: center;
        color: #FFD700;
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: none;
        margin-top: 5px;
    }

    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #f39c12;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
    }

    a {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #FFD700;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>✏️ Edit Obat</h2>
        <form method="POST">
            <label>Nama Obat</label>
            <input type="text" name="nama_obat" value="<?= htmlspecialchars($obat['nama_obat']) ?>" required>

            <label>Stok</label>
            <input type="number" name="stok" value="<?= $obat['stok'] ?>" required>

            <label>Harga</label>
            <input type="number" name="harga" value="<?= $obat['harga'] ?>" required>

            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= $obat['kategori'] ?>" required>

            <label>Tanggal Kadaluarsa</label>
            <input type="date" name="tgl_kadaluarsa" value="<?= $obat['tgl_kadaluarsa'] ?>" required>

            <button type="submit">💾 Simpan Perubahan</button>
        </form>
        <a href="../laporan/stok.php">⬅ Kembali ke Stok</a>

    </div>
</body>

</html>