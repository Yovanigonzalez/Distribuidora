<?php
if (isset($_GET['generate_pdf']) && isset($_GET['cliente'])) {
    require('../tcpdf/tcpdf.php');
    require('../config/conexion.php');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    date_default_timezone_set('America/Mexico_City');

    $clienteSeleccionado = $conn->real_escape_string($_GET['cliente']);
    $fechaActual = date('Y-m-d H:i:s'); // Obtener fecha y hora actual en formato 'año-mes-día hora:minuto:segundo'

    $queryDeudores = "SELECT folio_venta, cliente, direccion, fecha_hora, SUM(subtotal) as total, SUM(cajas) as totalCajas FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = DATE('$fechaActual') GROUP BY DATE(fecha_hora), HOUR(fecha_hora)";
    $resultDeudores = $conn->query($queryDeudores);

    if ($resultDeudores->num_rows > 0) {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);  // Orientación vertical
        $pdf->SetCreator('Your Name');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Notas PDF - Cliente: ' . $clienteSeleccionado);
        $pdf->SetSubject('Notas PDF');
        $pdf->SetKeywords('Nota, PDF, Download');

        while ($rowDeudor = $resultDeudores->fetch_assoc()) {
            $folioVenta = $rowDeudor['folio_venta']; // Obtener el folio de venta

            $pdf->AddPage();

            $pdf->SetFont('helvetica', 'B', 14); // Tamaño de fuente 14 para encabezados

            // Ruta de la imagen del logotipo
            $imagePath = '../log/logo2.jpg';
            $pdf->Image($imagePath, 15, 15, 40, 40);

            // Texto "Distribuidora Gonzalez" centrado
            $pdf->SetFont('helvetica', 'B', 12); // Establecer la fuente y el tamaño
            $pdf->SetXY(15, 10); // Establecer la posición del texto (ajusta según sea necesario)
            $pdf->Cell(0, 10, 'Distribuidora Gonzalez', 0, 1, 'C'); // Añadir el texto centrado
            $pdf->Cell(0, 10, 'Pollo procesado', 0, 1, 'C'); // Añadir el texto centrado
            $pdf->Cell(0, 10, 'Tel: 2491237040', 0, 1, 'C'); // Añadir el texto centrado

            // Mostrar el folio de venta encima de la fecha

            $pdf->SetFont('helvetica', '', 9);

            // Obtener el nombre del cliente
            $cliente = $rowDeudor['cliente'];

            // Obtener la dirección
            $direccion = $rowDeudor['direccion'];

            // Obtener la fecha
            $fecha = date('d/m/Y', strtotime($rowDeudor['fecha_hora']));

            $pdf->SetFont('helvetica', '', 10); // Restauro el tamaño de la fuente a 10

            // Mostrar el nombre del cliente y dirección antes de la tabla de productos
            $pdf->Cell(0, 10, '' , 0, 1, 'L');
            $pdf->Cell(0, 10, '' , 0, 1, 'L');
            $pdf->Cell(0, 10, 'Folio de Venta: ' . $folioVenta, 0, 1, 'L');
            $pdf->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1, 'L');
            $pdf->Cell(0, 10, 'Nombre: ' . $cliente, 0, 1, 'L');
            $pdf->Cell(0, 10, 'Dirección: ' . $direccion, 0, 1, 'L');

            // Título encima de la tabla
            //$pdf->Cell(0, 10, 'Ventas de Producto', 0, 1, 'C');

            // Encabezados de la tabla
            $pdf->SetFont('helvetica', 'B', 10); // Reduzco el tamaño de la fuente a 10
            $pdf->Cell(35, 7, 'Kilos', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Piezas', 1, 0, 'C');
            $pdf->Cell(70, 7, 'Producto', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Precio', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Subtotal', 1, 1, 'C');

            $totalDiaCliente = 0; // Inicializar total para el día y cliente actual

            // Datos de la base de datos
            $pdf->SetFont('helvetica', '', 10);
            $queryDetalle = "SELECT folio_venta, kilos, piezas, categoria, precio, cajas, subtotal FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = DATE('$fechaActual')";
            $resultDetalle = $conn->query($queryDetalle);
            while ($rowDetalle = $resultDetalle->fetch_assoc()) {
                $pdf->Cell(35, 7, $rowDetalle['kilos'], 1, 0, 'C');
                $pdf->Cell(25, 7, $rowDetalle['piezas'], 1, 0, 'C');
                $pdf->Cell(70, 7, $rowDetalle['categoria'], 1, 0, 'C');
                $pdf->Cell(25, 7, $rowDetalle['precio'], 1, 0, 'C');
                $pdf->Cell(35, 7, $rowDetalle['subtotal'], 1, 1, 'C');
                $totalDiaCliente += $rowDetalle['subtotal']; // Sumar al total del día y cliente
            }

            // Total debajo de la tabla de productos
            $pdf->Cell(0, 10, 'TOTAL: $' . number_format($totalDiaCliente, 2), 0, 1, 'R');

            // Agregar tabla adicional
            $pdf->Cell(0, 10, 'Canastilla', 0, 1, 'C');

            // Encabezados de la tabla adicional
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(25, 7, '', 1, 0, 'C');  // Celda vacía
            $pdf->Cell(55, 7, 'Saldo inicial', 1, 0, 'C');
            $pdf->Cell(55, 7, 'Cantidad recibida', 1, 0, 'C');
            $pdf->Cell(55, 7, 'Total Pendientes', 1, 1, 'C');

            // Contenido de la tabla adicional
            $pdf->SetFont('helvetica', '', 10);

            // Obtener el total de cajas desde la tabla 'cajas'
            $queryCajas = "SELECT total_cajas FROM cajas WHERE cliente = '$clienteSeleccionado'";
            $resultCajas = $conn->query($queryCajas);

            // Verificar si se encontró el total de cajas en la tabla 'cajas'
            if ($resultCajas->num_rows > 0) {
                $rowCajas = $resultCajas->fetch_assoc();
                $totalCajasCliente = $rowCajas['total_cajas'];
            } else {
                $totalCajasCliente = 0; // Establecer un valor predeterminado si no se encuentra el total de cajas
            }

            // Mostrar el total de cajas en la segunda celda
            $pdf->Cell(25, 7, 'Cajas', 1, 0, 'C');
            $pdf->Cell(55, 7, $totalCajasCliente, 1, 0, 'C');
            $pdf->Cell(55, 7, '', 1, 0, 'C');
            $pdf->Cell(55, 7, '', 1, 1, 'C');

            // Obtener el total de tapas desde la tabla 'cajas'
            $queryTapas = "SELECT total_tapas FROM cajas WHERE cliente = '$clienteSeleccionado'";
            $resultTapas = $conn->query($queryTapas);

            // Verificar si se encontró el total de tapas en la tabla 'cajas'
            if ($resultTapas->num_rows > 0) {
                $rowTapas = $resultTapas->fetch_assoc();
                $totalTapasCliente = $rowTapas['total_tapas'];
            } else {
                $totalTapasCliente = 0; // Establecer un valor predeterminado si no se encuentra el total de tapas
            }

            // Mostrar el total de tapas en la tercera celda
            $pdf->Cell(25, 7, 'Tapas', 1, 0, 'C');
            $pdf->Cell(55, 7, $totalTapasCliente, 1, 0, 'C');
            $pdf->Cell(55, 7, '', 1, 0, 'C');
            $pdf->Cell(55, 7, '', 1, 1, 'C');

            // Espacio entre la tabla y el texto adicional
            $pdf->Cell(0, 10, '', 0, 1);

            // Obtener el total del día y cliente actual
            $totalDiaCliente = 0;
            $queryDetalle = "SELECT kilos, piezas, categoria, precio, cajas, subtotal FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = DATE('$fechaActual')";
            $resultDetalle = $conn->query($queryDetalle);
            while ($rowDetalle = $resultDetalle->fetch_assoc()) {
                $totalDiaCliente += $rowDetalle['subtotal']; // Sumar al total del día y cliente
            }

            // Construir la cadena con el total
            $cadenaConTotal = 'Debo(mos) y pagare(mos) incondicionalmente por este pagare al orden de Francisco Gonzalez Flores, en Tehuacán Puebla la cantidad de $' . number_format($totalDiaCliente, 2) . '. Valor recibido a mi (nuestra) entera satisfacción. Y será exigible desde la fecha de vencimiento de este documento hasta el día de su liquidación, causará intereses moratorios al tipo de 3% mensual, pagaderos con el principal. En caso de incumplimiento de este pagare el beneficiario podrá demandar a su elección el cumplimiento del mismo en las ciudades Tecamachalco y/o Tehuacán.';

            // Mostrar la cadena completa en la MultiCell con justificación
            $pdf->MultiCell(0, 10, $cadenaConTotal, 0, 'J');

            // Espacio entre el texto adicional y la línea de firma
            $pdf->Cell(0, 10, '', 0, 1);

            // Línea de firma más corta
            $pdf->SetLineWidth(0.5); // Ancho de la línea
            $pdf->Line(15, $pdf->GetY(), 80, $pdf->GetY()); // Coordenadas de inicio y fin de la línea

            // Ajustar la posición vertical del texto "Firma del cliente"
            $firmaPosY = $pdf->GetY() + 5; // Ajustar según sea necesario

            // Texto "Firma del cliente"
            $pdf->SetXY(15, $firmaPosY); // Establecer la posición para el texto
            $pdf->Cell(0, 10, 'Firma del cliente', 0, 1, 'L');
        }

        $pdfFileName = 'Nota_' . $clienteSeleccionado . '_' . date('Ymd_His') . '.pdf'; // Agregar hora y fecha al nombre del archivo PDF
        $pdf->Output($pdfFileName, 'D');
    } else {
        echo 'No hay transacciones para el cliente y fecha actuales.';
    }

    $conn->close();
    exit;
}

?>


<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/exito.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Distribuidora | Notas</title>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Barra de navegación y sidebar aquí -->
        <div class="content-wrapper">
            <section class="content">
                <br>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Notas</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="get">
                                        <label for="cliente">Selecciona un cliente:</label>
                                        <select name="cliente" class="form-control" id="cliente">
                                            <?php
                                            // Conectar a la base de datos
                                            require('../config/conexion.php');

                                            // Obtener la lista de clientes desde la base de datos
                                            $queryClientes = "SELECT DISTINCT cliente FROM deudores";
                                            $resultClientes = $conn->query($queryClientes);

                                            // Generar opciones del menú desplegable
                                            while ($rowCliente = $resultClientes->fetch_assoc()) {
                                                echo "<option value='" . $rowCliente['cliente'] . "'>" . $rowCliente['cliente'] . "</option>";
                                            }

                                            // Cerrar la conexión a la base de datos
                                            $conn->close();
                                            ?>
                                        </select>
                                        <br><br>
                                        <button type="submit" class="btn btn-primary" name="generate_pdf">Descargar Nota</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>