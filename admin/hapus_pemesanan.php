<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus pemesanan
    $query = "DELETE FROM pemesanan WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: data_pemesanan.php?success=1");
    } else {
        header("Location: data_pemesanan.php?error=1");
    }
    exit;
} else {
    header("Location: data_pemesanan.php");
    exit;
}