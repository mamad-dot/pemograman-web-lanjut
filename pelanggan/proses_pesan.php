<?php
session_start();
include '../config/db.php';

$id_user = $_SESSION['user_id'];
$id_obat = $_POST['id_obat'];
$jumlah = $_POST['jumlah'];
$tanggal = date('Y-m-d');
$status = 'Selesai'; // langsung dianggap selesai agar masuk laporan harian

$bukti = null;
if (!empty($_FILES['bukti']['name'])) {
    $file_name = time() . "_" . basename($_FILES['bukti']['name']);
    $target = "../uploads/" . $file_name;
    if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target)) {
        $bukti = $file_name;
    }
}

// Simpan ke tabel pemesanan
$stmt = $conn->prepare("INSERT INTO pemesanan (id_user, id_obat, jumlah, tanggal, bukti_pembayaran, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiisss", $id_user, $id_obat, $jumlah, $tanggal, $bukti, $status);
$stmt->execute();

// Tambahkan juga ke tabel penjualan agar masuk ke laporan harian/bulanan
$conn->query("INSERT INTO penjualan (id_obat, jumlah, tanggal) VALUES ($id_obat, $jumlah, '$tanggal')");

// Kurangi stok
$conn->query("UPDATE obat SET stok = stok - $jumlah WHERE id_obat = $id_obat");

header("Location: index.php");
exit;