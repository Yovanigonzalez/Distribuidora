<?php
function convertNumberToWords($number)
{
    $hyphen = '-';
    $conjunction = ' y ';
    $separator = ', ';
    $negative = 'negativo ';
    $decimal = ' punto ';
    $dictionary = array(
        0                   => 'cero',
        1                   => 'uno',
        2                   => 'dos',
        3                   => 'tres',
        4                   => 'cuatro',
        5                   => 'cinco',
        6                   => 'seis',
        7                   => 'siete',
        8                   => 'ocho',
        9                   => 'nueve',
        10                  => 'diez',
        11                  => 'once',
        12                  => 'doce',
        13                  => 'trece',
        14                  => 'catorce',
        15                  => 'quince',
        16                  => 'dieciséis',
        17                  => 'diecisiete',
        18                  => 'dieciocho',
        19                  => 'diecinueve',
        20                  => 'veinte',
        30                  => 'treinta',
        40                  => 'cuarenta',
        50                  => 'cincuenta',
        60                  => 'sesenta',
        70                  => 'setenta',
        80                  => 'ochenta',
        90                  => 'noventa',
        100                 => 'cien',
        1000                => 'mil',
        1000000             => 'millón',
        1000000000          => 'mil millones',
        1000000000000       => 'billón',
        1000000000000000    => 'mil billones',
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' cientos';
            if ($remainder) {
                $string .= $conjunction . convertNumberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convertNumberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

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

                $pdf->SetFont('helvetica', 'B', 16);
                $pdf->Cell(0, 10, 'CLIENTE: ' . $rowDeudor['cliente'], 0, 1, 'C');

                $imagePath = '../svg/LOGO.jpg';
                $pdf->Image($imagePath, 15, 20, 50, 30);

                $pdf->Cell(0, 10, 'Fecha:', 0, 1, 'R');
                $pdf->Cell(0, 10, $rowDeudor['fecha_hora'], 0, 1, 'R');

                $cliente = $rowDeudor['cliente'];
                $direccion = $rowDeudor['direccion'];

                $pdf->SetFont('helvetica', '', 12);
                $pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
                $pdf->Cell(0, 10, 'Datos del cliente', 0, 1, 'C');
                $pdf->Cell(0, 10, 'Nombre: ' . $cliente, 0, 1, 'L'); // Alineado a la izquierda
                $pdf->Cell(0, 10, 'Dirección: ' . $direccion, 0, 1, 'L'); // Alineado a la izquierda

                // Título encima de la tabla
                $pdf->Cell(0, 10, 'Ventas de Producto', 0, 1, 'C');

                // Encabezados de la tabla
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(25, 7, 'Kilos', 1, 0, 'C');
                $pdf->Cell(25, 7, 'Piezas', 1, 0, 'C');
                $pdf->Cell(50, 7, 'Categoría', 1, 0, 'C');
                $pdf->Cell(35, 7, 'Precio', 1, 0, 'C');
                $pdf->Cell(25, 7, 'Cajas', 1, 0, 'C');
                $pdf->Cell(35, 7, 'Subtotal', 1, 1, 'C');

                $totalDiaCliente = 0; // Inicializar total para el día y cliente actual

                // Datos de la base de datos
                $pdf->SetFont('helvetica', '', 12);
                $queryDetalle = "SELECT kilos, piezas, categoria, precio, cajas, subtotal FROM deudores WHERE cliente = '$clienteSeleccionado' AND DATE(fecha_hora) = '$fechaActual'";
                $resultDetalle = $conn->query($queryDetalle);
                while ($rowDetalle = $resultDetalle->fetch_assoc()) {
                    $pdf->Cell(25, 7, $rowDetalle['kilos'], 1, 0, 'C');
                    $pdf->Cell(25, 7, $rowDetalle['piezas'], 1, 0, 'C');
                    $pdf->Cell(50, 7, $rowDetalle['categoria'], 1, 0, 'C');
                    $pdf->Cell(35, 7, $rowDetalle['precio'], 1, 0, 'C');
                    $pdf->Cell(25, 7, $rowDetalle['cajas'], 1, 0, 'C');
                    $pdf->Cell(35, 7, $rowDetalle['subtotal'], 1, 1, 'C');
                    
                    $totalDiaCliente += $rowDetalle['subtotal']; // Sumar al total del día y cliente
                }

                // Total debajo de la tabla de productos
                $pdf->Cell(0, 10, 'TOTAL: $' . number_format($totalDiaCliente, 2), 0, 1, 'L');

                // Importe con letra
                $importeConLetra = convertNumberToWords($totalDiaCliente);
                //$pdf->Cell(0, 10, 'Importe con letra: ' . $importeConLetra, 0, 1, 'L');

                // Nueva tabla con Saldos
                $pdf->Cell(0, 10, 'Saldo Cajas', 0, 1, 'C');
                // Calcular Saldos
                $saldoInicial = $rowDeudor['totalCajas']; // Saldo Inicial desde la suma de 'cajas'
                $devolucion = 0; // Dejar en blanco, se calculará según sea necesario
                $saldoEntregado = 0; // Dejar en blanco, se calculará según sea necesario
                $saldoFinal = $saldoInicial - $devolucion + $totalDiaCliente;

                // Encabezados de la tabla de saldos
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(50, 7, 'Saldo Inicial', 1, 0, 'C');
                $pdf->Cell(50, 7, 'Última Devolución', 1, 0, 'C');
                $pdf->Cell(50, 7, 'Saldo Entregado', 1, 0, 'C');
                $pdf->Cell(50, 7, 'Saldo Final', 1, 1, 'C');

                // Datos de la tabla de saldos
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Cell(50, 7, $saldoInicial, 1, 0, 'C');
                $pdf->Cell(50, 7, '', 1, 0, 'C'); // Última Devolución, dejar en blanco
                $pdf->Cell(50, 7, $saldoEntregado, 1, 0, 'C'); // Saldo Entregado
                $pdf->Cell(50, 7, $saldoFinal, 1, 1, 'C');

                // Línea para firma del cliente
                $pdf->Cell(0, 10, 'Firma del Cliente: ________________________________', 0, 1, 'L');

                // Línea para firma del responsable
                $pdf->Cell(0, 10, 'Firma del Responsable: ________________________________', 0, 1, 'L');

                // Línea para firma de auditoría
                $pdf->Cell(0, 10, 'Firma de Auditoría: ________________________________', 0, 1, 'L');

                // Botón de descarga
                $pdf->Cell(0, 10, '', 0, 1); // Espacio en blanco
            }

            $pdfFileName = 'inventario_' . $clienteSeleccionado . '_'.$fechaActual.'.pdf';
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
