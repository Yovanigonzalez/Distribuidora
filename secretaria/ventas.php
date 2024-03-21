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
        .categoria-item {
            cursor: pointer;
        }

        .categoria-item:hover,
        .categoria-item.selected {
            background-color: #f2f2f2;
        }
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
                                                <label for="cliente">Cliente:</label>
                                                <!-- Campo de búsqueda para clientes -->
                                                <input type="text" id="searchClientes" class="form-control" onkeyup="searchClientes()" placeholder="Buscar clientes...">
                                                <!-- Lista de clientes (inicialmente oculta) -->
                                                <br>
                                                <ul id="clientesList" style="display: none;">
                                                    <?php
                                                    if ($resultClientes->num_rows > 0) {
                                                        while ($rowCliente = $resultClientes->fetch_assoc()) {
                                                            echo '<li class="cliente-item" onclick="selectCliente(\'' . $rowCliente['nombre'] . '\', \'' . $rowCliente['direccion'] . '\')">' . $rowCliente['nombre'] . ' - ' . $rowCliente['direccion'] . '</li>';
                                                        }
                                                    } else {
                                                        echo "No se encontraron clientes en la tabla 'clientes'.";
                                                    }
                                                    ?>
                                                </ul>
                                                <!-- Campos de cliente y dirección seleccionados -->
                                                <input type="text" id="cliente" class="form-control" name="cliente" placeholder="Cliente" readonly>
                                                <input type="text" id="direccion" class="form-control" name="direccion" placeholder="Dirección" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="kilos">Kilos:</label>
                                                <input type="text" class="form-control" name="kilos" id="kilos" placeholder="Cantidad en kilos">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="piezas">Piezas:</label>
                                                <input type="text" class="form-control" name="piezas" id="piezas" placeholder="Cantidad de piezas">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="producto">Producto:</label>
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

                                            <div class="form-group col-md-2">
                                                <label for="precio">Precio:</label>
                                                <input type="text" class="form-control" name="precio" id="precio" placeholder="Ingrese el precio">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="cajas">Cajas:</label>
                                                <input type="text" class="form-control" name="cajas" id="cajas" placeholder="Cantidad de cajas">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="tapas">Tapas:</label>
                                                <input type="text" class="form-control" name="tapas" id="tapas" placeholder="Cantidad de tapas">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="metodoPago">Método de Pago:</label>
                                                <select class="form-control" name="metodoPago" id="metodoPago">
                                                    <option>Selecciona el Método de Pago</option>
                                                    <option value="contado">Decontado</option>
                                                    <option value="credito">Crédito</option>
                                                </select>
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
                                                <th scope="col">Kilos</th>
                                                <th scope="col">Piezas</th>
                                                <th scope="col">Descripcion</th>
                                                <th scope="col">Precio</th>
                                                <th scope="col">Cajas</th>
                                                <th scope="col">Tapas</th>
                                                <th scope="col">Pago</th>
                                                <th scope="col">Subtotal</th>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Direccion</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="7" class="text-right"><label>Total</label></th>
                                                <th id="total"><label>$0.00</label></th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <br>
                                    <button class="btn btn-primary btn-cobrar">Cobrar</button>
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
    // Evento de clic para el botón "Cobrar"
    document.querySelector(".btn-cobrar").addEventListener("click", function () {
        // Obtener datos de la tabla
        var tablaProductos = document.getElementById("tablaProductos").getElementsByTagName('tbody')[0];
        var filas = tablaProductos.getElementsByTagName('tr');

        // Verificar si hay productos en la tabla
        if (filas.length === 0) {
            alert("No hay productos para cobrar.");
            return;
        }

        // Crear un objeto con los datos para enviar
        var datos = [];
        var total = 0; // Variable para almacenar el total
        for (var i = 0; i < filas.length; i++) {
            var fila = filas[i].getElementsByTagName('td');
            var producto = {
                kilos: fila[0].innerText,
                piezas: fila[1].innerText,
                categoria: fila[2].innerText,
                precio: fila[3].innerText,
                cajas: fila[4].innerText,
                tapas: fila[5].innerText,
                metodoPago: fila[6].innerText, // Agregar el método de pago
                subtotal: fila[7].innerText,
                cliente: fila[8].innerText,
                direccion: fila[9].innerText // Ajustar el índice para la dirección
            };
            datos.push(producto);
            total += parseFloat(fila[7].innerText); // Sumar el subtotal al total
        }

        // Añadir el total al objeto de datos
        datos.total = total;

        // Realizar una solicitud AJAX para llamar a procesar_cobro.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "procesar_cobro.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Convertir el objeto a una cadena JSON y enviarlo
        var jsonData = JSON.stringify(datos);

        // Añadir un parámetro adicional para indicar la tabla de destino
        xhr.send("datos=" + jsonData + "&tabla_destino=ventas");

        // Manejar la respuesta del servidor
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Manejar la respuesta del servidor si es necesario
                alert(xhr.responseText);
                // Puedes realizar otras acciones después de cobrar si es necesario
            } else {
                // Manejar errores de la solicitud AJAX
                alert("Error al procesar el cobro. Por favor, inténtelo nuevamente.");
            }
        };
    });

    var agregarBtn = document.getElementById("agregarBtn");
    agregarBtn.addEventListener("click", agregarProducto);

    var clienteActual = "";
    var direccionActual = "";

    function agregarProducto() {
        var kilosInput = document.getElementById("kilos");
        var piezasInput = document.getElementById("piezas");
        var categoriaInput = document.getElementById("categoria");
        var precioInput = document.getElementById("precio");
        var cajasInput = document.getElementById("cajas");
        var tapasInput = document.getElementById("tapas");
        var metodoPagoInput = document.getElementById("metodoPago"); // Nuevo campo de método de pago

        var kilos = parseFloat(kilosInput.value.trim());
        var piezas = parseFloat(piezasInput.value.trim());
        var categoria = categoriaInput.value.trim();
        var precio = parseFloat(precioInput.value.trim());
        var cajas = parseInt(cajasInput.value.trim());
        var tapas = parseInt(tapasInput.value.trim());
        var metodoPago = metodoPagoInput.value.trim(); // Obtener el método de pago seleccionado

        if (isNaN(kilos) || isNaN(piezas) || isNaN(precio) || isNaN(cajas) || isNaN(tapas)) {
            alert("Por favor, ingrese valores numéricos válidos en los campos correspondientes.");
            return;
        }

        // Calcular el subtotal multiplicando kilos y precio
        var subtotal = kilos * precio;

        // Obtener el nombre del cliente y la dirección
        clienteActual = document.getElementById("cliente").value;
        direccionActual = document.getElementById("direccion").value;

        // Crear una nueva fila en la tabla
        var tablaProductos = document.getElementById("tablaProductos").getElementsByTagName('tbody')[0];
        var fila = tablaProductos.insertRow();

        // Insertar celdas con los valores proporcionados
        var celdaKilos = fila.insertCell(0);
        var celdaPiezas = fila.insertCell(1);
        var celdaCategoria = fila.insertCell(2);
        var celdaPrecio = fila.insertCell(3);
        var celdaCajas = fila.insertCell(4);
        var celdaTapas = fila.insertCell(5);
        var celdaMetodoPago = fila.insertCell(6); // Nueva celda para el método de pago
        var celdaSubtotal = fila.insertCell(7);
        var celdaCliente = fila.insertCell(8);
        var celdaDireccion = fila.insertCell(9); // Ajustar el índice para la dirección

        // Asignar valores a las celdas
        celdaKilos.innerHTML = kilos.toFixed(2);
        celdaPiezas.innerHTML = piezas;
        celdaCategoria.innerHTML = categoria;
        celdaPrecio.innerHTML = precio.toFixed(2);
        celdaCajas.innerHTML = cajas;
        celdaTapas.innerHTML = tapas;
        celdaMetodoPago.innerHTML = metodoPago; // Asignar el método de pago
        celdaSubtotal.innerHTML = subtotal.toFixed(2);
        celdaCliente.innerHTML = clienteActual;
        celdaDireccion.innerHTML = direccionActual;

        // Limpiar los campos de entrada
        kilosInput.value = "";
        piezasInput.value = "";
        categoriaInput.value = "";
        precioInput.value = "";
        cajasInput.value = "";
        tapasInput.value = "";
        metodoPagoInput.value = "Selecciona el Método de Pago"; // Restaurar la opción predeterminada

        // Calcular y actualizar el total
        calcularTotal();
    }

    function calcularTotal() {
        var tablaProductos = document.getElementById("tablaProductos");
        var filas = tablaProductos.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        var total = 0;

        for (var i = 0; i < filas.length; i++) {
            var subtotal = parseFloat(filas[i].getElementsByTagName('td')[7].innerHTML);
            total += subtotal;
        }

        document.getElementById("total").innerHTML = '$' + total.toFixed(2);
    }
});
</script>



