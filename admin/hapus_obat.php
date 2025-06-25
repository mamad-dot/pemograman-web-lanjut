<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("DELETE FROM obat WHERE id_obat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: data_obat.php");
exit;