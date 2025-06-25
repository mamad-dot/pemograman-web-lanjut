<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $action = $_POST['action'];

    // Validasi input
    if (!is_numeric($id_pesanan) || !in_array($action, ['approve', 'reject'])) {
        $_SESSION['error'] = "Data tidak valid!";
        header("Location: data_pemesanan.php");
        exit;
    }

    // Ambil data pesanan untuk verifikasi stok
    $query = $conn->prepare("SELECT p.*, o.stok FROM pesanan p 
                           INNER JOIN obat o ON p.id_obat = o.id_obat 
                           WHERE p.id = ?");
    $query->bind_param("i", $id_pesanan);
    $query->execute();
    $result = $query->get_result();
    $pesanan = $result->fetch_assoc();

    if (!$pesanan) {
        $_SESSION['error'] = "Pesanan tidak ditemukan!";
        header("Location: data_pemesanan.php");
        exit;
    }

    // Proses verifikasi
    if ($action === 'approve') {
        // Cek stok mencukupi
        if ($pesanan['stok'] < $pesanan['jumlah']) {
            $_SESSION['error'] = "Stok obat tidak mencukupi!";
            header("Location: data_pemesanan.php");
            exit;
        }

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Update status pesanan
            $update_pesanan = $conn->prepare("UPDATE pesanan SET status = 'approved' WHERE id = ?");
            $update_pesanan->bind_param("i", $id_pesanan);
            $update_pesanan->execute();

            // Update stok obat
            $update_stok = $conn->prepare("UPDATE obat SET stok = stok - ? WHERE id_obat = ?");
            $update_stok->bind_param("ii", $pesanan['jumlah'], $pesanan['id_obat']);
            $update_stok->execute();

            $conn->commit();
            $_SESSION['success'] = "Pesanan berhasil disetujui!";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Terjadi kesalahan saat memproses pesanan!";
        }
    } else {
        // Reject pesanan
        $update_pesanan = $conn->prepare("UPDATE pesanan SET status = 'rejected' WHERE id = ?");
        $update_pesanan->bind_param("i", $id_pesanan);

        if ($update_pesanan->execute()) {
            $_SESSION['success'] = "Pesanan ditolak!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menolak pesanan!";
        }
    }

    header("Location: data_pemesanan.php");
    exit;
}

header("Location: data_pemesanan.php");
exit;