<script>
var clientesList = document.getElementById("clientesList");
var clientesItems = clientesList.getElementsByTagName("li");
var selectedClienteIndex = -1; // Índice del elemento seleccionado en la lista

// Función para resaltar el elemento seleccionado
function highlightSelectedCliente(index) {
    // Elimina la clase 'selected' de todos los elementos de la lista
    for (var i = 0; i < clientesItems.length; i++) {
        clientesItems[i].classList.remove("selected");
    }

    // Añade la clase 'selected' al elemento seleccionado
    if (index >= 0 && index < clientesItems.length) {
        clientesItems[index].classList.add("selected");
    }
}

// Función para seleccionar un cliente y autocompletar los campos correspondientes
function selectCliente(cliente, direccion) {
    var clienteInput = document.getElementById("cliente");
    var direccionInput = document.getElementById("direccion");
    clienteInput.value = cliente;
    direccionInput.value = direccion;
    clientesList.style.display = "none";
    document.getElementById("searchClientes").value = ""; // Limpia el campo de búsqueda
}

// Evento de teclado para navegar por los resultados de búsqueda con las flechas
document.getElementById("searchClientes").addEventListener("keydown", function(event) {
    if (event.key === "ArrowUp") {
        selectedClienteIndex = Math.max(selectedClienteIndex - 1, 0);
        highlightSelectedCliente(selectedClienteIndex);
        event.preventDefault();
    } else if (event.key === "ArrowDown") {
        selectedClienteIndex = Math.min(selectedClienteIndex + 1, clientesItems.length - 1);
        highlightSelectedCliente(selectedClienteIndex);
        event.preventDefault();
    } else if (event.key === "Enter" && selectedClienteIndex >= 0) {
        selectCliente(clientesItems[selectedClienteIndex].innerText);
        selectedClienteIndex = -1; // Reinicia el índice seleccionado
        event.preventDefault();
    }
});

// Función para realizar la búsqueda y mostrar/ocultar elementos según el término de búsqueda
function searchClientes() {
    var input = document.getElementById("searchClientes");
    var searchTerm = input.value.trim().toLowerCase();

    for (var i = 0; i < clientesItems.length; i++) {
        var clienteText = clientesItems[i].textContent || clientesItems[i].innerText;
        if (clienteText.toLowerCase().indexOf(searchTerm) > -1) {
            clientesItems[i].style.display = "block";
        } else {
            clientesItems[i].style.display = "none";
        }
    }

    clientesList.style.display = "block";
    selectedClienteIndex = -1; // Reinicia el índice seleccionado
}
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
