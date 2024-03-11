<?php
// Configuración de la base de datos (reemplaza con tus propios valores)
include '../config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados desde la solicitud AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Datos de la venta
$cliente = $data['cliente'];
$direccion = $data['direccion'];
$productos = $data['productos'];
$total = $data['total'];

// Obtener la fecha y hora actual
$fechaHoraActual = date('Y-m-d H:i:s');

// Iterar sobre los productos y realizar la inserción
foreach ($productos as $producto) {
    $kilo = $producto['kilos'];
    $piezas = $producto['piezas'];
    $categoria = $producto['categoria'];
    $precio = $producto['precio'];
    $cajas = $producto['cajas'];
    $subtotal = $producto['subtotal'];

    // Insertar datos en la tabla de ventas con fecha y hora
    $sqlInsertVenta = "INSERT INTO ventas (cliente, direccion, kilos, piezas, categoria, precio, cajas, subtotal, total, fecha_hora)
                      VALUES ('$cliente', '$direccion', $kilo, $piezas, '$categoria', $precio, $cajas, $subtotal, $total, '$fechaHoraActual')";

    if ($conn->query($sqlInsertVenta) !== TRUE) {
        echo "Error al guardar la venta en la base de datos: " . $conn->error;
        // Puedes agregar alguna lógica adicional si falla la inserción de un producto
    }
}

// Éxito al guardar todos los productos en la base de datos
echo "Venta guardada en la base de datos con éxito.";

// Cerrar la conexión
$conn->close();
?>
