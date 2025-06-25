<?php
include '../config/db.php';
session_start();

$id = $_GET['id'];
$query = $conn->query("SELECT p.*, o.nama_obat, o.harga FROM pemesanan p JOIN obat o ON p.id_obat = o.id_obat WHERE p.id = $id");
$data = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cetak Nota</title>
    <style>
    body {
        font-family: Arial;
        padding: 20px;
    }

    .nota {
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
    }
    </style>
</head>

<body onload="window.print()">
    <div class="nota">
        <h3>🧾 NOTA PEMESANAN</h3>
        <p><strong>Tanggal:</strong> <?= $data['tanggal'] ?></p>
        <p><strong>Obat:</strong> <?= $data['nama_obat'] ?></p>
        <p><strong>Jumlah:</strong> <?= $data['jumlah'] ?></p>
        <p><strong>Harga Satuan:</strong> Rp<?= number_format($data['harga']) ?></p>
        <p><strong>Total:</strong> Rp<?= number_format($data['jumlah'] * $data['harga']) ?></p>
        <p><strong>Status:</strong> <?= $data['status'] ?></p>
    </div>
</body>

</html>