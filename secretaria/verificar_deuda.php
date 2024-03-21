<?php
// Incluir el archivo de conexión a la base de datos
include '../config/conexion.php';

// Obtener los parámetros enviados por la solicitud GET
$cliente = $_GET['cliente'];
$direccion = $_GET['direccion'];

// Consulta SQL para obtener la deuda del cliente
$sqlDeuda = "SELECT total_deuda FROM cliente_deudas WHERE cliente = '$cliente' AND direccion = '$direccion'";
$resultDeuda = $conn->query($sqlDeuda);

// Verificar si se encontró alguna deuda
if ($resultDeuda->num_rows > 0) {
    // Inicializar la variable de deuda
    $deuda = 0;
    // Sumar todas las deudas encontradas
    while ($rowDeuda = $resultDeuda->fetch_assoc()) {
        $deuda += $rowDeuda['total_deuda'];
    }
    // Devolver la deuda al cliente
    echo $deuda;
} else {
    // Si no hay deuda, devolver 0
    echo 0;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
