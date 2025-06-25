<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f5af19, #f12711);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #FFD700;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            border: none;
            color: #000;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #f1c40f;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #FFD700;
            text-decoration: underline;
        }

        a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📝 Daftar Admin</h2>
        <form method="POST" action="proses_register.php">
            <input type="hidden" name="role" value="admin">

            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">🧾 Daftar Admin</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
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