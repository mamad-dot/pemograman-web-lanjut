<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Query untuk mengambil data pembelian
$query = "SELECT pb.id_pembelian, pb.tanggal, pb.jumlah, pb.total_harga, pb.supplier, o.nama_obat 
         FROM pembelian pb 
         JOIN obat o ON pb.id_obat = o.id_obat 
         ORDER BY pb.tanggal DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pembelian - Admin</title>
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
        <h1><i class="fas fa-shopping-cart"></i> Data Pembelian</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Supplier</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            $tanggal = date('d/m/Y', strtotime($row['tanggal']));
                            echo "<tr>
                                    <td>" . $no . "</td>
                                    <td>" . $tanggal . "</td>
                                    <td>" . htmlspecialchars($row['nama_obat']) . "</td>
                                    <td>" . $row['jumlah'] . "</td>
                                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                    <td>" . htmlspecialchars($row['supplier']) . "</td>
                                    <td>
                                        <button onclick=\"if(confirm('Apakah Anda yakin ingin menghapus data ini?')) { window.location.href='hapus_pembelian.php?id=" . $row['id_pembelian'] . "' }\" class='btn-delete'>
                                            <i class='fas fa-trash'></i> Hapus
                                        </button>
                                    </td>
                                </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data pembelian</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</body>

</html>

<style>
    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }
</style>