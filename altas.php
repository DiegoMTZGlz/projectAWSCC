<?php
// Incluir el archivo de autenticación
require_once 'auth.php';

// Verificar la autenticación utilizando las cookies de usuario y contraseña
if (verificar()) {
    // Usuario autenticado, mostrar el contenido de la página principal
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            text-align: center;
        }

        .navbar a {
            display: inline-block;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: #333;
        }

        .navbar a.selected {
            background-color: #555;
            color: #fff; 
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="altas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'altas.php' ? 'selected' : ''; ?>">ALTAS</a>
    <a href="cambios.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cambios.php' ? 'selected' : ''; ?>">CAMBIOS</a>
    <a href="consultas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'consultas.php' ? 'selected' : ''; ?>">CONSULTAS</a>
    <a href="descargas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'descargas.php' ? 'selected' : ''; ?>">DESCARGAS</a>
</div>

<h1 style="text-align: center;">Bienvenido a la página de ALTAS</h1>

</body>
</html>
<?php
} else {
    // Usuario no autenticado, redirigir al usuario a la página de inicio de sesión
    header("Location: index.php?auth=2");
    setcookie("session", "", time() - 60 * 5);
    setcookie("user", "", time() - 60 * 5);
    setcookie("pass", "", time() - 60 * 5);
    exit();
}
?>
