<?php
// Establecer conexión a la base de datos
include 'config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"];
    // ... otros campos de registro ...

    // Validar los datos (puedes hacer más validaciones aquí)

    // Hash de la contraseña (nunca almacenes contraseñas en texto plano)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Crear la tabla 'usuarios' si no existe
    $createTableSQL = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";

    try {
        $conn->query($createTableSQL);

        // Insertar datos en la base de datos
        $insertSQL = "INSERT INTO usuarios (username, password) VALUES ('$username', '$hashed_password')";
        $conn->query($insertSQL);

        // Redirigir al usuario a register.php con el mensaje de éxito
        header("Location: register.php?success=1");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Cerrar la conexión
$conn->close();
?>
