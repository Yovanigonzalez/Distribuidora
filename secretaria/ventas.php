<?php include 'menu.php'; ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/ti.css"> <!-- Enlaza la hoja de estilo externa -->
    <link rel="stylesheet" href="../css/bus.css"> <!-- Enlaza la hoja de estilo externa -->
    <!-- Logo -->
    <link rel="shortcut icon" type="image/x-icon" href="../log/log.png">
    <title>Punto de Venta</title>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <br>
                <div class="container-fluid">
                    <div class="row">
                        <!-- Columna del Punto de Venta -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Punto de Venta</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Campos de búsqueda -->
                                    <div class="container mt-4">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="cantidad">Buscar o Escribir Nombre</label>
                                                <input type="number" class="form-control" name="cantidad" id="cantidad" placeholder="Ingrese el nombre" min="1">
                                            </div>


                                            <div class="form-group col-md-4">
                                                <label for="envase">Buscar Producto</label>
                                                <input type="text" class="form-control" name="envase" id="envaseInput" placeholder="Ingrese el nombre del producto">
                                                <div id="resultadoBusqueda"></div>
                                            </div>


                                        </div>
    Q1

                                        <button type="button" class="btn btn-success" id="agregarBtn">Agregarlo</button>
                                    </div>



                                    <!-- Tabla para mostrar los elementos agregados -->
                                    <div class="container mt-4">
                                        <table class="table" id="tablaProductos">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Número</th>
                                                    <th scope="col">Envase</th>
                                                    <th scope="col">Tendencia</th>
                                                    <th scope="col">Categoría</th>
                                                    <th scope="col">Precio</th>
                                                    <th scope="col">Gramos</th>
                                                    <th scope="col">Stock</th>
                                                    <th scope="col">Nuevo Stock</th><!-- Nueva columna -->
                                                    <th scope="col">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                        </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" class="text-right"><h2>Total</h2></th>
                                                    <th id="total"><h2>$0.00</h2></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!-- Bloque para Método de Pago -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="metodoPago">Método de Pago</label>
                                                <select class="form-control" name="metodoPago" id="metodoPago">
                                                    <option>Selecciona el Método de Pago</option>
                                                    <option value="Efectivo">Efectivo</option>
                                                    <option value="Tarjeta/Debito/Credito">Tarjeta/Débito/Crédito</option>
                                                    <option value="Transferencia">Transferencia</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Bloque para Dinero Recibido y Cambio a Dar -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dineroRecibido">Dinero Recibido</label>
                                                <input type="number" class="form-control" name="dineroRecibido" id="dineroRecibido" placeholder="Monto recibido" min="0" step="0.01">
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="cambioADar">Cambio a Dar</label>
                                                <input type="text" class="form-control" id="cambioADar" readonly>
                                            </div>
                                        </div>

                                            <button class="btn btn-primary btn-cobrar">Cobrar </button>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        

                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

