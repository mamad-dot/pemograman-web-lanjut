<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Tambahkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Query untuk mengambil data pemesanan
$query = "SELECT p.id, p.tanggal, p.jumlah, (p.jumlah * o.harga) as total_harga, p.status, 
                 o.nama_obat, o.harga, u.username as nama_pelanggan 
          FROM pemesanan p 
          JOIN obat o ON p.id_obat = o.id_obat 
          JOIN users u ON p.id_user = u.id 
          ORDER BY p.tanggal DESC";

// Eksekusi query dengan pengecekan error
try {
    $result = $conn->query($query);
    if ($result === false) {
        throw new Exception("Error executing query: " . $conn->error);
    }
} catch (Exception $e) {
    die("<div style='color: red; padding: 20px;'>" . $e->getMessage() . "</div>");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pemesanan - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

        .status-pending {
            color: #f1c40f;
            font-weight: bold;
        }

        .status-selesai {
            color: #2ecc71;
            font-weight: bold;
        }

        .status-batal {
            color: #e74c3c;
            font-weight: bold;
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

        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: #333;
            max-width: 400px;
            width: 90%;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-btn {
            padding: 8px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-btn-cancel {
            background: #95a5a6;
            color: white;
        }

        .modal-btn-confirm {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-clipboard-list"></i> Data Pemesanan</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                if (!$row) {
                                    throw new Exception("Error fetching row data");
                                }
                                $status_class = '';
                                switch ($row['status']) {
                                    case 'pending':
                                        $status_class = 'status-pending';
                                        break;
                                    case 'selesai':
                                        $status_class = 'status-selesai';
                                        break;
                                    case 'batal':
                                        $status_class = 'status-batal';
                                        break;
                                }

                                echo "<tr>
                                        <td>" . $no . "</td>
                                        <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_obat']) . "</td>
                                        <td>" . htmlspecialchars($row['jumlah']) . "</td>
                                        <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                        <td class='" . $status_class . "'>" . htmlspecialchars($row['status']) . "</td>
                                        <td>
                                            <a href='#' onclick='showDeleteModal(" . intval($row["id"]) . ")' class='btn-delete'>
                                                <i class='fas fa-trash'></i> Hapus
                                            </a>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center;'>Tidak ada data pemesanan</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='8' style='text-align: center; color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

        <!-- Modal Konfirmasi Hapus -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h3>Konfirmasi Hapus</h3>
                <p>Apakah Anda yakin ingin menghapus pemesanan ini?</p>
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">Batal</button>
                    <button class="modal-btn modal-btn-confirm" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>

        <script>
            let deleteId = null;
            const modal = document.getElementById('deleteModal');

            function showDeleteModal(id) {
                deleteId = id;
                modal.style.display = 'flex';
            }

            function hideDeleteModal() {
                modal.style.display = 'none';
                deleteId = null;
            }

            function confirmDelete() {
                if (deleteId) {
                    window.location.href = 'hapus_pemesanan.php?id=' + deleteId;
                }
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    hideDeleteModal();
                }
            }
        </script>
    </div>
</body>

</html>