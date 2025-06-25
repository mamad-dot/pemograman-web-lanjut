<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data obat
// Ambil data obat dengan harga
$obat_result = $conn->query("SELECT id_obat, nama_obat, harga FROM obat ORDER BY nama_obat ASC");

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = intval($_POST['id_obat']);
    $jumlah = intval($_POST['jumlah']);
    $harga_per_unit = floatval($_POST['harga_per_unit']);
    $total_harga = $jumlah * $harga_per_unit;
    $tanggal = date('Y-m-d');
    $supplier = trim(mysqli_real_escape_string($conn, $_POST['supplier']));

    if (empty($supplier)) {
        $error = "Supplier harus diisi.";
    } else if ($id_obat > 0 && $jumlah > 0 && $harga_per_unit > 0) {
        $conn->query("UPDATE obat SET stok = stok + $jumlah WHERE id_obat = $id_obat");
        $conn->query("INSERT INTO pembelian (id_obat, jumlah, total_harga, tanggal, supplier) 
                     VALUES ($id_obat, $jumlah, $total_harga, '$tanggal', '$supplier')");
        header("Location: pembelian_obat.php?success=1");
        exit;
    } else {
        $error = "Data tidak valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Obat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            background: #f0f2f5;
            margin: 20px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #34495e;
        }

        select,
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        select:focus,
        input[type="number"]:focus,
        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            width: 100%;
            background-color: #2ecc71;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            background-color: #27ae60;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .total-preview {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: right;
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-pills"></i> Pembelian Obat</h2>
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <?php if (isset($_GET['success'])): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> Pembelian berhasil dicatat
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="id_obat">
                    <i class="fas fa-capsules"></i> Pilih Obat
                </label>
                <select name="id_obat" id="id_obat" required>
                    <option value="">-- Pilih Obat --</option>
                    <?php while ($row = $obat_result->fetch_assoc()): ?>
                        <option value="<?= $row['id_obat'] ?>" data-harga="<?= $row['harga'] ?>">
                            <?= htmlspecialchars($row['nama_obat']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">
                    <i class="fas fa-sort-numeric-up"></i> Jumlah Pembelian
                </label>
                <input type="number" name="jumlah" id="jumlah" min="1" required>
            </div>

            <div class="form-group">
                <label for="harga_per_unit">
                    <i class="fas fa-tag"></i> Harga Per Unit (Rp)
                </label>
                <input type="number" name="harga_per_unit" id="harga_per_unit" min="1" required>
            </div>

            <div class="form-group">
                <label for="supplier">
                    <i class="fas fa-truck"></i> Supplier
                </label>
                <input type="text" name="supplier" id="supplier" required>
            </div>

            <div class="total-preview" id="totalPreview">
                Total: Rp. 0
            </div>

            <button type="submit">
                <i class="fas fa-save"></i> Catat Pembelian
            </button>
        </form>
    </div>

    <script>
        // Hitung total otomatis
        const jumlahInput = document.getElementById('jumlah');
        const hargaInput = document.getElementById('harga_per_unit');
        const totalPreview = document.getElementById('totalPreview');

        function hitungTotal() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const harga = parseInt(hargaInput.value) || 0;
            const total = jumlah * harga;
            totalPreview.textContent = `Total: Rp. ${total.toLocaleString('id-ID')}`;
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
    </script>
</body>

</html>