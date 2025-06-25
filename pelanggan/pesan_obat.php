<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Ambil semua obat
$obat = $conn->query("SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat ASC");

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $obat_id = $_POST['obat_id'] ?? 0;
    $jumlah = intval($_POST['jumlah'] ?? 0);

    // Validasi input dasar
    if ($obat_id <= 0 || $jumlah <= 0) {
        $error = "Jumlah dan obat harus dipilih!";
    } else {
        // Ambil stok dengan prepared statement
        $cek = $conn->prepare("SELECT stok FROM obat WHERE id_obat = ?");
        $cek->bind_param("i", $obat_id);
        $cek->execute();
        $result = $cek->get_result();

        if ($result && $data = $result->fetch_assoc()) {
            if ($jumlah > $data['stok']) {
                $error = "Jumlah melebihi stok yang tersedia!";
            } else {
                // Mulai transaksi
                $conn->begin_transaction();

                try {
                    // Simpan ke tabel pesanan
                    $tanggal = date('Y-m-d');
                    $status = 'pending';

                    $insert = $conn->prepare("INSERT INTO pesanan (user_id, id_obat, jumlah, tanggal, status) VALUES (?, ?, ?, ?, ?)");
                    $insert->bind_param("iiiss", $user_id, $obat_id, $jumlah, $tanggal, $status);
                    $insert->execute();

                    // Kurangi stok
                    $update = $conn->prepare("UPDATE obat SET stok = stok - ? WHERE id_obat = ?");
                    $update->bind_param("ii", $jumlah, $obat_id);
                    $update->execute();

                    $conn->commit();
                    header("Location: riwayat_pesanan.php?success=1");
                    exit;
                } catch (Exception $e) {
                    $conn->rollback();
                    $error = "Terjadi kesalahan saat memproses pesanan!";
                }
            }
        } else {
            $error = "Obat tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesan Obat</title>
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
            max-width: 600px;
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
            content: '💊';
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

        .error {
            background: rgba(231, 76, 60, 0.3);
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 12px;
            color: #fff;
            font-size: 1.1em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        select option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        select:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            border: none;
            border-radius: 50px;
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Animasi hover pada form elements */
        .form-group:hover label {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        /* Stok indicator */
        .stok-info {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-left: 10px;
            background: rgba(46, 204, 113, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Pesan Obat</h2>
        <a href="index.php" class="back-btn">← Kembali ke Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="error">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validateForm()" class="form-group">
            <div class="form-group">
                <label for="obat">Pilih Obat:</label>
                <select name="obat_id" id="obat" required>
                    <option value="">Pilih Obat</option>
                    <?php
                    if ($obat && $obat->num_rows > 0):
                        while ($row = $obat->fetch_assoc()):
                    ?>
                            <option value="<?= $row['id_obat'] ?>" data-stok="<?= $row['stok'] ?>">
                                <?= htmlspecialchars($row['nama_obat']) ?> (Stok: <?= $row['stok'] ?>)
                            </option>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah" id="jumlah" min="1" required>
                <span id="stok-info" class="stok-info" style="display: none;"></span>
            </div>

            <button type="submit">Pesan Sekarang</button>
        </form>

        <script>
            function validateForm() {
                var obatSelect = document.getElementById('obat');
                var jumlahInput = document.getElementById('jumlah');
                var stokInfo = document.getElementById('stok-info');

                var obat = obatSelect.value;
                var jumlah = parseInt(jumlahInput.value);

                if (!obat) {
                    alert('Silakan pilih obat terlebih dahulu!');
                    obatSelect.focus();
                    return false;
                }

                if (!jumlah || jumlah < 1) {
                    alert('Jumlah pesanan harus lebih dari 0!');
                    jumlahInput.focus();
                    return false;
                }

                // Cek stok
                var selectedOption = obatSelect.options[obatSelect.selectedIndex];
                var stokTersedia = parseInt(selectedOption.dataset.stok);

                if (jumlah > stokTersedia) {
                    alert('Jumlah pesanan melebihi stok yang tersedia! (Stok: ' + stokTersedia + ')');
                    jumlahInput.focus();
                    return false;
                }

                return true;
            }

            // Update informasi stok saat memilih obat
            document.getElementById('obat').addEventListener('change', function() {
                var stokInfo = document.getElementById('stok-info');
                var selectedOption = this.options[this.selectedIndex];

                if (this.value) {
                    var stok = selectedOption.dataset.stok;
                    stokInfo.textContent = 'Stok tersedia: ' + stok;
                    stokInfo.style.display = 'inline-block';
                } else {
                    stokInfo.style.display = 'none';
                }
            });

            // Reset jumlah saat obat berubah
            document.getElementById('obat').addEventListener('change', function() {
                document.getElementById('jumlah').value = '';
            });
        </script>
    </div>
</body>

</html>