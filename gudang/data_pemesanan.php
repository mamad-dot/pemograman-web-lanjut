<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data pemesanan dengan detail obat
$query = $conn->query("SELECT p.*, o.nama_obat, u.username 
FROM pesanan p 
INNER JOIN obat o ON p.id_obat = o.id_obat 
INNER JOIN users u ON p.user_id = u.id 
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
    <title>Verifikasi Pemesanan</title>
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

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .approve-btn {
            background-color: #28a745;
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
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
        <h2><i class="fas fa-clipboard-check"></i> Verifikasi Pemesanan</h2>
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> No</th>
                    <th><i class="fas fa-user"></i> Pelanggan</th>
                    <th><i class="fas fa-pills"></i> Nama Obat</th>
                    <th><i class="fas fa-box"></i> Jumlah</th>
                    <th><i class="fas fa-calendar-alt"></i> Tanggal</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-cogs"></i> Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($query->num_rows > 0):
                    while ($row = $query->fetch_assoc()):
                        $status_class = '';
                        switch ($row['status']) {
                            case 'pending':
                                $status_class = 'status-pending';
                                break;
                            case 'approved':
                                $status_class = 'status-approved';
                                break;
                            case 'rejected':
                                $status_class = 'status-rejected';
                                break;
                        }
                ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><?= number_format($row['jumlah']) ?></td>
                            <td><?= date('d F Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <span class="status-badge <?= $status_class ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <form action="proses_verifikasi.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="action-btn approve-btn">
                                            <i class="fas fa-check"></i> Setuju
                                        </button>
                                        <button type="submit" name="action" value="reject" class="action-btn reject-btn">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                <?php elseif ($row['status'] == 'approved'): ?>
                                    <a href="cetak_nota.php?id=<?= $row['id'] ?>" class="action-btn print-btn" title="Cetak Nota">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="detail_pesanan.php?id=<?= $row['id'] ?>" class="action-btn view-btn"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                <?php elseif ($row['status'] == 'rejected'): ?>
                                    <span class="action-btn rejected-info">
                                        <i class="fas fa-ban"></i> Ditolak
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="7" class="empty-message">
                            <i class="fas fa-info-circle"></i> Belum ada pemesanan yang perlu diverifikasi.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<style>
    /* Styling untuk tombol-tombol */
    .print-btn {
        background-color: #6c5ce7;
        color: white;
        margin-right: 5px;
        display: inline-block;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .print-btn:hover {
        background-color: #5f4dd4;
        transform: translateY(-2px);
    }

    .view-btn {
        background-color: #00b894;
        color: white;
        display: inline-block;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .view-btn:hover {
        background-color: #00a885;
        transform: translateY(-2px);
    }

    .rejected-info {
        background-color: #636e72;
        color: white;
        cursor: not-allowed;
        display: inline-block;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        margin: 0 2px;
    }

    .action-btn i {
        margin-right: 5px;
    }

    /* Tambahan efek hover untuk semua tombol */
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
</style>
</head>