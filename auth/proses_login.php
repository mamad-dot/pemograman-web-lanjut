<?php
session_start();
include '../config/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $query->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'admin': header("Location: ../admin/index.php"); break;
            case 'kasir': header("Location: ../kasir/index.php"); break;
            case 'gudang': header("Location: ../gudang/index.php"); break;
            case 'pelanggan': header("Location: ../pelanggan/index.php"); break;
        }
        exit;
    } else {
        echo "Login gagal, cek username atau password.";
    }
}
?>