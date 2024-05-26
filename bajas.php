<?php
include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

$mensaje_usuario = '';
$mensaje_curso = '';

// Verificar si se ha enviado el formulario de baja de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_usuario'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $mensaje_usuario = eliminarUsuario($nombre_usuario);
    echo "<script>alert('$mensaje_usuario');</script>";
}

// Verificar si se ha enviado el formulario de baja de curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_curso'])) {
    $id_curso = $_POST['id_curso'];
    $mensaje_curso = eliminarCurso($id_curso);
    echo "<script>alert('$mensaje_curso');</script>";
}

$usuarios = obtenerUsuarios();
$cursos = obtenerCursos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas</title>
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

<h1 style="text-align: center;">BAJAS</h1>

<!-- Botones para abrir el modal de baja de usuario y curso -->
<div class="modal-buttons">
    <button class="btn-dar-alta" onclick="mostrarModalUsuario()">ELIMINAR USUARIO</button>
    <button onclick="mostrarModalCurso()">ELIMINAR CURSO</button>
</div>

<!-- Modulo para eliminar usuarios -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalUsuario()">&times;</span>
        <h2 class="modal-title">&nbsp;&nbsp;&nbsp;ELIMINAR USUARIO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="eliminaruser" style="text-align: center;">
                <br>
                <label for="nomnre_usuario">USUARIO</label><br>
                <select name="nombre_usuario" required>
                    <option value="">Selecciona un usuario</option>
                    <?php foreach ($usuarios as $usuario) {
                        echo "<option value='{$usuario['usuario']}'>{$usuario['usuario']}</option>";
                    } ?>
                </select><br><br>
                <button class="btn-dar-alta" type="submit" name="eliminar_usuario">ELIMINAR USUARIO</button>
            </div>
        </form>
    </div>
</div>

<!-- Modulo para eliminar cursos -->
<div id="modalCurso" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalCurso()">&times;</span>
        <h2 style="text-align: center;">&nbsp;&nbsp;ELIMINAR CURSO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="eliminarcurso" style="text-align: center;">
                <br>
                <label for="id_curso">CURSO</label><br>
                <select name="id_curso" id="id_curso" onchange="mostrarDetallesCurso()" required>
                    <option value="">Selecciona un curso</option>
                    <?php foreach ($cursos as $curso) {
                        echo "<option value='{$curso['id']}' data-descripcion='{$curso['descripcion']}' data-instructor='{$curso['instructor']}' data-duracion_horas='{$curso['duracion_horas']}' data-duracion_minutos='{$curso['duracion_minutos']}' data-categoria='{$curso['categoria']}' data-tipo='{$curso['tipo']}'>{$curso['titulo']}</option>";
                    } ?>
                </select><br><br>
                <div id="detalles_curso" style="display:none;">
                    <p><strong>Descripción:</strong> <span id="curso_descripcion"></span></p>
                    <p><strong>Instructor:</strong> <span id="curso_instructor"></span></p>
                    <p><strong>Duración:</strong> <span id="curso_duracion"></span></p>
                    <p><strong>Categoría:</strong> <span id="curso_categoria"></span></p>
                    <p><strong>Tipo:</strong> <span id="curso_tipo"></span></p>
                </div>
                <button class="btn-dar-alta" type="submit" name="eliminar_curso">ELIMINAR CURSO</button>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarModalUsuario() {
    document.getElementById('modalUsuario').style.display = 'block';
}

function cerrarModalUsuario() {
    document.getElementById('modalUsuario').style.display = 'none';
}

function mostrarModalCurso() {
    document.getElementById('modalCurso').style.display = 'block';
}

function cerrarModalCurso() {
    document.getElementById('modalCurso').style.display = 'none';
}

function mostrarDetallesCurso() {
    var select = document.getElementById('id_curso');
    var selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('curso_descripcion').innerText = selectedOption.getAttribute('data-descripcion');
        document.getElementById('curso_instructor').innerText = selectedOption.getAttribute('data-instructor');
        var duracion_horas = selectedOption.getAttribute('data-duracion_horas');
        var duracion_minutos = selectedOption.getAttribute('data-duracion_minutos');
        document.getElementById('curso_duracion').innerText = duracion_horas + " horas " + duracion_minutos + " minutos";
        document.getElementById('curso_categoria').innerText = selectedOption.getAttribute('data-categoria');
        document.getElementById('curso_tipo').innerText = selectedOption.getAttribute('data-tipo');
        document.getElementById('detalles_curso').style.display = 'block';
    } else {
        document.getElementById('detalles_curso').style.display = 'none';
    }
}
</script>

</body>
</html>
