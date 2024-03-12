<?php

if (isset($_GET['generate_pdf'])) {
    if (isset($_GET['cliente'])) {
        require('../tcpdf/tcpdf.php');
        require('../config/conexion.php');

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        date_default_timezone_set('America/Mexico_City');

        $clienteSeleccionado = $conn->real_escape_string($_GET['cliente']);
        $fechaActual = date('Y-m-d');
        
        $queryDeudores = "SELECT cliente, direccion, fecha_hora, SUM(subtotal) as total, SUM(cajas) as totalCajas FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = '$fechaActual' GROUP BY fecha_hora";
        $resultDeudores = $conn->query($queryDeudores);

        if ($resultDeudores->num_rows > 0) {
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);  // Orientación vertical
            $pdf->SetCreator('Your Name');
            $pdf->SetAuthor('Your Name');
            $pdf->SetTitle('Notas PDF - Cliente: ' . $clienteSeleccionado);
            $pdf->SetSubject('Notas PDF');
            $pdf->SetKeywords('Nota, PDF, Download');

            while ($rowDeudor = $resultDeudores->fetch_assoc()) {
                $pdf->AddPage();

                $pdf->SetFont('helvetica', 'B', 14); // Tamaño de fuente 14 para encabezados

                $imagePath = '../svg/LOGO.jpg';
                $pdf->Image($imagePath, 15, 20, 50, 30);

                $pdf->SetFont('helvetica', '', 10); // Tamaño de fuente 10 para el contenido

                $pdf->Cell(0, 10, 'Fecha:', 0, 1, 'R');
                $pdf->SetFont('helvetica', '', 8); // Reduzco el tamaño de la fuente a 8 para "Fecha"
                $pdf->Cell(0, 10, $rowDeudor['fecha_hora'], 0, 1, 'R');
                $pdf->SetFont('helvetica', '', 10); // Restauro el tamaño de la fuente a 10

                $cliente = $rowDeudor['cliente'];
                $direccion = $rowDeudor['direccion'];

                $pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
                $pdf->Cell(0, 10, 'Datos del cliente', 0, 1, 'C');
                $pdf->Cell(0, 10, 'Nombre: ' . $cliente, 0, 1, 'L'); // Alineado a la izquierda
                $pdf->Cell(0, 10, 'Dirección: ' . $direccion, 0, 1, 'L'); // Alineado a la izquierda

                // Título encima de la tabla
                $pdf->Cell(0, 10, 'Ventas de Producto', 0, 1, 'C');

                // Encabezados de la tabla
                $pdf->SetFont('helvetica', 'B', 10); // Reduzco el tamaño de la fuente a 10
                $pdf->Cell(35, 7, 'Kilos', 1, 0, 'C');  // Aumentar el ancho a 30
                $pdf->Cell(25, 7, 'Piezas', 1, 0, 'C'); // Aumentar el ancho a 30
                $pdf->Cell(70, 7, 'Categoría', 1, 0, 'C'); // Aumentar el ancho a 40
                $pdf->Cell(25, 7, 'Precio', 1, 0, 'C');  // Aumentar el ancho a 30
                $pdf->Cell(35, 7, 'Subtotal', 1, 1, 'C'); // Aumentar el ancho a 30

                $totalDiaCliente = 0; // Inicializar total para el día y cliente actual

                // Datos de la base de datos
                $pdf->SetFont('helvetica', '', 10);
                $queryDetalle = "SELECT kilos, piezas, categoria, precio, cajas, subtotal FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = '$fechaActual'";
                $resultDetalle = $conn->query($queryDetalle);
                while ($rowDetalle = $resultDetalle->fetch_assoc()) {
                    $pdf->Cell(35, 7, $rowDetalle['kilos'], 1, 0, 'C'); // Aumentar el ancho a 30
                    $pdf->Cell(25, 7, $rowDetalle['piezas'], 1, 0, 'C'); // Aumentar el ancho a 30
                    $pdf->Cell(70, 7, $rowDetalle['categoria'], 1, 0, 'C'); // Aumentar el ancho a 40
                    $pdf->Cell(25, 7, $rowDetalle['precio'], 1, 0, 'C'); // Aumentar el ancho a 30
                    $pdf->Cell(35, 7, $rowDetalle['subtotal'], 1, 1, 'C'); // Aumentar el ancho a 30
                    $totalDiaCliente += $rowDetalle['subtotal']; // Sumar al total del día y cliente
                }

                // Total debajo de la tabla de productos
                $pdf->Cell(0, 10, 'TOTAL: $' . number_format($totalDiaCliente, 2), 0, 1, 'L');

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
                $pdf->Cell(55, 7, $totalCajasCliente, 1, 0, 'C');  // Puedes dejar vacía esta celda o poner el valor adecuado
                $pdf->Cell(55, 7, '', 1, 0, 'C');  // Mostrar el total de cajas en la segunda celda
                $pdf->Cell(55, 7, '', 1, 1, 'C');  // Puedes dejar vacía esta celda o poner el valor adecuado

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
                $pdf->Cell(55, 7, $totalTapasCliente, 1, 0, 'C');  // Puedes dejarlo vacío o poner el valor adecuado
                $pdf->Cell(55, 7, '', 1, 0, 'C');  // Mostrar el total de tapas en la tercera celda
                $pdf->Cell(55, 7, '', 1, 1, 'C');  // Puedes dejarlo vacío o poner el valor adecuado
            }

            $pdfFileName = 'Nota_' . $clienteSeleccionado . '_' . $fechaActual . '.pdf';
            $pdf->Output($pdfFileName, 'D');
        } else {
            echo 'No hay transacciones para el cliente y fecha actuales.';
        }

        $conn->close();
        exit;
    }
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
                                        <button type="submit" class="btn btn-primary" name="generate_pdf">Descargar Inventario</button>
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
