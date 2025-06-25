<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Obat Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);
        margin: 0;
        padding: 40px;
        color: #fff;
    }

    .container {
        max-width: 500px;
        margin: auto;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 32px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    h2 {
        margin-bottom: 24px;
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"] {
        width: 100%;
        padding: 10px 14px;
        margin-bottom: 20px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        background-color: #fff;
        color: #000;
    }

    .btn-group {
        text-align: center;
    }

    .btn {
        background-color: #1abc9c;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #16a085;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 16px;
        color: #f1c40f;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="container">
        <a href="data_obat.php" class="back-link">⬅ Kembali ke Data Obat</a>
        <h2>Tambah Obat Baru</h2>
        <form action="proses_tambah_obat.php" method="POST">
            <label for="nama_obat">Nama Obat</label>
            <input type="text" id="nama_obat" name="nama_obat" required>

            <label for="stok">Stok Awal</label>
            <input type="number" id="stok" name="stok" min="0" required>

            <label for="tgl_kadaluarsa">Tanggal Kadaluarsa</label>
            <input type="date" id="tgl_kadaluarsa" name="tgl_kadaluarsa" required>

            <div class="btn-group">
                <button type="submit" class="btn">💾 Simpan Obat</button>
            </div>
        </form>
    </div>
</body>

</html>