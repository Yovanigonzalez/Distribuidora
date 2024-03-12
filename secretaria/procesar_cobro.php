<?php
include '../config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer la zona horaria a GMT-6
date_default_timezone_set('America/Mexico_City');

// Obtener datos de la solicitud AJAX
$jsonData = json_decode($_POST['datos'], true);

// Obtener la fecha y hora actual en formato de 12 horas
$fechaHoraActual = date('Y-m-d h:i:s a');

// Iniciar una transacción para garantizar la integridad de los datos
$conn->begin_transaction();

try {
    // Recorrer los productos y actualizar el stock en la base de datos
    foreach ($jsonData as $producto) {
        $categoria = $producto['categoria'];
        $piezas = floatval($producto['piezas']);

        // Actualizar el stock restando la cantidad de piezas
        $sqlUpdateStock = "UPDATE entradas SET stock = stock - $piezas WHERE categoria = '$categoria'";

        if ($conn->query($sqlUpdateStock) !== TRUE) {
            // Error en la actualización
            throw new Exception("Error al actualizar el stock: " . $conn->error);
        }
    }

    // Insertar los datos en la tabla de ventas y deudores
    foreach ($jsonData as $producto) {
        $kilos = $producto['kilos'];
        $piezas = $producto['piezas'];
        $categoria = $producto['categoria'];
        $precio = $producto['precio'];
        $cajas = $producto['cajas'];
        $tapas = $producto['tapas']; // Agregar la cantidad de tapas
        $subtotal = $producto['subtotal'];
        $cliente = empty($producto['cliente']) ? 'CLIENTE VARIOS' : $producto['cliente'];
        $direccion = $producto['direccion'];
        $deuda = 'deuda';

        // Insertar en la tabla de ventas
        $sqlInsertVenta = "INSERT INTO ventas (kilos, piezas, categoria, precio, cajas, tapas, subtotal, cliente, direccion, fecha_hora)
                          VALUES ('$kilos', '$piezas', '$categoria', '$precio', '$cajas', '$tapas', '$subtotal', '$cliente', '$direccion', '$fechaHoraActual')";

        if ($conn->query($sqlInsertVenta) !== TRUE) {
            // Error en la inserción de ventas
            throw new Exception("Error al insertar en la tabla de ventas: " . $conn->error);
        }

        // Insertar en la tabla de deudores
        $sqlInsertDeudor = "INSERT INTO deudores (kilos, piezas, categoria, precio, cajas, tapas, subtotal, cliente, direccion, fecha_hora, estatus)
                        VALUES ('$kilos', '$piezas', '$categoria', '$precio', '$cajas', '$tapas', '$subtotal', '$cliente', '$direccion', '$fechaHoraActual', '$deuda')";

        if ($conn->query($sqlInsertDeudor) !== TRUE) {
            // Error en la inserción de deudores
            throw new Exception("Error al insertar en la tabla de deudores: " . $conn->error);
        }

        // Sumar las cajas y tapas por cliente y actualizar la tabla 'cajas'
        $sqlUpdateCajas = "INSERT INTO cajas (cliente, total_cajas, total_tapas) 
                   VALUES ('$cliente', '$cajas', '$tapas')
                   ON DUPLICATE KEY UPDATE total_cajas = total_cajas + VALUES(total_cajas), total_tapas = total_tapas + VALUES(total_tapas)";

        if ($conn->query($sqlUpdateCajas) !== TRUE) {
            // Error en la actualización de cajas
            throw new Exception("Error al actualizar la tabla de cajas: " . $conn->error);
        }
    }

    // Confirmar la transacción si todo fue exitoso
    $conn->commit();
    echo "Guardado correctamente.";

} catch (Exception $e) {
    // Rollback en caso de error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn->close();
?>
