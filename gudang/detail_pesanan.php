<?php
require_once('../config/db.php');
session_start();

// Cek role gudang
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'gudang') {
    header('Location: ../auth/login.php');
    exit();
}

// Ambil ID pesanan
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan
$query = "SELECT p.*, o.nama_obat, o.harga, o.stok, u.nama 
          FROM pesanan p 
          JOIN obat o ON p.id_obat = o.id_obat 
          JOIN users u ON p.user_id = u.id 
          WHERE p.id = ?";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param('i', $id_pesanan);
if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error);
}

$result = $stmt->get_result();
if ($result === false) {
    die('Error getting result: ' . $stmt->error);
}

if ($result->num_rows === 0) {
    die('Pesanan tidak ditemukan');
}

$pesanan = $result->fetch_assoc();
$total = $pesanan['jumlah'] * $pesanan['harga'];
$status_class = [
    'pending' => 'bg-warning',
    'approved' => 'bg-success',
    'rejected' => 'bg-danger'
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $id_pesanan ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pesanan #<?= $id_pesanan ?></h5>
                        <span class="badge <?= $status_class[$pesanan['status']] ?>">
                            <?= ucfirst($pesanan['status']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h6 class="mb-3">Data Pelanggan:</h6>
                                <div><strong>Nama:</strong> <?= htmlspecialchars($pesanan['nama']) ?></div>
                            </div>
                            <div class="col-sm-6">
                                <h6 class="mb-3">Informasi Pesanan:</h6>
                                <div><strong>Tanggal Pesan:</strong>
                                    <?= date('d/m/Y H:i', strtotime($pesanan['tanggal'])) ?></div>
                                <?php if ($pesanan['status'] === 'approved'): ?>
                                    <div><strong>Tanggal Verifikasi:</strong>
                                        <?= date('d/m/Y H:i', strtotime($pesanan['tanggal'])) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= htmlspecialchars($pesanan['nama_obat']) ?></td>
                                        <td><?= $pesanan['jumlah'] ?></td>
                                        <td>Rp <?= number_format($pesanan['harga'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Pembayaran:</strong></td>
                                        <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php if ($pesanan['status'] === 'approved'): ?>
                            <div class="text-end mt-4">
                                <a href="cetak_nota.php?id=<?= $id_pesanan ?>" class="btn btn-success">
                                    <i class="fas fa-print me-2"></i>Cetak Nota
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="data_pemesanan.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>