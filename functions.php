<?php
include 'config.php';
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
            
            // Verificar si la sesión ha expirado (por ejemplo, si ha pasado más de una hora)
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
