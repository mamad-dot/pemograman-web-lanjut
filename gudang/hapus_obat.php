<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM obat WHERE id_obat = $id");

header("Location: data_obat.php");
exit;
?>