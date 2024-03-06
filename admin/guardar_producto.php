<?php
// Establecer conexión a la base de datos
include '../config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario con datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre del producto desde el formulario
    $nombreProducto = $_POST["nombreProducto"];

    // Validar o procesar los datos según sea necesario

    // Verificar si ya existe un producto con el mismo nombre
    $consultaExistencia = "SELECT * FROM productos WHERE nombre = '$nombreProducto'";
    $resultadoExistencia = $conn->query($consultaExistencia);

    if ($resultadoExistencia->num_rows > 0) {
        // El producto ya existe, enviar mensaje de error
        header("Location: productos.php?mensaje=El producto ya existe&tipo=error");
        exit();
    }

    // Preparar la consulta SQL para insertar el producto
    $consulta = "INSERT INTO productos (nombre) VALUES ('$nombreProducto')";

    // Ejecutar la consulta
    if ($conn->query($consulta) === TRUE) {
        // Redirigir a productos.php después de guardar exitosamente con un mensaje
        header("Location: productos.php?mensaje=Producto guardado exitosamente&tipo=success");
        exit(); // Asegurar que el script se detenga después de redirigir
    } else {
        echo "Error al guardar el producto: " . $conn->error;
    }

} else {
    // Redirigir o manejar el caso en que el formulario no se haya enviado
    echo "El formulario no se ha enviado correctamente.";
}

// Cerrar la conexión
$conn->close();
?>
