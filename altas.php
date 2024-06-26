<?php
include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

$mensaje_usuario = '';
$mensaje_curso = '';

// Verificar si se ha enviado el formulario de alta de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_usuario'])) {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $confirmar_password = $_POST['confirmar_password'];

    // Verificar si las contraseñas coinciden
    if ($password === $confirmar_password) {
        // Intentar agregar el usuario
        $mensaje_usuario = agregarUsuario($usuario, $password, $nombre, $apellido); // Guardamos el mensaje de retorno
    } else {
        $mensaje_usuario = "Las contraseñas no coinciden";
    }
}

// Verificar si se ha enviado el formulario de alta de curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_curso'])) {
    // Obtener los datos del formulario
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion_curso = $_POST['descripcion_curso'];
    $instructor_curso = $_POST['instructor_curso'];
    $categoria_curso = $_POST['categoria_curso'];
    $tipo_curso = $_POST['tipo_curso'];
    $duracion_horas = $_POST['duracion_horas'];
    $duracion_minutos = $_POST['duracion_minutos'];

    // Intentar agregar el curso
    $mensaje_curso = agregarCurso($nombre_curso, $descripcion_curso, $instructor_curso, $duracion_horas, $duracion_minutos, $categoria_curso, $tipo_curso);
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

<h1 style="text-align: center;">ALTAS</h1>

<!-- Botones para abrir el modal de alta de usuario y curso -->
<div class="modal-buttons">
    <button class="btn-dar-alta" onclick="mostrarModalUsuario()">AGREGAR USUARIO</button>
    <button onclick="mostrarModalCurso()">AGREGAR CURSO</button>
</div>

<!-- Modulo para dar de alta usuarios -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalUsuario()">&times;</span>
        <h2 class="modal-title">&nbsp;&nbsp;&nbsp;AGREGAR USUARIO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="agregaruser" style="text-align: center;">
                <br>
                <label for="usuario">USUARIO</label><br>
                <input type="text" name="usuario" id="usuario" maxlength="50" required oninput="validarUsuario(this)" pattern="[a-zA-Z0-9_-]+"><br><br>
                <label for="password">CONTRASEÑA</label><br>
                <input type="password" name="password" maxlength="60" required><br><br>
                <label for="confirmar_password">REPETIR CONTRASEÑA</label><br>
                <input type="password" name="confirmar_password" required><br><br>
                <label for="nombre">NOMBRE</label><br>
                <input type="text" name="nombre" maxlength="50" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                <label for="apellido">APELLIDO</label><br>
                <input type="text" name="apellido" maxlength="50" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                <button class="btn-dar-alta" type="submit" name="agregar_usuario">AGREGAR USUARIO</button>
            </div>
        </form>
    </div>
</div>

<!-- Modulo para dar de alta cursos -->
<div id="modalCurso" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalCurso()">&times;</span>
        <h2 style="text-align: center;">&nbsp;&nbsp;AGREGAR CURSO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="agregarcurso" style="text-align: center;">
                <br>
                <label for="nombre_curso">CURSO</label><br>
                <input type="text" name="nombre_curso" id="nombre_curso" maxlength="40" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                <label for="descripcion_curso">DESCRIPCIÓN</label><br>
                <textarea name="descripcion_curso" rows="4" cols="50" required></textarea><br><br>
                <label for="instructor_curso">INSTRUCTOR</label><br>
                <input type="text" name="instructor_curso" maxlength="40" required pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios"><br><br>
                <label for="categoria_curso">CATEGORÍA</label><br>
                <select name="categoria_curso" required>
                    <option value="Programación">Programación</option>
                    <option value="Diseño">Diseño</option>
                    <option value="Desarrollo web">Desarrollo Web</option>
                    <option value="Cocina">Cocina</option>
                    <option value="Cálculo">Cálculo</option>
                    <option value="Álgebra">Álgebra</option>
                    <option value="Probabilidad">Probabilidad</option>
                    <option value="Estadística">Estadística</option>
                </select><br><br>
                <label for="tipo_curso">MODALIDAD</label><br>
                <select name="tipo_curso" required>
                    <option value="presencial">PRESENCIAL</option>
                    <option value="virtual">VIRTUAL</option>
                </select><br><br>
                <label>DURACIÓN</label><br>
                <select name="duracion_horas" required>
                    <?php
                    for ($i = 1; $i <= 24; $i++) {
                        if ($i == 1) {
                            echo "<option value='$i'>$i hora</option>";
                        } else {
                            echo "<option value='$i'>$i horas</option>";
                        }
                    }
                    ?>
                </select>
                <select name="duracion_minutos" required>
                    <?php
                    for ($i = 0; $i <= 59; $i+=10) {
                        echo "<option value='$i'>$i minutos</option>";
                    }
                    ?>
                </select><br><br>
                <button class="btn-dar-alta" type="submit" name="agregar_curso">AGREGAR CURSO</button>
            </div>
        </form>
    </div>
</div>

<!-- Script para mostrar mensajes -->
<script>
    <?php if (!empty($mensaje_usuario)) { ?>
        alert("<?php echo $mensaje_usuario; ?>");
    <?php } ?>

    <?php if (!empty($mensaje_curso)) { ?>
        alert("<?php echo $mensaje_curso; ?>");
    <?php } ?>
</script>

<!-- Script para mostrar los formularios de altas -->
<script>
    // Mostrar ventana para agregar usuario
    function mostrarModalUsuario() {
        document.getElementById('modalUsuario').style.display = 'block';
    }

    // Cerrar ventana para agregar usuario
    function cerrarModalUsuario() {
        document.getElementById('modalUsuario').style.display = 'none';
    }

    // Mostrar ventana para agregar curso
    function mostrarModalCurso() {
        document.getElementById('modalCurso').style.display = 'block';
    }

    // Cerrar ventana para agregar curso
    function cerrarModalCurso() {
        document.getElementById('modalCurso').style.display = 'none';
    }    

    function validarUsuario(input) {
        input.setCustomValidity('');
        var usuario = input.value.trim(); // Eliminar espacios en blanco al principio y al final
        var usuarioValido = /^[a-zA-Z0-9_-]+$/.test(usuario); // Validar el usuario con la expresión regular
        
        if (!usuarioValido || usuario.includes(' ')) {
            input.setCustomValidity('El usuario solo puede contener letras, números, guiones bajos (_) y guiones (-), sin espacios en blanco.');
        }
    }
</script>

</body>
</html>
