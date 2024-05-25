<?php

include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

// Verificar si se ha enviado el formulario de alta de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_usuario'])) {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Verificar si las contraseñas coinciden
    if ($password === $confirmar_password) {
        // Intentar agregar el usuario
        $mensaje = agregarUsuario($usuario, $password);
        echo "<script>alert('$mensaje');</script>";
        // Redirigir a otra página después de procesar el formulario
        header("Location: altas.php");
        exit();
    } else {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altas</title>
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

<h1 style="text-align: center;">Bienvenido a la página de ALTAS</h1>

<!-- Botones para abrir el modal de alta de usuario y curso -->
<div class="modal-buttons">
    <button class="btn-dar-alta" onclick="mostrarModalUsuario()">DAR ALTA USUARIO</button>
    <button onclick="mostrarModalCurso()">DAR ALTA CURSO</button>
</div>

<!-- Modal para dar de alta usuario -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalUsuario()">&times;</span>
        <h2 class="modal-title">&nbsp;&nbsp;&nbsp;Alta de Usuario</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="agregaruser" style="text-align: center;">
                <br>
                <label for="usuario">USUARIO</label><br>
                <input type="text" name="usuario" required><br><br>
                <label for="password">CONTRASEÑA</label><br>
                <input type="password" name="password" required><br><br>
                <label for="confirmar_password">REPETIR CONTRASEÑA</label><br>
                <input type="password" name="confirmar_password" required><br><br>
                <button class="btn-dar-alta" type="submit" name="agregar_usuario">AGREGAR USUARIO</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Mostrar ventana para agregar usuario
    function mostrarModalUsuario() {
        document.getElementById('modalUsuario').style.display = 'block';
    }

    // Cerrar ventana para agregar usuario
    function cerrarModalUsuario() {
        document.getElementById('modalUsuario').style.display = 'none';
    }
</script>

</body>
</html>
