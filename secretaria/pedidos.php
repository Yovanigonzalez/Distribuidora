<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/exito.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Distribuidora | Pedidos</title>
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
                                    <h3 class="card-title">Pedidos</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <label for="search_cliente">Buscar cliente:</label>
                                        <input type="text" name="search_cliente" class="form-control" id="search_cliente">
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="search_submit">Buscar</button>
                                    </form>

                                    <?php
                                    if (isset($_POST['search_submit'])) {
                                        // Conectar a la base de datos
                                        require('../config/conexion.php');

                                        $search_cliente = $_POST['search_cliente'];

                                        // Obtener la información del cliente
                                        $queryClienteInfo = "SELECT id, cliente, total_cajas, total_tapas FROM cajas WHERE cliente LIKE '%$search_cliente%'";
                                        $resultClienteInfo = $conn->query($queryClienteInfo);

                                        // Mostrar la información del cliente
                                        if ($resultClienteInfo->num_rows > 0) {
                                            echo "<div class='row'>
                                                    <div class='col-md-8'>";
                                            while ($rowClienteInfo = $resultClienteInfo->fetch_assoc()) {
                                                $cliente_nombre = $rowClienteInfo['cliente']; // Asignar el nombre del cliente
                                                echo "<h4>Cliente: " . $cliente_nombre . "</h4>";
                                                echo "<p>Cajas: " . $rowClienteInfo['total_cajas'] . "</p>";
                                                echo "<p>Tapas: " . $rowClienteInfo['total_tapas'] . "</p>";

                                                // Agregar campos de entrada para la resta
                                                echo "<form action='' method='post'>
                                                        <label for='restar_cajas'>Restar cajas:</label>
                                                        <input type='number' name='restar_cajas' class='form-control' required>
                                                        <label for='restar_tapas'>Restar tapas:</label>
                                                        <input type='number' name='restar_tapas' class='form-control' required>
                                                        <br>
                                                        <input type='hidden' name='cliente_id' value='".$rowClienteInfo['id']."'>
                                                        <button type='submit' class='btn btn-danger' name='restar_submit'>Restar</button>
                                                    </form>";
                                            }
                                            echo "</div></div>";
                                        } else {
                                            echo "<p>No se encontró información para el cliente '$search_cliente'.</p>";
                                        }

                                        // Cerrar la conexión a la base de datos
                                        $conn->close();
                                    }

                                    // Procesar la resta de cajas y tapas
                                    if (isset($_POST['restar_submit'])) {
                                        // Conectar a la base de datos
                                        require('../config/conexion.php');

                                        // Obtener valores del formulario
                                        $restar_cajas = $_POST['restar_cajas'];
                                        $restar_tapas = $_POST['restar_tapas'];
                                        $cliente_id = $_POST['cliente_id'];

                                        // Obtener el nombre del cliente seleccionado
                                        $queryClienteNombre = "SELECT cliente FROM cajas WHERE id = $cliente_id";
                                        $resultClienteNombre = $conn->query($queryClienteNombre);
                                        $rowClienteNombre = $resultClienteNombre->fetch_assoc();
                                        $cliente_nombre = $rowClienteNombre['cliente']; // Obtener el nombre del cliente

                                        // Obtener información actualizada del cliente antes de la resta
                                        $queryPrevInfo = "SELECT cliente, total_cajas, total_tapas FROM cajas WHERE id = $cliente_id";
                                        $resultPrevInfo = $conn->query($queryPrevInfo);
                                        $rowPrevInfo = $resultPrevInfo->fetch_assoc();

                                        // Actualizar la base de datos con la resta
                                        $updateQuery = "UPDATE cajas SET total_cajas = total_cajas - $restar_cajas, total_tapas = total_tapas - $restar_tapas WHERE id = $cliente_id";
                                        $conn->query($updateQuery);

                                        // Obtener información actualizada del cliente después de la resta
                                        $queryPostInfo = "SELECT cliente, total_cajas, total_tapas FROM cajas WHERE id = $cliente_id";
                                        $resultPostInfo = $conn->query($queryPostInfo);
                                        $rowPostInfo = $resultPostInfo->fetch_assoc();

                                        // Insertar detalles de la resta en una nueva tabla de la base de datos
                                        $fecha_actual = date('Y-m-d H:i:s');
                                        $insertDetailsQuery = "INSERT INTO detalles_resta (cliente_nombre, cliente_id, fecha_resta, cajas_restantes, tapas_restantes, prev_total_cajas, prev_total_tapas, post_total_cajas, post_total_tapas) VALUES ('$cliente_nombre', $cliente_id, '$fecha_actual', $restar_cajas, $restar_tapas, {$rowPrevInfo['total_cajas']}, {$rowPrevInfo['total_tapas']}, {$rowPostInfo['total_cajas']}, {$rowPostInfo['total_tapas']})";
                                        $conn->query($insertDetailsQuery);

                                        // Mostrar los nuevos resultados
                                        echo "<p>Actualizacion realizada. Nuevos resultados:</p>";
                                        echo "<div class='row'>
                                                <div class='col-md-8'>";
                                        echo "<h4>Cliente: " . $rowPostInfo['cliente'] . "</h4>";
                                        echo "<p>Cajas: " . $rowPostInfo['total_cajas'] . "</p>";
                                        echo "<p>Tapas: " . $rowPostInfo['total_tapas'] . "</p>";
                                        echo "</div></div>";

                                        // Cerrar la conexión a la base de datos
                                        $conn->close();
                                    }
                                    ?>
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

