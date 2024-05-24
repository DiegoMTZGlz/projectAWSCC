<?php
// Función para realizar la autenticación
function cerrar_sesion() {
    // Eliminar las cookies de sesión
    setcookie("session", "", time() - 60 * 5);
    setcookie("user", "", time() - 60 * 5);
    setcookie("pass", "", time() - 60 * 5);
    
    // Redirigir al usuario a la página de inicio de sesión después de eliminar las cookies
    header("Location: index.php?auth=3");
    exit();
}

function autenticar($username, $password) {
    // Datos para la conexión a la BD
    $servername = "localhost";
    $username_bd = "root";
    $password_bd = "";
    $database = "proyjd";
    
    // Conexión a la BD
    $conn = new mysqli($servername, $username_bd, $password_bd, $database);
    
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL utilizando sentencias preparadas (para evitar inyección SQL)
    $sql = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // 's' = tipo de dato STRING
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt->close();
    $conn->close();
    
    return $result->num_rows > 0;
}

// Función para verificar la autenticación
function verificar() {
    // Verificar si se enviaron datos de inicio de sesión por POST
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $user = $_POST["username"];
        $pass = $_POST["password"];

        if (autenticar($user, $pass)) {
            // El usuario y la contraseña son válidos
            setcookie("session", "OK", time() + 60 * 5);
            setcookie("user", $user, time() + 60 * 5);
            setcookie("pass", $pass, time() + 60 * 5);
            header("Location: main.php");
            exit();
        } else {
            // El usuario y/o la contraseña son incorrectos
            header("Location: index.php?auth=1");
            setcookie("session", "", time() - 60 * 5);
            setcookie("user", "", time() - 60 * 5);
            setcookie("pass", "", time() - 60 * 5);
            exit();
        }
    }

    // Verificar si las cookies de sesión, usuario y contraseña están establecidas
    if (isset($_COOKIE["session"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
        $userc = $_COOKIE["user"];
        $passc = $_COOKIE["pass"];

        if (autenticar($userc, $passc)) {
            // El usuario y la contraseña son válidos
            setcookie("session", "OK", time() + 60 * 5);
            setcookie("user", $userc, time() + 60 * 5);
            setcookie("pass", $passc, time() + 60 * 5);
            return true;
        } else {
            // El usuario y/o la contraseña son incorrectos
            header("Location: index.php?auth=2");
            setcookie("session", "", time() - 60 * 5);
            setcookie("user", "", time() - 60 * 5);
            setcookie("pass", "", time() - 60 * 5);
            exit();
        }
    }
    header("Location: index.php?auth=0");
    setcookie("session", "", time() - 60 * 5);
    setcookie("user", "", time() - 60 * 5);
    setcookie("pass", "", time() - 60 * 5);
    exit();
}

// Verificar la autenticación
verificar();
?>