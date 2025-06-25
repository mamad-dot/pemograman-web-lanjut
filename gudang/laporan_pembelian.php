<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'gudang'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data pembelian dan nama obat
$query = $conn->query("SELECT p.*, o.nama_obat 
FROM pembelian p 
INNER JOIN obat o ON p.id_obat = o.id_obat 
ORDER BY p.tanggal DESC");

if (!$query) {
    die("Error dalam query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian Obat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #34495e;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f1f4f6;
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            th,
            td {
                min-width: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-file-invoice"></i> Laporan Pembelian Obat</h2>
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> No</th>
                    <th><i class="fas fa-pills"></i> Nama Obat</th>
                    <th><i class="fas fa-box"></i> Jumlah</th>
                    <th><i class="fas fa-calendar-alt"></i> Tanggal Pembelian</th>
                    <th><i class="fas fa-money-bill-wave"></i> Total Harga</th>
                    <th><i class="fas fa-truck"></i> Supplier</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($query->num_rows > 0):
                    while ($row = $query->fetch_assoc()):
                        $tanggal = date('d F Y', strtotime($row['tanggal']));
                ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><?= number_format($row['jumlah']) ?></td>
                            <td><?= $tanggal ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['supplier']) ?></td>
                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="6" class="empty-message">
                            <i class="fas fa-info-circle"></i> Belum ada data pembelian.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>