<?php
include 'config.php';
include 'conexion.php';

// Función para agregar un usuario a la base de datos
function agregarUsuario($usuario, $password) {
    global $conexion; // Accede a la conexión a la base de datos definida en conexion.php

    // Verificar si el usuario ya existe en la base de datos
    if (existeUsuario($usuario)) {
        return "El usuario ya existe.";
    } else {
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta para insertar el usuario en la base de datos
        $stmt = $conexion->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $hashed_password);

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
    global $conexion; // Accede a la conexión a la base de datos definida en conexion.php

    // Preparar la consulta para verificar si el usuario existe en la base de datos
    $stmt = $conexion->prepare("SELECT username FROM login WHERE username = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // Devolver true si el usuario existe, false si no existe
    return $stmt->num_rows > 0;
}

function cerrar_sesion() {
    // Eliminar las cookies de sesión
    setcookie("session_cookie", "", time() - 60 * 5);
    
    // Redirigir al usuario a la página de inicio de sesión después de eliminar las cookies
    header("Location: index.php?auth=3");
    exit();
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
                setcookie('session_cookie', '', time() - 3600, '/');
                header("Location: index.php?auth=0");
                exit();
            }
            
            // La sesión es válida
            return;
        }
    }

    // Si no existe la cookie de sesión o si la firma no coincide, redirigir al usuario al inicio de sesión
    header("Location: index.php?auth=0");
    exit();
}
?>
