<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pemesanan = $_POST['id_pemesanan'];
    $status_baru = $_POST['status'];

    // Ambil data pemesanan
    $cek = $conn->query("SELECT * FROM pemesanan WHERE id = $id_pemesanan");
    if ($cek->num_rows === 0) {
        echo "Pemesanan tidak ditemukan.";
        exit;
    }

    $data = $cek->fetch_assoc();
    $id_obat = $data['id_obat'];
    $jumlah = $data['jumlah'];

    // Jika status baru adalah "Diproses" atau "Selesai", kurangi stok
    if (in_array($status_baru, ['Diproses', 'Selesai'])) {
        $stok_obat = $conn->query("SELECT stok FROM obat WHERE id_obat = $id_obat")->fetch_assoc()['stok'];
        if ($stok_obat < $jumlah) {
            echo "Stok tidak mencukupi untuk diproses.";
            exit;
        }

        // Kurangi stok
        $conn->query("UPDATE obat SET stok = stok - $jumlah WHERE id_obat = $id_obat");
    }

    // Update status pemesanan
    $conn->query("UPDATE pemesanan SET status = '$status_baru' WHERE id = $id_pemesanan");

    header("Location: data_pemesanan.php?pesan=berhasil");
    exit;
} else {
    echo "Akses ditolak.";
    exit;
}