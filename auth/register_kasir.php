<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Kasir</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #FDEB71, #F8D800);
        margin: 0;
        padding: 0;
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .register-container {
        background-color: rgba(0, 0, 0, 0.75);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        width: 100%;
        max-width: 450px;
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
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

    p {
        text-align: center;
        margin-top: 20px;
        color: #fff;
    }

    a {
        color: #FFD700;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>🧾 Form Pendaftaran Kasir</h2>
        <form method="POST" action="proses_register.php">
            <input type="hidden" name="role" value="kasir">

            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Daftar Kasir</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>

</html>