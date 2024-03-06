<?php
include 'config/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validar los datos (puedes hacer más validaciones aquí)

    // Consultar la base de datos para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION["username"] = $username;
            header("Location: dashboard.php"); // Redirigir a la página después del inicio de sesión
            exit();
        } else {
            echo "Error: Contraseña incorrecta";
        }
    } else {
        echo "Error: Usuario no encontrado";
    }
}

// Cerrar la conexión
$conn->close();
?>
