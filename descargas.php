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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        .content {
            margin: 20px auto;
            width: 90%;
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 30%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px; /* Ajustar el padding */
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .download-btn {
            background-color: #008CBA;
            color: white;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-right: 5px;
        }

        .show-pdf-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }

        #pdf-container {
            width: 65%;
            max-height: 600px; /* Altura máxima del contenedor */
            overflow: auto; /* Agregar barra de desplazamiento si el PDF es demasiado grande */
            border: 1px solid #ddd;
            padding: 20px;
        }

        #pdf-container embed {
            width: 100%;
            height: 100%; /* Ajustar el tamaño del PDF al contenedor */
        }

        /* Reducir el tamaño de la fuente de los ensayos */
        .essay-name {
            font-size: 14px;
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

<div class="content">
    <table>
        <tr>
            <th>Nombre del Ensayo</th>
            <th>Acciones</th>
        </tr>
        <?php
        $path = './Ensayos';
        $files = scandir($path);

        // Filtrar y ordenar archivos
        $files = array_filter($files, function($file) {
            return $file !== '.' && $file !== '..';
        });

        natsort($files);

        foreach ($files as $file) {
            echo '<tr>';
            $filePath = $path . '/' . $file;
            echo '<td class="essay-name">' . $file . '</td>'; // Agregar la clase "essay-name"
            echo '<td><a href="' . $filePath . '" class="download-btn" download><i class="fas fa-download"></i></a>';
            echo '<a href="#" class="show-pdf-btn" onclick="mostrarPDF(\'' . $filePath . '\')"><i class="far fa-eye"></i></a></td>';
            echo '</tr>';
        }
        ?>
    </table>

    <div id="pdf-container"></div>
</div>

<script>
    function mostrarPDF(filePath) {
        // Mostrar el PDF embebido en el contenedor
        document.getElementById('pdf-container').innerHTML = '<embed src="' + filePath + '" type="application/pdf" />';
    }
</script>

</body>
</html>
