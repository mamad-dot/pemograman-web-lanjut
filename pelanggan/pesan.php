<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obat = $_POST['id_obat'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d');
    $bukti_path = null;

    if (!empty($_FILES['bukti']['name'])) {
        $bukti_name = time() . '_' . basename($_FILES['bukti']['name']);
        $bukti_tmp = $_FILES['bukti']['tmp_name'];
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        move_uploaded_file($bukti_tmp, $upload_dir . $bukti_name);
        $bukti_path = $bukti_name;
    }

    $stmt = $conn->prepare("INSERT INTO pemesanan (id_user, id_obat, jumlah, tanggal, bukti_pembayaran, status) VALUES (?, ?, ?, ?, ?, 'Menunggu')");
    $stmt->bind_param("iiiss", $user_id, $id_obat, $jumlah, $tanggal, $bukti_path);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$obat = $conn->query("SELECT * FROM obat");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pemesanan Obat</title>
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
        input[type="number"],
        input[type="file"] {
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
        }

        input[type="file"] {
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
        }

        input[type="file"]::-webkit-file-upload-button {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            border: none;
            border-radius: 8px;
            color: white;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        select:focus,
        input[type="number"]:focus,
        input[type="file"]:focus {
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

        /* Preview gambar */
        #preview {
            max-width: 100%;
            margin-top: 10px;
            border-radius: 10px;
            display: none;
        }
    </style>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateHarga() {
            const select = document.getElementById('id_obat');
            const option = select.options[select.selectedIndex];
            const harga = option.getAttribute('data-harga');
            document.getElementById('harga').textContent = harga ? `Harga: Rp ${harga}` : '';
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>Pesan Obat</h2>
        <a href="index.php" class="back-btn">← Kembali ke Dashboard</a>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="id_obat">Pilih Obat 💊</label>
                <select name="id_obat" id="id_obat" required onchange="updateHarga()">
                    <option value="">-- Pilih Obat --</option>
                    <?php while ($row = $obat->fetch_assoc()): ?>
                        <option value="<?= $row['id_obat'] ?>"
                            data-harga="<?= number_format($row['harga'], 0, ',', '.') ?>">
                            <?= htmlspecialchars($row['nama_obat']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <div id="harga" style="margin-top: 8px; font-size: 0.9em; color: #2ecc71;"></div>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah 🔢</label>
                <input type="number" name="jumlah" id="jumlah" required min="1">
            </div>

            <div class="form-group">
                <label for="bukti">Bukti Pembayaran 📄</label>
                <input type="file" name="bukti" id="bukti" required accept="image/*" onchange="previewImage(this)">
                <img id="preview" src="#" alt="Preview bukti pembayaran">
            </div>

            <button type="submit">Kirim Pesanan 🚀</button>
        </form>
    </div>
</body>

</html>