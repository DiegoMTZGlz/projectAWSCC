<?php

include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <style>
        table {
            border-collapse: separate;
            border-spacing: 5px;
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        td {
            border: 1px solid #ddd;
            padding: 20px;
            vertical-align: middle;
            background-color: #f9f9f9;
        }

        img {
            max-width: 150px;
            height: 150px;
            display: block;
            margin: 0 auto;
        }

        h1, h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="navbar" style="display: flex; justify-content: space-between;">
    <div style="display: flex; align-items: center;">
        <a href="main.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'main.php' ? 'selected' : ''; ?>">INICIO</a>
        <a href="altas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'altas.php' ? 'selected' : ''; ?>">ALTAS</a>
        <a href="bajas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bajas.php' ? 'selected' : ''; ?>">BAJAS</a>
        <a href="cambios.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cambios.php' ? 'selected' : ''; ?>">CAMBIOS</a>
        <a href="consultas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'consultas.php' ? 'selected' : ''; ?>">CONSULTAS</a>
        <a href="descargas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'descargas.php' ? 'selected' : ''; ?>">DESCARGAS</a>
    </div>
    <div>
        <a href="?cerrar_sesion">Cerrar Sesión</a>
    </div>
</div>

<h1 style="text-align: center;">Proyecto AWS</h1>
<h2 style="text-align: center;">Elaborado por Juan y Diego</h2>

<table>
    <tr>
        <td><img src="imgs/wamp.png" alt="Imagen 1"></td>
        <td><img src="imgs/php.png" alt="Imagen 2"></td>
        <td><img src="imgs/phpmyadmin.png" alt="Imagen 3"></td>
        <td><img src="imgs/mysql.png" alt="Imagen 4"></td>
    </tr>
    <tr>
        <td>WAMP</td>
        <td>PHP</td>
        <td>PHPMyAdmin</td>
        <td>MySQL</td>
    </tr>
    <tr>
        <td><img src="imgs/html.png" alt="Imagen 5"></td>
        <td><img src="imgs/css.png" alt="Imagen 6"></td>
        <td><img src="imgs/aws.jpg" alt="Imagen 7"></td>
        <td><img src="imgs/javascript.png" alt="Imagen 8"></td>
    </tr>
    <tr>
        <td>HTML</td>
        <td>CSS</td>
        <td>AWS</td>
        <td>JavaScript</td>
    </tr>
</table>

</body>
</html>
