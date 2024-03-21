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

// Obtener el método de pago seleccionado
$metodoPago = isset($_POST['metodoPago']) ? $_POST['metodoPago'] : '';

// Obtener la fecha y hora actual en formato de 12 horas
$fechaHoraActual = date('Y-m-d h:i:s a');

// Iniciar una transacción para garantizar la integridad de los datos
$conn->begin_transaction();

try {
    // Consultar el último folio registrado para la misma fecha y hora
    $sqlGetLastFolio = "SELECT folio FROM ventas WHERE fecha_hora = '$fechaHoraActual' ORDER BY folio DESC LIMIT 1";
    $result = $conn->query($sqlGetLastFolio);

    if ($result->num_rows > 0) {
        // Hay registros para la misma fecha y hora, obtener el último folio
        $row = $result->fetch_assoc();
        $folio = $row['folio'];
    } else {
        // No hay registros para la misma fecha y hora, asignar un nuevo folio
        // Consultar el último folio registrado en general
        $sqlGetLastFolioOverall = "SELECT MAX(folio) AS max_folio FROM ventas";
        $resultOverall = $conn->query($sqlGetLastFolioOverall);
        
        if ($resultOverall->num_rows > 0) {
            $rowOverall = $resultOverall->fetch_assoc();
            $folio = $rowOverall['max_folio'] + 1;
        } else {
            // No hay registros en absoluto, establecer el folio inicial
            $folio = 1;
        }
    }

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

    // Variables para total de la venta y cliente
    $totalVenta = 0;
    $cliente = '';

    // Insertar los datos en la tabla de ventas, deudores y remiciones
    foreach ($jsonData as $producto) {
        $kilos = $producto['kilos'];
        $piezas = $producto['piezas'];
        $categoria = $producto['categoria'];
        $precio = $producto['precio'];
        $cajas = $producto['cajas'];
        $tapas = $producto['tapas']; // Agregar la cantidad de tapas
        $metodoPago = $producto['metodoPago']; // Agregar el método de pago
        $subtotal = $producto['subtotal'];
        $cliente = empty($producto['cliente']) ? 'CLIENTE VARIOS' : $producto['cliente'];
        $direccion = $producto['direccion'];
        $deuda = 'deuda';
        $estatus = 'estatus';

        $totalVenta += $subtotal; // Sumar al total de la venta

        // Insertar en la tabla de ventas (incluye el campo folio)
        $sqlInsertVenta = "INSERT INTO ventas (folio, kilos, piezas, categoria, precio, cajas, tapas, metodo_pago, subtotal, cliente, direccion, fecha_hora)
                   VALUES ('$folio', '$kilos', '$piezas', '$categoria', '$precio', '$cajas', '$tapas', '$metodoPago', '$subtotal', '$cliente', '$direccion', '$fechaHoraActual')";

        if ($conn->query($sqlInsertVenta) !== TRUE) {
            // Error en la inserción de ventas
            throw new Exception("Error al insertar en la tabla de ventas: " . $conn->error);
        }

        // Incrementar el folio solo si la fecha y hora son diferentes
        $sqlIncrementFolio = "UPDATE ventas SET folio = folio + 1 WHERE fecha_hora <> '$fechaHoraActual'";
        $conn->query($sqlIncrementFolio);

        // Insertar en la tabla de deudores
        $sqlInsertDeudor = "INSERT INTO deudores (folio_venta, kilos, piezas, categoria, precio, cajas, tapas, metodo_pago, subtotal, cliente, direccion, fecha_hora, estatus)
        VALUES ('$folio', '$kilos', '$piezas', '$categoria', '$precio', '$cajas', '$tapas', '$metodoPago', '$subtotal', '$cliente', '$direccion', '$fechaHoraActual', '$estatus')";

        if ($conn->query($sqlInsertDeudor) !== TRUE) {
            // Error en la inserción de deudores
            throw new Exception("Error al insertar en la tabla de deudores: " . $conn->error);
        }

        // Insertar en la tabla de remiciones
        $sqlInsertRemicion = "INSERT INTO remiciones (folio_venta, kilos, piezas, categoria, precio, cajas, tapas, metodo_pago, subtotal, cliente, direccion, fecha_hora, estatus)
        VALUES ('$folio', '$kilos', '$piezas', '$categoria', '$precio', '$cajas', '$tapas', '$metodoPago', '$subtotal', '$cliente', '$direccion', '$fechaHoraActual', '$estatus')";

        if ($conn->query($sqlInsertRemicion) !== TRUE) {
            // Error en la inserción de remiciones
            throw new Exception("Error al insertar en la tabla de remiciones: " . $conn->error);
        }

        // Sumar las cajas y tapas por cliente y actualizar la tabla 'cajas'
        $sqlUpdateCajas = "INSERT INTO cajas (cliente, direccion, total_cajas, total_tapas) 
        VALUES ('$cliente', '$direccion', '$cajas', '$tapas')
        ON DUPLICATE KEY UPDATE 
            total_cajas = total_cajas + VALUES(total_cajas), 
            total_tapas = total_tapas + VALUES(total_tapas);
        ";

        if ($conn->query($sqlUpdateCajas) !== TRUE) {
            // Error en la actualización de cajas
            throw new Exception("Error al actualizar la tabla de cajas: " . $conn->error);
        }
    }

    // Insertar los datos
    // Insertar los datos en la tabla cliente_deudas
    $sqlInsertClienteDeuda = "INSERT INTO cliente_deudas (cliente, direccion, total_deuda)
                            VALUES ('$cliente', '$direccion', '$totalVenta')";

    if ($conn->query($sqlInsertClienteDeuda) !== TRUE) {
        // Error en la inserción de cliente_deudas
        throw new Exception("Error al insertar en la tabla cliente_deudas: " . $conn->error);
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
