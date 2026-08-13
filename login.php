<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - IDESK</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .login-box {
            width: 340px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 0 8px #ccc;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #004aad;
            border: none;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #003580;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Acceso de Usuario</h2>

    <form action="autenticacion.php" method="POST">
        <input type="email" name="correo" placeholder="Correo institucional" required>
        <button type="submit">Ingresar</button>
    </form>
</div>

</body>
</html>
