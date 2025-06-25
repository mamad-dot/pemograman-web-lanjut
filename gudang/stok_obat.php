<?php
include '../config/db.php';

// Ambil data stok dengan JOIN ke tabel obat
$query = "
    SELECT 
        o.nama_obat, 
        s.jumlah AS stok, 
        s.tgl_kadaluarsa 
    FROM stok_obat s
    JOIN obat o ON s.id_obat = o.id_obat
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Stok Obat</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background: #f2f2f2;
    }

    h2 {
        color: #333;
    }

    a {
        text-decoration: none;
        color: #007bff;
        margin-bottom: 15px;
        display: inline-block;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        background: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    th {
        background-color: #2e3d49;
        color: white;
    }

    tr:hover {
        background-color: #f0f0f0;
    }
    </style>
</head>

<body>

    <h2>Data Stok Obat</h2>
    <a href="../admin/index.php">⬅ Kembali ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Stok</th>
                <th>Tanggal Kadaluarsa</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $no = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td>{$row['nama_obat']}</td>";
                echo "<td>{$row['stok']}</td>";
                echo "<td>{$row['tgl_kadaluarsa']}</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='4'>Belum ada data stok obat.</td></tr>";
        }
        ?>
        </tbody>
    </table>

</body>

</html>