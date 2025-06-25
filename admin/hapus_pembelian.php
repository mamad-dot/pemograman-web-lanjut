<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Dapatkan informasi pembelian sebelum dihapus
    $query = "SELECT id_obat, jumlah FROM pembelian WHERE id_pembelian = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pembelian = $result->fetch_assoc();

    if ($pembelian) {
        // Kurangi stok obat
        $update_stok = "UPDATE obat SET stok = stok - ? WHERE id_obat = ?";
        $stmt = $conn->prepare($update_stok);
        $stmt->bind_param("ii", $pembelian['jumlah'], $pembelian['id_obat']);
        $stmt->execute();

        // Hapus data pembelian
        $delete = "DELETE FROM pembelian WHERE id_pembelian = ?";
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: data_pembelian.php?success=delete");
        } else {
            header("Location: data_pembelian.php?error=delete");
        }
    } else {
        header("Location: data_pembelian.php?error=not_found");
    }
} else {
    header("Location: data_pembelian.php");
}