
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
