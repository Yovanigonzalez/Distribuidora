<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
                                    <h3 class="card-title">Agregar Entradas</h3>
                                </div>

                                <div class="card-body">
                                    <!-- Display success message if it exists -->
                                    <?php
                                            // Verificar si se debe mostrar un mensaje de éxito o error
                                            if (isset($_GET['success']) && $_GET['success'] === 'true') {
                                                echo '<div class="alert alert-success" role="alert">Los datos se han guardado correctamente.</div>';
                                            } elseif (isset($_GET['success']) && $_GET['success'] === 'false') {
                                                echo '<div class="alert alert-danger" role="alert">Error al guardar los datos: ' . $_GET['error_message'] . '</div>';
                                            }
                                        ?>
                                    <!-- Display error message if it exists -->


                                    <form method="post" action="g_entradas.php">
                                        <!-- Categoría: Entradas -->
                                        <div class="form-group">
                                            <label class="form-label" for="categoria">Categoría:</label>
                                            <select class="form-select" name="categoria" id="categoria">
                                                <option value="" disabled selected>Selecciona categoría</option>
                                                <option value="Pollo">Pollo</option>
                                                <option value="Alitas">Alitas</option>
                                                <option value="Tenders">Tenders</option>
                                                <option value="Crepas">Crepas</option>
                                                <option value="Hamburguesas">Hamburguesas</option>
                                                <option value="Costillas">Costillas</option>
                                                <option value="Guarniciones">Guarniciones</option>
                                                <option value="Mixiotes">Mixiotes</option>
                                                <option value="Bebidas">Bebidas</option>
                                            </select>
                                        </div>

                                        <!-- Productos de la categoría Entradas -->
                                        <div class="form-group">
                                            <label class="form-label" for="producto">Productos:</label>
                                            <select class="form-select" name="producto" id="producto">
                                                <option value="" disabled selected>Selecciona producto</option>
                                                <option value="Pollo">Pollo</option>
                                                <option value="Alitas">Alitas</option>
                                                <option value="Tenders">Tenders</option>
                                                <option value="Crepas">Crepas</option>
                                                <option value="Hamburguesas">Hamburguesas</option>
                                                <option value="Costillas">Costillas</option>
                                                <option value="Guarniciones">Guarniciones</option>
                                                <option value="Mixiotes">Mixiotes</option>
                                                <option value="Bebidas">Bebidas</option>

                                            </select>
                                        </div>

                                        <!-- Stock -->
                                        <div class="form-group">
                                            <label class="form-label" for="stock">Stock:</label>
                                            <input type="number" class="form-control" name="stock" id="stock" min="0" required>
                                        </div>

                                        <!-- Botón para agregar entrada -->
                                        <button type="submit" class="btn btn-primary">Agregar entrada</button>
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