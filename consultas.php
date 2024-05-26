<?php

include 'functions.php';
verificar_sesion();

if (isset($_GET['cerrar_sesion'])) {
    cerrar_sesion();
    exit();
}

$usuarios = obtenerUsuarios(); // Obtener usuarios
$cursos = obtenerCursos(); // Obtener cursos

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
</head>

<style>
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; 
    overflow: auto;
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px; 
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 800px; 
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.detalle-usuario {
    text-align: center;
}
</style>

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

<h1 style="text-align: center;">CONSULTAS</h1>

<!-- Botones para abrir el modal de baja de usuario y curso -->
<div class="modal-buttons">
    <button class="btn-dar-alta" onclick="mostrarModalUsuario()">VER USUARIOS</button>
    <button onclick="mostrarModalCurso()">VER CURSOS</button>
</div>

<!-- Modal para mostrar detalles de usuario -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalUsuario()">&times;</span>
        <h2 class="modal-title">&nbsp;&nbsp;&nbsp;DETALLES DEL USUARIO</h2>
        <hr>
        <div class="detalle-usuario" style="text-align: center;">
            <br>
            <label for="nombre_usuario">USUARIO</label><br>
            <select name="nombre_usuario" id="nombre_usuario" onchange="mostrarDetallesUsuario()" required>
                <option value="">Selecciona un usuario</option>
                <?php foreach ($usuarios as $usuario) {
                    echo "<option value='{$usuario['username']}' data-password='{$usuario['password']}' data-nombre='{$usuario['nombre']}' data-apellido='{$usuario['apellido']}' data-created_at='{$usuario['created_at']}'>{$usuario['username']}</option>";
                } ?>
            </select><br><br>
            <div id="detalles_usuario" style="display:none;">
                <p><strong>Usuario:</strong> <span id="usuario"></span></p>
                <p><strong>Password:</strong> <span id="password"></span></p>
                <p><strong>Nombre:</strong> <span id="nombre"></span></p>
                <p><strong>Apellido:</strong> <span id="apellido"></span></p>
                <p><strong>Fecha de Creación:</strong> <span id="created_at"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Modulo para mostrar detalles de curso -->
<div id="modalCurso" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalCurso()">&times;</span>
        <h2 style="text-align: center;">&nbsp;&nbsp;DETALLES DEL CURSO</h2>
        <hr>
        <div class="detalle-curso" style="text-align: center;">
            <br>
            <label for="id_curso">CURSO</label><br>
            <select name="id_curso" id="id_curso" onchange="mostrarDetallesCurso()" required>
                <option value="">Selecciona un curso</option>
                <?php foreach ($cursos as $curso) {
                    echo "<option value='{$curso['id']}' data-descripcion='{$curso['descripcion']}' data-instructor='{$curso['instructor']}' data-duracion_horas='{$curso['duracion_horas']}' data-duracion_minutos='{$curso['duracion_minutos']}' data-categoria='{$curso['categoria']}' data-tipo='{$curso['tipo']}' data-fecha_creacion='{$curso['fecha_creacion']}'>{$curso['titulo']}</option>";
                } ?>
            </select><br><br>
            <div id="detalles_curso" style="display:none;">
                <p><strong>Descripción:</strong> <span id="curso_descripcion"></span></p>
                <p><strong>Instructor:</strong> <span id="curso_instructor"></span></p>
                <p><strong>Duración:</strong> <span id="curso_duracion"></span></p>
                <p><strong>Categoría:</strong> <span id="curso_categoria"></span></p>
                <p><strong>Tipo:</strong> <span id="curso_tipo"></span></p>
                <p><strong>Fecha de Creación:</strong> <span id="curso_fecha_creacion"></span></p>
            </div>
        </div>
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
            // Obtener los detalles del usuario
            var usuario = selectedOption.value;
            var password = selectedOption.getAttribute('data-password');
            var nombre = selectedOption.getAttribute('data-nombre');
            var apellido = selectedOption.getAttribute('data-apellido');
            var created_at = selectedOption.getAttribute('data-created_at');

            document.getElementById('usuario').innerText = usuario;
            document.getElementById('password').innerText = password;
            document.getElementById('nombre').innerText = nombre;
            document.getElementById('apellido').innerText = apellido;
            document.getElementById('created_at').innerText = created_at;
            document.getElementById('detalles_usuario').style.display = 'block';
        } else {
            document.getElementById('detalles_usuario').style.display = 'none';
        }
    }

    function mostrarDetallesCurso() {
        var select = document.getElementById('id_curso');
        var selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value) {
            // Obtener los detalles del curso
            var descripcion = selectedOption.getAttribute('data-descripcion');
            var instructor = selectedOption.getAttribute('data-instructor');
            var duracion_horas = selectedOption.getAttribute('data-duracion_horas');
            var duracion_minutos = selectedOption.getAttribute('data-duracion_minutos');
            var categoria = selectedOption.getAttribute('data-categoria');
            var tipo = selectedOption.getAttribute('data-tipo');
            var fecha_creacion = selectedOption.getAttribute('data-fecha_creacion');

            document.getElementById('curso_descripcion').innerText = descripcion;
            document.getElementById('curso_instructor').innerText = instructor;
            document.getElementById('curso_duracion').innerText = duracion_horas + " horas " + duracion_minutos + " minutos";
            document.getElementById('curso_categoria').innerText = categoria;
            document.getElementById('curso_tipo').innerText = tipo;
            document.getElementById('curso_fecha_creacion').innerText = fecha_creacion;
            document.getElementById('detalles_curso').style.display = 'block';
        } else {
            document.getElementById('detalles_curso').style.display = 'none';
        }
    }
</script>

</body>
</html>
