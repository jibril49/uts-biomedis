<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #ffffff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        .instansi-name {
            font-size: 18px;
            font-weight: 700;
            color: #2575fc;
            margin-bottom: 20px;
        }
        .login-container h2 {
            margin: 0 0 20px;
            font-weight: 700;
            text-align: center;
            color: #2575fc;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2575fc;
            box-shadow: 0 0 4px rgba(37, 117, 252, 0.5);
        }
        .login-btn {
            width: 100%;
            background: #2575fc;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-btn:hover {
            background: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo Instansi -->
        <img src="assets/images/logo.png" alt="Logo Instansi" class="logo">
        <!-- Nama Instansi -->
        <div class="instansi-name">Malaikat Penyembuh</div>

        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username Anda" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required>
            </div>
            <button class="login-btn" type="submit">Login</button>
        </form>
    </div>
</body>
</html>
