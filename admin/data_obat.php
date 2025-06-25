<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Query untuk mengambil data obat
$query = "SELECT * FROM obat ORDER BY nama_obat";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Obat - Admin</title>
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

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #3498db;
        }

        .btn-phone {
            background: #27ae60;
        }

        .btn-delete {
            background: #e74c3c;
        }

        /* Tambahkan style untuk modal konfirmasi */
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
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: #333;
        }

        .modal-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-btn-yes {
            background: #e74c3c;
            color: white;
        }

        .modal-btn-no {
            background: #95a5a6;
            color: white;
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

        .low-stock {
            color: #e74c3c;
            font-weight: bold;
        }

        .expired {
            color: #e74c3c;
            font-weight: bold;
            background-color: rgba(231, 76, 60, 0.1);
        }

        .near-expired {
            color: #f39c12;
            font-weight: bold;
            background-color: rgba(243, 156, 18, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-pills"></i> Data Obat</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        $today = new DateTime();
                        while ($row = $result->fetch_assoc()) {
                            $stok_class = $row['stok'] < 10 ? 'low-stock' : '';

                            // Menentukan status kadaluarsa
                            $exp_class = '';
                            $exp_text = '-';
                            if (!empty($row['tgl_kadaluarsa'])) {
                                $exp_date = new DateTime($row['tgl_kadaluarsa']);
                                $interval = $today->diff($exp_date);

                                if ($today > $exp_date) {
                                    $exp_class = 'expired';
                                    $exp_text = 'KADALUARSA';
                                } else if ($interval->days <= 90) { // 3 bulan
                                    $exp_class = 'near-expired';
                                    $exp_text = date('d/m/Y', strtotime($row['tgl_kadaluarsa']));
                                } else {
                                    $exp_text = date('d/m/Y', strtotime($row['tgl_kadaluarsa']));
                                }
                            }

                            echo "<tr>
                                    <td>" . $no . "</td>
                                    <td>" . $row['nama_obat'] . "</td>
                                    <td class='" . $stok_class . "'>" . $row['stok'] . "</td>
                                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                    <td class='" . $exp_class . "'>" . $exp_text . "</td>
                                    <td class='actions'>
                                        <a href='edit_obat.php?id=" . $row['id_obat'] . "' class='btn btn-edit'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <button onclick='confirmDelete(" . $row['id_obat'] . ")' class='btn btn-delete'>
                                            <i class='fas fa-trash'></i> Hapus
                                        </button>
                                    </td>
                                </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data obat</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus obat ini?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-no" onclick="closeModal()">Batal</button>
                <button class="modal-btn modal-btn-yes" onclick="deleteObat()">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;
        const modal = document.getElementById('deleteModal');

        function confirmDelete(id) {
            deleteId = id;
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function deleteObat() {
            if (deleteId) {
                window.location.href = 'hapus_obat.php?id=' + deleteId;
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>