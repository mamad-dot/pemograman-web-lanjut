<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$nama = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            color: #ecf0f1;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            text-align: center;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2em;
            margin: 0;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .welcome {
            font-size: 1.2em;
            margin: 20px 0;
            text-align: center;
            color: #fff;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        .menu-item i {
            font-size: 2em;
            margin-bottom: 10px;
            color: #fff;
        }

        .menu-item h3 {
            margin: 10px 0;
            color: #fff;
        }

        .menu-item p {
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
        }

        .logout {
            text-align: center;
            margin-top: 30px;
        }

        .logout a {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logout a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="header">
            <h1>Dashboard Admin</h1>
        </div>

        <div class="welcome">
            Selamat datang, <?php echo htmlspecialchars($nama); ?>!
        </div>

        <div class="menu">
            <a href="index.php" class="menu-item">
                <i class="fas fa-home"></i>
                <h3>Dashboard</h3>
                <p>Ringkasan data sistem</p>
            </a>

            <a href="data_obat.php" class="menu-item">
                <i class="fas fa-pills"></i>
                <h3>Data Obat</h3>
                <p>Kelola data obat</p>
            </a>

            <a href="kelola_user.php" class="menu-item">
                <i class="fas fa-users"></i>
                <h3>Data Pengguna</h3>
                <p>Kelola akun pengguna</p>
            </a>

            <a href="data_penjualan.php" class="menu-item">
                <i class="fas fa-cash-register"></i>
                <h3>Data Penjualan</h3>
                <p>Monitoring transaksi penjualan</p>
            </a>

            <a href="data_pembelian.php" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <h3>Data Pembelian</h3>
                <p>Transaksi pembelian gudang</p>
            </a>

            <a href="data_pemesanan.php" class="menu-item">
                <i class="fas fa-clipboard-list"></i>
                <h3>Data Pemesanan</h3>
                <p>Pemesanan dari pelanggan</p>
            </a>
        </div>

    </div>

    <div class="logout">
        <a href="../auth/logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="copyright">
        © 2025 Mohamad Toha RF. All rights reserved.
    </div>

    <style>
        .copyright {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #FFD700;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            z-index: 1000;
        }

        /* Menambahkan margin bottom pada logout agar tidak tertutup copyright */
        .logout {
            margin-bottom: 60px;
            /* Memberikan ruang untuk copyright */
        }
    </style>
    </div>
</body>

</html>