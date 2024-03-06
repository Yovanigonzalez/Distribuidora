<?php
// Establecer conexión a la base de datos
include '../config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario con datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre del cliente y la dirección desde el formulario
    $nombreCliente = $_POST["nombreCliente"];
    $direccionCliente = $_POST["direccionCliente"];

    // Validar o procesar los datos según sea necesario

    // Preparar la consulta SQL para insertar el cliente
    $consulta = "INSERT INTO clientes (nombre, direccion) VALUES ('$nombreCliente', '$direccionCliente')";

    // Ejecutar la consulta
    if ($conn->query($consulta) === TRUE) {
        // Redirigir a clientes.php después de guardar exitosamente con un mensaje
        header("Location: clientes.php?mensaje=Cliente guardado exitosamente&tipo=success");
        exit(); // Asegurar que el script se detenga después de redirigir
    } else {
        echo "Error al guardar el cliente: " . $conn->error;
    }

} else {
    // Redirigir o manejar el caso en que el formulario no se haya enviado
    echo "El formulario no se ha enviado correctamente.";
}

// Cerrar la conexión
$conn->close();
?>
