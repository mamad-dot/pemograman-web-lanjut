<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gudang') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            text-align: center;
            padding: 40px 20px;
            margin: 0;
            min-height: 100vh;
        }

        .dashboard {
            max-width: 800px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        h2 {
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .menu-item i {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .menu-item span {
            font-size: 1.1em;
            font-weight: 500;
        }

        .logout {
            background: rgba(231, 76, 60, 0.2);
            margin-top: 30px;
        }

        .logout:hover {
            background: rgba(231, 76, 60, 0.4);
        }

        .welcome-text {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #ffffff;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .dashboard {
                padding: 20px;
            }

            h2 {
                font-size: 2em;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h2><i class="fas fa-warehouse"></i> Dashboard Gudang</h2>
        <p class="welcome-text">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>

        <div class="menu-grid">
            <a href="data_obat.php" class="menu-item">
                <i class="fas fa-pills"></i>
                <span>Data Obat</span>
            </a>
            <a href="pembelian_obat.php" class="menu-item">
                <i class="fas fa-cart-plus"></i>
                <span>Tambah Pembelian</span>
            </a>
            <a href="laporan_pembelian.php" class="menu-item">
                <i class="fas fa-file-invoice"></i>
                <span>Data Pembelian</span>
            </a>
            <a href="data_pemesanan.php" class="menu-item">
                <i class="fas fa-clipboard-check"></i>
                <span>Verifikasi Pemesanan</span>
            </a>
        </div>

        <a href="../auth/logout.php" class="menu-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</body>

</html>