<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$id_obat = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data obat
$stmt = $conn->prepare("SELECT * FROM obat WHERE id_obat = ?");
$stmt->bind_param("i", $id_obat);
$stmt->execute();
$result = $stmt->get_result();
$obat = $result->fetch_assoc();

if (!$obat) {
    echo "<h3>Obat tidak ditemukan.</h3>";
    exit;
}

// Update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_obat = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];
    $kategori = $_POST['kategori'];

    $stmt = $conn->prepare("UPDATE obat SET nama_obat=?, stok=?, harga=?, tgl_kadaluarsa=?, kategori=? WHERE id_obat=?");
    $stmt->bind_param("siissi", $nama_obat, $stok, $harga, $tgl_kadaluarsa, $kategori, $id_obat);
    $stmt->execute();

    header("Location: data_obat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2196F3;
            --secondary-color: #FFC107;
            --danger-color: #f44336;
            --success-color: #4CAF50;
            --text-color: #333;
            --light-bg: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus,
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
            outline: none;
        }

        button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #1976D2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #1976D2;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            input,
            select,
            button {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-pills"></i> Edit Data Obat</h2>
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-prescription-bottle-alt"></i> Nama Obat:</label>
                <input type="text" name="nama_obat" value="<?= htmlspecialchars($obat['nama_obat']) ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-boxes"></i> Stok:</label>
                <input type="number" name="stok" value="<?= $obat['stok'] ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-tag"></i> Harga:</label>
                <input type="number" name="harga" value="<?= $obat['harga'] ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Tanggal Kadaluarsa:</label>
                <input type="date" name="tgl_kadaluarsa" value="<?= $obat['tgl_kadaluarsa'] ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-list-alt"></i> Kategori:</label>
                <select name="kategori" required>
                    <option value="tablet" <?= $obat['kategori'] == 'tablet' ? 'selected' : '' ?>>Tablet</option>
                    <option value="sirup" <?= $obat['kategori'] == 'sirup' ? 'selected' : '' ?>>Sirup</option>
                    <option value="kapsul" <?= $obat['kategori'] == 'kapsul' ? 'selected' : '' ?>>Kapsul</option>
                </select>
            </div>

            <button type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="data_obat.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>
</body>

</html>