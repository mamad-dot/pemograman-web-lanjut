<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data dari form
$nama_obat = $_POST['nama_obat'];
$stok = $_POST['stok'];
$tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];

// Validasi sederhana
if (empty($nama_obat) || empty($stok) || empty($tgl_kadaluarsa)) {
    echo "Semua kolom harus diisi.";
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO obat (nama_obat, stok, tgl_kadaluarsa) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $nama_obat, $stok, $tgl_kadaluarsa);

if ($stmt->execute()) {
    header("Location: data_obat.php?status=sukses");
    exit;
} else {
    echo "Gagal menyimpan obat: " . $stmt->error;
}