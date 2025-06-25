<?php
include '../config/db.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $allowed_roles = ['admin', 'kasir', 'gudang', 'pelanggan'];
    if (!in_array($role, $allowed_roles)) die("Role tidak valid");

    $query = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')";

    if ($conn->query($query)) {
        header("Location: login.php?role=" . $role);
    } else {
        echo "Gagal mendaftar: " . $conn->error;
    }
}