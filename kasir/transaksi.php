<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data obat yang stok > 0
$obat_result = $conn->query("SELECT id_obat, nama_obat, harga, stok FROM obat WHERE stok > 0 ORDER BY nama_obat");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = intval($_POST['id_obat']);
    $jumlah = intval($_POST['jumlah']);
    $tanggal = date('Y-m-d');

    // Debug input
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; font-family: monospace;'>";
    echo "Data Input:<br>";
    echo "ID Obat: $id_obat<br>";
    echo "Jumlah: $jumlah<br>";
    echo "Tanggal: $tanggal<br>";

    // Ambil data stok dan harga - Perbaiki nama kolom dari 'id' menjadi 'id_obat'
    $cek = $conn->query("SELECT stok, harga FROM obat WHERE id_obat = $id_obat");
    if (!$cek) {
        echo "Error query cek: " . $conn->error . "<br>";
    } else {
        $data = $cek->fetch_assoc();

        if ($data && $data['stok'] >= $jumlah) {
            if ($data['harga'] > 0) {
                // Kurangi stok - Perbaiki nama kolom
                $update_query = "UPDATE obat SET stok = stok - $jumlah WHERE id_obat = $id_obat";
                if (!$conn->query($update_query)) {
                    echo "Error update stok: " . $conn->error . "<br>";
                }

                // Simpan ke tabel penjualan
                $insert_query = "INSERT INTO penjualan (id_obat, jumlah, tanggal) VALUES ($id_obat, $jumlah, '$tanggal')";
                if (!$conn->query($insert_query)) {
                    echo "Error insert penjualan: " . $conn->error . "<br>";
                } else {
                    echo "Berhasil menyimpan transaksi!<br>";
                    echo "</div>";
                    header("Location: transaksi.php?success=1");
                    exit;
                }
            } else {
                echo "</div>";
                $error = "❌ Harga obat belum diisi!";
            }
        } else {
            echo "</div>";
            $error = "❌ Stok tidak mencukupi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f5af19, #f12711);
            color: #fff;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #FFD700;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: none;
        }

        button {
            background: #27ae60;
            color: #fff;
            padding: 12px 20px;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            width: 100%;
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            background-color: #34495e;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .success {
            color: #2ecc71;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: #e74c3c;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🛒 Transaksi Penjualan Obat</h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="success">✅ Transaksi berhasil dicatat!</p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="id_obat">Pilih Obat:</label>
            <select name="id_obat" required>
                <option value="">-- Pilih Obat --</option>
                <?php while ($row = $obat_result->fetch_assoc()): ?>
                    <option value="<?= $row['id_obat'] ?>">
                        <?= htmlspecialchars($row['nama_obat']) ?> (Rp<?= number_format($row['harga'], 0, ',', '.') ?> |
                        Stok: <?= $row['stok'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" min="1" required>

            <button type="submit">💾 Simpan Transaksi</button>
        </form>

        <a href="index.php">⬅ Kembali ke Dashboard</a>
    </div>
</body>

</html>