<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'gudang', 'kasir'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Proses hapus jika ada aksi POST
if (isset($_POST['hapus']) && isset($_POST['hapus_id'])) {
    $id_obat = intval($_POST['hapus_id']);
    $conn->query("DELETE FROM obat WHERE id_obat = $id_obat");
}

// Ambil semua data obat
$query = $conn->query("SELECT * FROM obat ORDER BY nama_obat ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Stok Obat</title>
    <style>
        <style>body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        h2 {
            text-align: center;
            color: #FFD700;
            font-size: 24px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            color: #333;
        }

        th {
            background: #2c3e50;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.95);
        }

        .btn-edit,
        .btn-delete {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-edit:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-delete:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background: #FFD700;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #FFC000;
            transform: translateY(-2px);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 20px 0;
            color: #ff7675;
        }

        @media print {
            body {
                background: white;
                padding: 20px;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .btn-edit,
            .btn-delete,
            .back-link {
                display: none;
            }
        }
    </style>
    </style>
    h2 {
    text-align: center;
    margin-bottom: 20px;
    }

    a {
    display: inline-block;
    margin-bottom: 20px;
    color: #f1c40f;
    text-decoration: none;
    font-weight: bold;
    }

    a:hover {
    text-decoration: underline;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    color: #000;
    border-radius: 8px;
    overflow: hidden;
    }

    th,
    td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    }

    th {
    background-color: #2c3e50;
    color: #fff;
    }

    tr:last-child td {
    border-bottom: none;
    }

    .no-data {
    color: #ff7675;
    margin-top: 20px;
    text-align: center;
    }

    .btn-delete {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    }

    .btn-delete:hover {
    background-color: #c0392b;
    }

    .btn-edit {
    background-color: #f39c12;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 5px;
    text-decoration: none;
    }

    .btn-edit:hover {
    background-color: #e67e22;
    }

    .aksi-buttons {
    display: flex;
    justify-content: center;
    gap: 6px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>📊 Data Stok Obat</h2>
        <a href="../<?= $_SESSION['role'] ?>/index.php">⬅ Kembali ke Dashboard</a>

        <?php if ($query->num_rows > 0): ?>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Stok</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Aksi</th>
                </tr>
                <?php $no = 1;
                while ($row = $query->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= isset($row['stok']) ? $row['stok'] : 'Belum diisi' ?></td>
                        <td><?= isset($row['tgl_kadaluarsa']) && $row['tgl_kadaluarsa'] !== null ? $row['tgl_kadaluarsa'] : 'Belum diisi' ?>
                        </td>
                        <td class="aksi-buttons">
                            <?php if ($_SESSION['role'] === 'gudang'): ?>
                                <a class="btn-edit" href="../gudang/edit_obat.php?id=<?= $row['id_obat'] ?>">✏️ Edit</a>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'gudang' || $_SESSION['role'] === 'admin'): ?>
                                <form method="POST" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                    <input type="hidden" name="hapus_id" value="<?= $row['id_obat'] ?>">
                                    <button type="submit" name="hapus" class="btn-delete">🗑 Hapus</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">Belum ada data obat dalam sistem.</p>
        <?php endif; ?>
    </div>
</body>

</html>