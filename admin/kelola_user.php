<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #2c3e50, #4b6b88);
        color: #ecf0f1;
        margin: 0;
        padding: 0;
    }

    .container {
        padding: 30px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        background-color: #7f8c8d;
        padding: 10px 15px;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .back-btn:hover {
        background-color: #95a5a6;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #34495e;
        color: #fff;
    }

    th,
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #777;
        text-align: center;
    }

    th {
        background-color: #2c3e50;
    }

    tr:hover {
        background-color: #3d566e;
    }

    .btn-hapus {
        background-color: #e74c3c;
        color: #fff;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-hapus:hover {
        background-color: #c0392b;
    }

    @media (max-width: 600px) {
        table {
            font-size: 14px;
        }

        th,
        td {
            padding: 8px;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>Daftar Pengguna</h2>
        <a href="index.php" class="back-btn">⬅ Kembali ke Dashboard</a>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['role']}</td>
                        <td>";
                    if ($row['role'] !== 'admin') {
                        echo "<a href='hapus_user.php?id={$row['id']}' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus pengguna ini?');\">Hapus</a>";
                    } else {
                        echo "-";
                    }
                    echo "</td></tr>";
                    $no++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>