    <?php include 'menu.php'; ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Distribuidora | Entradas</title>

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
                                                <select class="form-control" name="categoria" id="categoria">
                                                    <option value="" disabled selected>Selecciona categoría</option>

                                                    <option value="ROSTICERO LH">R-LH</option>
                                                    <option value="ROSTICERO R-10">R-10</option>
                                                    <option value="ROSTICERO R-20">R-20</option>
                                                    <option value="ROSTICERO R-30">R-30</option>
                                                    <option value="ROSTICERO R-40">R-40</option>
                                                    <option value="ROSTICERO R-50">R-50</option>
                                                    <option value="ROSTICERO R-60">R-60</option>
                                                    <option value="ROSTICERO R-70">R-70</option>
                                                    <option value="ROSTICERO R-80">R-80</option>

                                                    <!-- NUEVOS CAMPOS -->

                                                    <option value="ROSTICERO NATURAL 1.0 - 1.1">NATURAL 1.0 - 1.1</option>
                                                    <option value="ROSTICERO NATURAL 1.1 - 1.2">NATURAL 1.1 - 1.2</option>
                                                    <option value="ROSTICERO NATURAL 1.2 - 1.3">NATURAL 1.2 - 1.3</option>
                                                    <option value="ROSTICERO NATURAL 1.3 - 1.4">NATURAL 1.3 - 1.4</option>
                                                    <option value="ROSTICERO NATURAL 1.4 - 1.5">NATURAL 1.4 - 1.5</option>
                                                    <option value="ROSTICERO NATURAL 1.5 - 1.6">NATURAL 1.5 - 1.6</option>
                                                    <option value="ROSTICERO NATURAL 1.6 - 1.7">NATURAL 1.6 - 1.7</option>
                                                    <option value="ROSTICERO NATURAL 1.7 - 1.8">NATURAL 1.7 - 1.8</option>

                                                    <!-- CAMPOS NUEVOS -->

                                                    <option value="ALA NATURAL">ALA NATURAL</option>
                                                    <option value="ALA MARINADA">ALA MARINADA</option>
                                                    <option value="CABEZA NATURAL">CABEZA NATURAL</option>
                                                    <option value="CABEZA ESCALDADA">CABEZA ESCALDADA</option>
                                                    <option value="PIERNA / MUSLO">PIERNA / MUSLO</option>
                                                    <option value="MOLLEJA">MOLLEJA</option>

                                                </select>
                                            </div>

                                            <!-- Productos de la categoría Entradas -->
                                            <div class="form-group">
                                                <label class="form-label" for="producto">Productos:</label>
                                                <select class="form-control" name="producto" id="producto">
                                                    <option value="" disabled selected>Selecciona producto</option>

                                                    <option value="ROSTICERO LH">R-LH</option>
                                                    <option value="ROSTICERO R-10">R-10</option>
                                                    <option value="ROSTICERO R-20">R-20</option>
                                                    <option value="ROSTICERO R-30">R-30</option>
                                                    <option value="ROSTICERO R-40">R-40</option>
                                                    <option value="ROSTICERO R-50">R-50</option>
                                                    <option value="ROSTICERO R-60">R-60</option>
                                                    <option value="ROSTICERO R-70">R-70</option>
                                                    <option value="ROSTICERO R-80">R-80</option>

                                                    <!-- NUEVOS CAMPOS -->


                                                    <option value="ROSTICERO NATURAL 1.0 - 1.1">NATURAL 1.0 - 1.1</option>
                                                    <option value="ROSTICERO NATURAL 1.1 - 1.2">NATURAL 1.1 - 1.2</option>
                                                    <option value="ROSTICERO NATURAL 1.2 - 1.3">NATURAL 1.2 - 1.3</option>
                                                    <option value="ROSTICERO NATURAL 1.3 - 1.4">NATURAL 1.3 - 1.4</option>
                                                    <option value="ROSTICERO NATURAL 1.4 - 1.5">NATURAL 1.4 - 1.5</option>
                                                    <option value="ROSTICERO NATURAL 1.5 - 1.6">NATURAL 1.5 - 1.6</option>
                                                    <option value="ROSTICERO NATURAL 1.6 - 1.7">NATURAL 1.6 - 1.7</option>
                                                    <option value="ROSTICERO NATURAL 1.7 - 1.8">NATURAL 1.7 - 1.8</option>

                                                    <!-- CAMPOS NUEVOS -->

                                                    <option value="ALA NATURAL">ALA NATURAL</option>
                                                    <option value="ALA MARINADA">ALA MARINADA</option>
                                                    <option value="CABEZA NATURAL">CABEZA NATURAL</option>
                                                    <option value="CABEZA ESCALDADA">CABEZA ESCALDADA</option>
                                                    <option value="PIERNA / MUSLO">PIERNA / MUSLO</option>
                                                    <option value="MOLLEJA">MOLLEJA</option>

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

    </body>
    </html>