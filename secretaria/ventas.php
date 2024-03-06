<?php include 'menu.php'; ?>

<?php
include '../config/conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los clientes de la tabla 'clientes'
$sqlClientes = "SELECT id, nombre, direccion FROM clientes";
$resultClientes = $conn->query($sqlClientes);


// Consulta SQL para obtener todas las categorías de la tabla 'productos'
$sqlCategorias = "SELECT DISTINCT categoria FROM entradas";
$resultCategorias = $conn->query($sqlCategorias);

// Consulta SQL para obtener todos los productos de la tabla 'productos'
$sqlProductos = "SELECT id, categoria, stock, producto, fecha_registro FROM entradas";
$resultProductos = $conn->query($sqlProductos);

// Manejo de errores
if (!$resultCategorias || !$resultProductos) {
    die("Error en la consulta SQL: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Logo -->
    <link rel="shortcut icon" type="image/x-icon" href="../log/log.png">
    <title>Distribuidora | Punto de Venta</title>

    <style>
        <style>
    .categoria-item {
        cursor: pointer;
    }

    .categoria-item:hover,
    .categoria-item.selected {
        background-color: #f2f2f2;
    }
</style>

    </style>
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
                                                <label for="kilos">KILOS</label>
                                                <input type="text" class="form-control" name="kilos" id="kilos" placeholder="Ingrese la cantidad en kilos">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="piezas">PIEZAS</label>
                                                <input type="text" class="form-control" name="piezas" id="piezas" placeholder="Ingrese la cantidad de piezas">
                                            </div>

                                            <div class="form-group col-md-4">
                                            <label for="producto">Producto</label>
                                            <!-- Campo de búsqueda para categorías -->
                                            <input type="text" id="searchCategorias" class="form-control" onkeyup="searchCategorias()" placeholder="Buscar categorías...">
                                            <!-- Lista de categorías (inicialmente oculta) -->
                                            <br>
                                            <ul id="categoriasList" style="display: none;">
                                                <?php
                                                if ($resultCategorias->num_rows > 0) {
                                                    while ($rowCategoria = $resultCategorias->fetch_assoc()) {
                                                        echo '<li class="categoria-item" onclick="selectCategoria(\'' . $rowCategoria['categoria'] . '\')">' . $rowCategoria['categoria'] . '</li>';
                                                    }
                                                } else {
                                                    echo "No se encontraron categorías en la tabla 'productos'.";
                                                }
                                                ?>
                                            </ul>
                                            <!-- Campo de categoría seleccionada -->
                                            <input type="text" id="categoria" class="form-control" name="categoria" placeholder="Categoría" readonly>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="precio">$PRECIO</label>
                                                <input type="text" class="form-control" name="precio" id="precio" placeholder="Ingrese el precio">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="cajas">CAJAS</label>
                                                <input type="text" class="form-control" name="cajas" id="cajas" placeholder="Ingrese la cantidad de cajas">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-success" id="agregarBtn">Agregarlo</button>
                                </div>

                                <!-- Tabla para mostrar los elementos agregados -->
                                
                                <div class="container mt-4">
  
                                    <table class="table" id="tablaProductos">
                                        <thead>

                                            <tr>
                                                <th scope="col">KILOS</th>
                                                <th scope="col">PIEZAS</th>
                                                <th scope="col">CATEGORÍA</th>
                                                <th scope="col">PRECIO</th>
                                                <th scope="col">CAJAS</th>
                                                <th scope="col">SUBTOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right"><h2>Total</h2></th>
                                                <th id="total"><h2>$0.00</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <br>
                                    <button class="btn btn-primary btn-cobrar">Cobrar </button>
                                    <br><br>
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

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var agregarBtn = document.getElementById("agregarBtn");
        agregarBtn.addEventListener("click", agregarProducto);

        function agregarProducto() {
            var kilosInput = document.getElementById("kilos");
            var piezasInput = document.getElementById("piezas");
            var categoriaInput = document.getElementById("categoria");
            var precioInput = document.getElementById("precio");
            var cajasInput = document.getElementById("cajas");

            var kilos = parseFloat(kilosInput.value.trim());
            var piezas = parseInt(piezasInput.value.trim());
            var categoria = categoriaInput.value.trim();
            var precio = parseFloat(precioInput.value.trim());
            var cajas = parseInt(cajasInput.value.trim());

            if (isNaN(kilos) || isNaN(piezas) || isNaN(precio) || isNaN(cajas)) {
                alert("Por favor, ingrese valores numéricos válidos en los campos correspondientes.");
                return;
            }

            // Calcular el subtotal multiplicando kilos por precio
            var subtotal = kilos * precio;

            // Crear una nueva fila en la tabla
            var tablaProductos = document.getElementById("tablaProductos").getElementsByTagName('tbody')[0];
            var fila = tablaProductos.insertRow();

            // Insertar celdas con los valores proporcionados
            var celdaKilos = fila.insertCell(0);
            var celdaPiezas = fila.insertCell(1);
            var celdaCategoria = fila.insertCell(2);
            var celdaPrecio = fila.insertCell(3);
            var celdaCajas = fila.insertCell(4);
            var celdaSubtotal = fila.insertCell(5);

            // Asignar valores a las celdas
            celdaKilos.innerHTML = kilos.toFixed(2);
            celdaPiezas.innerHTML = piezas;
            celdaCategoria.innerHTML = categoria;
            celdaPrecio.innerHTML = precio.toFixed(2);
            celdaCajas.innerHTML = cajas;
            celdaSubtotal.innerHTML = subtotal.toFixed(2);

            // Limpiar los campos de entrada
            kilosInput.value = "";
            piezasInput.value = "";
            categoriaInput.value = "";
            precioInput.value = "";
            cajasInput.value = "";

            // Calcular y actualizar el total
            calcularTotal();
        }

        function calcularTotal() {
            var tablaProductos = document.getElementById("tablaProductos");
            var filas = tablaProductos.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            var total = 0;

            for (var i = 0; i < filas.length; i++) {
                var subtotal = parseFloat(filas[i].getElementsByTagName('td')[5].innerHTML);
                total += subtotal;
            }

            document.getElementById("total").innerHTML = '$' + total.toFixed(2);
        }
    });
