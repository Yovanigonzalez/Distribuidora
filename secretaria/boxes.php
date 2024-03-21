<?php
// Conectar a la base de datos - lo dejamos que puede funcionar
require('../config/conexion.php');

if (isset($_POST['search_cliente'])) {
    // Obtener el valor de búsqueda
    $search_cliente = $_POST['search_cliente'];

    // Realizar la búsqueda en la base de datos
    $queryClienteInfo = "SELECT cliente, total_cajas, total_tapas FROM cajas WHERE cliente LIKE '%$search_cliente%'";
    $resultClienteInfo = $conn->query($queryClienteInfo);

    // Construir la respuesta para AJAX
    $response = "";

    if ($resultClienteInfo->num_rows > 0) {
        while ($rowClienteInfo = $resultClienteInfo->fetch_assoc()) {
            $response .= "<div class='row'>
                            <div class='col-md-8'>
                                <h4>Cliente: " . $rowClienteInfo['cliente'] . "</h4>
                                <p>Cajas: " . $rowClienteInfo['total_cajas'] . "</p>
                                <p>Tapas: " . $rowClienteInfo['total_tapas'] . "</p>
                            </div>
                        </div>";
        }
    } else {
        $response = "<p>No se encontró información para el cliente '$search_cliente'.</p>";
    }

    // Devolver la respuesta a AJAX
    echo $response;

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
