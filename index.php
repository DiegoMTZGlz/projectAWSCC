<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar sesión</title>
<style>
    body {
        background-color: #121212;
        font-family: Arial, sans-serif;
        color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .container {
        max-width: 400px;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #333333;
        text-align: center;
    }
    h2 {
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        text-align: center;
        display: block;
        margin-bottom: 5px;
    }
    .form-group input {
        text-align: center;
        width: calc(100% - 20px);
        padding: 10px;
        border: 1px solid #ffffff;
        border-radius: 4px;
        background-color: #555555;
        color: #ffffff;
    }
    .btn {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        background-color: #ff4500;
        color: #ffffff;
        cursor: pointer;
        margin-top: 20px;
    }
    .btn:hover {
        background-color: #e63c00;
    }
    #auth{
        font-size: 12px; 
        font-weight: bold; 
        color: red;
    }
</style>
</head>
<body>
<div class="container">
    <h2>INICIAR SESIÓN</h2>
    <hr>
    <form action="auth.php" method="POST">
        <div class="form-group">
            <label style="font-size: 16px; font-weight: bold;" for="username">USUARIO</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label style="font-size: 16px; font-weight: bold;" for="password">CONTRASEÑA</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
        <?php
            if (isset($_GET["auth"])){
                $err = $_GET["auth"];
                switch ($err){
                    case 0:
                        echo '<label id="auth">INICIO DE SESIÓN EXPIRADO</label>';
                        break;
                    case 1:
                        echo '<label id="auth">USUARIO y/o CONTRASEÑA INCORRECTOS</label>';
                        break;
                    case 2:
                        echo '<label id="auth">CREDENCIALES EXPIRADAS</label>';
                        break;
                    case 3:
                        echo '<label id="auth">SESIÓN CERRADA</label>';
                        break;
                    default:
                        echo '<label id="auth">ERROR DESCONOCIDO</label>';
                        break;
                }
            }
        ?>
        </div>
        <button type="submit" class="btn">ACCEDER</button>
    </form>
</div>
</body>
</html>
