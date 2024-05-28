<?php
include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

$mensaje_usuario = '';
$mensaje_curso = '';

// Obtener el nombre de usuario de la cookie
$current_user = isset($_COOKIE['username_cookie']) ? $_COOKIE['username_cookie'] : '';

// Verificar si se ha enviado el formulario de actualización de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_usuario'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    if ($nombre_usuario != $current_user) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $password = isset($_POST['cambiar_password']) ? $_POST['password'] : null;
        $mensaje_usuario = actualizarUsuario($nombre_usuario, $nombre, $apellido, $password);
        echo "<script>alert('$mensaje_usuario');</script>";
    } else {
        echo "<script>alert('No puedes cambiar tu propio usuario.');</script>";
    }
}

// Verificar si se ha enviado el formulario de actualización de curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_curso'])) {
    $id_curso = $_POST['id_curso'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $instructor = $_POST['instructor'];
    $duracion_horas = $_POST['duracion_horas'];
    $duracion_minutos = $_POST['duracion_minutos'];
    $categoria = $_POST['categoria'];
    $tipo = $_POST['tipo'];
    $mensaje_curso = actualizarCurso($id_curso, $titulo, $descripcion, $instructor, $duracion_horas, $duracion_minutos, $categoria, $tipo);
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
    <title>Cambios</title>
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

<h1 style="text-align: center;">CAMBIOS</h1>

<!-- Botones para abrir el modal de cambio de usuario y curso -->
<div class="modal-buttons">
    <button class="btn-dar-alta" onclick="mostrarModalUsuario()">CAMBIAR USUARIO</button>
    <button onclick="mostrarModalCurso()">CAMBIAR CURSO</button>
</div>

<!-- Modal para cambiar usuario -->
<div id="modalUsuario" class="modal">
    <div class="modal-content" style="width: 60%;">
        <span class="close" onclick="cerrarModalUsuario()">&times;</span>
        <h2 style="text-align: center;">CAMBIAR USUARIO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div style="text-align: center;">
                <br>
                <label for="nombre_usuario">USUARIO</label><br>
                <select name="nombre_usuario" id="nombre_usuario" onchange="mostrarDetallesUsuario()" required>
                    <option value="">Selecciona un usuario</option>
                    <?php foreach ($usuarios as $usuario) {
                        if ($usuario['username'] != $current_user) {
                            echo "<option value='{$usuario['username']}' data-nombre='{$usuario['nombre']}' data-apellido='{$usuario['apellido']}'>{$usuario['username']}</option>";
                        }
                    } ?>
                </select><br><br>
                <div id="detalles_usuario" style="display:none;">
                    <label for="nombre">Nombre</label><br>
                    <input type="text" id="nombre" name="nombre" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                    <label for="apellido">Apellido</label><br>
                    <input type="text" id="apellido" name="apellido" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                    <label for="cambiar_password">Cambiar Contraseña</label>
                    <input type="checkbox" id="cambiar_password" name="cambiar_password" onchange="togglePasswordInput()"><br><br>
                    <div id="password_input" style="display: none;">
                        <label for="password">Nueva Contraseña</label><br>
                        <input type="password" id="password" name="password"><br><br>
                    </div>
                </div>
                <button class="btn-dar-alta" type="submit" name="actualizar_usuario">ACTUALIZAR USUARIO</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para cambiar curso -->
<div id="modalCurso" class="modal">
    <div class="modal-content" style="width: 60%;">
        <span class="close" onclick="cerrarModalCurso()">&times;</span>
        <h2 style="text-align: center;">CAMBIAR CURSO</h2>
        <hr>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div style="text-align: center;">
                <br>
                <label for="id_curso">CURSO</label><br>
                <select name="id_curso" id="id_curso" onchange="mostrarDetallesCurso()" required>
                    <option value="">Selecciona un curso</option>
                    <?php foreach ($cursos as $curso) {
                        echo "<option value='{$curso['id']}' data-titulo='{$curso['titulo']}' data-descripcion='{$curso['descripcion']}' data-instructor='{$curso['instructor']}' data-duracion_horas='{$curso['duracion_horas']}' data-duracion_minutos='{$curso['duracion_minutos']}' data-categoria='{$curso['categoria']}' data-tipo='{$curso['tipo']}'>{$curso['titulo']}</option>";
                    } ?>
                </select><br><br>
                <div id="detalles_curso" style="display:none;">
                    <label for="titulo">TÍTULO DEL CURSO</label><br>
                    <input type="text" id="titulo" name="titulo" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                    <label for="descripcion">DESCRIPCIÓN</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>
                    <label for="instructor">INSTRUCTOR</label><br>
                    <input type="text" id="instructor" name="instructor" pattern="[A-Za-z ]+" title="Solo se permiten letras (A-Z, a-z) y espacios" required><br><br>
                    <label for="duracion_horas">DURACIÓN (Horas)</label><br>
                    <select id="duracion_horas" name="duracion_horas" required>
                        <?php
                        for ($i = 1; $i <= 24; $i++) {
                            if ($i == 1) {
                                echo "<option value='$i'>$i hora</option>";
                            } else {
                                echo "<option value='$i'>$i horas</option>";
                            }
                        }
                        ?>
                    </select><br><br>
                    <label for="duracion_minutos">DURACIÓN (Minutos)</label><br>
                    <select id="duracion_minutos" name="duracion_minutos" required>
                        <?php for ($i = 0; $i < 60; $i += 10) {
                            echo "<option value='$i'>$i minutos</option>";
                        } ?>
                    </select><br><br>
                    <label for="categoria">CATEGORÍA</label><br>
                    <select id="categoria" name="categoria" required>
                        <option value="Programación">Programación</option>
                        <option value="Diseño">Diseño</option>
                        <option value="Desarrollo web">Desarrollo Web</option>
                        <option value="Cocina">Cocina</option>
                        <option value="Cálculo">Cálculo</option>
                        <option value="Álgebra">Álgebra</option>
                        <option value="Probabilidad">Probabilidad</option>
                        <option value="Estadística">Estadística</option>
                    </select><br><br>
                    <label for="tipo">MODALIDAD</label><br>
                    <select id="tipo" name="tipo" required>
                        <option value="presencial">PRESENCIAL</option>
                        <option value="virtual">VIRTUAL</option>
                    </select><br><br>
                </div>
                <button class="btn-dar-alta" type="submit" name="actualizar_curso">ACTUALIZAR CURSO</button>
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

function mostrarDetallesUsuario() {
    var select = document.getElementById('nombre_usuario');
    var selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('nombre').value = selectedOption.getAttribute('data-nombre');
        document.getElementById('apellido').value = selectedOption.getAttribute('data-apellido');
        document.getElementById('detalles_usuario').style.display = 'block';
    } else {
        document.getElementById('detalles_usuario').style.display = 'none';
    }
}

function togglePasswordInput() {
    var checkbox = document.getElementById('cambiar_password');
    var passwordInput = document.getElementById('password_input');
    
    if (checkbox.checked) {
        passwordInput.style.display = 'block';
    } else {
        passwordInput.style.display = 'none';
    }
}

function mostrarDetallesCurso() {
    var select = document.getElementById('id_curso');
    var selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('titulo').value = selectedOption.getAttribute('data-titulo');
        document.getElementById('descripcion').value = selectedOption.getAttribute('data-descripcion');
        document.getElementById('instructor').value = selectedOption.getAttribute('data-instructor');
        document.getElementById('duracion_horas').value = selectedOption.getAttribute('data-duracion_horas');
        document.getElementById('duracion_minutos').value = selectedOption.getAttribute('data-duracion_minutos');
        document.getElementById('categoria').value = selectedOption.getAttribute('data-categoria');
        document.getElementById('tipo').value = selectedOption.getAttribute('data-tipo');
        document.getElementById('detalles_curso').style.display = 'block';
    } else {
        document.getElementById('detalles_curso').style.display = 'none';
    }
}
</script>

</body>
</html>
