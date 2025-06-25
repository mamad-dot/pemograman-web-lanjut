<?php
session_start();
include '../config/db.php';

$error = "";
$role = isset($_GET['role']) ? $_GET['role'] : 'pengguna';

// Menentukan judul berdasarkan role
switch ($role) {
    case 'admin':
        $title = 'Login Admin';
        break;
    case 'kasir':
        $title = 'Login Kasir';
        break;
    case 'gudang':
        $title = 'Login Gudang';
        break;
    case 'pelanggan':
        $title = 'Login Pelanggan';
        break;
    default:
        $title = 'Login Pengguna';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'admin':
                header("Location: ../admin/index.php");
                break;
            case 'gudang':
                header("Location: ../gudang/index.php");
                break;
            case 'kasir':
                header("Location: ../kasir/index.php");
                break;
            case 'pelanggan':
                header("Location: ../pelanggan/index.php");
                break;
        }
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #FDEB71, #F8D800);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #FFD700;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            margin-top: 8px;
            margin-bottom: 16px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e6be00;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #FFD700;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>🔐 <?= $title ?></h2>
        <a class="back-link" href="../index.php">⬅ Kembali ke Beranda</a>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
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
    </style>
</body>

</html>