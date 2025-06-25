<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Ambil riwayat penjualan pelanggan
$query = "SELECT p.*, o.nama_obat 
         FROM penjualan p 
         JOIN obat o ON p.id_obat = o.id_obat 
         ORDER BY p.tanggal DESC";
$result = $conn->query($query);

// Cek error query
if (!$result) {
    // Tampilkan pesan error yang lebih informatif
    echo "<div style='background: rgba(231, 76, 60, 0.9); color: white; padding: 20px; border-radius: 10px; margin: 20px;'>
            <h3>⚠️ Error Database</h3>
            <p>Mohon maaf, terjadi kesalahan saat mengambil data. Detail error:</p>
            <code style='background: rgba(0,0,0,0.2); padding: 10px; display: block; margin-top: 10px;'>
                {$conn->error}
            </code>
            <p style='margin-top: 20px;'>
                <a href='index.php' style='color: white; text-decoration: underline;'>Kembali ke Dashboard</a>
            </p>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d);
            margin: 0;
            padding: 40px;
            color: #fff;
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
            font-size: 2.2em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        h2:after {
            content: '📋';
            font-size: 0.8em;
            margin-left: 10px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            font-weight: bold;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .success-message {
            background: rgba(46, 204, 113, 0.2);
            border-left: 4px solid #2ecc71;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(0, 0, 0, 0.3);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            font-size: 1.1em;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-top: 20px;
        }

        .empty-message:before {
            content: '📭';
            font-size: 2em;
            display: block;
            margin-bottom: 10px;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            th,
            td {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Riwayat Pembelian Obat</h2>
        <a href="index.php" class="back-btn">← Kembali ke Dashboard</a>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                ✅ Pembelian berhasil disimpan!
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pesan</th>
                    </tr>
                    <?php
                    $no = 1;
                    while ($row = $result->fetch_assoc()):
                        $tanggal = date('d F Y', strtotime($row['tanggal']));
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $tanggal ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-message">
                Belum ada riwayat pembelian.
                <br>
                <a href="pesan_obat.php" class="back-btn" style="margin-top: 20px;">Beli Obat Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>