</script>


    <script>
    var categoriasList = document.getElementById("categoriasList");
    var categoriasItems = categoriasList.getElementsByTagName("li");
    var selectedCategoriaIndex = -1; // Índice del elemento seleccionado en la lista

    // Función para resaltar el elemento seleccionado
    function highlightSelectedCategoria(index) {
        // Elimina la clase 'selected' de todos los elementos de la lista
        for (var i = 0; i < categoriasItems.length; i++) {
            categoriasItems[i].classList.remove("selected");
        }

        // Añade la clase 'selected' al elemento seleccionado
        if (index >= 0 && index < categoriasItems.length) {
            categoriasItems[index].classList.add("selected");
        }
    }

    // Función para seleccionar una categoría y autocompletar el campo correspondiente
    function selectCategoria(categoria) {
        var categoriaInput = document.getElementById("categoria");
        categoriaInput.value = categoria;
        categoriasList.style.display = "none";
        document.getElementById("searchCategorias").value = ""; // Limpia el campo de búsqueda
    }

    // Evento de teclado para navegar por los resultados de búsqueda con las flechas
    document.getElementById("searchCategorias").addEventListener("keydown", function(event) {
        if (event.key === "ArrowUp") {
            selectedCategoriaIndex = Math.max(selectedCategoriaIndex - 1, 0);
            highlightSelectedCategoria(selectedCategoriaIndex);
            event.preventDefault();
        } else if (event.key === "ArrowDown") {
            selectedCategoriaIndex = Math.min(selectedCategoriaIndex + 1, categoriasItems.length - 1);
            highlightSelectedCategoria(selectedCategoriaIndex);
            event.preventDefault();
        } else if (event.key === "Enter" && selectedCategoriaIndex >= 0) {
            selectCategoria(categoriasItems[selectedCategoriaIndex].innerText);
            selectedCategoriaIndex = -1; // Reinicia el índice seleccionado
            event.preventDefault();
        }
    });

    // Función para realizar la búsqueda y mostrar/ocultar elementos según el término de búsqueda
    function searchCategorias() {
        var input = document.getElementById("searchCategorias");
        var searchTerm = input.value.trim().toLowerCase();

        for (var i = 0; i < categoriasItems.length; i++) {
            var categoriaText = categoriasItems[i].textContent || categoriasItems[i].innerText;
            if (categoriaText.toLowerCase().indexOf(searchTerm) > -1) {
                categoriasItems[i].style.display = "block";
            } else {
                categoriasItems[i].style.display = "none";
            }
        }

        categoriasList.style.display = "block";
        selectedCategoriaIndex = -1; // Reinicia el índice seleccionado
    }
</script>


</body>
</html>
