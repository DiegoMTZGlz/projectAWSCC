<?php
include 'config.php'; // Incluir el archivo config.php para obtener la clave secreta

// Verificar si se enviaron datos de usuario y contraseña
if(isset($_POST['username']) && isset($_POST['password'])) {

    // Crear conexión utilizando consultas preparadas para evitar la inyección SQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta utilizando consultas preparadas
    $stmt = $conn->prepare("SELECT password FROM login WHERE username=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener la contraseña encriptada almacenada en la base de datos
        $row = $result->fetch_assoc();
        $password_hash_db = $row['password'];

        // Verificar si la contraseña ingresada coincide con la contraseña almacenada en la base de datos
        if (password_verify($_POST['password'], $password_hash_db)) {
            // Credenciales correctas, crear cookies de sesión firmadas
            $cookie_data = array(
                'usuario' => $_POST['username'],
                'tiempo' => time()
            );
            $cookie = base64_encode(json_encode($cookie_data));
            $cookie_signature = hash_hmac('sha256', $cookie, $secret_key);
            setcookie('session_cookie', $cookie . '|' . $cookie_signature, time() + (60 * 10), "/");
            
            // Redirigir a la página de inicio de sesión exitosa
            header("Location: main.php");
            exit();
        } else {
            // Credenciales incorrectas, redirigir a index.php con un indicador de error
            header("Location: index.php?auth=1");
            exit();
        }
    } else {
        // No se encontró el usuario en la base de datos, redirigir a index.php con un indicador de error
        header("Location: index.php?auth=1");
        exit();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si no se enviaron datos de usuario y contraseña, redirigir a index.php
    header("Location: index.php");
    exit();
}
?>
