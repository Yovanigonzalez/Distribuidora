<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora | Agregar Producto</title>

    <!-- Agregar enlaces a los archivos CSS de Bootstrap y Krub font -->
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">

    <!-- Agregar estilo personalizado para la fuente -->
    <style>
        body {
            font-family: 'Krub', sans-serif;
        }

        .form-label, .form-control, .btn {
            font-family: 'Krub', sans-serif;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Barra de navegación y sidebar aquí -->

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <section class="content">
                <br>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Agregar Producto</h3>
                                </div>

                                <div class="card-body">
                                    <!-- Mostrar mensajes de éxito o error -->
                                    <?php
                                    if (isset($_GET['mensaje'])) {
                                        $tipoMensaje = isset($_GET['tipo']) ? $_GET['tipo'] : 'success';
                                        $mensaje = $_GET['mensaje'];
                                        echo '<div class="alert alert-' . $tipoMensaje . '">' . htmlspecialchars($mensaje) . '</div>';
                                    }
                                    ?>

                                    <!-- Formulario para agregar productos -->
                                    <form action="guardar_producto.php" method="POST">
                                        <div class="mb-3">
                                            <label for="nombreProducto" class="form-label">Nombre del Producto:</label>
                                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" oninput="this.value = this.value.toUpperCase();" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Agregar enlaces a los archivos JS de Bootstrap (jQuery y Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
