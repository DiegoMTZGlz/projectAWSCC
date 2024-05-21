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

        .content {
            margin: 20px;
            text-align: center;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .download-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }

        .download-btn:hover {
            background-color: #45a049;
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

<h1 style="text-align: center;">Bienvenido a la página de DESCARGAS</h1>

<div class="content">
    <table>
        <tr>
            <th>Nombre del Archivo</th>
            <th>Descripción</th>
            <th>Descargar</th>
            <th>Nombre del Archivo</th>
            <th>Descripción</th>
            <th>Descargar</th>
        </tr>
        <?php
        $path = './Ensayos';
        $files = scandir($path);

        // Filtrar y ordenar archivos
        $files = array_filter($files, function($file) {
            return $file !== '.' && $file !== '..';
        });

        natsort($files);

        // Array de descripciones personalizadas
        $descriptions = [
            'ensayo 1 - c.c.pdf' => 'Computo en la Nube, brecha digital y redundancia en la nube',
            'ensayo 2 - c.c.pdf' => 'Introducción a la nube, sitios web en la nube',
            'ensayo 3 - c.c.pdf' => 'Introducción a la nube de AWS, construcción en la nube y conexiones',
            'ensayo 4 - c.c.pdf' => 'Uso de la cube, conexiones en la nube y el poder del computo virtual',
            'ensayo 5 - c.c.pdf' => 'Almacenamiento en la nube, algoritmos, diseño de programas e introducción al computo',
            'ensayo 6 - c.c.pdf' => 'Seguridad de datos, programación y variables',
            'ensayo 7 - c.c.pdf' => 'Modelos de datos, transformación de datos y trabajar con datos de usuario',
            'ensayo 8 - c.c.pdf' => 'Privacidad en línea, impacto global y realidad virtual',
            'ensayo 9 - c.c.pdf' => 'Análisis de datos, datos masivos e introducción al computo',
            'ensayo 10 - c.c.pdf' => 'Introducción a bases de datos, ciberseguridad y protegiendo la nube',
            'ensayo 11 - c.c.pdf' => 'Estableciendo redes e introducción a servicios sin servidor',
            'ensayo 12 - c.c.pdf' => 'Introducción a Amazon CodeWhisperer y su uso',
            'ensayo 13 - c.c.pdf' => 'Inteligencia artificial e introducción a la inteligencia artificial generativa',
            'ensayo 14 - c.c.pdf' => 'Machine learning'
        ];

        // Dividir los archivos en dos columnas
        $totalFiles = count($files);
        $half = ceil($totalFiles / 2);
        $files = array_values($files);

        for ($i = 0; $i < $half; $i++) {
            echo '<tr>';
            // Primera columna
            if (isset($files[$i])) {
                $file1 = $files[$i];
                $filePath1 = $path . '/' . $file1;
                $normalizedFile1 = strtolower(trim($file1));
                $description1 = isset($descriptions[$normalizedFile1]) ? $descriptions[$normalizedFile1] : 'Descripción no disponible';
                echo '<td>' . $file1 . '</td>';
                echo '<td>' . $description1 . '</td>';
                echo '<td><a href="' . $filePath1 . '" download class="download-btn">Descargar</a></td>';
            } else {
                echo '<td colspan="3"></td>';
            }
            
            // Segunda columna
            if (isset($files[$i + $half])) {
                $file2 = $files[$i + $half];
                $filePath2 = $path . '/' . $file2;
                $normalizedFile2 = strtolower(trim($file2));
                $description2 = isset($descriptions[$normalizedFile2]) ? $descriptions[$normalizedFile2] : 'Descripción no disponible';
                echo '<td>' . $file2 . '</td>';
                echo '<td>' . $description2 . '</td>';
                echo '<td><a href="' . $filePath2 . '" download class="download-btn">Descargar</a></td>';
            } else {
                echo '<td colspan="3"></td>';
            }

            echo '</tr>';
        }
        ?>
    </table>
</div>

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
