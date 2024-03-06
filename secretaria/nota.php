<?php
if (isset($_GET['generate_pdf'])) {
    require('../tcpdf/tcpdf.php');
    require('../config/conexion.php');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

    $pdf->SetCreator('Your Name');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Inventario PDF');
    $pdf->SetSubject('Inventario PDF');
    $pdf->SetKeywords('Inventario, PDF, Download');

    $pdf->AddPage();

    date_default_timezone_set('America/Mexico_City');
    
    $currentDateTime = date('Y-m-d h:i a', time());

    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Fecha y Hora: ' . $currentDateTime, 0, 1, 'C');

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Inventario de Pollos la Fortaleza', 0, 1, 'C');

    $query = "SELECT id, categoria, producto, stock FROM entradas";
    $result = $conn->query($query);

    $header = array('ID', 'Categoría', 'Producto', 'Stock');
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = array($row['id'], $row['categoria'], $row['producto'], $row['stock']);
    }

    $pdf->SetFont('helvetica', '', 12);
    $tableWidth = $pdf->getPageWidth() - 20;
    $cellWidth = $tableWidth / count($header);

    $pdf->SetFillColor(248, 91, 255);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0);
    $pdf->SetFont('helvetica', 'B', 12);

    foreach ($header as $col) {
        $pdf->Cell($cellWidth, 10, $col, 1, 0, 'C', 1);
    }
    $pdf->Ln();

    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('helvetica', '', 12);

    $yellow = [255, 255, 102];
    $red = [255, 102, 102];
    $lightBlue = [173, 216, 230];

    foreach ($data as $row) {
        $bgColor = null;

        if ($row[3] >= 11 && $row[3] <= 20) {
            $bgColor = $yellow;
        } elseif ($row[3] <= 10) {
            $bgColor = $red;
        } elseif ($row[3] >= 21 && $row[3] <= 30) {
            $bgColor = $lightBlue;
        }

        if ($bgColor) {
            $pdf->SetFillColor($bgColor[0], $bgColor[1], $bgColor[2]);
        }

        foreach ($row as $col) {
            $pdf->Cell($cellWidth, 10, $col, 1, 0, 'C', 1);
        }

        $pdf->SetFillColor(255);
        $pdf->Ln();
    }

    $pdf->Output('inventario.pdf', 'D');

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
    <title>Inventario</title>
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
                                    <h3 class="card-title">Inventario</h3>
                                </div>
                                <div class="card-body">
                                    <a class="btn btn-primary" href="?generate_pdf=1" target="_blank">Descargar Inventario</a>
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


