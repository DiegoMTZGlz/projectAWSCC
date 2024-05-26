<?php
include 'config.php';
include 'conexion.php';

function cerrar_sesion() {
    // Eliminar la cookie de sesión
    setcookie('session_cookie', '', time() - 3600, "/");
    
    // Eliminar la cookie del nombre de usuario
    setcookie('username_cookie', '', time() - 3600, "/");

    header("Location: index.php?auth=3");
    exit();
}

// Función para agregar un curso a la base de datos
function agregarCurso($titulo, $descripcion, $instructor, $duracion_horas, $duracion_minutos, $categoria, $tipo) {
    global $conexion;

    // Preparar la consulta para insertar el curso en la tabla
    $stmt = $conexion->prepare("INSERT INTO cursos (titulo, descripcion, instructor, duracion_horas, duracion_minutos, categoria, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Vincular los parámetros de la consulta
    $stmt->bind_param("sssiiss", $titulo, $descripcion, $instructor, $duracion_horas, $duracion_minutos, $categoria, $tipo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        return "El curso '$titulo' ha sido agregado correctamente.";
    } else {
        return "Error al agregar el curso: " . $conexion->error;
    }

    // Cerrar la consulta
    $stmt->close();
}

// Función para agregar un usuario a la base de datos
function agregarUsuario($usuario, $password, $nombre, $apellido) {
    global $conexion; // Accede a la conexión a la base de datos definida en conexion.php

    // Verificar si el usuario ya existe en la base de datos
    if (existeUsuario($usuario)) {
        return "El usuario ya existe.";
    } else {
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta para insertar el usuario en la base de datos
        $stmt = $conexion->prepare("INSERT INTO login (username, password, nombre, apellido) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $hashed_password, $nombre, $apellido);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return "Usuario agregado correctamente.";
        } else {
            return "Error al agregar el usuario.";
        }
    }
}

// Función para verificar si un usuario ya existe en la base de datos
function existeUsuario($usuario) {
    global $conexion;

    // Preparar la consulta para verificar si el usuario existe en la base de datos
    $stmt = $conexion->prepare("SELECT username FROM login WHERE username = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // Devolver true si el usuario existe, false si no existe
    return $stmt->num_rows > 0;
}

function verificar_sesion() {
    global $secret_key;

    // Verificar si existe la cookie de sesión
    if(isset($_COOKIE['session_cookie'])) {
        // Separar los datos de la cookie y la firma
        list($cookie_data, $cookie_signature) = explode('|', $_COOKIE['session_cookie']);
        
        // Verificar la firma
        $expected_signature = hash_hmac('sha256', $cookie_data, $secret_key);
        
        // Si la firma es válida
        if(hash_equals($cookie_signature, $expected_signature)) {
            // Decodificar los datos de la cookie
            $cookie_data = json_decode(base64_decode($cookie_data), true);
            
            // Verificar si la sesión ha expirado
            if(time() - $cookie_data['tiempo'] > 3600) {
                // La sesión ha expirado, eliminar la cookie y redirigir al usuario al inicio de sesión
                // Eliminar la cookie de sesión
                setcookie('session_cookie', '', time() - 3600, "/");

                // Eliminar la cookie del nombre de usuario
                setcookie('username_cookie', '', time() - 3600, "/");
                header("Location: index.php?auth=0");
                exit();
            }
            
            // La sesión es válida
            return;
        }
    }

    // Si no existe la cookie de sesión o si la firma no coincide, redirigir al usuario al inicio de sesión
    // Eliminar la cookie de sesión
    setcookie('session_cookie', '', time() - 3600, "/");
    
    // Eliminar la cookie del nombre de usuario
    setcookie('username_cookie', '', time() - 3600, "/");
    header("Location: index.php?auth=0");
    exit();
}

function obtenerUsuarios() {
    global $conexion;
    $sql = "SELECT username, password, nombre, apellido, created_at FROM login";
    $result = $conexion->query($sql);
    $usuarios = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    return $usuarios;
}

function obtenerCursos() {
    global $conexion;
    $sql = "SELECT * FROM cursos";
    $result = $conexion->query($sql);
    $cursos = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cursos[] = $row;
        }
    }

    return $cursos;
}

function eliminarUsuario($nombre_usuario) {
    global $conexion;

    // Detectar el usuario que inició sesión
    if (isset($_COOKIE['username_cookie'])) {
        $nombre_usuario_actual = $_COOKIE['username_cookie'];
    } else {
        $nombre_usuario_actual = null;
    }

    // Evitar que se elimine el usuario que inició sesión
    if ($nombre_usuario === $nombre_usuario_actual) {
        return "No puedes eliminar tu propio usuario.";
    }

    // Preparar la consulta para eliminar el usuario de la base de datos
    $stmt = $conexion->prepare("DELETE FROM login WHERE username = ?");
    $stmt->bind_param("s", $nombre_usuario);
    if ($stmt->execute()) {
        return "Usuario eliminado correctamente.";
    } else {
        return "Error al eliminar el usuario.";
    }
}

function eliminarCurso($id_curso) {
    global $conexion;
    $stmt = $conexion->prepare("DELETE FROM cursos WHERE id = ?");
    $stmt->bind_param("i", $id_curso);
    if ($stmt->execute()) {
        return "Curso eliminado correctamente.";
    } else {
        return "Error al eliminar el curso.";
    }
}

function actualizarUsuario($username, $nombre, $apellido, $password = null) {
    global $conexion;
    try {
        $sql = "UPDATE login SET nombre = ?, apellido = ?";
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql .= ", password = ?";
        }
        $sql .= " WHERE username = ?";
        
        $stmt = $conexion->prepare($sql);
        if ($password) {
            $stmt->bind_param("ssss", $nombre, $apellido, $hashed_password, $username);
        } else {
            $stmt->bind_param("sss", $nombre, $apellido, $username);
        }
        $stmt->execute();
        return "Usuario actualizado exitosamente.";
    } catch (Exception $e) {
        return "Error al actualizar usuario: " . $e->getMessage();
    }
}

function actualizarCurso($id, $descripcion, $instructor, $duracion_horas, $duracion_minutos, $categoria, $tipo) {
    global $conexion;
    try {
        $sql = "UPDATE cursos SET descripcion = ?, instructor = ?, duracion_horas = ?, duracion_minutos = ?, categoria = ?, tipo = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssiissi", $descripcion, $instructor, $duracion_horas, $duracion_minutos, $categoria, $tipo, $id);
        $stmt->execute();
        return "Curso actualizado exitosamente.";
    } catch (Exception $e) {
        return "Error al actualizar curso: " . $e->getMessage();
    }
}

?>
