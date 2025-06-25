<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Query untuk mengambil data penjualan
$query = "SELECT p.*, o.nama_obat, o.harga, (p.jumlah * o.harga) as total_harga 
          FROM penjualan p 
          JOIN obat o ON p.id_obat = o.id_obat 
          ORDER BY p.tanggal DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Penjualan - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Gunakan style yang sama dengan data_obat.php */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(0, 0, 0, 0.2);
            font-weight: bold;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        .btn-back:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-cash-register"></i> Data Penjualan</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $no . "</td>
                                    <td>" . $row['tanggal'] . "</td>
                                    <td>" . $row['nama_obat'] . "</td>
                                    <td>" . $row['jumlah'] . "</td>
                                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data penjualan</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</body>

</html>